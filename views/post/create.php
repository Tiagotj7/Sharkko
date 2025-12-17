<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/../partials/header.php'; ?>

<main class="container main">
  <?php include __DIR__ . '/../partials/flash.php'; ?>

  <h1>Criar novo projeto</h1>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <ul>
        <?php foreach ($errors as $error): ?>
          <li><?= esc($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="post_store.php" method="post" enctype="multipart/form-data" class="card">
    <input type="hidden" name="csrf_token" value="<?= esc(csrf_token()) ?>">

    <label>
      Título do projeto
      <input type="text" name="title" value="<?= esc(old('title')) ?>" required>
    </label>

    <label>
      Descrição
      <textarea name="description" rows="5" required><?= esc(old('description')) ?></textarea>
    </label>

    <label>
      Imagem do projeto (opcional)
      <input type="file" name="image" accept="image/*">
    </label>

    <label>
      Linguagens (separadas por vírgula)
      <input type="text" name="languages" value="<?= esc(old('languages')) ?>" placeholder="PHP, JavaScript, Python">
    </label>

    <label>
      Tags (separadas por vírgula)
      <input type="text" name="tags" value="<?= esc(old('tags')) ?>" placeholder="web, mobile, api">
    </label>

    <label>
      E-mail de contato (opcional)
      <input type="email" name="contact_email" value="<?= esc(old('contact_email')) ?>">
    </label>

    <label>
      Link de contato (opcional)
      <input type="url" name="contact_link" value="<?= esc(old('contact_link')) ?>">
    </label>

    <button type="submit" class="btn-primary">Criar projeto</button>
  </form>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>
