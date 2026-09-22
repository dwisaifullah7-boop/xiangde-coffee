<?php
/**
 * Footer publik (Redesigned).
 */
$businessName = setting('business_name', 'Xiang De Coffee');
$address      = setting('business_address', '');
$phone        = setting('business_phone', '');
$email        = setting('business_email', '');
$ig           = setting('instagram_url', '#');
$gofood       = setting('gofood_url', '#');
$maps         = setting('google_maps_url', '#');
$hours        = setting('opening_hours', '');
$footerText   = setting('footer_text', 'Xiang De Coffee');
$year         = date('Y');
?>
</main>

<footer class="xd-footer">
  <div class="container">
    <div class="row g-4 py-5">
      <div class="col-lg-4 col-md-6">
        <h4 class="footer-title">
          <span class="brand-icon"><i class="fas fa-mug-hot"></i></span>
          <?= e($businessName) ?>
        </h4>
        <p class="small" style="color:rgba(240,235,227,.65); line-height:1.8;">
          <?= e(setting('about_text', '')) ?>
        </p>
        <div class="d-flex gap-2 mt-3">
          <a href="<?= e($ig) ?>" target="_blank" class="social-btn" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="<?= e($gofood) ?>" target="_blank" class="social-btn" aria-label="GoFood"><i class="fas fa-utensils"></i></a>
          <a href="<?= e($maps) ?>" target="_blank" class="social-btn" aria-label="Maps"><i class="fas fa-map-marker-alt"></i></a>
          <a href="tel:<?= e($phone) ?>" class="social-btn" aria-label="Telepon"><i class="fas fa-phone"></i></a>
        </div>
      </div>

      <div class="col-lg-2 col-md-6 col-6">
        <h6 class="footer-subtitle">Navigasi</h6>
        <ul class="footer-links">
          <li><a href="<?= url('index.php') ?>"><i class="fas fa-chevron-right" style="font-size:.55rem;"></i> Home</a></li>
          <li><a href="<?= url('about.php') ?>"><i class="fas fa-chevron-right" style="font-size:.55rem;"></i> About</a></li>
          <li><a href="<?= url('menu.php') ?>"><i class="fas fa-chevron-right" style="font-size:.55rem;"></i> Menu</a></li>
          <li><a href="<?= url('gallery.php') ?>"><i class="fas fa-chevron-right" style="font-size:.55rem;"></i> Gallery</a></li>
          <li><a href="<?= url('contact.php') ?>"><i class="fas fa-chevron-right" style="font-size:.55rem;"></i> Contact</a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-6 col-6">
        <h6 class="footer-subtitle">Kontak</h6>
        <ul class="footer-contact">
          <li><i class="fas fa-map-marker-alt"></i> <span><?= e($address) ?></span></li>
          <li><i class="fas fa-phone"></i> <span><?= e($phone) ?></span></li>
          <li><i class="fas fa-envelope"></i> <span><?= e($email) ?></span></li>
          <li><i class="fas fa-clock"></i> <span><?= e($hours) ?></span></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-6">
        <h6 class="footer-subtitle">Order Sekarang</h6>
        <p class="small mb-3" style="color:rgba(240,235,227,.65);">
          Pesan langsung melalui GoFood atau kunjungi kami untuk dine-in.
        </p>
        <a href="<?= e($gofood) ?>" target="_blank" class="btn btn-gofood w-100 mb-2">
          <i class="fas fa-utensils me-2"></i> Order via GoFood
        </a>
        <a href="<?= url('menu.php') ?>" class="btn btn-outline-light w-100">
          <i class="fas fa-coffee me-2"></i> Lihat Menu
        </a>
      </div>
    </div>

    <hr class="footer-divider">
    <div class="footer-bottom">
      <small>&copy; <?= $year ?> <?= e($footerText) ?>. All rights reserved.</small>
      <small>Made with <i class="fas fa-heart text-danger"></i> in Cirebon</small>
    </div>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- App JS -->
<script src="<?= url('assets/js/main.js') ?>"></script>
<script src="<?= url('assets/js/notifications.js') ?>"></script>

<?php
// Hook untuk menambahkan script khusus halaman (dari variable $extraJs)
if (!empty($extraJs) && is_array($extraJs)):
    foreach ($extraJs as $jsUrl): ?>
        <script src="<?= e($jsUrl) ?>"></script>
    <?php endforeach;
endif;
?>

<?php $flashes = get_flashes(); if ($flashes): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    <?php foreach ($flashes as $f): ?>
    Swal.fire({
      toast: true, position: 'top-end', showConfirmButton: false, timer: 3500, timerProgressBar: true,
      icon: '<?= $f['type'] === 'error' ? 'error' : ($f['type'] === 'warning' ? 'warning' : ($f['type'] === 'info' ? 'info' : 'success')) ?>',
      title: <?= json_encode($f['message']) ?>
    });
    <?php endforeach; ?>
  });
</script>
<?php endif; ?>

</body>
</html>
