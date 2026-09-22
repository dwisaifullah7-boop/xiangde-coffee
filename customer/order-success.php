<?php
/**
 * Halaman sukses checkout
 */
require_once __DIR__ . '/../includes/customer-auth.php';
$activePage = 'orders';
$pageTitle  = 'Pesanan Berhasil';

$orderId = (int)($_GET['id'] ?? 0);
if ($orderId <= 0) redirect('customer/dashboard.php');

$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ?');
$stmt->execute([$orderId, $_SESSION['user_id']]);
$order = $stmt->fetch();
if (!$order) redirect('customer/dashboard.php');

$user = current_user();
$extraCss = [url('assets/css/customer.css')];
require __DIR__ . '/../includes/header.php';
?>

<div class="customer-layout">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-7">
        <div class="customer-main text-center">
          <div class="success-icon"><i class="fas fa-check"></i></div>
          <h3 class="text-coffee">Pesanan Berhasil Dibuat!</h3>
          <p class="text-muted">Terima kasih sudah memesan. Pesanan Anda sedang menunggu konfirmasi admin.</p>

          <div class="bg-cream rounded p-3 my-4 text-start">
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Nomor Invoice</span>
              <strong><?= e($order['invoice_number']) ?></strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Total Pembayaran</span>
              <strong class="text-coffee"><?= rupiah($order['total_price']) ?></strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Metode Pembayaran</span>
              <strong><?= strtoupper($order['payment_method']) ?></strong>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-muted">Jenis Pesanan</span>
              <strong><?= ucfirst(str_replace('_',' ',$order['order_type'])) ?></strong>
            </div>
          </div>

          <div class="d-flex gap-2 justify-content-center flex-wrap">
            <a href="<?= url('customer/invoice.php?id=' . $order['id']) ?>" class="btn btn-coffee">
              <i class="fas fa-file-invoice me-1"></i> Lihat Invoice
            </a>
            <a href="<?= url('customer/orders.php') ?>" class="btn btn-outline-coffee">
              <i class="fas fa-receipt me-1"></i> Riwayat Pesanan
            </a>
            <a href="<?= url('menu.php') ?>" class="btn btn-outline-coffee">
              <i class="fas fa-coffee me-1"></i> Pesan Lagi
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
