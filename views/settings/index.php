<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/../partials/header.php'; ?>

<main class="container main">
  <?php include __DIR__ . '/../partials/flash.php'; ?>

  <h1>Configurações</h1>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <ul>
        <?php foreach ($errors as $error): ?>
          <li><?= esc($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="settings.php" method="post" class="card">
    <input type="hidden" name="csrf_token" value="<?= esc(csrf_token()) ?>">

    <label>
      Tema
      <select name="theme">
        <option value="light" <?= $data['theme'] == 'light' ? 'selected' : '' ?>>Claro</option>
        <option value="dark" <?= $data['theme'] == 'dark' ? 'selected' : '' ?>>Escuro</option>
      </select>
    </label>

    <fieldset>
      <legend>Alterar senha (opcional)</legend>

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
        <input type="password" name="confirm_password">
      </label>
    </fieldset>

    <button type="submit" class="btn-primary">Salvar</button>
  </form>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>
