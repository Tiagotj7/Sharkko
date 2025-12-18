<?php
define('BASE_PATH', dirname(__DIR__, 2));

require_once BASE_PATH . '/app/database/connection.php';
require_once BASE_PATH . '/app/helpers/utils.php';
require_once BASE_PATH . '/app/helpers/auth.php';
require_once BASE_PATH . '/app/helpers/csrf.php';
require_once BASE_PATH . '/app/helpers/validation.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
