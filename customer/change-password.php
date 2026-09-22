<?php
/**
 * Ganti password customer
 */
require_once __DIR__ . '/../includes/customer-auth.php';
$activePage = 'profile';
$pageTitle  = 'Ganti Password';
$user = current_user();

if (is_post() && csrf_verify()) {
    $result = change_password($user['id'], $_POST);
    if ($result['success']) {
        set_flash('success', 'Password berhasil diubah.');
        redirect('customer/profile.php');
    } else {
        $errors = $result['errors'];
    }
}

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
            <li><a href="<?= url('customer/profile-edit.php') ?>"><i class="fas fa-edit"></i> Edit Profil</a></li>
            <li><a href="<?= url('customer/change-password.php') ?>" class="active"><i class="fas fa-key"></i> Ganti Password</a></li>
            <li><a href="<?= url('logout.php') ?>" class="danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-9 order-1 order-lg-2">
        <div class="customer-main">
          <h4><i class="fas fa-key me-2"></i> Ganti Password</h4>

          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
              <ul class="mb-0 ps-3"><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul>
            </div>
          <?php endif; ?>

          <form method="post" class="checkout-form">
            <?= csrf_field() ?>
            <div class="mb-3">
              <label class="form-label">Password Saat Ini</label>
              <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password Baru</label>
              <input type="password" name="new_password" class="form-control" required minlength="6">
              <small class="text-muted">Minimal 6 karakter.</small>
            </div>
            <div class="mb-3">
              <label class="form-label">Konfirmasi Password Baru</label>
              <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-coffee"><i class="fas fa-save me-1"></i> Ubah Password</button>
            <a href="<?= url('customer/profile.php') ?>" class="btn btn-outline-coffee">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
