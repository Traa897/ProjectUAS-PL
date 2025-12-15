<?php
// index.php - FIXED: Default ke halaman public (film) bukan admin
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

// FIXED: Default ke 'film' (halaman public), bukan 'admin'
$module = isset($_GET['module']) ? $_GET['module'] : 'film';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

try {
    // Batasi akses admin - TIDAK BOLEH block dashboard admin!
    $admin_only_actions = [
        'admin' => ['createFilm', 'storeFilm', 'editFilm', 'updateFilm', 'deleteFilm', 'kelolaUser', 'detailUser', 'toggleUserStatus', 'detailTransaksi', 'updateStatus']
    ];
    
    // PENTING: 'dashboard' TIDAK ada di list admin_only_actions
    // Karena AdminController::__construct() sudah handle proteksi
    
    if(isset($admin_only_actions[$module]) && in_array($action, $admin_only_actions[$module])) {
        if(!isset($_SESSION['admin_id'])) {
            $_SESSION['flash'] = 'Anda harus login sebagai admin!';
            header('Location: index.php?module=auth&action=index');
            exit();
        }
    }
    
    // Batasi akses user
    $user_only_actions = [
        'user' => ['dashboard', 'profile', 'updateProfile', 'riwayat', 'detailTiket'],
        'transaksi' => ['pilihJadwal', 'booking', 'prosesBooking', 'konfirmasi']
    ];
    
    if(isset($user_only_actions[$module]) && in_array($action, $user_only_actions[$module])) {
        if(!isset($_SESSION['user_id'])) {
            $_SESSION['flash'] = 'Anda harus login sebagai user!';
            header('Location: index.php?module=auth&action=index');
            exit();
        }
    }
    
    // Initialize controller
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