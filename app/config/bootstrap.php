<?php
// app/config/bootstrap.php

// 1️⃣ Sessão PRIMEIRO
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2️⃣ Banco
require_once BASE_PATH . '/database/connection.php';

// 3️⃣ Models
require_once BASE_PATH . '/models/User.php';

// 4️⃣ Helpers
require_once BASE_PATH . '/helpers/utils.php';
require_once BASE_PATH . '/helpers/auth.php';
require_once BASE_PATH . '/helpers/csrf.php';
require_once BASE_PATH . '/helpers/validation.php';
