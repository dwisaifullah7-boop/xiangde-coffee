/* =====================================================
   CHECKOUT JS - Checkout page interactions
   ===================================================== */

(function () {
  'use strict';

  // Selected styling for payment & order type
  document.querySelectorAll('.payment-option, .order-type-option').forEach(opt => {
    const input = opt.querySelector('input[type="radio"]');
    if (!input) return;
    if (input.checked) opt.classList.add('selected');

    opt.addEventListener('click', function () {
      // Uncheck siblings
      const siblings = this.parentElement.querySelectorAll('.payment-option, .order-type-option');
      siblings.forEach(s => s.classList.remove('selected'));
      this.classList.add('selected');
      input.checked = true;
    });
  });

  // Confirm before placing order
  const checkoutForm = document.getElementById('checkoutForm');
  if (checkoutForm) {
    checkoutForm.addEventListener('submit', function (e) {
      e.preventDefault();
      Swal.fire({
        title: 'Konfirmasi Pesanan',
        text: 'Pastikan data pesanan sudah benar. Lanjutkan checkout?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4a2c1a',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Ya, pesan sekarang',
        cancelButtonText: 'Periksa lagi'
      }).then(r => { if (r.isConfirmed) this.submit(); });
    });
  }

  // Print invoice
  const printBtn = document.getElementById('printInvoice');
  if (printBtn) {
    printBtn.addEventListener('click', () => window.print());
  }
})();
