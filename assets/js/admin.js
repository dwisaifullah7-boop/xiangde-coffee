/* =====================================================
   ADMIN JS - Sidebar toggle, charts init, helpers (v2)
   ===================================================== */

(function () {
  'use strict';

  // Sidebar toggle
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar       = document.getElementById('adminSidebar');
  const overlay       = document.getElementById('sidebarOverlay');

  function openSidebar() {
    if (sidebar) sidebar.classList.add('show');
    if (overlay) overlay.classList.add('show');
    document.body.style.overflow = 'hidden';
  }
  function closeSidebar() {
    if (sidebar) sidebar.classList.remove('show');
    if (overlay) overlay.classList.remove('show');
    document.body.style.overflow = '';
  }

  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      if (window.innerWidth >= 992) {
        document.body.classList.toggle('sidebar-collapsed');
      } else {
        if (sidebar && sidebar.classList.contains('show')) closeSidebar();
        else openSidebar();
      }
    });
  }
  if (overlay) overlay.addEventListener('click', closeSidebar);

  // Close sidebar on escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeSidebar();
  });

  // Confirm delete
  document.querySelectorAll('[data-confirm-delete]').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      const msg = btn.getAttribute('data-confirm-delete') || 'Hapus data ini?';
      const href = btn.getAttribute('href');
      Swal.fire({
        title: 'Konfirmasi Hapus',
        text: msg,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#a8a29e',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
      }).then(r => { if (r.isConfirmed && href) window.location.href = href; });
    });
  });

  // Generic confirm
  document.querySelectorAll('[data-confirm]').forEach(btn => {
    btn.addEventListener('click', e => {
      const msg = btn.getAttribute('data-confirm') || 'Apakah Anda yakin?';
      e.preventDefault();
      Swal.fire({
        title: 'Konfirmasi',
        text: msg,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#292524',
        cancelButtonColor: '#a8a29e',
        confirmButtonText: 'Ya, lanjutkan',
        cancelButtonText: 'Batal'
      }).then(r => { if (r.isConfirmed) window.location.href = btn.getAttribute('href'); });
    });
  });

  // Render dashboard charts if data is present
  const salesEl = document.getElementById('salesChart');
  if (salesEl && window.salesData) {
    new Chart(salesEl, {
      type: 'line',
      data: {
        labels: window.salesData.labels,
        datasets: [{
          label: 'Pendapatan (Rp)',
          data: window.salesData.values,
          borderColor: '#c8a97e',
          backgroundColor: 'rgba(200,169,126,.12)',
          tension: .4,
          fill: true,
          borderWidth: 3,
          pointBackgroundColor: '#fff',
          pointBorderColor: '#c8a97e',
          pointBorderWidth: 2,
          pointRadius: 5,
          pointHoverRadius: 7
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#1c1917',
            titleColor: '#fff',
            bodyColor: '#fff',
            padding: 12,
            borderRadius: 10,
            callbacks: { label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID') }
          }
        },
        scales: {
          y: {
            ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID'), color: '#a8a29e' },
            grid: { color: 'rgba(0,0,0,.04)' }
          },
          x: { ticks: { color: '#a8a29e' }, grid: { display: false } }
        }
      }
    });
  }

  const catEl = document.getElementById('categoryChart');
  if (catEl && window.categoryData) {
    new Chart(catEl, {
      type: 'doughnut',
      data: {
        labels: window.categoryData.labels,
        datasets: [{
          data: window.categoryData.values,
          backgroundColor: ['#c8a97e', '#b8956a', '#e8d5b7', '#6b3e25', '#f5ebe0', '#a67c52'],
          borderWidth: 3,
          borderColor: '#fff'
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
          legend: { position: 'bottom', labels: { color: '#57534e', padding: 14, font: { size: 12 } } },
          tooltip: {
            backgroundColor: '#1c1917',
            padding: 12,
            borderRadius: 10
          }
        }
      }
    });
  }
})();
