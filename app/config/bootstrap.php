<?php

define('BASE_PATH', dirname(__DIR__, 2));

require_once BASE_PATH . '/app/config/config.php';
require_once BASE_PATH . '/app/database/connection.php';
require_once BASE_PATH . '/app/helpers/helpers.php';
require_once BASE_PATH . '/app/helpers/auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
