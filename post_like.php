<?php
require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/helpers/utils.php';
require_once __DIR__ . '/app/models/Like.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = (int)($_POST['post_id'] ?? 0);
    $user = current_user();

    if ($postId > 0) {
        Like::toggle($postId, (int)$user['id']);
    }
    // volta para a página anterior
    $referer = $_SERVER['HTTP_REFERER'] ?? 'feed.php';
    redirect($referer);
}

redirect('feed.php');