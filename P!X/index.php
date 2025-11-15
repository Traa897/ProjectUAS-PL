<?php
// index.php - Main Router (Film sebagai Welcome Page)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include semua controllers
require_once __DIR__ . '/controllers/FilmController.php';
require_once __DIR__ . '/controllers/AktorController.php';
require_once __DIR__ . '/controllers/BioskopController.php';
require_once __DIR__ . '/controllers/JadwalController.php';

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
    default:
        $controller = new FilmController();
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
    default:
        $controller->index();
        break;
}
?>