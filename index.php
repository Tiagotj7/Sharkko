<?php
/**
 * index.php
 * Entry point único da aplicação
 * Responsável por:
 *  - definir BASE_PATH
 *  - carregar bootstrap
 *  - rotear requisições
 */

// ===============================
// 1️⃣ DEFINE BASE_PATH (UMA VEZ)
// ===============================
define('BASE_PATH', __DIR__ . '/app');

// ===============================
// 2️⃣ BOOTSTRAP (config, helpers, session, db)
// ===============================
require_once BASE_PATH . '/config/bootstrap.php';

// ===============================
// 3️⃣ CONTROLLERS
// ===============================
require_once BASE_PATH . '/controllers/AuthController.php';

// ===============================
// 4️⃣ ROTEAMENTO SIMPLES
// ===============================
// Exemplo:
// index.php?r=login
// index.php?r=register
// index.php?r=logout

$route = $_GET['r'] ?? 'login';

switch ($route) {
    case 'login':
        AuthController::login();
        break;

    case 'register':
        AuthController::register();
        break;

    case 'logout':
        AuthController::logout();
        break;

    default:
        http_response_code(404);
        echo 'Página não encontrada';
        break;
}
