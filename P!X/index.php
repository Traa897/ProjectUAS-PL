<?php
// index.php - Main Router (Film sebagai Welcome Page)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session for auth
if(session_status() == PHP_SESSION_NONE) session_start();

// Include semua controllers
require_once __DIR__ . '/controllers/FilmController.php';
require_once __DIR__ . '/controllers/AktorController.php';
require_once __DIR__ . '/controllers/BioskopController.php';
require_once __DIR__ . '/controllers/JadwalController.php';
require_once __DIR__ . '/controllers/AuthController.php';

// Get parameters
$module = isset($_GET['module']) ? $_GET['module'] : 'film'; // Default: film
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Route berdasarkan module
switch($module) {
    case 'film':
        $controller = new FilmController();
        break;
    case 'aktor':
        $controller = new AktorController();
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
    default:
        $controller = new FilmController();
}

// Route berdasarkan action
// If action modifies data, ensure only admin can proceed
$protected_actions = ['create','store','edit','update','delete'];
if(in_array($action, $protected_actions)) {
    $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
    if(!$user || $user['role'] !== 'admin') {
        header('Location: index.php?error=Anda tidak memiliki izin untuk melakukan aksi tersebut');
        exit();
    }
}

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
    default:
        $controller->index();
        break;
}
?>