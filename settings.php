<?php
// settings.php (ENTRY POINT)

// 1️⃣ Define BASE_PATH
define('BASE_PATH', __DIR__ . '/app');

// 2️⃣ Bootstrap (models, helpers, sessão)
require_once BASE_PATH . '/config/bootstrap.php';

// 3️⃣ Controller
require_once BASE_PATH . '/controllers/SettingsController.php';

// 4️⃣ Executa
SettingsController::index();
