<?php

class FeedController
{
    public static function index()
    {
        require_login();

        // Por enquanto feed vazio
        $posts = [];

        // Carrega a view
        require BASE_PATH . '/views/feed/index.php';
    }
}
