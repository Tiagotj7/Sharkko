<?php

class FeedController
{
    public static function index()
    {
        require_login();

        // SEMPRE definir $posts
        $posts = [];

        require BASE_PATH . '/views/feed/index.php';
    }
}
