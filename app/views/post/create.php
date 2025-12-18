<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/partials/head.php'; ?>
</head>
<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/partials/header.php'; ?>

<main class="container main">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/partials/flash.php'; ?>

  <h1>Novo projeto</h1>

  <form action="/post_create.php" method="post" enctype="multipart/form-data" class="card">
    <input type="hidden" name="csrf" value="<?= esc(csrf_token()) ?>">

    <label>
      Título
      <input type="text" name="title" value="<?= esc(old('title')) ?>" required>
    </label>

    <label>
      Descrição
      <textarea name="description" required><?= esc(old('description')) ?></textarea>
    </label>

    <label>
      Imagem
      <input type="file" name="image">
    </label>

    <label>
      Linguagens
      <input type="text" name="languages" value="<?= esc(old('languages')) ?>">
    </label>

    <label>
      Tags
      <input type="text" name="tags" value="<?= esc(old('tags')) ?>">
    </label>

    <label>
      Email
      <input type="email" name="contact_email" value="<?= esc(old('contact_email')) ?>">
    </label>

    <label>
      Link
      <input type="url" name="contact_link" value="<?= esc(old('contact_link')) ?>">
    </label>

    <button type="submit">Publicar</button>
  </form>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/partials/footer.php'; ?>
</body>
</html>
