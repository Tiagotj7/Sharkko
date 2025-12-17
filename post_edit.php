<?php
require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/helpers/utils.php';
require_once __DIR__ . '/app/helpers/csrf.php';
require_once __DIR__ . '/app/models/User.php';

require_login();
$user = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        flash('error', 'Token CSRF inválido.');
        redirect('profile_edit.php');
    }

    $name        = trim($_POST['name'] ?? '');
    $bio         = trim($_POST['bio'] ?? '');
    $location    = trim($_POST['location'] ?? '');
    $github_url  = trim($_POST['github_url'] ?? '');
    $linkedin_url= trim($_POST['linkedin_url'] ?? '');
    $website_url = trim($_POST['website_url'] ?? '');
    $theme       = in_array($_POST['theme'] ?? 'dark', ['dark','light']) ? $_POST['theme'] : 'dark';

    $avatarName = $user['avatar'] ?? null;
    if (!empty($_FILES['avatar']['name'])) {
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $avatarName = uniqid('avatar_') . '.' . $ext;
            move_uploaded_file($_FILES['avatar']['tmp_name'], UPLOAD_AVATARS . '/' . $avatarName);
        }
    }

    User::updateProfile((int)$user['id'], [
        'name'         => $name,
        'bio'          => $bio,
        'location'     => $location,
        'github_url'   => $github_url,
        'linkedin_url' => $linkedin_url,
        'website_url'  => $website_url,
        'avatar'       => $avatarName,
        'theme'        => $theme,
    ]);

    flash('success', 'Perfil atualizado.');
    redirect('profile.php?id=' . (int)$user['id']);
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

  <h1>Editar perfil</h1>

  <form action="profile_edit.php" method="post" enctype="multipart/form-data" class="card">
    <input type="hidden" name="csrf" value="<?= esc(csrf_token()) ?>">

    <label>
      Nome
      <input type="text" name="name" value="<?= esc($user['name']) ?>" required>
    </label>

    <label>
      Bio
      <textarea name="bio" rows="4"><?= esc($user['bio'] ?? '') ?></textarea>
    </label>

    <label>
      Localização
      <input type="text" name="location" value="<?= esc($user['location'] ?? '') ?>">
    </label>

    <label>
      GitHub URL
      <input type="url" name="github_url" value="<?= esc($user['github_url'] ?? '') ?>">
    </label>

    <label>
      LinkedIn URL
      <input type="url" name="linkedin_url" value="<?= esc($user['linkedin_url'] ?? '') ?>">
    </label>

    <label>
      Website / Portfólio
      <input type="url" name="website_url" value="<?= esc($user['website_url'] ?? '') ?>">
    </label>

    <label>
      Avatar (imagem)
      <input type="file" name="avatar" accept="image/*">
    </label>

    <label>
      Tema
      <select name="theme">
        <option value="dark" <?= ($user['theme'] ?? 'dark') === 'dark' ? 'selected' : '' ?>>Dark</option>
        <option value="light" <?= ($user['theme'] ?? 'dark') === 'light' ? 'selected' : '' ?>>Light</option>
      </select>
    </label>

    <button class="btn-primary" type="submit">Salvar</button>
  </form>
</main>
<?php include __DIR__ . '/views/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>