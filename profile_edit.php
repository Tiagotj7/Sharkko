<?php
// 1️⃣ Define BASE_PATH and bootstrap the app (keeps consistency with other entry points)
define('BASE_PATH', __DIR__ . '/app');

require BASE_PATH . '/config/bootstrap.php';
require BASE_PATH . '/controllers/ProfileController.php';

ProfileController::edit();
