<?php

class FeedController
{
    public static function index()
    {
        require_login();

        // DEFINIÇÃO OBRIGATÓRIA
        $posts = [];

        require BASE_PATH . '/views/feed/index.php';
    }
}
