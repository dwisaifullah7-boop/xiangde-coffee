/* =====================================================
   AUTH JS - Login & Register form interactions
   ===================================================== */

(function () {
  'use strict';

  // Toggle password visibility (fix: target the <i> inside the toggle span)
  document.querySelectorAll('.password-toggle').forEach(toggle => {
    toggle.addEventListener('click', function () {
      const input = this.closest('.input-group').querySelector('input');
      if (!input) return;
      const icon = this.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        if (icon) {
          icon.classList.remove('fa-eye');
          icon.classList.add('fa-eye-slash');
        }
      } else {
        input.type = 'password';
        if (icon) {
          icon.classList.remove('fa-eye-slash');
          icon.classList.add('fa-eye');
        }
      }
    });
  });

  // Password strength meter (register page)
  const pwInput = document.getElementById('password');
  const meter   = document.getElementById('pwStrength');
  if (pwInput && meter) {
    pwInput.addEventListener('input', function () {
      const v = this.value;
      let score = 0;
      if (v.length >= 6) score++;
      if (v.length >= 10) score++;
      if (/[A-Z]/.test(v)) score++;
      if (/[0-9]/.test(v)) score++;
      if (/[^a-zA-Z0-9]/.test(v)) score++;

      const labels = ['Sangat lemah', 'Lemah', 'Cukup', 'Baik', 'Kuat'];
      const colors = ['#dc3545', '#fd7e14', '#ffc107', '#17a2b8', '#28a745'];
      meter.style.width = (score / 5 * 100) + '%';
      meter.style.background = colors[score - 1] || '#dc3545';
      meter.textContent = labels[score - 1] || '';
    });
  }

  // Confirm password match
  const confirmInput = document.getElementById('confirm_password');
  if (confirmInput) {
    confirmInput.addEventListener('input', function () {
      const original = document.getElementById('password').value;
      if (this.value && this.value !== original) {
        this.setCustomValidity('Konfirmasi password tidak cocok.');
      } else {
        this.setCustomValidity('');
      }
    });
  }

  // Auto-hide alert after 5 seconds
  setTimeout(() => {
    document.querySelectorAll('.auth-alert').forEach(a => {
      a.style.transition = 'opacity .5s';
      a.style.opacity = '0';
      setTimeout(() => a.remove(), 500);
    });
  }, 5000);
})();
