<?php
/**
 * Halaman Gallery (Redesigned)
 */
require_once __DIR__ . '/includes/functions.php';
$currentPage = 'gallery';
$pageTitle   = 'Gallery';

$images = [
    ['src' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=800', 'caption' => 'Suasana kedai'],
    ['src' => 'https://images.unsplash.com/photo-1442512595331-e89e73853f31?w=800', 'caption' => 'Sudut hangat'],
    ['src' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=800', 'caption' => 'Kopi signature'],
    ['src' => 'https://images.unsplash.com/photo-1453614512568-c4024d13c247?w=800', 'caption' => 'Latte art'],
    ['src' => 'https://images.unsplash.com/photo-1521017432531-fbd92d768814?w=800', 'caption' => 'Cappuccino'],
    ['src' => 'https://images.unsplash.com/photo-1442975631115-c4f7b05b8a2c?w=800', 'caption' => 'Coffee corner'],
    ['src' => 'https://images.unsplash.com/photo-1511081692775-05d0f180a065?w=800', 'caption' => 'Interior dua lantai'],
    ['src' => 'https://images.unsplash.com/photo-1525193612562-0ec53b0e5d7c?w=800', 'caption' => 'Tempat berkumpul'],
    ['src' => 'https://images.unsplash.com/photo-1525193612562-0ec53b0e5d7c?w=800', 'caption' => 'Hangat & cozy'],
    ['src' => 'https://images.unsplash.com/photo-1559305616-3f99cd43e353?w=800', 'caption' => 'Menu pilihan'],
    ['src' => 'https://images.unsplash.com/photo-1559925393-8be0ec4767c8?w=800', 'caption' => 'Barista corner'],
    ['src' => 'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?w=800', 'caption' => 'Sudut istirahat'],
];

require __DIR__ . '/includes/header.php';
?>

<section class="breadcrumb-section">
  <div class="container">
    <h1>Galeri</h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li>
      <li class="breadcrumb-item active">Gallery</li>
    </ol></nav>
  </div>
</section>

<section class="pt-5">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">Gallery</span>
      <h2>Suasana Xiang De Coffee</h2>
      <p>Cuplikan suasana hangat, menu pilihan, dan momen istimewa di kedai kami.</p>
    </div>
    <div class="gallery-grid">
      <?php foreach ($images as $img): ?>
        <div class="gallery-item">
          <img src="<?= e($img['src']) ?>" alt="<?= e($img['caption']) ?>" loading="lazy">
          <span class="gallery-caption"><?= e($img['caption']) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
