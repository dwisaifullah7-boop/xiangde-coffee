<?php
/**
 * Admin: Tambah Kategori
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$activeMenu = 'categories';
$pageTitle  = 'Tambah Kategori';

if (is_post() && csrf_verify()) {
    $name        = clean_input($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name === '') {
        $errors[] = 'Nama kategori wajib diisi.';
    } else {
        // Cek duplikat
        $stmt = $pdo->prepare('SELECT id FROM categories WHERE name = ?');
        $stmt->execute([$name]);
        if ($stmt->fetch()) $errors[] = 'Nama kategori sudah ada.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('INSERT INTO categories (name, description) VALUES (?, ?)');
        $stmt->execute([$name, $description]);
        set_flash('success', 'Kategori berhasil ditambahkan.');
        redirect('admin/categories.php');
    }
}

require __DIR__ . '/../includes/admin-header.php';
?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5>Tambah Kategori Baru</h5>
    <a href="<?= url('admin/categories.php') ?>" class="btn btn-admin-secondary btn-sm">Kembali</a>
  </div>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger"><ul class="mb-0 ps-3"><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul></div>
  <?php endif; ?>

  <form method="post" class="admin-form">
    <?= csrf_field() ?>
    <div class="mb-3">
      <label class="form-label">Nama Kategori *</label>
      <input type="text" name="name" class="form-control" required value="<?= e($_POST['name'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Deskripsi</label>
      <textarea name="description" class="form-control" rows="3"><?= e($_POST['description'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-admin-primary"><i class="fas fa-save me-1"></i> Simpan</button>
    <a href="<?= url('admin/categories.php') ?>" class="btn btn-admin-secondary">Batal</a>
  </form>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
