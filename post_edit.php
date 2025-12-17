<?php
require_once __DIR__ . '/app/controllers/PostController.php';

PostController::edit();

$postId = (int)($_GET['id'] ?? 0);
$post = Post::findById($postId);

if (!$post || (int)$post['user_id'] !== (int)$user['id']) {
    flash('error', 'Post não encontrado ou você não é o autor.');
    redirect('feed.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        flash('error', 'Token CSRF inválido.');
        redirect('post_edit.php?id=' . $postId);
    }

    $title         = trim($_POST['title'] ?? '');
    $description   = trim($_POST['description'] ?? '');
    $tags          = trim($_POST['tags'] ?? '');
    $languages     = trim($_POST['languages'] ?? '');
    $contact_email = trim($_POST['contact_email'] ?? '');
    $contact_link  = trim($_POST['contact_link'] ?? '');

    if ($title === '' || $description === '') {
        flash('error', 'Título e descrição são obrigatórios.');
        redirect('post_edit.php?id=' . $postId);
    }

    $imageName = $post['image'] ?? null;
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $imageName = uniqid('post_') . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_POSTS . '/' . $imageName);
        }
    }

    Post::update($postId, (int)$user['id'], [
        'title'         => $title,
        'description'   => $description,
        'image'         => $imageName,
        'tags'          => $tags,
        'languages'     => $languages,
        'contact_email' => $contact_email,
        'contact_link'  => $contact_link,
    ]);

    flash('success', 'Projeto atualizado.');
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

  <h1>Editar projeto</h1>

  <form action="post_edit.php?id=<?= (int)$post['id'] ?>" method="post" enctype="multipart/form-data" class="card">
    <input type="hidden" name="csrf" value="<?= esc(csrf_token()) ?>">

    <label>
      Título do projeto
      <input type="text" name="title" value="<?= esc($post['title']) ?>" required>
    </label>

    <label>
      Descrição
      <textarea name="description" rows="6" required><?= esc($post['description']) ?></textarea>
    </label>

    <?php if (!empty($post['image'])): ?>
      <p>Imagem atual:</p>
      <img src="uploads/posts/<?= esc($post['image']) ?>" class="post-image" alt="">
    <?php endif; ?>

    <label>
      Nova imagem (opcional)
      <input type="file" name="image" accept="image/*">
    </label>

    <label>
      Linguagens / tecnologias
      <input type="text" name="languages" value="<?= esc($post['languages']) ?>">
    </label>

    <label>
      Hashtags
      <input type="text" name="tags" value="<?= esc($post['tags']) ?>">
    </label>

    <label>
      E-mail para contato
      <input type="email" name="contact_email" value="<?= esc($post['contact_email']) ?>">
    </label>

    <label>
      Link de contato/projeto
      <input type="url" name="contact_link" value="<?= esc($post['contact_link']) ?>">
    </label>

    <button class="btn-primary" type="submit">Salvar</button>
  </form>
</main>
<?php include __DIR__ . '/views/partials/footer.php'; ?>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>