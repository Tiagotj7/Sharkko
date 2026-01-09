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
      <input id="avatarInput" type="file" name="avatar" accept="image/*">
      <?php if (!empty($data['avatar'])): ?>
        <p>Avatar atual: <img src="uploads/avatars/<?= esc($data['avatar']) ?>" width="80" alt="Avatar"></p>
      <?php endif; ?>

      <!-- Preview and crop interface -->
      <div id="avatarPreviewWrap" style="display:none; margin-top:15px; padding:15px; background:#f5f5f5; border-radius:8px;">
        <h4 style="margin-top:0;">Ajuste sua foto</h4>
        <p style="font-size:0.9rem; color:#666; margin:5px 0 10px 0;">Arraste para reposicionar • Use o zoom para ajustar</p>
        
        <div style="text-align:center; margin-bottom:15px;">
          <div id="avatarPreview" style="width:200px; height:200px; margin:0 auto; border-radius:50%; overflow:hidden; background:#ddd; position:relative; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <img id="avatarPreviewImg" src="" alt="Preview" style="position:absolute; top:0; left:0;">
          </div>
        </div>

        <!-- Zoom control -->
        <div style="margin-bottom:15px;">
          <label style="display:flex; align-items:center; gap:10px;">
            <span>🔍 Zoom:</span>
            <input id="avatarZoom" type="range" min="1" max="3" step="0.1" value="1" style="flex:1;">
            <span id="zoomValue" style="min-width:50px; font-weight:bold; color:#2563eb;">100%</span>
          </label>
        </div>

        <!-- Validation message -->
        <div id="avatarValidationMsg" style="display:none; padding:10px; margin-bottom:15px; border-radius:4px; font-size:0.9rem;"></div>

        <!-- Hidden inputs -->
        <input type="hidden" name="avatar_crop_scale" id="avatarCropScale" value="1">
        <input type="hidden" name="avatar_crop_x" id="avatarCropX" value="0">
        <input type="hidden" name="avatar_crop_y" id="avatarCropY" value="0">

        <div style="display:flex; gap:10px; justify-content:flex-end;">
          <button type="button" id="avatarCancelBtn" class="btn-secondary">Cancelar</button>
          <button type="button" id="avatarConfirmBtn" class="btn-primary">Confirmar</button>
        </div>

        <p style="font-size:0.85rem; color:#999; margin-top:10px; margin-bottom:0;">JPG, PNG ou GIF • Mín. 400x400px • Máx. 5MB</p>
      </div>
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
<script src="assets/js/main.js"></script>
<script src="assets/js/avatar-preview.js"></script>
<script src="assets/js/theme-toggle.js"></script>
</body>
</html>
