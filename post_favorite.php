<?php
// post_favorite.php
require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/helpers/utils.php';
require_once __DIR__ . '/app/models/Favorite.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = (int)($_POST['post_id'] ?? 0);
    $user   = current_user();

    if ($postId > 0 && $user) {
        Favorite::toggle($postId, (int)$user['id']);
    }

    $referer = $_SERVER['HTTP_REFERER'] ?? 'feed.php';
    redirect($referer);
}

redirect('feed.php');