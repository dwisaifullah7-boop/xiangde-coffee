/* =====================================================
   CART JS - Cart interactions (add, update, remove)
   ===================================================== */

(function () {
  'use strict';

  // Add to cart form (product detail)
  const addForm = document.getElementById('addCartForm');
  if (addForm) {
    addForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      const btn = this.querySelector('button[type="submit"]');
      const original = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menambahkan...';

      fetch(this.action, { method: 'POST', body: formData })
        .then(function (r) {
          // Cek content-type — kalau bukan JSON, server kemungkinan
          // mengembalikan halaman error HTML (mis. fatal error PHP).
          var ct = r.headers.get('content-type') || '';
          if (ct.indexOf('application/json') === -1) {
            return r.text().then(function (txt) {
              console.error('Non-JSON response from cart-add:', txt.substring(0, 500));
              throw new Error('Server mengembalikan respons tidak valid (bukan JSON).');
            });
          }
          return r.json();
        })
        .then(function (data) {
          if (data.success) {
            Swal.fire({
              toast: true, position: 'top-end', showConfirmButton: false, timer: 2500,
              icon: 'success', title: data.message || 'Ditambahkan ke keranjang'
            });
            // Update cart counter
            const counter = document.getElementById('cartCount');
            if (counter && data.cart_count !== undefined) {
              counter.textContent = data.cart_count;
              counter.style.display = data.cart_count > 0 ? 'inline-block' : 'none';
            }
          } else {
            // Bila server minta login, arahkan ke halaman login.
            if (data.require_login && typeof XD_BASE_URL !== 'undefined') {
              Swal.fire({
                icon: 'warning',
                title: 'Login Diperlukan',
                text: data.message || 'Silakan login terlebih dahulu.',
                confirmButtonText: 'Login'
              }).then(function (res) {
                if (res.isConfirmed) {
                  window.location.href = XD_BASE_URL + '/login.php';
                }
              });
              return;
            }
            Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Terjadi kesalahan.' });
          }
        })
        .catch(function (err) {
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: err && err.message ? err.message : 'Tidak dapat terhubung ke server. Periksa koneksi Anda.'
          });
        })
        .finally(function () { btn.disabled = false; btn.innerHTML = original; });
    });
  }

  // Cart update (quantity change)
  document.querySelectorAll('.cart-qty-update').forEach(function (select) {
    select.addEventListener('change', function () {
      const form = this.closest('form');
      if (form) form.submit();
    });
  });
})();
