/* =====================================================
   MAIN JS - Public website interactions (v2)
   ===================================================== */

(function () {
  'use strict';

  // Navbar scroll effect — adds .scrolled when user scrolls down
  const navbar = document.getElementById('mainNavbar');
  if (navbar) {
    const onScroll = () => {
      if (window.scrollY > 40) navbar.classList.add('scrolled');
      else navbar.classList.remove('scrolled');
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // Auto-hide bootstrap collapse on click (mobile)
  document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
    link.addEventListener('click', () => {
      const nav = document.getElementById('mainNav');
      if (nav && nav.classList.contains('show')) {
        const bsCollapse = bootstrap.Collapse.getInstance(nav) || new bootstrap.Collapse(nav, {toggle:false});
        bsCollapse.hide();
      }
    });
  });

  // Quantity controls (product detail, cart)
  document.querySelectorAll('[data-quantity]').forEach(wrapper => {
    const input = wrapper.querySelector('input');
    if (!input) return;
    const min = parseInt(input.min || '1', 10);
    const max = parseInt(input.max || '0', 10);

    wrapper.querySelector('.qty-minus')?.addEventListener('click', () => {
      let v = parseInt(input.value || '1', 10);
      if (isNaN(v)) v = min;
      if (v > min) input.value = v - 1;
      input.dispatchEvent(new Event('change', { bubbles: true }));
    });
    wrapper.querySelector('.qty-plus')?.addEventListener('click', () => {
      let v = parseInt(input.value || '1', 10);
      if (isNaN(v)) v = min;
      v = v + 1;
      if (max > 0 && v > max) v = max;
      input.value = v;
      input.dispatchEvent(new Event('change', { bubbles: true }));
    });
    input.addEventListener('input', () => {
      let v = parseInt(input.value || '1', 10);
      if (isNaN(v) || v < min) input.value = min;
      if (max > 0 && v > max) input.value = max;
    });
  });

  // Filter form on menu page (auto-submit on change)
  const filterForm = document.getElementById('filterForm');
  if (filterForm) {
    filterForm.querySelectorAll('select').forEach(el => {
      el.addEventListener('change', () => filterForm.submit());
    });
  }

  // Confirm before delete
  document.querySelectorAll('[data-confirm]').forEach(btn => {
    btn.addEventListener('click', e => {
      const msg = btn.getAttribute('data-confirm') || 'Apakah Anda yakin?';
      e.preventDefault();
      Swal.fire({
        title: 'Konfirmasi',
        text: msg,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#292524',
        cancelButtonColor: '#a8a29e',
        confirmButtonText: 'Ya, lanjutkan',
        cancelButtonText: 'Batal'
      }).then(r => {
        if (r.isConfirmed) {
          const href = btn.getAttribute('href');
          if (href) window.location.href = href;
        }
      });
    });
  });

  // Reveal-on-scroll animation
  const revealEls = document.querySelectorAll('[data-reveal]');
  if (revealEls.length && 'IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });
    revealEls.forEach(el => io.observe(el));
  }
})();
