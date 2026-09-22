<?php
/**
 * Halaman Cart / Keranjang
 */
require_once __DIR__ . '/../includes/customer-auth.php';
$activePage = 'cart';
$pageTitle  = 'Keranjang Belanja';

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare(
    'SELECT c.*, p.name, p.harga, p.image, p.status, p.id AS product_id
     FROM cart c JOIN products p ON p.id = c.product_id
     WHERE c.user_id = ?
     ORDER BY c.created_at DESC'
);
$stmt->execute([$userId]);
$items = $stmt->fetchAll();

$subtotal = 0;
foreach ($items as $it) {
    $subtotal += $it['harga'] * $it['quantity'];
}
$tax = $subtotal * 0.0; // gratis untuk now
$total = $subtotal + $tax;

$user = current_user();
$extraCss = [url('assets/css/customer.css')];
require __DIR__ . '/../includes/header.php';
?>

<div class="customer-layout">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-3 order-2 order-lg-1">
        <div class="customer-sidebar">
          <div class="customer-profile">
            <?= render_avatar($user) ?>
            <h5><?= e($user['name']) ?></h5>
            <small><?= e($user['email']) ?></small>
          </div>
          <ul class="customer-nav">
            <li><a href="<?= url('customer/dashboard.php') ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="<?= url('customer/cart.php') ?>" class="active"><i class="fas fa-shopping-cart"></i> Keranjang</a></li>
            <li><a href="<?= url('customer/orders.php') ?>"><i class="fas fa-receipt"></i> Riwayat Pesanan</a></li>
            <li><a href="<?= url('customer/profile.php') ?>"><i class="fas fa-user"></i> Profil Saya</a></li>
            <li><a href="<?= url('customer/profile-edit.php') ?>"><i class="fas fa-edit"></i> Edit Profil</a></li>
            <li><a href="<?= url('customer/change-password.php') ?>"><i class="fas fa-key"></i> Ganti Password</a></li>
            <li><a href="<?= url('logout.php') ?>" class="danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
          </ul>
        </div>
      </div>

      <div class="col-lg-9 order-1 order-lg-2">
        <div class="customer-main">
          <h4><i class="fas fa-shopping-cart me-2"></i> Keranjang Belanja</h4>

          <?php if (empty($items)): ?>
            <div class="empty-state">
              <i class="fas fa-shopping-cart"></i>
              <h4>Keranjang Kosong</h4>
              <p>Anda belum menambahkan menu apapun ke keranjang.</p>
              <a href="<?= url('menu.php') ?>" class="btn btn-coffee">Mulai Belanja</a>
            </div>
          <?php else: ?>
            <div class="row g-4">
              <div class="col-lg-8">
                <?php foreach ($items as $it): ?>
                  <div class="cart-item">
                    <div class="cart-item-img">
                      <img src="<?= e(product_image_url($it['image'])) ?>" alt="<?= e($it['name']) ?>">
                    </div>
                    <div class="flex-grow-1">
                      <div class="d-flex justify-content-between">
                        <h6 class="mb-1"><?= e($it['name']) ?></h6>
                        <a href="<?= url('customer/cart-delete.php?id=' . $it['id']) ?>" class="text-danger" data-confirm="Hapus item ini dari keranjang?">
                          <i class="fas fa-trash"></i>
                        </a>
                      </div>
                      <small class="text-muted"><?= rupiah($it['harga']) ?> / porsi</small>
                      <?php if ($it['status'] === PRODUCT_UNAVAILABLE): ?>
                        <div class="badge bg-danger mt-1">Sedang tidak tersedia</div>
                      <?php endif; ?>

                      <form action="<?= url('customer/cart-update.php') ?>" method="post" class="mt-2 d-flex align-items-center gap-2">
                        <?= csrf_field() ?>
                        <input type="hidden" name="cart_id" value="<?= $it['id'] ?>">
                        <div class="quantity-control" data-quantity style="border:1px solid var(--cream);border-radius:30px;overflow:hidden;">
                          <button type="button" class="qty-minus" style="background:var(--cream);border:none;width:32px;height:32px;">−</button>
                          <input type="number" name="quantity" value="<?= $it['quantity'] ?>" min="1" max="99" class="form-control border-0 text-center" style="width:60px;padding:4px;">
                          <button type="button" class="qty-plus" style="background:var(--cream);border:none;width:32px;height:32px;">+</button>
                        </div>
                        <input type="text" name="note" class="form-control form-control-sm" placeholder="Catatan..." value="<?= e($it['note'] ?? '') ?>" style="border-radius:30px;max-width:200px;">
                        <button type="submit" class="btn btn-coffee btn-sm"><i class="fas fa-save"></i></button>
                      </form>
                    </div>
                    <div class="text-end">
                      <div class="fw-bold text-coffee"><?= rupiah($it['harga'] * $it['quantity']) ?></div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>

              <div class="col-lg-4">
                <div class="cart-summary">
                  <h5>Ringkasan Belanja</h5>
                  <div class="summary-row"><span>Subtotal</span><span><?= rupiah($subtotal) ?></span></div>
                  <div class="summary-row"><span>Pajak</span><span><?= rupiah($tax) ?></span></div>
                  <div class="summary-row summary-total"><span>Total</span><span><?= rupiah($total) ?></span></div>

                  <a href="<?= url('customer/checkout.php') ?>" class="btn btn-coffee w-100 mt-3">
                    <i class="fas fa-credit-card me-2"></i> Checkout Sekarang
                  </a>
                  <a href="<?= url('menu.php') ?>" class="btn btn-outline-coffee w-100 mt-2">Lanjut Belanja</a>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
