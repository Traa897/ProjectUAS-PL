<?php
// index.php - Main Router (FIXED)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if(session_status() == PHP_SESSION_NONE) session_start();

// Get parameters
$module = isset($_GET['module']) ? $_GET['module'] : 'film';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Route berdasarkan module
switch($module) {
    case 'film':
        require_once __DIR__ . '/controllers/FilmController.php';
        $controller = new FilmController();
        break;
        
    case 'bioskop':
        require_once __DIR__ . '/controllers/BioskopController.php';
        $controller = new BioskopController();
        break;
        
    case 'jadwal':
        require_once __DIR__ . '/controllers/JadwalController.php';
        $controller = new JadwalController();
        break;
        
    case 'auth':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        break;
        
    case 'user':
        require_once __DIR__ . '/controllers/UserController.php';
        $controller = new UserController();
        break;
        
    case 'transaksi':
        require_once __DIR__ . '/controllers/TransaksiController.php';
        $controller = new TransaksiController();
        break;
        
    case 'admin':
        require_once __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        break;
        
    default:
        require_once __DIR__ . '/controllers/FilmController.php';
        $controller = new FilmController();
}

// Check admin permission for protected actions
$adminOnlyActions = ['create', 'store', 'edit', 'update', 'delete'];
$adminOnlyModules = ['film', 'bioskop', 'jadwal'];

if(in_array($action, $adminOnlyActions) && in_array($module, $adminOnlyModules)) {
    if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
        $_SESSION['flash'] = 'Anda harus login sebagai admin untuk mengakses halaman ini';
        header('Location: index.php?module=auth&action=index');
        exit();
    }
}

// Route berdasarkan action
switch($action) {
    case 'index':
        $controller->index();
        break;
    case 'create':
        $controller->create();
        break;
    case 'store':
        $controller->store();
        break;
    case 'show':
        $controller->show();
        break;
    case 'edit':
        $controller->edit();
        break;
    case 'update':
        $controller->update();
        break;
    case 'delete':
        $controller->delete();
        break;
        
    // Auth specific
    case 'login':
        if(method_exists($controller, 'login')) $controller->login();
        break;
    case 'register':
        if(method_exists($controller, 'register')) $controller->register();
        break;
    case 'logout':
        if(method_exists($controller, 'logout')) $controller->logout();
        break;
        
    // User specific
    case 'dashboard':
        if(method_exists($controller, 'dashboard')) $controller->dashboard();
        break;
    case 'profile':
        if(method_exists($controller, 'profile')) $controller->profile();
        break;
    case 'updateProfile':
        if(method_exists($controller, 'updateProfile')) $controller->updateProfile();
        break;
    case 'riwayat':
        if(method_exists($controller, 'riwayat')) $controller->riwayat();
        break;
    case 'detailTiket':
        if(method_exists($controller, 'detailTiket')) $controller->detailTiket();
        break;
        
    // Transaksi specific
    case 'pilihJadwal':
        if(method_exists($controller, 'pilihJadwal')) $controller->pilihJadwal();
        break;
    case 'booking':
        if(method_exists($controller, 'booking')) $controller->booking();
        break;
    case 'prosesBooking':
        if(method_exists($controller, 'prosesBooking')) $controller->prosesBooking();
        break;
    case 'konfirmasi':
        if(method_exists($controller, 'konfirmasi')) $controller->konfirmasi();
        break;
    case 'updateStatus':
        if(method_exists($controller, 'updateStatus')) $controller->updateStatus();
        break;
    case 'detail':
        if(method_exists($controller, 'detail')) $controller->detail();
        break;
        
    // Admin specific
    case 'laporan':
        if(method_exists($controller, 'laporan')) $controller->laporan();
        break;
    case 'detailPenjualan':
        if(method_exists($controller, 'detailPenjualan')) $controller->detailPenjualan();
        break;
        
    default:
        $controller->index();
        break;
}
?>