<?php
// index.php - FIXED ROUTER WITH PUBLIC/USER/ADMIN SEPARATION
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

// DEFAULT: Public page (film list)
$module = isset($_GET['module']) ? $_GET['module'] : 'film';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

try {
    // =================================================================
    // ADMIN-ONLY ACTIONS - Requires admin login
    // =================================================================
    $admin_only_modules = ['admin'];
    $admin_only_actions = [
        'admin' => ['dashboard', 'createFilm', 'storeFilm', 'editFilm', 'updateFilm', 'deleteFilm', 
                    'kelolaUser', 'detailUser', 'toggleUserStatus', 'detailTransaksi', 'updateStatus'],
        'jadwal' => ['create', 'store', 'edit', 'update', 'delete']
    ];
    
    // Check if current request needs admin access
    if(in_array($module, $admin_only_modules) || 
       (isset($admin_only_actions[$module]) && in_array($action, $admin_only_actions[$module]))) {
        
        if(!isset($_SESSION['admin_id'])) {
            $_SESSION['flash'] = '⚠️ Anda harus login sebagai admin untuk mengakses halaman ini!';
            header('Location: index.php?module=auth&action=index');
            exit();
        }
    }
    
    // =================================================================
    // USER-ONLY ACTIONS - Requires user login
    // =================================================================
    $user_only_actions = [
        'user' => ['dashboard', 'profile', 'updateProfile', 'riwayat', 'detailTiket'],
        'transaksi' => ['pilihJadwal', 'booking', 'prosesBooking', 'konfirmasi']
    ];
    
    // Check if current request needs user access
    if(isset($user_only_actions[$module]) && in_array($action, $user_only_actions[$module])) {
        if(!isset($_SESSION['user_id'])) {
            $_SESSION['flash'] = '🔒 Silakan login terlebih dahulu untuk melakukan booking!';
            header('Location: index.php?module=auth&action=index');
            exit();
        }
    }
    
    // =================================================================
    // INITIALIZE CONTROLLER
    // =================================================================
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
            // Default to public film list
            $controller = new FilmController();
            $module = 'film';
    }

    // =================================================================
    // EXECUTE ACTION
    // =================================================================
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        if (method_exists($controller, 'index')) {
            $controller->index();
        } else {
            throw new Exception("Method '$action' tidak ditemukan di controller '$module'");
        }
    }
    
} catch (Exception $e) {
    if (session_status() == PHP_SESSION_NONE) session_start();
    $_SESSION['flash'] = '❌ Error: ' . $e->getMessage();
    header('Location: index.php?module=film');
    exit();
}
?>