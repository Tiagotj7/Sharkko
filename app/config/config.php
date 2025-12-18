<?php
// app/config/config.php

// Caminhos
define('APP_PATH', __DIR__ . '/app');
define('ROOT_PATH', dirname(__DIR__, 2));

// Load environment variables from .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

loadEnv(ROOT_PATH . '/.env');

// Database credentials from .env
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'sharkko');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// URL base do site (ajuste se estiver em subpasta)
$baseUrl = getenv('BASE_URL') ?: ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST']);
define('BASE_URL', $baseUrl);

// Caminhos
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('UPLOAD_AVATARS', UPLOAD_PATH . '/avatars');
define('UPLOAD_POSTS', UPLOAD_PATH . '/posts');

// Cria pastas de upload se não existirem (localmente, no servidor normalmente já existe)
if (!is_dir(UPLOAD_AVATARS)) {
    @mkdir(UPLOAD_AVATARS, 0777, true);
}
if (!is_dir(UPLOAD_POSTS)) {
    @mkdir(UPLOAD_POSTS, 0777, true);
}