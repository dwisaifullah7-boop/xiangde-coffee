<?php
/**
 * Detail pesanan customer
 */
require_once __DIR__ . '/../includes/customer-auth.php';
$activePage = 'orders';
$pageTitle  = 'Detail Pesanan';

$orderId = (int)($_GET['id'] ?? 0);
if ($orderId <= 0) redirect('customer/orders.php');

$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ?');
$stmt->execute([$orderId, $_SESSION['user_id']]);
$order = $stmt->fetch();
if (!$order) {
    set_flash('error', 'Pesanan tidak ditemukan.');
    redirect('customer/orders.php');
}

$stmt = $pdo->prepare('SELECT * FROM order_details WHERE order_id = ?');
$stmt->execute([$orderId]);
$details = $stmt->fetchAll();

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
            <li><a href="<?= url('customer/cart.php') ?>"><i class="fas fa-shopping-cart"></i> Keranjang</a></li>
            <li><a href="<?= url('customer/orders.php') ?>" class="active"><i class="fas fa-receipt"></i> Riwayat Pesanan</a></li>
            <li><a href="<?= url('customer/profile.php') ?>"><i class="fas fa-user"></i> Profil Saya</a></li>
            <li><a href="<?= url('logout.php') ?>" class="danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
          </ul>
        </div>
      </div>

      <div class="col-lg-9 order-1 order-lg-2">
        <div class="customer-main">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h4 class="mb-0"><i class="fas fa-receipt me-2"></i> Detail Pesanan</h4>
            <?php $canPrint = !in_array($order['order_status'], ['pending', 'cancelled'], true); ?>
            <?php if ($canPrint): ?>
              <a href="<?= url('customer/invoice.php?id=' . $order['id']) ?>" class="btn btn-outline-coffee btn-sm">
                <i class="fas fa-print me-1"></i> Cetak Invoice
              </a>
            <?php else: ?>
              <button type="button" class="btn btn-outline-coffee btn-sm" disabled
                      style="opacity:.55; cursor:not-allowed;"
                      title="Cetak invoice hanya tersedia setelah admin mengkonfirmasi pesanan Anda.">
                <i class="fas fa-lock me-1"></i> Cetak Invoice
              </button>
            <?php endif; ?>
          </div>

          <div class="bg-cream rounded p-3 mb-3">
            <?php if (!$canPrint): ?>
              <div class="alert alert-warning py-2 mb-2" style="font-size:.88rem;">
                <i class="fas fa-clock me-1"></i>
                Pesanan Anda <strong>menunggu konfirmasi admin</strong>. Tombol Cetak Invoice akan aktif otomatis setelah pesanan dikonfirmasi.
              </div>
            <?php endif; ?>
            <div class="row g-2">
              <div class="col-md-6">
                <small class="text-muted">No. Invoice</small><br>
                <strong><?= e($order['invoice_number']) ?></strong>
              </div>
              <div class="col-md-6">
                <small class="text-muted">Tanggal</small><br>
                <strong><?= format_date($order['created_at']) ?></strong>
              </div>
              <div class="col-md-6">
                <small class="text-muted">Metode Pembayaran</small><br>
                <strong><?= strtoupper($order['payment_method']) ?></strong>
              </div>
              <div class="col-md-6">
                <small class="text-muted">Jenis Pesanan</small><br>
                <strong><?= ucfirst(str_replace('_',' ',$order['order_type'])) ?></strong>
              </div>
              <div class="col-md-6">
                <small class="text-muted">Status</small><br>
                <?= status_badge($order['order_status']) ?>
              </div>
            </div>
            <?php if (!empty($order['note'])): ?>
              <div class="mt-2">
                <small class="text-muted">Catatan:</small>
                <p class="mb-0 fst-italic">"<?= e($order['note']) ?>"</p>
              </div>
            <?php endif; ?>
          </div>

          <h6 class="text-coffee">Item Pesanan</h6>
          <table class="table">
            <thead class="bg-cream">
              <tr><th>Produk</th><th class="text-center">Qty</th><th class="text-end">Harga</th><th class="text-end">Subtotal</th></tr>
            </thead>
            <tbody>
              <?php foreach ($details as $d): ?>
                <tr>
                  <td><?= e($d['product_name']) ?></td>
                  <td class="text-center"><?= $d['quantity'] ?></td>
                  <td class="text-end"><?= rupiah($d['price']) ?></td>
                  <td class="text-end"><?= rupiah($d['subtotal']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="3" class="text-end">Total</th>
                <th class="text-end text-coffee"><?= rupiah($order['total_price']) ?></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
