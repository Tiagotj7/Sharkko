// assets/js/main.js

// Confirm delete actions
document.addEventListener('DOMContentLoaded', function () {
  const deleteButtons = document.querySelectorAll('button[type="submit"][onclick*="confirm"]');
  deleteButtons.forEach(btn => {
    btn.addEventListener('click', function (e) {
      if (!confirm('Tem certeza que deseja excluir?')) {
        e.preventDefault();
      }
    });
  });

  // Auto-hide flash messages after 5 seconds
  const flashes = document.querySelectorAll('.alert');
  flashes.forEach(flash => {
    setTimeout(() => {
      flash.style.opacity = '0';
      setTimeout(() => flash.remove(), 300);
    }, 5000);
  });

  // Simple form validation
  const forms = document.querySelectorAll('form');
  forms.forEach(form => {
    form.addEventListener('submit', function (e) {
      const requiredFields = form.querySelectorAll('input[required], textarea[required]');
      let valid = true;
      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          field.style.borderColor = 'red';
          valid = false;
        } else {
          field.style.borderColor = '';
        }
      });
      if (!valid) {
        e.preventDefault();
        alert('Preencha todos os campos obrigatórios.');
      }
    });
  });
});
