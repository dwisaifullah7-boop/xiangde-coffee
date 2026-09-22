<?php
/**
 * Halaman Contact (Redesigned)
 */
require_once __DIR__ . '/includes/functions.php';
$currentPage = 'contact';
$pageTitle   = 'Contact';

require __DIR__ . '/includes/header.php';
?>

<section class="breadcrumb-section">
  <div class="container">
    <h1>Hubungi Kami</h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li>
      <li class="breadcrumb-item active">Contact</li>
    </ol></nav>
  </div>
</section>

<section class="pt-5">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">Kontak</span>
      <h2>Mari Terhubung</h2>
      <p>Punya pertanyaan atau ingin memberikan feedback? Tim kami siap membantu Anda.</p>
    </div>

    <div class="row g-4 mb-5">
      <div class="col-lg-4 col-md-6">
        <div class="location-card h-100 text-center">
          <div class="feature-icon mx-auto mb-3" style="width:60px;height:60px;border-radius:16px;background:var(--accent-light);color:var(--accent-hover);display:inline-flex;align-items:center;justify-content:center;font-size:1.5rem;">
            <i class="fas fa-map-marker-alt"></i>
          </div>
          <h5 class="text-coffee" style="font-family:var(--font-heading);">Alamat</h5>
          <p class="text-muted small"><?= e(setting('business_address', 'Cirebon, Jawa Barat')) ?></p>
          <a href="<?= e(setting('google_maps_url', '#')) ?>" target="_blank" rel="noopener" class="btn btn-outline-coffee btn-sm">Buka Maps</a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="location-card h-100 text-center">
          <div class="feature-icon mx-auto mb-3" style="width:60px;height:60px;border-radius:16px;background:var(--accent-light);color:var(--accent-hover);display:inline-flex;align-items:center;justify-content:center;font-size:1.5rem;">
            <i class="fas fa-phone"></i>
          </div>
          <h5 class="text-coffee" style="font-family:var(--font-heading);">Telepon</h5>
          <p class="text-muted small"><?= e(setting('business_phone', '-')) ?></p>
          <a href="tel:<?= e(setting('business_phone', '')) ?>" class="btn btn-outline-coffee btn-sm">Hubungi Kami</a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="location-card h-100 text-center">
          <div class="feature-icon mx-auto mb-3" style="width:60px;height:60px;border-radius:16px;background:var(--accent-light);color:var(--accent-hover);display:inline-flex;align-items:center;justify-content:center;font-size:1.5rem;">
            <i class="fas fa-clock"></i>
          </div>
          <h5 class="text-coffee" style="font-family:var(--font-heading);">Jam Buka</h5>
          <p class="text-muted small"><?= e(setting('opening_hours', 'Setiap hari, 09.00 - 22.00')) ?></p>
          <a href="<?= e(setting('instagram_url', '#')) ?>" target="_blank" rel="noopener" class="btn btn-outline-coffee btn-sm">Instagram</a>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-7">
        <div class="map-embed">
          <iframe src="https://www.google.com/maps?q=Xiang+De+Coffee+Cirebon&output=embed" allowfullscreen loading="lazy"></iframe>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="location-card">
          <h4 class="text-coffee mb-3" style="font-family:var(--font-heading);">Kirim Pesan</h4>
          <form onsubmit="return false;">
            <div class="mb-3">
              <label class="form-label small fw-medium">Nama</label>
              <input type="text" class="form-control" placeholder="Nama Anda" style="border-radius:14px;padding:12px 18px;border-color:var(--border);">
            </div>
            <div class="mb-3">
              <label class="form-label small fw-medium">Email</label>
              <input type="email" class="form-control" placeholder="email@contoh.com" style="border-radius:14px;padding:12px 18px;border-color:var(--border);">
            </div>
            <div class="mb-3">
              <label class="form-label small fw-medium">Pesan</label>
              <textarea class="form-control" rows="4" placeholder="Tulis pesan Anda..." style="border-radius:14px;padding:12px 18px;border-color:var(--border);"></textarea>
            </div>
            <button class="btn btn-coffee w-100" onclick="xdNotify('Pesan terkirim! Kami akan menghubungi Anda segera.', 'success'); this.form.reset();">
              <i class="fas fa-paper-plane me-2"></i> Kirim Pesan
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
