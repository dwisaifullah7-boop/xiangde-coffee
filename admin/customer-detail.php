<?php
/**
 * Admin: Detail Pelanggan
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$activeMenu = 'customers';
$pageTitle  = 'Detail Pelanggan';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { set_flash('error', 'Pelanggan tidak ditemukan.'); redirect('admin/customers.php'); }

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'customer'");
$stmt->execute([$id]);
$customer = $stmt->fetch();
if (!$customer) { set_flash('error', 'Pelanggan tidak ditemukan.'); redirect('admin/customers.php'); }

$stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$id]);
$orders = $stmt->fetchAll();

$totalSpent = 0;
foreach ($orders as $o) if ($o['order_status'] !== STATUS_CANCELLED) $totalSpent += $o['total_price'];

require __DIR__ . '/../includes/admin-header.php';
?>

<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="stat-box">
      <div class="stat-icon bg-soft-info"><i class="fas fa-receipt"></i></div>
      <div>
        <div class="stat-value"><?= count($orders) ?></div>
        <div class="stat-label">Total Pesanan</div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-box">
      <div class="stat-icon bg-soft-success"><i class="fas fa-wallet"></i></div>
      <div>
        <div class="stat-value" style="font-size:1.1rem;"><?= rupiah($totalSpent) ?></div>
        <div class="stat-label">Total Belanja</div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-box">
      <div class="stat-icon bg-soft-coffee"><i class="fas fa-calendar"></i></div>
      <div>
        <div class="stat-value" style="font-size:1rem;"><?= date('d M Y', strtotime($customer['created_at'])) ?></div>
        <div class="stat-label">Bergabung Sejak</div>
      </div>
    </div>
  </div>
</div>

<div class="admin-card mb-4">
  <div class="admin-card-header">
    <h5>Informasi Pelanggan</h5>
    <a href="<?= url('admin/customer-reset-password.php?id=' . $customer['id']) ?>" class="btn btn-admin-secondary btn-sm">
      <i class="fas fa-key me-1"></i> Reset Password
    </a>
  </div>
  <div class="admin-customer-detail">
    <div class="admin-customer-avatar">
      <?= render_avatar($customer, 'avatar-lg') ?>
    </div>
    <div class="admin-customer-info">
      <h4 class="mb-1"><?= e($customer['name']) ?></h4>
      <p class="text-muted mb-2">
        <i class="fas fa-at me-1"></i> <?= e($customer['username']) ?> &middot;
        <i class="fas fa-envelope me-1"></i> <?= e($customer['email']) ?>
      </p>
    </div>
  </div>
  <table class="table mt-3">
    <tr><th style="width:180px;">Nama</th><td><?= e($customer['name']) ?></td></tr>
    <tr><th>Username</th><td><?= e($customer['username']) ?></td></tr>
    <tr><th>Email</th><td><?= e($customer['email']) ?></td></tr>
    <tr><th>Telepon</th><td><?= e($customer['phone'] ?: '-') ?></td></tr>
    <tr><th>Alamat</th><td><?= e($customer['alamat'] ?: '-') ?></td></tr>
    <tr><th>Foto Profil</th><td>
      <?php if (avatar_url($customer['profile_photo'])): ?>
        <span class="badge bg-success-soft text-success"><i class="fas fa-check-circle me-1"></i> Sudah dipasang</span>
      <?php else: ?>
        <span class="badge bg-secondary"><i class="fas fa-circle-info me-1"></i> Belum dipasang</span>
      <?php endif; ?>
    </td></tr>
  </table>
</div>

<div class="admin-card">
  <div class="admin-card-header"><h5>Riwayat Pesanan</h5></div>
  <div class="table-responsive">
    <table class="table">
      <thead class="bg-cream">
        <tr><th>Invoice</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php if (empty($orders)): ?>
          <tr><td colspan="5" class="text-center text-muted py-3">Belum ada pesanan.</td></tr>
        <?php else: foreach ($orders as $o): ?>
          <tr>
            <td><strong><?= e($o['invoice_number']) ?></strong></td>
            <td><?= rupiah($o['total_price']) ?></td>
            <td><?= status_badge($o['order_status']) ?></td>
            <td><small><?= format_date($o['created_at']) ?></small></td>
            <td><a href="<?= url('admin/order-detail.php?id=' . $o['id']) ?>" class="action-btn view"><i class="fas fa-eye"></i></a></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
