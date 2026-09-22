<?php
/**
 * Halaman Home / Landing Page (Redesigned)
 */
require_once __DIR__ . '/includes/functions.php';

$currentPage = 'home';
$pageTitle   = 'Home';

$categories = get_categories();
$featured   = get_featured_products(8);

// Testimonials (static sample)
$testimonials = [
    ['name' => 'Andini P.',   'rating' => 5, 'text' => 'Tempatnya cozy banget, dua lantai dengan suasana hangat. Chicken Sambal Matahnya juara!'],
    ['name' => 'Bayu S.',     'rating' => 5, 'text' => 'Kopinya enak, stafnya ramah. Cocok untuk nongkrong bareng teman atau keluarga.'],
    ['name' => 'Citra W.',    'rating' => 4, 'text' => 'Nasi Daun Jeruknya unik dan enak. Suasana dua lantai bikin betah.'],
    ['name' => 'Dimas R.',    'rating' => 5, 'text' => 'Beef Sambal Matah khas Xiang De wajib coba. Pelayanan cepat, harga bersahabat.'],
];

// Gallery (placeholder URLs)
$galleryImages = [
    'https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=600',
    'https://images.unsplash.com/photo-1442512595331-e89e73853f31?w=600',
    'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=600',
    'https://images.unsplash.com/photo-1453614512568-c4024d13c247?w=600',
    'https://images.unsplash.com/photo-1521017432531-fbd92d768814?w=600',
    'https://images.unsplash.com/photo-1442975631115-c4f7b05b8a2c?w=600',
    'https://images.unsplash.com/photo-1511081692775-05d0f180a065?w=600',
    'https://images.unsplash.com/photo-1525193612562-0ec53b0e5d7c?w=600',
];

// Fallback values
$heroTagline  = setting('hero_tagline', 'Secangkir kopi, sejuta cerita');
$heroSubtitle = setting('hero_subtitle', 'Coffee shop dua lantai di Cirebon dengan kopi premium, kuliner khas, dan suasana hangat untuk berkumpul.');
$gofoodUrl    = setting('gofood_url', '#');
$aboutText    = setting('about_text', 'Xiang De Coffee adalah coffee shop & kuliner di Cirebon dengan konsep dua lantai yang nyaman, menyajikan kopi premium dan menu khas seperti Chicken Sambal Matah, Beef Sambal Matah, dan Nasi Daun Jeruk.');

require __DIR__ . '/includes/header.php';
?>

<!-- ============== HERO ============== -->
<section class="hero">
  <div class="container">
    <div class="hero-content">
      <span class="hero-eyebrow">
        <i class="fas fa-mug-hot"></i>
        Coffee Shop &middot; Kuliner &middot; Cirebon
      </span>
      <h1 class="hero-tagline"><?= e($heroTagline) ?></h1>
      <p class="hero-subtitle"><?= e($heroSubtitle) ?></p>
      <div class="hero-cta">
        <a href="<?= url('menu.php') ?>" class="btn btn-hero">
          <i class="fas fa-coffee me-2"></i> Lihat Menu
        </a>
        <a href="<?= e($gofoodUrl) ?>" target="_blank" rel="noopener" class="btn btn-hero-outline">
          <i class="fas fa-utensils me-2"></i> Order via GoFood
        </a>
      </div>

      <div class="hero-meta">
        <div class="hero-meta-item">
          <span class="meta-icon"><i class="fas fa-mug-saucer"></i></span>
          <div>
            <div class="meta-value">2 Lantai</div>
            <div class="meta-label">Suasana nyaman</div>
          </div>
        </div>
        <div class="hero-meta-item">
          <span class="meta-icon"><i class="fas fa-utensils"></i></span>
          <div>
            <div class="meta-value">20+</div>
            <div class="meta-label">Menu pilihan</div>
          </div>
        </div>
        <div class="hero-meta-item">
          <span class="meta-icon"><i class="fas fa-star"></i></span>
          <div>
            <div class="meta-value">4.5</div>
            <div class="meta-label">Rating pelanggan</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="hero-scroll">
    <span>Scroll</span>
    <span class="scroll-line"></span>
  </div>
</section>

<!-- ============== ABOUT ============== -->
<section id="about">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="about-img">
          <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=900" alt="Xiang De Coffee">
          <div class="about-badge">
            <span class="badge-icon"><i class="fas fa-award"></i></span>
            <div>
              <div class="badge-value">Premium</div>
              <div class="badge-label">Coffee & Kuliner</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <span class="eyebrow">Tentang Kami</span>
        <h2 class="mb-3">Cerita di Balik <span style="color:var(--accent); font-style:italic;">Xiang De Coffee</span></h2>
        <p class="text-muted"><?= e($aboutText) ?></p>
        <ul class="about-features">
          <li>
            <span class="feature-icon"><i class="fas fa-mug-hot"></i></span>
            <div>
              <h6 class="mb-1">Kopi Premium</h6>
              <small>Biji kopi pilihan yang diseduh dengan teknik terbaik oleh barista berpengalaman.</small>
            </div>
          </li>
          <li>
            <span class="feature-icon"><i class="fas fa-utensils"></i></span>
            <div>
              <h6 class="mb-1">Kuliner Khas</h6>
              <small>Menu andalan seperti Chicken Sambal Matah, Beef Sambal Matah, dan Nasi Daun Jeruk.</small>
            </div>
          </li>
          <li>
            <span class="feature-icon"><i class="fas fa-couch"></i></span>
            <div>
              <h6 class="mb-1">Suasana Dua Lantai</h6>
              <small>Tempat yang nyaman dengan dua lantai untuk berkumpul bersama teman dan keluarga.</small>
            </div>
          </li>
        </ul>
        <a href="<?= url('about.php') ?>" class="btn btn-coffee mt-3">Selengkapnya <i class="fas fa-arrow-right ms-1"></i></a>
      </div>
    </div>
  </div>
</section>

<!-- ============== CATEGORIES ============== -->
<section class="bg-cream">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">Kategori</span>
      <h2>Pilihan Menu Kami</h2>
      <p>Temukan berbagai kategori minuman dan makanan favorit yang kami sajikan.</p>
    </div>
    <div class="row g-4">
      <?php foreach ($categories as $cat): ?>
        <div class="col-lg-3 col-md-6 col-6">
          <?php $category = $cat; require __DIR__ . '/includes/category-card.php'; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============== FEATURED PRODUCTS ============== -->
<section id="menu">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">Recommended</span>
      <h2>Menu Andalan</h2>
      <p>Pilihan terbaik dari Xiang De Coffee yang wajib Anda coba.</p>
    </div>
    <div class="row g-4">
      <?php foreach ($featured as $p): ?>
        <div class="col-lg-3 col-md-6 col-6">
          <?php $product = $p; require __DIR__ . '/includes/product-card.php'; ?>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-5">
      <a href="<?= url('menu.php') ?>" class="btn btn-outline-coffee">Lihat Semua Menu <i class="fas fa-arrow-right ms-1"></i></a>
    </div>
  </div>
</section>

<!-- ============== PROMO ============== -->
<section>
  <div class="container">
    <div class="promo-banner">
      <div class="promo-content">
        <span class="hero-eyebrow">
          <i class="fas fa-tag"></i> Promo Spesial
        </span>
        <h2>Pesan Sekarang, Nikmati di Tempat</h2>
        <p>Kunjungi Xiang De Coffee di Cirebon atau pesan via GoFood untuk pengalaman kopi dan kuliner terbaik. Kisaran harga Rp50.000 - Rp75.000 per orang.</p>
        <div class="d-flex flex-wrap gap-2">
          <a href="<?= e($gofoodUrl) ?>" target="_blank" rel="noopener" class="btn btn-gofood">
            <i class="fas fa-utensils me-2"></i> Order via GoFood
          </a>
          <a href="<?= url('contact.php') ?>" class="btn btn-outline-light">Lihat Lokasi</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============== GALLERY ============== -->
<section class="bg-cream">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">Gallery</span>
      <h2>Suasana Xiang De Coffee</h2>
      <p>Cuplikan suasana hangat dan cozy di kedai kami.</p>
    </div>
    <div class="gallery-grid">
      <?php foreach ($galleryImages as $img): ?>
        <div class="gallery-item">
          <img src="<?= e($img) ?>" alt="Gallery Xiang De Coffee" loading="lazy">
        </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-5">
      <a href="<?= url('gallery.php') ?>" class="btn btn-outline-coffee">Lihat Semua Galeri <i class="fas fa-arrow-right ms-1"></i></a>
    </div>
  </div>
</section>

<!-- ============== TESTIMONIALS ============== -->
<section>
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">Testimoni</span>
      <h2>Kata Pelanggan Kami</h2>
      <p>Cerita pelanggan yang telah menikmati pengalaman di Xiang De Coffee.</p>
    </div>
    <div class="row g-4">
      <?php foreach ($testimonials as $t): ?>
        <div class="col-lg-3 col-md-6">
          <div class="testimonial-card">
            <div class="testimonial-stars">
              <?php for ($i=1;$i<=5;$i++): ?>
                <i class="<?= $i>$t['rating'] ? 'far' : 'fas' ?> fa-star"></i>
              <?php endfor; ?>
            </div>
            <p class="testimonial-text">"<?= e($t['text']) ?>"</p>
            <div class="testimonial-user">
              <div class="testimonial-avatar"><?= e(strtoupper(substr($t['name'],0,1))) ?></div>
              <div>
                <h6><?= e($t['name']) ?></h6>
                <small>Pelanggan Xiang De</small>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============== LOCATION ============== -->
<section class="bg-cream">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">Lokasi</span>
      <h2>Temukan Kami di Cirebon</h2>
      <p>Kunjungi Xiang De Coffee untuk pengalaman kopi dan kuliner terbaik.</p>
    </div>
    <div class="row g-4">
      <div class="col-lg-5">
        <div class="location-card">
          <ul class="location-info">
            <li>
              <i class="fas fa-map-marker-alt"></i>
              <div>
                <strong>Alamat</strong>
                <?= e(setting('business_address', 'Cirebon, Jawa Barat')) ?>
              </div>
            </li>
            <li>
              <i class="fas fa-phone"></i>
              <div>
                <strong>Telepon</strong>
                <?= e(setting('business_phone', '-')) ?>
              </div>
            </li>
            <li>
              <i class="fas fa-clock"></i>
              <div>
                <strong>Jam Buka</strong>
                <?= e(setting('opening_hours', 'Setiap hari, 09.00 - 22.00')) ?>
              </div>
            </li>
            <li>
              <i class="fas fa-tag"></i>
              <div>
                <strong>Kisaran Harga</strong>
                <?= e(setting('price_range', 'Rp50.000 - Rp75.000 / orang')) ?>
              </div>
            </li>
          </ul>
          <a href="<?= e(setting('google_maps_url', '#')) ?>" target="_blank" rel="noopener" class="btn btn-coffee w-100">
            <i class="fas fa-directions me-2"></i> Buka di Google Maps
          </a>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="map-embed">
          <iframe src="https://www.google.com/maps?q=Xiang+De+Coffee+Cirebon&output=embed" allowfullscreen loading="lazy"></iframe>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============== CTA ============== -->
<section class="cta-section">
  <div class="container">
    <h2>Siap Menikmati Secangkir Kopi?</h2>
    <p>Pesan sekarang melalui GoFood atau kunjungi langsung kedai kami di Cirebon.</p>
    <div class="cta-buttons">
      <a href="<?= e($gofoodUrl) ?>" target="_blank" rel="noopener" class="btn btn-gofood">
        <i class="fas fa-utensils me-2"></i> Order via GoFood
      </a>
      <a href="<?= url('menu.php') ?>" class="btn btn-outline-light">Lihat Menu</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
