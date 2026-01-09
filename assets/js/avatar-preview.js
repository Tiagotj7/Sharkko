// assets/js/avatar-preview.js

document.addEventListener('DOMContentLoaded', function () {
  const input = document.getElementById('avatarInput');
  const container = document.querySelector('.avatar-container');
  const previewWrap = document.getElementById('avatarPreviewWrap');
  const previewImg = document.getElementById('avatarPreviewImg');
  const preview = document.getElementById('avatarPreview');
  const zoom = document.getElementById('avatarZoom');
  const zoomValue = document.getElementById('zoomValue');
  const validationMsg = document.getElementById('avatarValidationMsg');
  const confirmBtn = document.getElementById('avatarConfirmBtn');
  const cancelBtn = document.getElementById('avatarCancelBtn');
  const cropScale = document.getElementById('avatarCropScale');
  const cropX = document.getElementById('avatarCropX');
  const cropY = document.getElementById('avatarCropY');

  const MIN_WIDTH = 400;
  const MAX_WIDTH = 4000;
  const MIN_HEIGHT = 400;
  const MAX_HEIGHT = 4000;
  const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
  const PREVIEW_SIZE = 240;

  let dragging = false;
  let startX = 0;
  let startY = 0;
  let imgX = 0;
  let imgY = 0;
  let scale = 1;
  let minScale = 1;
  let imgWidth = 0;
  let imgHeight = 0;

  // Open file input when container clicked
  if (container) {
    container.addEventListener('click', () => input.click());
  }

  function showValidation(message, isError = true) {
    if (validationMsg) {
      validationMsg.textContent = message;
      validationMsg.className = 'avatar-validation-msg ' + (isError ? 'error' : 'success');
      validationMsg.style.display = 'block';
      if (!isError) {
        setTimeout(() => validationMsg.style.display = 'none', 3000);
      }
    }
  }

  function updateTransform() {
    // Use scale first, then translate for proper centering
    // With transformOrigin at 0,0: scale(s) translate(x, y)
    previewImg.style.transformOrigin = '0 0';
    const scaledWidth = imgWidth * scale;
    const scaledHeight = imgHeight * scale;
    const tx = (PREVIEW_SIZE - scaledWidth) / 2 + imgX;
    const ty = (PREVIEW_SIZE - scaledHeight) / 2 + imgY;
    previewImg.style.transform = `scale(${scale}) translate(${tx}px, ${ty}px)`;
    
    cropScale.value = Number(scale.toFixed(2));
    cropX.value = Number(imgX.toFixed(0));
    cropY.value = Number(imgY.toFixed(0));
  }

  function constrainPosition() {
    // Allow dragging but limit how far off-center it can go
    // imgX and imgY are offsets from the center
    const maxDrag = 40; // pixels
    imgX = Math.max(-maxDrag, Math.min(maxDrag, imgX));
    imgY = Math.max(-maxDrag, Math.min(maxDrag, imgY));
  }

  if (zoom) {
    zoom.addEventListener('input', function () {
      scale = parseFloat(this.value);
      if (zoomValue) zoomValue.textContent = Math.round(scale * 100) + '%';
      constrainPosition();
      updateTransform();
    });
  }

  if (input) {
    input.addEventListener('change', function (e) {
      const file = this.files && this.files[0];
      if (!file) return;

      if (file.size > MAX_FILE_SIZE) {
        showValidation('Arquivo muito grande. Máximo de 5MB.');
        input.value = '';
        return;
      }

      if (!file.type.startsWith('image/')) {
        showValidation('Tipo inválido. Use JPG, PNG ou GIF.');
        input.value = '';
        return;
      }

      const url = URL.createObjectURL(file);
      previewImg.src = url;

      previewImg.onload = function () {
        imgWidth = previewImg.naturalWidth;
        imgHeight = previewImg.naturalHeight;

        if (imgWidth < MIN_WIDTH || imgHeight < MIN_HEIGHT) {
          showValidation(`Imagem muito pequena. Mínimo ${MIN_WIDTH}x${MIN_HEIGHT}px.`);
          previewWrap.style.display = 'none';
          input.value = '';
          return;
        }

        if (imgWidth > MAX_WIDTH || imgHeight > MAX_HEIGHT) {
          showValidation(`Imagem muito grande. Máximo ${MAX_WIDTH}x${MAX_HEIGHT}px.`);
          previewWrap.style.display = 'none';
          input.value = '';
          return;
        }

        const scaleX = PREVIEW_SIZE / imgWidth;
        const scaleY = PREVIEW_SIZE / imgHeight;
        minScale = Math.max(scaleX, scaleY);

        zoom.min = minScale.toFixed(2);
        zoom.max = '3';
        zoom.step = '0.1';

        scale = minScale;
        zoom.value = minScale.toFixed(2);
        if (zoomValue) zoomValue.textContent = Math.round(scale * 100) + '%';

        // Start centered (imgX and imgY should be 0 when centered)
        imgX = 0;
        imgY = 0;

        if (validationMsg) validationMsg.style.display = 'none';
        previewWrap.style.display = 'block';
        updateTransform();
      };

      previewImg.onerror = function () {
        showValidation('Não foi possível carregar a imagem.');
        input.value = '';
        previewWrap.style.display = 'none';
      };
    });
  }

  if (preview) {
    preview.addEventListener('pointerdown', function (e) {
      if (scale <= minScale + 0.01) return;
      dragging = true;
      startX = e.clientX;
      startY = e.clientY;
      preview.style.cursor = 'grabbing';
      try { preview.setPointerCapture(e.pointerId); } catch (err) {}
    });

    preview.addEventListener('pointermove', function (e) {
      if (!dragging) return;
      const dx = e.clientX - startX;
      const dy = e.clientY - startY;
      startX = e.clientX;
      startY = e.clientY;
      imgX += dx;
      imgY += dy;
      constrainPosition();
      updateTransform();
    });

    preview.addEventListener('pointerup', function (e) {
      dragging = false;
      preview.style.cursor = 'grab';
      try { preview.releasePointerCapture(e.pointerId); } catch (err) {}
    });

    preview.addEventListener('pointercancel', function () {
      dragging = false;
      preview.style.cursor = 'grab';
    });
  }

  if (cancelBtn) {
    cancelBtn.addEventListener('click', function () {
      input.value = '';
      previewWrap.style.display = 'none';
      if (validationMsg) validationMsg.style.display = 'none';
    });
  }

  if (confirmBtn) {
    confirmBtn.addEventListener('click', function () {
      previewWrap.style.display = 'none';
      showValidation('Foto pronta para salvar!', false);
    });
  }

});
