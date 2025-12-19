<?php

// proteção mínima para não quebrar a view
if (!isset($posts) || !is_array($posts)) {
    $posts = [];
}



define('BASE_PATH', __DIR__ . '/app');
require BASE_PATH . '/config/bootstrap.php';

$route = $_GET['r'] ?? 'feed';

switch ($route) {
    case 'feed':
        PostController::index();
        break;

    case 'post_show':
        PostController::show();
        break;

    case 'post_create':
        PostController::create();
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
