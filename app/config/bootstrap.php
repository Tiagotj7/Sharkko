<?php

// sessão primeiro
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// database
require_once BASE_PATH . '/database/connection.php';

// models (ANTES dos helpers)
require_once BASE_PATH . '/models/User.php';

// helpers
require_once BASE_PATH . '/helpers/utils.php';
require_once BASE_PATH . '/helpers/auth.php';
require_once BASE_PATH . '/helpers/csrf.php';
require_once BASE_PATH . '/helpers/validation.php';
