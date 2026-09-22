<?php
/**
 * Edit Profil customer + Upload / Ganti / Hapus Foto Profil
 */
require_once __DIR__ . '/../includes/customer-auth.php';
$activePage = 'profile';
$pageTitle  = 'Edit Profil';
$user = current_user();

$errors = [];

/* ----------------------------------------------------------
 * Aksi: UPLOAD / GANTI FOTO PROFIL
 * -------------------------------------------------------- */
if (is_post() && ($_POST['action'] ?? '') === 'upload_photo' && csrf_verify()) {
    $result = update_profile_photo($user['id'], 'profile_photo');
    if ($result['success']) {
        set_flash('success', 'Foto profil berhasil diperbarui.');
        redirect('customer/profile-edit.php');
    } else {
        $errors = $result['errors'];
    }
}
/* ----------------------------------------------------------
 * Aksi: HAPUS FOTO PROFIL
 * -------------------------------------------------------- */
elseif (is_post() && ($_POST['action'] ?? '') === 'remove_photo' && csrf_verify()) {
    $result = remove_profile_photo($user['id']);
    if ($result['success']) {
        set_flash('success', 'Foto profil berhasil dihapus.');
    } else {
        set_flash('warning', $result['errors'][0] ?? 'Gagal menghapus foto profil.');
    }
    redirect('customer/profile-edit.php');
}
/* ----------------------------------------------------------
 * Aksi: UPDATE DATA PROFIL (nama, email, telepon, alamat)
 * -------------------------------------------------------- */
elseif (is_post() && csrf_verify()) {
    $result = update_profile($user['id'], $_POST);
    if ($result['success']) {
        $_SESSION['user_name'] = $_POST['name'];
        set_flash('success', 'Profil berhasil diperbarui.');
        redirect('customer/profile.php');
    } else {
        $errors = $result['errors'];
    }
}

// Refresh data user terbaru (jika foto baru saja diganti)
$user = current_user(true);

$extraCss = [url('assets/css/customer.css')];
require __DIR__ . '/../includes/header.php';
?>

<div class="customer-layout">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-3 order-2 order-lg-1">
        <div class="customer-sidebar">
          <div class="customer-profile">
            <?= render_avatar($user) ?>
            <h5><?= e($user['name']) ?></h5>
            <small><?= e($user['email']) ?></small>
          </div>
          <ul class="customer-nav">
            <li><a href="<?= url('customer/dashboard.php') ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="<?= url('customer/cart.php') ?>"><i class="fas fa-shopping-cart"></i> Keranjang</a></li>
            <li><a href="<?= url('customer/orders.php') ?>"><i class="fas fa-receipt"></i> Riwayat Pesanan</a></li>
            <li><a href="<?= url('customer/profile.php') ?>"><i class="fas fa-user"></i> Profil Saya</a></li>
            <li><a href="<?= url('customer/profile-edit.php') ?>" class="active"><i class="fas fa-edit"></i> Edit Profil</a></li>
            <li><a href="<?= url('customer/change-password.php') ?>"><i class="fas fa-key"></i> Ganti Password</a></li>
            <li><a href="<?= url('logout.php') ?>" class="danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-9 order-1 order-lg-2">
        <div class="customer-main">
          <h4><i class="fas fa-edit me-2"></i> Edit Profil</h4>

          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
              <ul class="mb-0 ps-3"><?php foreach ($errors as $e) echo '<li>' . e($e) . '</li>'; ?></ul>
            </div>
          <?php endif; ?>

          <?php
          $flashes = get_flashes();
          foreach ($flashes as $f):
              $cls = $f['type'] === 'success' ? 'alert-success' : ($f['type'] === 'warning' ? 'alert-warning' : 'alert-info');
          ?>
            <div class="alert <?= $cls ?> alert-dismissible fade show">
              <?= e($f['message']) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endforeach; ?>

          <!-- ============ KARTU FOTO PROFIL ============ -->
          <div class="profile-photo-card">
            <div class="profile-photo-preview">
              <?= render_avatar($user, 'avatar-xl') ?>
            </div>
            <div class="profile-photo-actions">
              <h5 class="mb-1">Foto Profil</h5>
              <p class="text-muted small mb-3">
                Format: <strong>JPG, PNG, WEBP</strong> &middot; Maksimal <strong>2 MB</strong>.
                <br>Foto profil akan tampil di dashboard, halaman profil, dan sidebar.
              </p>

              <form method="post" enctype="multipart/form-data" id="photoUploadForm" class="d-flex flex-wrap gap-2 align-items-center">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="upload_photo">
                <label for="profilePhotoInput" class="btn btn-coffee btn-sm mb-0">
                  <i class="fas fa-camera me-1"></i>
                  <?= avatar_url($user['profile_photo']) ? 'Ganti Foto' : 'Unggah Foto' ?>
                </label>
                <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/jpeg,image/png,image/webp" hidden>
                <span id="photoFileName" class="text-muted small"></span>
                <button type="submit" class="btn btn-outline-coffee btn-sm" id="photoSubmitBtn" disabled>
                  <i class="fas fa-upload me-1"></i> Simpan Foto
                </button>

                <?php if (avatar_url($user['profile_photo'])): ?>
                  <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmRemovePhoto">
                    <i class="fas fa-trash me-1"></i> Hapus
                  </button>
                <?php endif; ?>
              </form>
            </div>
          </div>

          <hr class="my-4">

          <!-- ============ FORM EDIT PROFIL ============ -->
          <form method="post" class="checkout-form">
            <?= csrf_field() ?>
            <div class="mb-3">
              <label class="form-label">Nama Lengkap</label>
              <input type="text" name="name" class="form-control" required value="<?= e($_POST['name'] ?? $user['name']) ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required value="<?= e($_POST['email'] ?? $user['email']) ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">No. Telepon</label>
              <input type="text" name="phone" class="form-control" value="<?= e($_POST['phone'] ?? $user['phone']) ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Alamat</label>
              <textarea name="alamat" class="form-control" rows="3"><?= e($_POST['alamat'] ?? $user['alamat']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-coffee"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="<?= url('customer/profile.php') ?>" class="btn btn-outline-coffee">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal: konfirmasi hapus foto -->
<div class="modal fade" id="confirmRemovePhoto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="remove_photo">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-triangle-exclamation me-1 text-warning"></i> Hapus Foto Profil?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="mb-0">Foto profil Anda akan dihapus permanen dan otomatis diganti dengan inisial nama. Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-coffee" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Ya, Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
(function () {
  const input    = document.getElementById('profilePhotoInput');
  const fileName = document.getElementById('photoFileName');
  const submitBtn = document.getElementById('photoSubmitBtn');
  const form     = document.getElementById('photoUploadForm');
  const maxBytes = 2 * 1024 * 1024; // 2 MB

  if (!input) return;

  input.addEventListener('change', function () {
    const file = this.files && this.files[0];
    if (!file) {
      fileName.textContent = '';
      submitBtn.disabled = true;
      return;
    }
    // Validasi ukuran
    if (file.size > maxBytes) {
      fileName.innerHTML = '<span class="text-danger"><i class="fas fa-circle-exclamation me-1"></i>Ukuran melebihi 2 MB</span>';
      submitBtn.disabled = true;
      this.value = '';
      return;
    }
    // Validasi tipe
    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (allowedTypes.indexOf(file.type) === -1) {
      fileName.innerHTML = '<span class="text-danger"><i class="fas fa-circle-exclamation me-1"></i>Format tidak diizinkan</span>';
      submitBtn.disabled = true;
      this.value = '';
      return;
    }
    fileName.innerHTML = '<i class="fas fa-image me-1 text-success"></i>' + file.name;
    submitBtn.disabled = false;
  });
})();
</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>
