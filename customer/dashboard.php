<?php
/**
 * Customer Dashboard
 */
require_once __DIR__ . '/../includes/customer-auth.php';
$activePage = 'dashboard';
$pageTitle  = 'Dashboard Saya';

$userId = $_SESSION['user_id'];

// Stats
$stmt = $pdo->prepare('SELECT COUNT(*) FROM orders WHERE user_id = ?');
$stmt->execute([$userId]);
$totalOrders = (int)$stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COALESCE(SUM(total_price),0) FROM orders WHERE user_id = ? AND order_status <> ?');
$stmt->execute([$userId, STATUS_CANCELLED]);
$totalSpent = (float)$stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM cart WHERE user_id = ?');
$stmt->execute([$userId]);
$cartItems = (int)$stmt->fetchColumn();

// Recent orders
$stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5');
$stmt->execute([$userId]);
$recentOrders = $stmt->fetchAll();

$user = current_user();

// Header (reuse public header + customer.css via extraCss hook)
$extraCss = [url('assets/css/customer.css')];
require __DIR__ . '/../includes/header.php';
?>

<div class="customer-layout">
  <div class="container">
    <div class="row g-4">
      <!-- Sidebar -->
      <div class="col-lg-3 order-2 order-lg-1">
        <div class="customer-sidebar">
          <div class="customer-profile">
            <?= render_avatar($user) ?>
            <h5><?= e($user['name']) ?></h5>
            <small><?= e($user['email']) ?></small>
          </div>
          <ul class="customer-nav">
            <li><a href="<?= url('customer/dashboard.php') ?>" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="<?= url('customer/cart.php') ?>"><i class="fas fa-shopping-cart"></i> Keranjang</a></li>
            <li><a href="<?= url('customer/orders.php') ?>"><i class="fas fa-receipt"></i> Riwayat Pesanan</a></li>
            <li><a href="<?= url('customer/profile.php') ?>"><i class="fas fa-user"></i> Profil Saya</a></li>
            <li><a href="<?= url('customer/profile-edit.php') ?>"><i class="fas fa-edit"></i> Edit Profil</a></li>
            <li><a href="<?= url('customer/change-password.php') ?>"><i class="fas fa-key"></i> Ganti Password</a></li>
            <li><a href="<?= url('logout.php') ?>" class="danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
          </ul>
        </div>
      </div>

      <!-- Main -->
      <div class="col-lg-9 order-1 order-lg-2">
        <div class="customer-main">
          <h4>Selamat datang, <?= e($user['name']) ?>!</h4>
          <p class="text-muted">Berikut ringkasan aktivitas akun Anda di Xiang De Coffee.</p>

          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <div class="stat-card">
                <div class="stat-icon bg-soft-coffee"><i class="fas fa-receipt"></i></div>
                <div>
                  <div class="stat-value"><?= $totalOrders ?></div>
                  <div class="stat-label">Total Pesanan</div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="stat-card">
                <div class="stat-icon bg-caramel"><i class="fas fa-shopping-cart"></i></div>
                <div>
                  <div class="stat-value"><?= $cartItems ?></div>
                  <div class="stat-label">Item di Keranjang</div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="stat-card">
                <div class="stat-icon bg-success-soft"><i class="fas fa-wallet"></i></div>
                <div>
                  <div class="stat-value" style="font-size:1.1rem;"><?= rupiah($totalSpent) ?></div>
                  <div class="stat-label">Total Belanja</div>
                </div>
              </div>
            </div>
          </div>

          <h5 class="mt-4">Pesanan Terbaru</h5>
          <?php if (empty($recentOrders)): ?>
            <div class="empty-state">
              <i class="fas fa-receipt"></i>
              <h4>Belum ada pesanan</h4>
              <p>Mulai pesan menu favorit Anda sekarang.</p>
              <a href="<?= url('menu.php') ?>" class="btn btn-coffee">Lihat Menu</a>
            </div>
          <?php else: ?>
            <?php foreach ($recentOrders as $order): ?>
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
                  <a href="<?= url('customer/order-detail.php?id=' . $order['id']) ?>" class="btn btn-coffee btn-sm">Detail</a>
                </div>
              </div>
            <?php endforeach; ?>
            <div class="text-center mt-3">
              <a href="<?= url('customer/orders.php') ?>" class="btn btn-outline-coffee">Lihat Semua Pesanan</a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
