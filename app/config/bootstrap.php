<?php

// ===============================
// 1️⃣ SESSÃO
// ===============================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ===============================
// 2️⃣ BANCO
// ===============================
require_once BASE_PATH . '/database/connection.php';

// ===============================
// 3️⃣ MODELS (ANTES DOS HELPERS)
// ===============================
require_once BASE_PATH . '/models/User.php';

// ===============================
// 4️⃣ HELPERS
// ===============================
require_once BASE_PATH . '/helpers/utils.php';
require_once BASE_PATH . '/helpers/auth.php';
require_once BASE_PATH . '/helpers/csrf.php';
require_once BASE_PATH . '/helpers/validation.php';

// ===============================
// 5️⃣ ROUTES HELPER
// ===============================
function route(string $name, array $params = []): string
{
    $query = array_merge(['r' => $name], $params);
    return 'index.php?' . http_build_query($query);
}
