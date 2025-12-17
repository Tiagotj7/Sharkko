<?php
require_once __DIR__ . '/app/controllers/SettingsController.php';

SettingsController::index();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        flash('error', 'Token CSRF inválido.');
        redirect('settings.php');
    }

    $theme          = in_array($_POST['theme'] ?? 'dark', ['dark', 'light']) ? $_POST['theme'] : 'dark';
    $current_pass   = $_POST['current_password'] ?? '';
    $new_pass       = $_POST['new_password'] ?? '';
    $new_pass_conf  = $_POST['new_password_confirmation'] ?? '';

    $newHash = null;

    if ($new_pass !== '' || $new_pass_conf !== '') {
        if (!password_verify($current_pass, $user['password_hash'])) {
            flash('error', 'Senha atual incorreta.');
            redirect('settings.php');
        }
        if ($new_pass !== $new_pass_conf) {
            flash('error', 'Nova senha e confirmação não coincidem.');
            redirect('settings.php');
        }
        $newHash = password_hash($new_pass, PASSWORD_DEFAULT);
    }

    User::updateSettings((int)$user['id'], $theme, $newHash);
    flash('success', 'Configurações atualizadas.');
    redirect('settings.php');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/views/partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/views/partials/header.php'; ?>
<main class="container main">
  <?php include __DIR__ . '/views/partials/flash.php'; ?>

  <h1>Configurações</h1>

  <form action="settings.php" method="post" class="card">
    <input type="hidden" name="csrf" value="<?= esc(csrf_token()) ?>">

    <h3>Tema</h3>
    <label>
      Tema
      <select name="theme">
        <option value="dark" <?= ($user['theme'] ?? 'dark') === 'dark' ? 'selected' : '' ?>>Dark</option>
        <option value="light" <?= ($user['theme'] ?? 'dark') === 'light' ? 'selected' : '' ?>>Light</option>
      </select>
    </label>

    <hr>

    <h3>Alterar senha</h3>
    <p style="font-size:.85rem;color:var(--muted);">Preencha os campos abaixo somente se quiser alterar sua senha.</p>

    <label>
      Senha atual
      <input type="password" name="current_password">
    </label>

    <label>
      Nova senha
      <input type="password" name="new_password">
    </label>

    <label>
      Confirmar nova senha
      <input type="password" name="new_password_confirmation">
    </label>

    <button class="btn-primary" type="submit" style="margin-top:.5rem;">Salvar configurações</button>
  </form>
</main>
<?php include __DIR__ . '/views/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>