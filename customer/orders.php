<?php
/**
 * Daftar riwayat pesanan customer
 */
require_once __DIR__ . '/../includes/customer-auth.php';
$activePage = 'orders';
$pageTitle  = 'Riwayat Pesanan';

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();

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
            <li><a href="<?= url('customer/profile-edit.php') ?>"><i class="fas fa-edit"></i> Edit Profil</a></li>
            <li><a href="<?= url('customer/change-password.php') ?>"><i class="fas fa-key"></i> Ganti Password</a></li>
            <li><a href="<?= url('logout.php') ?>" class="danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
          </ul>
        </div>
      </div>

      <div class="col-lg-9 order-1 order-lg-2">
        <div class="customer-main">
          <h4><i class="fas fa-receipt me-2"></i> Riwayat Pesanan</h4>

          <?php if (empty($orders)): ?>
            <div class="empty-state">
              <i class="fas fa-receipt"></i>
              <h4>Belum ada pesanan</h4>
              <p>Mulai pesan menu favorit Anda sekarang.</p>
              <a href="<?= url('menu.php') ?>" class="btn btn-coffee">Lihat Menu</a>
            </div>
          <?php else: ?>
            <?php foreach ($orders as $order): ?>
              <div class="order-card">
                <div class="order-card-header">
                  <div>
                    <div class="order-card-title"><?= e($order['invoice_number']) ?></div>
                    <div class="order-card-meta"><?= format_date($order['created_at']) ?></div>
                  </div>
                  <div class="text-end">
                    <?= status_badge($order['order_status']) ?>
                    <div class="fw-bold text-coffee mt-1"><?= rupiah($order['total_price']) ?></div>
                  </div>
                </div>
                <div class="order-card-footer">
                  <small class="text-muted">
                    <i class="fas fa-<?= $order['order_type']==='dine_in'?'utensils':($order['order_type']==='take_away'?'shopping-bag':'truck') ?> me-1"></i>
                    <?= ucfirst(str_replace('_',' ',$order['order_type'])) ?> &middot;
                    <i class="fas fa-money-bill-wave me-1"></i> <?= strtoupper($order['payment_method']) ?>
                  </small>
                  <div>
                    <a href="<?= url('customer/order-detail.php?id=' . $order['id']) ?>" class="btn btn-coffee btn-sm">Detail</a>
                    <?php $canPrint = !in_array($order['order_status'], ['pending', 'cancelled'], true); ?>
                    <?php if ($canPrint): ?>
                      <a href="<?= url('customer/invoice.php?id=' . $order['id']) ?>" class="btn btn-outline-coffee btn-sm" title="Cetak Invoice"><i class="fas fa-print"></i></a>
                    <?php else: ?>
                      <button type="button" class="btn btn-outline-coffee btn-sm" disabled
                              style="opacity:.55; cursor:not-allowed;"
                              title="Cetak invoice hanya tersedia setelah admin mengkonfirmasi pesanan Anda.">
                        <i class="fas fa-lock"></i>
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
