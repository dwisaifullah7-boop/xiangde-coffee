<?php
/**
 * Admin: List Kategori
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$activeMenu = 'categories';
$pageTitle  = 'Kelola Kategori';

$categories = $pdo->query(
    "SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) AS product_count
     FROM categories c ORDER BY c.name ASC"
)->fetchAll();

require __DIR__ . '/../includes/admin-header.php';
?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5>Daftar Kategori (<?= count($categories) ?>)</h5>
    <a href="<?= url('admin/category-add.php') ?>" class="btn btn-admin-primary btn-sm">
      <i class="fas fa-plus me-1"></i> Tambah Kategori
    </a>
  </div>

  <div class="table-responsive">
    <table class="table">
      <thead class="bg-cream">
        <tr><th>#</th><th>Nama</th><th>Deskripsi</th><th>Jumlah Produk</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php if (empty($categories)): ?>
          <tr><td colspan="5" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
        <?php else: $i=1; foreach ($categories as $c): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><strong><?= e($c['name']) ?></strong></td>
            <td><small class="text-muted"><?= mb_strimwidth(strip_tags($c['description']), 0, 80, '...') ?></small></td>
            <td><span class="badge bg-secondary"><?= (int)$c['product_count'] ?></span></td>
            <td>
              <div class="action-btns">
                <a href="<?= url('admin/category-edit.php?id=' . $c['id']) ?>" class="action-btn edit"><i class="fas fa-edit"></i></a>
                <a href="<?= url('admin/category-delete.php?id=' . $c['id']) ?>" class="action-btn delete" data-confirm-delete="Hapus kategori <?= e($c['name']) ?>? Semua produk di dalamnya juga akan dihapus."><i class="fas fa-trash"></i></a>
              </div>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
