<?php
/**
 * Admin: List Pelanggan
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$activeMenu = 'customers';
$pageTitle  = 'Kelola Pelanggan';

$search = trim($_GET['search'] ?? '');
$where  = "WHERE role = 'customer'";
$params = [];
if ($search !== '') {
    $where .= " AND (name LIKE ? OR email LIKE ? OR username LIKE ? OR phone LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$stmt = $pdo->prepare("SELECT u.*, (SELECT COUNT(*) FROM orders o WHERE o.user_id = u.id) AS order_count
                       FROM users u $where ORDER BY u.created_at DESC");
$stmt->execute($params);
$customers = $stmt->fetchAll();

require __DIR__ . '/../includes/admin-header.php';
?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5>Daftar Pelanggan (<?= count($customers) ?>)</h5>
  </div>

  <form method="get" class="mb-3">
    <div class="input-group" style="max-width:400px;">
      <input type="text" name="search" value="<?= e($search) ?>" class="form-control" placeholder="Cari nama / email / username / telepon...">
      <button class="btn btn-admin-primary"><i class="fas fa-search"></i></button>
      <?php if ($search): ?>
        <a href="<?= url('admin/customers.php') ?>" class="btn btn-admin-secondary">Reset</a>
      <?php endif; ?>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table">
      <thead class="bg-cream">
        <tr><th>#</th><th>Nama</th><th>Username</th><th>Email</th><th>Telepon</th><th>Pesanan</th><th>Bergabung</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php if (empty($customers)): ?>
          <tr><td colspan="8" class="text-center text-muted py-4">Belum ada pelanggan.</td></tr>
        <?php else: $i=1; foreach ($customers as $c): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><strong><?= e($c['name']) ?></strong></td>
            <td><?= e($c['username']) ?></td>
            <td><?= e($c['email']) ?></td>
            <td><?= e($c['phone'] ?: '-') ?></td>
            <td><span class="badge bg-secondary"><?= (int)$c['order_count'] ?></span></td>
            <td><small><?= format_date($c['created_at']) ?></small></td>
            <td>
              <div class="action-btns">
                <a href="<?= url('admin/customer-detail.php?id=' . $c['id']) ?>" class="action-btn view" title="Detail"><i class="fas fa-eye"></i></a>
                <a href="<?= url('admin/customer-reset-password.php?id=' . $c['id']) ?>" class="action-btn edit" title="Reset Password"><i class="fas fa-key"></i></a>
              </div>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
