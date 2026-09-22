/* =====================================================
   NOTIFICATIONS JS - Toast / flash message helpers
   ===================================================== */

window.xdNotify = function (message, type = 'success') {
  if (typeof Swal === 'undefined') {
    alert(message);
    return;
  }
  Swal.fire({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    icon: type === 'error' ? 'error' : (type === 'warning' ? 'warning' : (type === 'info' ? 'info' : 'success')),
    title: message
  });
};

// Auto-init tooltips
document.addEventListener('DOMContentLoaded', function () {
  if (window.bootstrap) {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
      new bootstrap.Tooltip(el);
    });
  }
});
