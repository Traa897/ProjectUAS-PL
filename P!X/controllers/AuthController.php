<?php
// controllers/AuthController.php - FIXED VERSION
require_once 'config/database.php';
require_once 'models/User.php';
require_once 'models/Admin.php';

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
        // Jangan load header.php karena sudah ada di login.php
        require_once 'views/auth/login.php';
    }

    // Process Login
    public function login() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?module=auth&action=index');
            exit();
        }

        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $query = "SELECT * FROM Admin WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $adminCheck = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jika username ditemukan di tabel Admin, maka role = admin
        $role = $adminCheck ?  'admin': 'user';

        if($role === 'admin') {
            // ADMIN LOGIN - PERBAIKAN
            // Langsung cek di database tanpa hashing dulu
            $query = "SELECT * FROM Admin WHERE username = :username LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($admin) {
                // Cek password - bisa plain text atau hashed
                $passwordValid = false;
                
                // Cek apakah password di database adalah plain text
                if($admin['password'] === $password) {
                    $passwordValid = true;
                }
                // Atau cek dengan password_verify untuk password yang di-hash
                elseif(password_verify($password, $admin['password'])) {
                    $passwordValid = true;
                }
                
                if($passwordValid) {
                    // Login berhasil
                    $_SESSION['admin_id'] = $admin['id_admin'];
                    $_SESSION['admin_username'] = $admin['username'];
                    $_SESSION['admin_name'] = $admin['nama_lengkap'];
                    $_SESSION['admin_role'] = $admin['role'];
                    $_SESSION['flash'] = 'Selamat datang, Admin ' . $admin['nama_lengkap'] . '!';
                    
                    header('Location: index.php?module=admin&action=dashboard');
                    exit();
                }
            }
            
            // Login gagal
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

    // Show Register Form
    public function register() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $nama_lengkap = isset($_POST['nama_lengkap']) ? trim($_POST['nama_lengkap']) : '';
            $no_telpon = isset($_POST['no_telpon']) ? trim($_POST['no_telpon']) : '';

            // Validasi
            if($username === '' || $email === '' || $password === '' || $nama_lengkap === '') {
                $error = 'Semua field wajib diisi';
                require_once 'views/auth/register.php';
                return;
            }

            // Check username exists
            if($this->user->usernameExists($username)) {
                $error = 'Username sudah digunakan';
                require_once 'views/auth/register.php';
                return;
            }

            // Check email exists
            if($this->user->emailExists($email)) {
                $error = 'Email sudah digunakan';
                require_once 'views/auth/register.php';
                return;
            }

            // Create user
            $this->user->username = $username;
            $this->user->email = $email;
            $this->user->password = $password;
            $this->user->nama_lengkap = $nama_lengkap;
            $this->user->no_telpon = $no_telpon;
            $this->user->tanggal_lahir = isset($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : null;
            $this->user->alamat = isset($_POST['alamat']) ? $_POST['alamat'] : null;

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
    public function logout() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        // Destroy all session data
        session_unset();
        session_destroy();
        
        if(session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['flash'] = 'Anda telah logout';
        header('Location: index.php?module=film');
        exit();
    }
}
?>