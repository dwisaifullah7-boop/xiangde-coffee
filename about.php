<?php
/**
 * Halaman About (Redesigned)
 */
require_once __DIR__ . '/includes/functions.php';
$currentPage = 'about';
$pageTitle   = 'About';

$aboutText = setting('about_text', 'Xiang De Coffee adalah coffee shop & kuliner di Cirebon dengan konsep dua lantai yang nyaman, menyajikan kopi premium dan menu khas.');

require __DIR__ . '/includes/header.php';
?>

<section class="breadcrumb-section">
  <div class="container">
    <h1>Tentang Kami</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li>
        <li class="breadcrumb-item active">About</li>
      </ol>
    </nav>
  </div>
</section>

<section>
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="about-img">
          <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?w=900" alt="Xiang De Coffee">
          <div class="about-badge">
            <span class="badge-icon"><i class="fas fa-coffee"></i></span>
            <div>
              <div class="badge-value">Since 2023</div>
              <div class="badge-label">Cirebon</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <span class="eyebrow">Tentang Xiang De Coffee</span>
        <h2 class="mb-3">Coffee Shop & Kuliner di <span style="color:var(--accent); font-style:italic;">Cirebon</span></h2>
        <p class="text-muted"><?= e($aboutText) ?></p>
        <p class="text-muted">Berlokasi strategis di Kabupaten Cirebon, Xiang De Coffee hadir untuk menjadi tempat berkumpul yang nyaman. Konsep dua lantai memberikan ruang yang berbeda — lantai dasar yang hangat untuk berbincang, dan lantai atas yang lebih tenang untuk bekerja atau sekadar menikmati waktu sendiri.</p>

        <div class="row mt-4 g-3">
          <div class="col-6 col-md-3">
            <div class="stat-block">
              <div class="stat-num">2</div>
              <div class="stat-text">Lantai</div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-block">
              <div class="stat-num">20+</div>
              <div class="stat-text">Menu</div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-block">
              <div class="stat-num">4.5</div>
              <div class="stat-text">Rating</div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-block">
              <div class="stat-num">100%</div>
              <div class="stat-text">Hangat</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="bg-cream">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">Nilai Kami</span>
      <h2>Mengapa Xiang De Coffee?</h2>
      <p>Tiga nilai utama yang kami pegang dalam setiap pelayanan.</p>
    </div>
    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-mug-hot"></i></div>
          <h5>Kualitas Tanpa Kompromi</h5>
          <p>Kami memilih biji kopi terbaik dan bahan segar untuk setiap sajian, dipersiapkan dengan penuh perhatian oleh tim barista kami.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-heart"></i></div>
          <h5>Pelayanan Tulus</h5>
          <p>Staf kami yang ramah siap menyambut Anda dengan hangat. Kepuasan pelanggan adalah prioritas utama kami.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-users"></i></div>
          <h5>Tempat Berkumpul</h5>
          <p>Dua lantai dengan suasana berbeda, cocok untuk berkumpul bersama teman, keluarga, maupun bekerja.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="promo-banner">
      <div class="promo-content">
        <span class="hero-eyebrow"><i class="fas fa-utensils"></i> Menu Andalan</span>
        <h2>Menu Khas yang Wajib Dicoba</h2>
        <p>Jelajahi menu andalan Xiang De Coffee — dari Chicken Sambal Matah yang menggugah selera hingga Nasi Daun Jeruk yang unik. Setiap hidangan dibuat dengan resep khas dan bahan terbaik.</p>
        <div class="d-flex flex-wrap gap-2">
          <a href="<?= url('menu.php') ?>" class="btn btn-hero">
            <i class="fas fa-utensils me-2"></i> Lihat Menu Lengkap
          </a>
          <a href="<?= url('gallery.php') ?>" class="btn btn-outline-light">Lihat Galeri</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
