<?php
/**
 * Admin: List Transaksi / Pesanan
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$activeMenu = 'orders';
$pageTitle  = 'Kelola Transaksi';

$status = $_GET['status'] ?? '';
$search = trim($_GET['search'] ?? '');

$where = '1=1';
$params = [];
if ($status !== '') {
    $where .= ' AND o.order_status = ?';
    $params[] = $status;
}
if ($search !== '') {
    $where .= ' AND (o.invoice_number LIKE ? OR u.name LIKE ? OR u.email LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$stmt = $pdo->prepare("SELECT o.*, u.name AS customer_name, u.email
                       FROM orders o JOIN users u ON u.id = o.user_id
                       WHERE $where
                       ORDER BY o.created_at DESC");
$stmt->execute($params);
$orders = $stmt->fetchAll();

$statuses = [
    'pending'    => 'Pending',
    'processing' => 'Diproses',
    'ready'      => 'Siap',
    'completed'  => 'Selesai',
    'cancelled'  => 'Dibatalkan',
];

require __DIR__ . '/../includes/admin-header.php';
?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5>Daftar Transaksi (<?= count($orders) ?>)</h5>
  </div>

  <form method="get" class="row g-2 mb-3">
    <div class="col-md-5">
      <input type="text" name="search" value="<?= e($search) ?>" class="form-control" placeholder="Cari invoice / nama / email...">
    </div>
    <div class="col-md-4">
      <select name="status" class="form-select">
        <option value="">Semua Status</option>
        <?php foreach ($statuses as $k => $v): ?>
          <option value="<?= $k ?>" <?= $status === $k ? 'selected' : '' ?>><?= $v ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <button class="btn btn-admin-primary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table">
      <thead class="bg-cream">
        <tr><th>Invoice</th><th>Pelanggan</th><th>Total</th><th>Pembayaran</th><th>Jenis</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php if (empty($orders)): ?>
          <tr><td colspan="8" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
        <?php else: foreach ($orders as $o): ?>
          <tr>
            <td><strong><?= e($o['invoice_number']) ?></strong></td>
            <td>
              <?= e($o['customer_name']) ?><br>
              <small class="text-muted"><?= e($o['email']) ?></small>
            </td>
            <td><?= rupiah($o['total_price']) ?></td>
            <td><small><?= strtoupper($o['payment_method']) ?></small></td>
            <td><small><?= ucfirst(str_replace('_',' ',$o['order_type'])) ?></small></td>
            <td><?= status_badge($o['order_status']) ?></td>
            <td><small><?= format_date($o['created_at']) ?></small></td>
            <td>
              <div class="action-btns">
                <a href="<?= url('admin/order-detail.php?id=' . $o['id']) ?>" class="action-btn view" title="Detail"><i class="fas fa-eye"></i></a>
                <a href="<?= url('admin/order-delete.php?id=' . $o['id']) ?>" class="action-btn delete" data-confirm-delete="Hapus transaksi <?= e($o['invoice_number']) ?>?" title="Hapus"><i class="fas fa-trash"></i></a>
              </div>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
