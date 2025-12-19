<?php
// messages.php (ENTRY POINT)

// 1️⃣ Define BASE_PATH
define('BASE_PATH', __DIR__ . '/app');

// 2️⃣ Carrega bootstrap (models + helpers)
require_once BASE_PATH . '/config/bootstrap.php';

// 3️⃣ Controller
require_once BASE_PATH . '/controllers/MessageController.php';

// 4️⃣ Executa
MessageController::index();
