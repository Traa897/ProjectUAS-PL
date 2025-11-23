<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
        if(session_status() == PHP_SESSION_NONE) session_start();
    }

    public function index() {
        // show login form
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function login() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            $user = $this->userModel->findByUsername($username);
            if($user && password_verify($password, $user['password'])) {
                // store minimal user info in session
                $_SESSION['user'] = ['username' => $user['username'], 'role' => $user['role']];
                $_SESSION['flash'] = 'Anda login sebagai ' . ($user['role'] === 'admin' ? 'Admin' : $user['username']);
                header('Location: index.php');
                exit();
            } else {
                $error = 'Username atau password salah';
                require_once __DIR__ . '/../views/auth/login.php';
            }
        }
    }

    public function register() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            if($username === '' || $password === '') {
                $error = 'Username dan password wajib diisi';
                require_once __DIR__ . '/../views/auth/register.php';
                return;
            }

            $created = $this->userModel->create($username, $password);
            if($created) {
                $_SESSION['flash'] = 'Akun berhasil dibuat. Silakan login.';
                header('Location: index.php?module=auth&action=index');
                exit();
            } else {
                $error = 'Username sudah digunakan';
                require_once __DIR__ . '/../views/auth/register.php';
            }
        } else {
            require_once __DIR__ . '/../views/auth/register.php';
        }
    }

    public function logout() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        unset($_SESSION['user']);
        $_SESSION['flash'] = 'Anda telah logout';
        header('Location: index.php');
        exit();
    }
}

?>
