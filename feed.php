<?php
define('BASE_PATH', __DIR__ . '/app');

require_once BASE_PATH . '/config/bootstrap.php';

require_login();

// ✅ DEFINE A VARIÁVEL ESPERADA PELA VIEW
$posts = [];

require_once BASE_PATH . '/views/feed/index.php';
