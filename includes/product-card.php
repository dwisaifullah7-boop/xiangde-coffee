<?php
/**
 * Komponen kartu produk (dipakai di home & menu).
 * Variabel: $product (array)
 */
if (!isset($product)) return;
$img = product_image_url($product['image'] ?? null);
$placeholderUrl = BASE_URL . '/assets/images/placeholders/product.svg';
?>
<div class="product-card">
  <a href="<?= url('product-detail.php?id=' . $product['id']) ?>" class="product-card-link">
    <div class="product-img">
      <img src="<?= e($img) ?>" alt="<?= e($product['name']) ?>" loading="lazy"
           onerror="this.onerror=null; this.src='<?= e($placeholderUrl) ?>';">
      <?php if (!empty($product['featured'])): ?>
        <span class="product-badge">Featured</span>
      <?php endif; ?>
      <?php if (($product['status'] ?? 'available') === 'unavailable'): ?>
        <span class="product-badge unavailable">Habis</span>
      <?php endif; ?>
    </div>
    <div class="product-body">
      <small class="product-cat"><?= e($product['category_name'] ?? '') ?></small>
      <h5 class="product-name"><?= e($product['name']) ?></h5>
      <p class="product-desc"><?= e(mb_strimwidth(strip_tags($product['description'] ?? ''), 0, 80, '...')) ?></p>
      <div class="product-card-footer">
        <span class="product-price"><?= rupiah($product['harga']) ?></span>
        <span class="product-detail-btn" aria-hidden="true"><i class="fas fa-arrow-right"></i></span>
      </div>
    </div>
  </a>
</div>
