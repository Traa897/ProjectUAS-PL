<?php
// index.php - Main Router FIXED
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if(session_status() == PHP_SESSION_NONE) session_start();

// Include controllers
require_once __DIR__ . '/controllers/FilmController.php';
require_once __DIR__ . '/controllers/BioskopController.php';
require_once __DIR__ . '/controllers/JadwalController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/TransaksiController.php';
require_once __DIR__ . '/controllers/AdminController.php';

// Get parameters
$module = isset($_GET['module']) ? $_GET['module'] : 'film';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Route berdasarkan module
try {
    switch($module) {
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

    // Protected actions untuk admin (kecuali module admin)
    $admin_actions = ['create','store','edit','update','delete'];
    if(in_array($action, $admin_actions) && $module != 'admin' && $module != 'auth') {
        if(!isset($_SESSION['admin_id'])) {
            $_SESSION['flash'] = 'Anda harus login sebagai admin untuk mengakses halaman ini';
            header('Location: index.php?module=auth&action=index');
            exit();
        }
    }

    // Execute action
    if(method_exists($controller, $action)) {
        $controller->$action();
    } else {
        // Jika method tidak ada, panggil index sebagai default
        if(method_exists($controller, 'index')) {
            $controller->index();
        } else {
            throw new Exception("Method '$action' tidak ditemukan di controller '$module'");
        }
    }
} catch (Exception $e) {
    // Error handling
    if(session_status() == PHP_SESSION_NONE) session_start();
    $_SESSION['flash'] = 'Error: ' . $e->getMessage();
    header('Location: index.php?module=film');
    exit();
}
?>