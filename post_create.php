<?php
require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/helpers/utils.php';
require_once __DIR__ . '/app/helpers/csrf.php';
require_once __DIR__ . '/app/models/Post.php';

require_login();
$user = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        flash('error', 'Token CSRF inválido.');
        redirect('post_create.php');
    }

    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $tags        = trim($_POST['tags'] ?? '');
    $languages   = trim($_POST['languages'] ?? '');
    $contact_email = trim($_POST['contact_email'] ?? '');
    $contact_link  = trim($_POST['contact_link'] ?? '');

    remember_old($_POST);

    if ($title === '' || $description === '') {
        flash('error', 'Título e descrição são obrigatórios.');
        redirect('post_create.php');
    }

    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $imageName = uniqid('post_') . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_POSTS . '/' . $imageName);
        }
    }

    $postId = Post::create((int)$user['id'], [
        'title'         => $title,
        'description'   => $description,
        'image'         => $imageName,
        'tags'          => $tags,
        'languages'     => $languages,
        'contact_email' => $contact_email,
        'contact_link'  => $contact_link,
    ]);

    flash('success', 'Projeto criado com sucesso!');
    redirect('post_show.php?id=' . $postId);
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

  <h1>Novo projeto</h1>

  <form action="post_create.php" method="post" enctype="multipart/form-data" class="card">
    <input type="hidden" name="csrf" value="<?= esc(csrf_token()) ?>">

    <label>
      Título do projeto
      <input type="text" name="title" value="<?= esc(old('title')) ?>" required>
    </label>

    <label>
      Descrição
      <textarea name="description" rows="6" required><?= esc(old('description')) ?></textarea>
    </label>

    <label>
      Imagem do projeto (opcional)
      <input type="file" name="image" accept="image/*">
    </label>

    <label>
      Linguagens / tecnologias (separadas por vírgula)
      <input type="text" name="languages" placeholder="PHP, MySQL, JavaScript" value="<?= esc(old('languages')) ?>">
    </label>

    <label>
      Hashtags (separadas por vírgula)
      <input type="text" name="tags" placeholder="startup, backend, frontend" value="<?= esc(old('tags')) ?>">
    </label>

    <label>
      E-mail para contato (opcional)
      <input type="email" name="contact_email" value="<?= esc(old('contact_email')) ?>">
    </label>

    <label>
      Link para contato/projeto (GitHub, site, etc)
      <input type="url" name="contact_link" value="<?= esc(old('contact_link')) ?>">
    </label>

    <button class="btn-primary" type="submit">Publicar</button>
  </form>
</main>
<?php include __DIR__ . '/views/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>