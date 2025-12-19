<?php
// login.php (entry point)

define('BASE_PATH', __DIR__ . '/app');

require_once BASE_PATH . '/config/bootstrap.php';
require_once BASE_PATH . '/controllers/AuthController.php';

AuthController::login();
