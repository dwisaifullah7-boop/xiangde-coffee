<?php
/**
 * Admin: Tambah Produk
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$activeMenu = 'products';
$pageTitle  = 'Tambah Produk';

$categories = get_categories();

if (is_post() && csrf_verify()) {
    $name        = clean_input($_POST['name'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $harga       = (float)($_POST['harga'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $status      = $_POST['status'] ?? PRODUCT_AVAILABLE;
    $featured    = isset($_POST['featured']) ? 1 : 0;

    $errors = [];
    if ($name === '') $errors[] = 'Nama produk wajib diisi.';
    if ($category_id <= 0) $errors[] = 'Kategori wajib dipilih.';
    if ($harga <= 0) $errors[] = 'Harga harus lebih besar dari 0.';
    if (!in_array($status, [PRODUCT_AVAILABLE, PRODUCT_UNAVAILABLE], true)) $errors[] = 'Status tidak valid.';

    $image = null;
    if (empty($errors)) {
        $uploaded = validate_image_upload('image');
        if (is_array($uploaded) && isset($uploaded['error'])) {
            $errors[] = $uploaded['error'];
        } elseif (is_array($uploaded)) {
            if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0775, true);
            move_uploaded_file($uploaded['tmp_name'], UPLOAD_DIR . $uploaded['name']);
            $image = $uploaded['name'];
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'INSERT INTO products (category_id, name, description, harga, image, status, featured)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$category_id, $name, $description, $harga, $image, $status, $featured]);
        set_flash('success', 'Produk berhasil ditambahkan.');
        redirect('admin/products.php');
    }
}

require __DIR__ . '/../includes/admin-header.php';
?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5>Tambah Produk Baru</h5>
    <a href="<?= url('admin/products.php') ?>" class="btn btn-admin-secondary btn-sm">Kembali</a>
  </div>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger"><ul class="mb-0 ps-3"><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="admin-form">
    <?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-md-8">
        <label class="form-label">Nama Produk *</label>
        <input type="text" name="name" class="form-control" required value="<?= e($_POST['name'] ?? '') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Kategori *</label>
        <select name="category_id" class="form-select" required>
          <option value="">Pilih kategori...</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($_POST['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Harga (Rp) *</label>
        <input type="number" name="harga" class="form-control" required min="0" value="<?= e($_POST['harga'] ?? '') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="available" <?= ($_POST['status'] ?? '') === 'available' ? 'selected' : '' ?>>Tersedia</option>
          <option value="unavailable" <?= ($_POST['status'] ?? '') === 'unavailable' ? 'selected' : '' ?>>Habis</option>
        </select>
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <div class="form-check">
          <input type="checkbox" name="featured" value="1" class="form-check-input" id="featured" <?= !empty($_POST['featured']) ? 'checked' : '' ?>>
          <label class="form-check-label" for="featured">Tampilkan sebagai menu unggulan (featured)</label>
        </div>
      </div>
      <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="4"><?= e($_POST['description'] ?? '') ?></textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label">Gambar Produk (opsional, max 2MB)</label>
        <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
        <small class="text-muted">Format: JPG, PNG, WEBP. Maksimal 2 MB.</small>
      </div>
    </div>
    <div class="mt-4">
      <button type="submit" class="btn btn-admin-primary"><i class="fas fa-save me-1"></i> Simpan Produk</button>
      <a href="<?= url('admin/products.php') ?>" class="btn btn-admin-secondary">Batal</a>
    </div>
  </form>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
