<?php
// register.php (entry point)

// 1️⃣ Define BASE_PATH
define('BASE_PATH', __DIR__ . '/app');

// 2️⃣ Carrega bootstrap (sessão, helpers, banco, models)
require_once BASE_PATH . '/config/bootstrap.php';

// 3️⃣ Carrega o controller
require_once BASE_PATH . '/controllers/AuthController.php';

// 4️⃣ Executa a ação
AuthController::register();
