<?php
/**
 * Komponen kartu kategori.
 * Variabel: $category (array)
 */
if (!isset($category)) return;
?>
<a href="<?= url('menu.php?category=' . $category['id']) ?>" class="category-card">
  <div class="category-icon"><i class="fas fa-mug-saucer"></i></div>
  <h5 class="category-name"><?= e($category['name']) ?></h5>
  <p class="category-desc"><?= e(mb_strimwidth(strip_tags($category['description'] ?? ''), 0, 70, '...')) ?></p>
  <span class="category-cta">Lihat Menu <i class="fas fa-arrow-right"></i></span>
</a>
