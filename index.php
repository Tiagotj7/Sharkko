<?php
// ===============================
// DEBUG
// ===============================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ===============================
// BASE PATH
// ===============================
define('BASE_PATH', __DIR__ . '/app');

// ===============================
// BOOTSTRAP
// ===============================
require BASE_PATH . '/config/bootstrap.php';

// ===============================
// CONTROLLERS
// ===============================
require BASE_PATH . '/controllers/PostController.php';
require BASE_PATH . '/controllers/AuthController.php';
require BASE_PATH . '/controllers/FavoriteController.php';

// ===============================
// ROUTER
// ===============================
$route = $_GET['r'] ?? 'feed';

switch ($route) {

    case 'feed':
        PostController::index();
        break;

    case 'post_show':
        require_once BASE_PATH . '/controllers/PostController.php';
        PostController::show();
        break;

    case 'login':
        AuthController::login();
        break;

    case 'register':
        AuthController::register();
        break;

    case 'logout':
        AuthController::logout();
        break;

    case 'post_create':
        PostController::create();
        break;

    case 'post_edit':
        PostController::edit();
        break;

    case 'post_delete':
        PostController::delete();
        break;

    case 'post_like':
        PostController::like();
        break;

    case 'post_comment':
        PostController::comment();
        break;

    default:
        http_response_code(404);
        echo 'Página não encontrada';
}
