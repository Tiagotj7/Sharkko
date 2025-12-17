<?php
require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/helpers/utils.php';

logout_user();
flash('success', 'Você saiu da sua conta.');
redirect('index.php');