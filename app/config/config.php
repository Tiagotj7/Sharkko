<?php
// app/config/config.php

// AJUSTE ISSO COM OS DADOS DO INFINITYFREE
define('DB_HOST', 'sqlXXX.infinityfree.com'); // host do MySQL
define('DB_NAME', 'seu_banco');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');

// URL base do site (ajuste se estiver em subpasta)
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$baseUrl .= "://".$_SERVER['HTTP_HOST'];
define('BASE_URL', $baseUrl);

// Caminhos
define('APP_PATH', __DIR__ . '/..');
define('ROOT_PATH', dirname(__DIR__, 2));
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