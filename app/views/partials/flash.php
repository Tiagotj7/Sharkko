<?php
// app/views/partials/flash.php
require_once __DIR__ . '/../../helpers/utils.php';
?>

<?php if ($msg = flash('success')): ?>
  <div class="alert alert-success"><?= esc($msg) ?></div>
<?php endif; ?>

<?php if ($msg = flash('error')): ?>
  <div class="alert alert-error"><?= esc($msg) ?></div>
<?php endif; ?>
