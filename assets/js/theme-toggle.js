// assets/js/theme-toggle.js
document.addEventListener('DOMContentLoaded', function () {
  const btn = document.getElementById('themeToggle');
  if (!btn) return;

  btn.addEventListener('click', function () {
    const html = document.documentElement;
    const current = html.getAttribute('data-theme') || 'dark';
    const next = current === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
  });
});