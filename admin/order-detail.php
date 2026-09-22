<?php
/**
 * Admin: Detail Transaksi
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$activeMenu = 'orders';
$pageTitle  = 'Detail Transaksi';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { set_flash('error', 'Transaksi tidak ditemukan.'); redirect('admin/orders.php'); }

$stmt = $pdo->prepare('SELECT o.*, u.name AS customer_name, u.email, u.phone, u.alamat
                       FROM orders o JOIN users u ON u.id = o.user_id
                       WHERE o.id = ?');
$stmt->execute([$id]);
$order = $stmt->fetch();
if (!$order) { set_flash('error', 'Transaksi tidak ditemukan.'); redirect('admin/orders.php'); }

$stmt = $pdo->prepare('SELECT * FROM order_details WHERE order_id = ?');
$stmt->execute([$id]);
$details = $stmt->fetchAll();

require __DIR__ . '/../includes/admin-header.php';
?>

<div class="admin-card mb-4">
  <div class="admin-card-header">
    <h5>Detail Transaksi</h5>
    <div class="d-flex gap-2">
      <?php $canPrint = !in_array($order['order_status'], ['pending', 'cancelled'], true); ?>
      <?php if ($canPrint): ?>
        <a href="<?= url('admin/invoice.php?id=' . $order['id']) ?>" target="_blank" class="btn btn-admin-secondary btn-sm">
          <i class="fas fa-print me-1"></i> Invoice
        </a>
      <?php else: ?>
        <button type="button" class="btn btn-admin-secondary btn-sm" disabled
                style="opacity:.55; cursor:not-allowed;"
                title="Cetak invoice hanya tersedia setelah pesanan dikonfirmasi (status ≠ Pending/Cancelled).">
          <i class="fas fa-lock me-1"></i> Invoice
        </button>
      <?php endif; ?>
      <a href="<?= url('admin/orders.php') ?>" class="btn btn-admin-secondary btn-sm">Kembali</a>
    </div>
  </div>

  <?php if (!$canPrint): ?>
    <div class="alert alert-warning mb-0" style="font-size:.9rem;">
      <i class="fas fa-clock me-1"></i>
      Tombol invoice <strong>belum aktif</strong>. Ubah status pesanan menjadi <em>Diproses</em>, <em>Siap</em>, atau <em>Selesai</em> pada form di bawah, lalu muat ulang halaman untuk mencetak invoice.
    </div>
  <?php endif; ?>

  <div class="row g-3">
    <div class="col-md-6">
      <h6 class="text-muted">Informasi Pesanan</h6>
      <table class="table table-sm">
        <tr><th style="width:160px;">No. Invoice</th><td><?= e($order['invoice_number']) ?></td></tr>
        <tr><th>Tanggal</th><td><?= format_date($order['created_at']) ?></td></tr>
        <tr><th>Metode Bayar</th><td><?= strtoupper($order['payment_method']) ?></td></tr>
        <tr><th>Jenis Pesanan</th><td><?= ucfirst(str_replace('_',' ',$order['order_type'])) ?></td></tr>
        <tr><th>Status</th><td><?= status_badge($order['order_status']) ?></td></tr>
      </table>
    </div>
    <div class="col-md-6">
      <h6 class="text-muted">Informasi Pelanggan</h6>
      <table class="table table-sm">
        <tr><th style="width:160px;">Nama</th><td><?= e($order['customer_name']) ?></td></tr>
        <tr><th>Email</th><td><?= e($order['email']) ?></td></tr>
        <tr><th>Telepon</th><td><?= e($order['phone'] ?: '-') ?></td></tr>
        <tr><th>Alamat</th><td><?= e($order['alamat'] ?: '-') ?></td></tr>
      </table>
    </div>
  </div>

  <?php if (!empty($order['note'])): ?>
    <div class="alert alert-info">
      <strong>Catatan Pelanggan:</strong> <?= e($order['note']) ?>
    </div>
  <?php endif; ?>
</div>

<div class="admin-card mb-4">
  <div class="admin-card-header"><h5>Item Pesanan</h5></div>
  <div class="table-responsive">
    <table class="table">
      <thead class="bg-cream">
        <tr><th>#</th><th>Produk</th><th class="text-center">Qty</th><th class="text-end">Harga</th><th class="text-end">Subtotal</th></tr>
      </thead>
      <tbody>
        <?php $i=1; foreach ($details as $d): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= e($d['product_name']) ?></td>
            <td class="text-center"><?= $d['quantity'] ?></td>
            <td class="text-end"><?= rupiah($d['price']) ?></td>
            <td class="text-end"><?= rupiah($d['subtotal']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr><th colspan="4" class="text-end">Total</th><th class="text-end"><?= rupiah($order['total_price']) ?></th></tr>
      </tfoot>
    </table>
  </div>
</div>

<div class="admin-card">
  <div class="admin-card-header"><h5>Update Status Pesanan</h5></div>
  <form action="<?= url('admin/order-update-status.php') ?>" method="post" class="admin-form">
    <?= csrf_field() ?>
    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
    <div class="row g-3 align-items-end">
      <div class="col-md-6">
        <label class="form-label">Status Pesanan</label>
        <select name="order_status" class="form-select">
          <option value="pending"    <?= $order['order_status']==='pending'?'selected':'' ?>>Pending</option>
          <option value="processing" <?= $order['order_status']==='processing'?'selected':'' ?>>Diproses</option>
          <option value="ready"      <?= $order['order_status']==='ready'?'selected':'' ?>>Siap</option>
          <option value="completed"  <?= $order['order_status']==='completed'?'selected':'' ?>>Selesai</option>
          <option value="cancelled"  <?= $order['order_status']==='cancelled'?'selected':'' ?>>Dibatalkan</option>
        </select>
      </div>
      <div class="col-md-6">
        <button type="submit" class="btn btn-admin-primary"><i class="fas fa-save me-1"></i> Update Status</button>
      </div>
    </div>
  </form>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
