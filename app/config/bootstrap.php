<?php
// app/config/bootstrap.php

require_once BASE_PATH . '/database/connection.php';
require_once BASE_PATH . '/helpers/utils.php';
require_once BASE_PATH . '/helpers/auth.php';
require_once BASE_PATH . '/helpers/csrf.php';
require_once BASE_PATH . '/helpers/validation.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
