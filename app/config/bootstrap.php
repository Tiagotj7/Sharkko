<?php
session_start();

define('UPLOAD_POSTS', $_SERVER['DOCUMENT_ROOT'] . '/uploads/posts');

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/database.php';
