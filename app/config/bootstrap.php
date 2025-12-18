<?php

define('BASE_PATH', dirname(__DIR__, 2));

require_once BASE_PATH . '/app/config/config.php';
require_once BASE_PATH . '/app/database/connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once BASE_PATH . '/app/helpers/auth.php';
require_once BASE_PATH . '/app/helpers/flash.php';
require_once BASE_PATH . '/app/helpers/utils.php';
require_once BASE_PATH . '/app/helpers/csrf.php';
require_once BASE_PATH . '/app/helpers/validation.php';
require_once BASE_PATH . '/app/models/User.php';
require_once BASE_PATH . '/app/models/Post.php';
require_once BASE_PATH . '/app/models/Comment.php';
require_once BASE_PATH . '/app/models/Favorite.php';
require_once BASE_PATH . '/app/models/Like.php';
require_once BASE_PATH . '/app/models/Conversation.php';
require_once BASE_PATH . '/app/models/Message.php';
// You can add more global initializations here if needed