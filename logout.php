<?php
// logout.php (entry point)

// 1️⃣ Define BASE_PATH
define('BASE_PATH', __DIR__ . '/app');

// 2️⃣ Bootstrap
require_once BASE_PATH . '/config/bootstrap.php';

// 3️⃣ Controller
require_once BASE_PATH . '/controllers/AuthController.php';

// 4️⃣ Executa
AuthController::logout();
