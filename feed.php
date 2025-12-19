<?php
// feed.php (entry point)

// 1️⃣ Define BASE_PATH
define('BASE_PATH', __DIR__ . '/app');

// 2️⃣ Carrega bootstrap
require_once BASE_PATH . '/config/bootstrap.php';

// 3️⃣ (opcional) proteção de rota
require_login();

// 4️⃣ View ou controller
require BASE_PATH . '/views/feed.php';
