<?php
#/app/login.php

define('BASE_PATH', __DIR__ . '/app');

require_once __DIR__ . '/app/controllers/AuthController.php';

AuthController::login();