<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php include __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/../partials/header.php'; ?>

<main class="container main">
  <?php include __DIR__ . '/../partials/flash.php'; ?>

  <h1>Editar perfil</h1>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <ul>
        <?php foreach ($errors as $error): ?>
          <li><?= esc($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="profile_edit.php" method="post" enctype="multipart/form-data" class="card">
    <input type="hidden" name="csrf_token" value="<?= esc(csrf_token()) ?>">

    <label>
      Nome
      <input type="text" name="name" value="<?= esc(old('name', $data['name'])) ?>" required>
    </label>

    <label>
      Bio
      <textarea name="bio" rows="3"><?= esc(old('bio', $data['bio'])) ?></textarea>
    </label>

    <label>
      Localização
      <input type="text" name="location" value="<?= esc(old('location', $data['location'])) ?>">
    </label>

    <label>
      Avatar (opcional)
      <input type="file" name="avatar" accept="image/*">
      <?php if (!empty($data['avatar'])): ?>
        <p>Avatar atual: <img src="uploads/avatars/<?= esc($data['avatar']) ?>" width="50" alt=""></p>
      <?php endif; ?>
    </label>

    <label>
      GitHub URL
      <input type="url" name="github_url" value="<?= esc(old('github_url', $data['github_url'])) ?>">
    </label>

    <label>
      LinkedIn URL
      <input type="url" name="linkedin_url" value="<?= esc(old('linkedin_url', $data['linkedin_url'])) ?>">
    </label>

    <label>
      Website URL
      <input type="url" name="website_url" value="<?= esc(old('website_url', $data['website_url'])) ?>">
    </label>

    <button type="submit" class="btn-primary">Salvar</button>
  </form>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>
