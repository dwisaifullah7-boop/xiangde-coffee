<?php
/**
 * Halaman Detail Produk.
 */
require_once __DIR__ . '/includes/functions.php';
$currentPage = 'menu';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { set_flash('error', 'Produk tidak ditemukan.'); redirect('menu.php'); }

$stmt = $pdo->prepare(
    'SELECT p.*, c.name AS category_name
     FROM products p JOIN categories c ON c.id = p.category_id
     WHERE p.id = ?'
);
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) { set_flash('error', 'Produk tidak ditemukan.'); redirect('menu.php'); }

$pageTitle = $product['name'];
$extraJs   = [url('assets/js/cart.js')];

// Related products (same category, exclude current)
$stmt = $pdo->prepare(
    'SELECT p.*, c.name AS category_name
     FROM products p JOIN categories c ON c.id = p.category_id
     WHERE p.category_id = ? AND p.id <> ? AND p.status = ?
     ORDER BY p.created_at DESC LIMIT 4'
);
$stmt->execute([$product['category_id'], $id, PRODUCT_AVAILABLE]);
$related = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<section class="breadcrumb-section">
  <div class="container">
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li>
      <li class="breadcrumb-item"><a href="<?= url('menu.php') ?>">Menu</a></li>
      <li class="breadcrumb-item active"><?= e($product['name']) ?></li>
    </ol></nav>
  </div>
</section>

<section class="pt-5">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-6">
        <div class="detail-img">
          <img src="<?= e(product_image_url($product['image'])) ?>" alt="<?= e($product['name']) ?>"
               onerror="this.onerror=null; this.src='<?= e(BASE_URL . '/assets/images/placeholders/product.svg') ?>';">
        </div>
      </div>
      <div class="col-lg-6">
        <div class="detail-info">
          <span class="product-cat"><?= e($product['category_name']) ?></span>
          <h1><?= e($product['name']) ?></h1>
          <p class="detail-price"><?= rupiah($product['harga']) ?></p>

          <div class="mb-3">
            <?php if ($product['status'] === PRODUCT_AVAILABLE): ?>
              <span class="detail-status available"><i class="fas fa-check-circle"></i> Tersedia</span>
            <?php else: ?>
              <span class="detail-status unavailable"><i class="fas fa-times-circle"></i> Sedang Habis</span>
            <?php endif; ?>
          </div>

          <h6 class="text-coffee" style="font-family:var(--font-heading); font-size:1.1rem;">Deskripsi</h6>
          <p class="detail-desc"><?= nl2br(e($product['description'])) ?></p>

          <?php if ($product['status'] === PRODUCT_AVAILABLE): ?>
            <?php if (is_customer()): ?>
              <form id="addCartForm" action="<?= url('customer/cart-add.php') ?>" method="post" class="mt-3">
                <?= csrf_field() ?>
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                <div class="mb-3">
                  <label class="form-label text-coffee fw-medium">Jumlah</label>
                  <div class="quantity-control" data-quantity>
                    <button type="button" class="qty-minus" aria-label="Kurangi">−</button>
                    <input type="number" name="quantity" value="1" min="1" max="99">
                    <button type="button" class="qty-plus" aria-label="Tambah">+</button>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label text-coffee fw-medium">Catatan (opsional)</label>
                  <textarea name="note" class="form-control" rows="2" placeholder="Contoh: tanpa es, less sugar, dll."></textarea>
                </div>

                <div class="d-flex flex-wrap gap-2">
                  <button type="submit" class="btn btn-coffee btn-lg">
                    <i class="fas fa-cart-plus me-2"></i> Tambah ke Keranjang
                  </button>
                  <a href="<?= url('menu.php') ?>" class="btn btn-outline-coffee btn-lg">Kembali</a>
                </div>
              </form>
            <?php elseif (is_admin()): ?>
              <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-1"></i> Anda login sebagai admin. Untuk menambah ke keranjang, silakan login sebagai customer.
              </div>
            <?php else: ?>
              <div class="alert alert-warning mt-3">
                <i class="fas fa-exclamation-circle me-1"></i> Silakan <a href="<?= url('login.php') ?>" class="fw-bold">login</a> atau
                <a href="<?= url('register.php') ?>" class="fw-bold">daftar</a> untuk memesan.
              </div>
            <?php endif; ?>
          <?php else: ?>
            <div class="alert alert-danger mt-3">
              <i class="fas fa-times-circle me-1"></i> Produk ini sedang tidak tersedia.
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <?php if ($related): ?>
      <hr class="my-5">
      <h3 class="mb-4" style="font-family:var(--font-heading);">Menu Terkait</h3>
      <div class="row g-4">
        <?php foreach ($related as $p): ?>
          <div class="col-lg-3 col-md-6">
            <?php $product = $p; require __DIR__ . '/includes/product-card.php'; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
