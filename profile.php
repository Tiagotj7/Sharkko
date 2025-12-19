<?php
// ===================================
// profile.php (ENTRY POINT CORRETO)
// ===================================

define('BASE_PATH', __DIR__ . '/app');

require BASE_PATH . '/config/bootstrap.php';
require BASE_PATH . '/controllers/ProfileController.php';

ProfileController::show();
