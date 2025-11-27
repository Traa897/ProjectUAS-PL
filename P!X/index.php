
<?php
// index.php - FIXED VERSION
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(session_status() == PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/controllers/FilmController.php';
require_once __DIR__ . '/controllers/BioskopController.php';
require_once __DIR__ . '/controllers/JadwalController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/TransaksiController.php';
require_once __DIR__ . '/controllers/AdminController.php';

$module = isset($_GET['module']) ? $_GET['module'] : 'film';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

try {
    // PERBAIKAN: Batasi akses berdasarkan role
    $user_only_modules = ['film', 'transaksi', 'auth', 'user'];
    $admin_only_modules = ['admin', 'bioskop', 'jadwal'];

    // Jika user login dan bukan admin, batasi akses
    if (isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
        if (!in_array($module, $user_only_modules)) {
            $_SESSION['flash'] = 'Anda tidak memiliki akses ke halaman ini!';
            header('Location: index.php?module=film');
            exit();
        }
    }

    // Jika admin login, izinkan akses ke semua modul
    if (isset($_SESSION['admin_id'])) {
        switch ($module) {
            case 'film':
                $controller = new FilmController();
                break;
            case 'bioskop':
                $controller = new BioskopController();
                break;
            case 'jadwal':
                $controller = new JadwalController();
                break;
            case 'auth':
                $controller = new AuthController();
                break;
            case 'user':
                $controller = new UserController();
                break;
            case 'transaksi':
                $controller = new TransaksiController();
                break;
            case 'admin':
                $controller = new AdminController();
                break;
            default:
                $controller = new FilmController();
        }
    } else {
        // Jika bukan admin, hanya izinkan akses modul tertentu
        switch ($module) {
            case 'film':
                $controller = new FilmController();
                break;
            case 'transaksi':
                $controller = new TransaksiController();
                break;
            case 'auth':
                $controller = new AuthController();
                break;
            case 'user':
                $controller = new UserController();
                break;
            default:
                $_SESSION['flash'] = 'Anda tidak memiliki akses ke halaman ini!';
                header('Location: index.php?module=film');
                exit();
        }
    }

    // PERBAIKAN: Cek akses admin untuk action tertentu
    $admin_only_actions = ['createFilm', 'storeFilm', 'editFilm', 'updateFilm', 'deleteFilm', 
                           'dashboard', 'kelolaUser', 'detailUser', 'toggleUserStatus'];

    if ($module === 'admin' && !isset($_SESSION['admin_id'])) {
        $_SESSION['flash'] = 'Anda harus login sebagai admin!';
        header('Location: index.php?module=auth&action=index');
        exit();
    }

    // Execute action
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        if (method_exists($controller, 'index')) {
            $controller->index();
        } else {
            throw new Exception("Method '$action' tidak ditemukan");
        }
    }
} catch (Exception $e) {
    if (session_status() == PHP_SESSION_NONE) session_start();
    $_SESSION['flash'] = 'Error: ' . $e->getMessage();
    header('Location: index.php?module=film');
    exit();
}