<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/controllers/MovieController.php';

$controller = new MovieController();
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch($action) {
    case 'dashboard':
        $controller->dashboard();
        break;
    case 'create':
        $controller->create();
        break;
    case 'store':
        $controller->store();
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
    case 'show':
        $controller->show();
        break;
    default:
        $controller->index();
        break;
}
?>