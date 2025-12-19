<?php

class FeedController
{
    public static function index()
    {
        require_login();

        // depois você troca por busca no banco
        $posts = [];

        require BASE_PATH . '/views/feed/index.php';
    }
}
