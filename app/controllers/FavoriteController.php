<?php
// app/controllers/FavoriteController.php
require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/helpers/csrf.php';
require_once __DIR__ . '/app/helpers/utils.php';
require_once __DIR__ . '/app/models/Favorite.php';

class FavoriteController
{
    public static function index()
    {
        require_login();

        $user = current_user();
        $favorites = Favorite::forUser($user['id']);

        require_once __DIR__ . '/app/views/favorites/index.php';
    }

    public static function toggle()
    {
        require_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('feed.php');
        }

        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Token CSRF inválido.');
            redirect('feed.php');
        }

        $postId = (int)($_POST['post_id'] ?? 0);
        $user = current_user();

        Favorite::toggle($postId, $user['id']);

        redirect($_SERVER['HTTP_REFERER'] ?? 'feed.php');
    }
}
