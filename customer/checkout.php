<?php
/**
 * Halaman Checkout
 */
require_once __DIR__ . '/../includes/customer-auth.php';
$activePage = 'cart';
$pageTitle  = 'Checkout';

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare(
    'SELECT c.*, p.name, p.harga, p.image, p.status, p.id AS product_id
     FROM cart c JOIN products p ON p.id = c.product_id
     WHERE c.user_id = ?
     ORDER BY c.created_at DESC'
);
$stmt->execute([$userId]);
$items = $stmt->fetchAll();

if (empty($items)) {
    set_flash('warning', 'Keranjang Anda kosong. Silakan pilih menu terlebih dahulu.');
    redirect('menu.php');
}

$subtotal = 0;
$hasUnavailable = false;
foreach ($items as $it) {
    if ($it['status'] === PRODUCT_UNAVAILABLE) $hasUnavailable = true;
    $subtotal += $it['harga'] * $it['quantity'];
}
$total = $subtotal;

$user = current_user();
$extraCss = [url('assets/css/customer.css')];
$extraJs  = [url('assets/js/checkout.js')];
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
            <li><a href="<?= url('logout.php') ?>" class="danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
          </ul>
        </div>
      </div>

      <div class="col-lg-9 order-1 order-lg-2">
        <div class="customer-main">
          <h4><i class="fas fa-credit-card me-2"></i> Checkout</h4>

          <?php if ($hasUnavailable): ?>
            <div class="alert alert-warning">
              <i class="fas fa-exclamation-triangle me-1"></i> Beberapa item di keranjang sedang tidak tersedia. Silakan hapus atau ganti quantity.
            </div>
          <?php endif; ?>

          <form id="checkoutForm" action="<?= url('customer/checkout-process.php') ?>" method="post" class="checkout-form">
            <?= csrf_field() ?>

            <div class="row g-4">
              <div class="col-lg-7">
                <!-- Order Type -->
                <h6 class="text-coffee mb-3">Jenis Pesanan</h6>
                <div class="mb-4">
                  <label class="order-type-option selected">
                    <input type="radio" name="order_type" value="<?= ORDER_DINE_IN ?>" checked>
                    <div class="opt-content">
                      <i class="fas fa-utensils"></i>
                      <div>
                        <strong>Dine In</strong>
                        <div class="small text-muted">Nikmati di tempat</div>
                      </div>
                    </div>
                  </label>
                  <label class="order-type-option">
                    <input type="radio" name="order_type" value="<?= ORDER_TAKE_AWAY ?>">
                    <div class="opt-content">
                      <i class="fas fa-shopping-bag"></i>
                      <div>
                        <strong>Take Away</strong>
                        <div class="small text-muted">Bawa pulang</div>
                      </div>
                    </div>
                  </label>
                  <label class="order-type-option">
                    <input type="radio" name="order_type" value="<?= ORDER_DELIVERY ?>">
                    <div class="opt-content">
                      <i class="fas fa-truck"></i>
                      <div>
                        <strong>Delivery</strong>
                        <div class="small text-muted">Antar ke alamat</div>
                      </div>
                    </div>
                  </label>
                </div>

                <!-- Payment Method -->
                <h6 class="text-coffee mb-3">Metode Pembayaran</h6>
                <div class="mb-4">
                  <label class="payment-option selected">
                    <input type="radio" name="payment_method" value="<?= PAY_CASH ?>" checked>
                    <div class="opt-content">
                      <i class="fas fa-money-bill-wave"></i>
                      <div><strong>Cash</strong><div class="small text-muted">Bayar di kasir</div></div>
                    </div>
                  </label>
                  <label class="payment-option">
                    <input type="radio" name="payment_method" value="<?= PAY_TRANSFER ?>">
                    <div class="opt-content">
                      <i class="fas fa-university"></i>
                      <div><strong>Transfer Bank</strong><div class="small text-muted">BCA / BRI / Mandiri</div></div>
                    </div>
                  </label>
                  <label class="payment-option">
                    <input type="radio" name="payment_method" value="<?= PAY_QRIS ?>">
                    <div class="opt-content">
                      <i class="fas fa-qrcode"></i>
                      <div><strong>QRIS</strong><div class="small text-muted">Scan & bayar via e-wallet</div></div>
                    </div>
                  </label>
                </div>

                <!-- Note -->
                <h6 class="text-coffee mb-3">Catatan Pesanan (opsional)</h6>
                <textarea name="note" class="form-control" rows="3" placeholder="Contoh: tanpa es, level pedas sedang, dll."></textarea>
              </div>

              <!-- Summary -->
              <div class="col-lg-5">
                <div class="cart-summary">
                  <h5>Ringkasan Pesanan</h5>
                  <?php foreach ($items as $it): ?>
                    <div class="d-flex justify-content-between mb-2 small">
                      <span><?= $it['quantity'] ?> × <?= e($it['name']) ?></span>
                      <span><?= rupiah($it['harga'] * $it['quantity']) ?></span>
                    </div>
                  <?php endforeach; ?>
                  <hr>
                  <div class="summary-row"><span>Subtotal</span><span><?= rupiah($subtotal) ?></span></div>
                  <div class="summary-row summary-total"><span>Total</span><span><?= rupiah($total) ?></span></div>
                  <button type="submit" class="btn btn-coffee w-100 mt-3">
                    <i class="fas fa-check-circle me-2"></i> Buat Pesanan
                  </button>
                  <a href="<?= url('customer/cart.php') ?>" class="btn btn-outline-coffee w-100 mt-2">Kembali ke Keranjang</a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
