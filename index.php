<?php

// proteção mínima para não quebrar a view
if (!isset($posts) || !is_array($posts)) {
    $posts = [];
}



define('BASE_PATH', __DIR__ . '/app');

require_once BASE_PATH . '/config/bootstrap.php';

require_once BASE_PATH . '/controllers/AuthController.php';
require_once BASE_PATH . '/controllers/FeedController.php';

$route = $_GET['r'] ?? 'login';

switch ($route) {
case 'feed':
    FeedController::index();
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

    default:
        http_response_code(404);
        echo '404';
}
