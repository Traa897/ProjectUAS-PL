<?php
// controllers/AuthController.php 
require_once 'config/database.php';
require_once 'models/User.php';
require_once 'models/Admin.php';
require_once 'models/Validator.php';

class AuthController {
    private $db;
    private $user;
    private $admin;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
        $this->admin = new Admin($this->db);
        
        if(session_status() == PHP_SESSION_NONE) session_start();
    }

    // Show Login Form
    public function index() {
        require_once 'views/auth/login.php';
    }

    // Process Login - WITH VALIDATION
    public function login() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?module=auth&action=index');
            exit();
        }

        // VALIDASI INPUT
        $validator = new Validator($_POST);
        $validator
            ->required('username', 'Username wajib diisi')
            ->required('password', 'Password wajib diisi')
            ->min('username', 3, 'Username minimal 3 karakter');
        
        if($validator->fails()) {
            $error = $validator->firstError();
            require_once 'views/auth/login.php';
            return;
        }

        $username = trim($_POST['username']);
        $password = $_POST['password'];
        
        // Cek apakah admin
        $query = "SELECT * FROM Admin WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $adminCheck = $stmt->fetch(PDO::FETCH_ASSOC);

        $role = $adminCheck ? 'admin' : 'user';

        if($role === 'admin') {
            // ADMIN LOGIN
            $query = "SELECT * FROM Admin WHERE username = :username LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($admin) {
                $passwordValid = false;
                
                if($admin['password'] === $password) {
                    $passwordValid = true;
                } elseif(password_verify($password, $admin['password'])) {
                    $passwordValid = true;
                }
                
                if($passwordValid) {
                    $_SESSION['admin_id'] = $admin['id_admin'];
                    $_SESSION['admin_username'] = $admin['username'];
                    $_SESSION['admin_name'] = $admin['nama_lengkap'];
                    $_SESSION['admin_role'] = $admin['role'];
                    $_SESSION['flash'] = 'Selamat datang, Admin ' . $admin['nama_lengkap'] . '!';
                    
                    header('Location: index.php?module=admin&action=dashboard');
                    exit();
                }
            }
            
            $error = 'Username atau password admin salah';
            require_once 'views/auth/login.php';
            
        } else {
            // USER LOGIN
            $user = $this->user->verifyLogin($username, $password);
            if($user) {
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['user_username'] = $user['username'];
                $_SESSION['user_name'] = $user['nama_lengkap'];
                $_SESSION['flash'] = 'Selamat datang, ' . $user['nama_lengkap'] . '!';
                
                header('Location: index.php?module=user&action=dashboard');
                exit();
            } else {
                $error = 'Username atau password salah, atau akun nonaktif';
                require_once 'views/auth/login.php';
            }
        }
    }

    // Show Register Form - WITH VALIDATION
    public function register() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // VALIDASI KOMPREHENSIF
            $validator = new Validator($_POST);
            $validator
                ->required('username', 'Username wajib diisi')
                ->min('username', 3, 'Username minimal 3 karakter')
                ->max('username', 50, 'Username maksimal 50 karakter')
                ->required('email', 'Email wajib diisi')
                ->email('email', 'Format email tidak valid')
                ->required('password', 'Password wajib diisi')
                ->min('password', 6, 'Password minimal 6 karakter')
                ->required('nama_lengkap', 'Nama lengkap wajib diisi')
                ->min('nama_lengkap', 3, 'Nama lengkap minimal 3 karakter');
            
            // Validasi unique username & email
            if(isset($_POST['username'])) {
                $validator->unique('username', 'User', 'username', $this->db, null, 'Username sudah digunakan');
            }
            
            if(isset($_POST['email'])) {
                $validator->unique('email', 'User', 'email', $this->db, null, 'Email sudah digunakan');
            }
            
            // Validasi no_telpon jika diisi
            if(!empty($_POST['no_telpon'])) {
                $validator
                    ->numeric('no_telpon', 'No. telepon harus berupa angka')
                    ->min('no_telpon', 10, 'No. telepon minimal 10 digit')
                    ->max('no_telpon', 15, 'No. telepon maksimal 15 digit');
            }
            
            // Validasi tanggal lahir jika diisi
            if(!empty($_POST['tanggal_lahir'])) {
                $validator->date('tanggal_lahir', 'Y-m-d', 'Format tanggal tidak valid');
            }
            
            // Cek hasil validasi
            if($validator->fails()) {
                $error = $validator->firstError();
                $errors = $validator->errors(); // untuk tampilkan semua error
                require_once 'views/auth/register.php';
                return;
            }

            // Jika validasi lolos, create user
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $nama_lengkap = trim($_POST['nama_lengkap']);
            $no_telpon = trim($_POST['no_telpon'] ?? '');

            $this->user->username = $username;
            $this->user->email = $email;
            $this->user->password = $password;
            $this->user->nama_lengkap = $nama_lengkap;
            $this->user->no_telpon = $no_telpon;
            $this->user->tanggal_lahir = $_POST['tanggal_lahir'] ?? null;
            $this->user->alamat = $_POST['alamat'] ?? null;

            if($this->user->create()) {
                $_SESSION['flash'] = 'Akun berhasil dibuat! Silakan login.';
                header('Location: index.php?module=auth&action=index');
                exit();
            } else {
                $error = 'Gagal membuat akun. Coba lagi.';
                require_once 'views/auth/register.php';
            }
        } else {
            require_once 'views/auth/register.php';
        }
    }

    // Logout
// Logout
    public function logout() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        session_unset();
        session_destroy();
        
        // Start new session for flash message
        session_start();
        $_SESSION['flash'] = 'Anda telah logout';
        
        header('Location: index.php?module=film');
        exit();
    }
}
?>