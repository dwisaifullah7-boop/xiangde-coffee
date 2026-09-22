<?php
/**
 * Admin Dashboard
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$activeMenu = 'dashboard';
$pageTitle  = 'Dashboard';

// Stats
$totalProducts = (int)$pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$totalCustomers = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
$totalOrders = (int)$pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$totalRevenue = (float)$pdo->query("SELECT COALESCE(SUM(total_price),0) FROM orders WHERE order_status = 'completed'")->fetchColumn();
$pendingOrders = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE order_status = 'pending'")->fetchColumn();

// Sales last 7 days
$salesLabels = [];
$salesValues = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $salesLabels[] = date('d M', strtotime($date));
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(total_price),0) FROM orders WHERE DATE(created_at) = ? AND order_status <> 'cancelled'");
    $stmt->execute([$date]);
    $salesValues[] = (float)$stmt->fetchColumn();
}

// Orders per category
$catLabels = []; $catValues = [];
$stmt = $pdo->query("SELECT c.name, COUNT(od.id) AS cnt
                     FROM categories c
                     LEFT JOIN products p ON p.category_id = c.id
                     LEFT JOIN order_details od ON od.product_id = p.id
                     GROUP BY c.id ORDER BY cnt DESC");
foreach ($stmt->fetchAll() as $r) {
    $catLabels[] = $r['name'];
    $catValues[] = (int)$r['cnt'];
}

// Recent orders
$recent = $pdo->query("SELECT o.*, u.name AS customer_name
                       FROM orders o JOIN users u ON u.id = o.user_id
                       ORDER BY o.created_at DESC LIMIT 5")->fetchAll();

require __DIR__ . '/../includes/admin-header.php';
?>

<div class="row g-3 mb-4">
  <div class="col-xl-3 col-md-6">
    <div class="stat-box">
      <div class="stat-icon bg-soft-coffee"><i class="fas fa-box"></i></div>
      <div>
        <div class="stat-value"><?= $totalProducts ?></div>
        <div class="stat-label">Total Produk</div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="stat-box">
      <div class="stat-icon bg-soft-info"><i class="fas fa-users"></i></div>
      <div>
        <div class="stat-value"><?= $totalCustomers ?></div>
        <div class="stat-label">Pelanggan</div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="stat-box">
      <div class="stat-icon bg-soft-warning"><i class="fas fa-receipt"></i></div>
      <div>
        <div class="stat-value"><?= $totalOrders ?></div>
        <div class="stat-label">Total Pesanan (<?= $pendingOrders ?> pending)</div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="stat-box">
      <div class="stat-icon bg-soft-success"><i class="fas fa-wallet"></i></div>
      <div>
        <div class="stat-value" style="font-size:1.2rem;"><?= rupiah($totalRevenue) ?></div>
        <div class="stat-label">Pendapatan (selesai)</div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-lg-8">
    <div class="admin-card">
      <div class="admin-card-header"><h5>Grafik Penjualan 7 Hari Terakhir</h5></div>
      <div class="chart-container"><canvas id="salesChart"></canvas></div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="admin-card">
      <div class="admin-card-header"><h5>Pesanan per Kategori</h5></div>
      <div class="chart-container"><canvas id="categoryChart"></canvas></div>
    </div>
  </div>
</div>

<div class="admin-card">
  <div class="admin-card-header">
    <h5>Pesanan Terbaru</h5>
    <a href="<?= url('admin/orders.php') ?>" class="btn btn-admin-secondary btn-sm">Lihat Semua</a>
  </div>
  <div class="table-responsive">
    <table class="table">
      <thead class="bg-cream">
        <tr><th>Invoice</th><th>Pelanggan</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php if (empty($recent)): ?>
          <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pesanan</td></tr>
        <?php else: foreach ($recent as $o): ?>
          <tr>
            <td><strong><?= e($o['invoice_number']) ?></strong></td>
            <td><?= e($o['customer_name']) ?></td>
            <td><?= rupiah($o['total_price']) ?></td>
            <td><?= status_badge($o['order_status']) ?></td>
            <td><small><?= format_date($o['created_at']) ?></small></td>
            <td>
              <a href="<?= url('admin/order-detail.php?id=' . $o['id']) ?>" class="action-btn view"><i class="fas fa-eye"></i></a>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  window.salesData    = { labels: <?= json_encode($salesLabels) ?>, values: <?= json_encode($salesValues) ?> };
  window.categoryData = { labels: <?= json_encode($catLabels) ?>, values: <?= json_encode($catValues) ?> };
</script>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
