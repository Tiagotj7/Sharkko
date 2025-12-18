<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config/bootstrap.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/controllers/PostController.php';

PostController::index();
