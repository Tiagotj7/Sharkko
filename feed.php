<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config/bootstrap.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/controllers/PostController.php';

PostController::index();
