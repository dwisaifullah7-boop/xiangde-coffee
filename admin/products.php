<?php
/**
 * Admin: List Produk
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$activeMenu = 'products';
$pageTitle  = 'Kelola Produk';

$search = trim($_GET['search'] ?? '');
$where = '';
$params = [];
if ($search !== '') {
    $where = 'WHERE p.name LIKE ? OR c.name LIKE ?';
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql = "SELECT p.*, c.name AS category_name
        FROM products p JOIN categories c ON c.id = p.category_id
        $where
        ORDER BY p.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

require __DIR__ . '/../includes/admin-header.php';
?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5>Daftar Produk (<?= count($products) ?>)</h5>
    <a href="<?= url('admin/product-add.php') ?>" class="btn btn-admin-primary btn-sm">
      <i class="fas fa-plus me-1"></i> Tambah Produk
    </a>
  </div>

  <form method="get" class="mb-3">
    <div class="input-group" style="max-width:400px;">
      <input type="text" name="search" value="<?= e($search) ?>" class="form-control" placeholder="Cari produk...">
      <button class="btn btn-admin-primary"><i class="fas fa-search"></i></button>
      <?php if ($search): ?>
        <a href="<?= url('admin/products.php') ?>" class="btn btn-admin-secondary">Reset</a>
      <?php endif; ?>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table">
      <thead class="bg-cream">
        <tr>
          <th>#</th><th>Gambar</th><th>Nama Produk</th><th>Kategori</th>
          <th>Harga</th><th>Status</th><th>Featured</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($products)): ?>
          <tr><td colspan="8" class="text-center text-muted py-4">Belum ada produk. <a href="<?= url('admin/product-add.php') ?>">Tambah sekarang</a></td></tr>
        <?php else: $i=1; foreach ($products as $p): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><div class="product-thumb"><img src="<?= e(product_image_url($p['image'])) ?>" alt="" onerror="this.onerror=null; this.src='<?= e(BASE_URL . '/assets/images/placeholders/product.svg') ?>';"></div></td>
            <td>
              <strong><?= e($p['name']) ?></strong><br>
              <small class="text-muted"><?= mb_strimwidth(strip_tags($p['description']), 0, 50, '...') ?></small>
            </td>
            <td><?= e($p['category_name']) ?></td>
            <td><?= rupiah($p['harga']) ?></td>
            <td>
              <span class="badge-status <?= $p['status'] ?>"><?= $p['status'] === 'available' ? 'Tersedia' : 'Habis' ?></span>
            </td>
            <td><?= $p['featured'] ? '<i class="fas fa-star text-warning"></i>' : '-' ?></td>
            <td>
              <div class="action-btns">
                <a href="<?= url('admin/product-edit.php?id=' . $p['id']) ?>" class="action-btn edit" title="Edit"><i class="fas fa-edit"></i></a>
                <a href="<?= url('admin/product-delete.php?id=' . $p['id']) ?>" class="action-btn delete" data-confirm-delete="Hapus produk <?= e($p['name']) ?>?" title="Hapus"><i class="fas fa-trash"></i></a>
              </div>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
