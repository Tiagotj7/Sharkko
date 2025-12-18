<?php
// app/views/partials/head.php

require_once __DIR__ . '/../../config/bootstrap.php';

$user  = current_user();
$theme = $user['theme'] ?? 'dark';
?>
<meta charset="UTF-8">
<title>DevNetwork</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="assets/css/main.css">
<script>
  document.documentElement.setAttribute('data-theme', '<?= esc($theme) ?>');
</script>
