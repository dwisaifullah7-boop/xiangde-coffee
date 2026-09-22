<?php
/**
 * Profil customer (read-only)
 */
require_once __DIR__ . '/../includes/customer-auth.php';
$activePage = 'profile';
$pageTitle  = 'Profil Saya';
$user = current_user();
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
            <li><a href="<?= url('customer/profile.php') ?>" class="active"><i class="fas fa-user"></i> Profil Saya</a></li>
            <li><a href="<?= url('customer/profile-edit.php') ?>"><i class="fas fa-edit"></i> Edit Profil</a></li>
            <li><a href="<?= url('customer/change-password.php') ?>"><i class="fas fa-key"></i> Ganti Password</a></li>
            <li><a href="<?= url('logout.php') ?>" class="danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-9 order-1 order-lg-2">
        <div class="customer-main">
          <h4><i class="fas fa-user me-2"></i> Profil Saya</h4>

          <div class="profile-hero">
            <div class="profile-hero-avatar">
              <?= render_avatar($user, 'avatar-lg') ?>
              <?php if (avatar_url($user['profile_photo'])): ?>
                <span class="profile-photo-badge" title="Foto profil terpasang"><i class="fas fa-check"></i></span>
              <?php endif; ?>
            </div>
            <div class="profile-hero-info">
              <h3><?= e($user['name']) ?></h3>
              <p class="text-muted mb-0">
                <i class="fas fa-at me-1"></i> <?= e($user['username']) ?> &middot;
                <i class="fas fa-envelope me-1"></i> <?= e($user['email']) ?>
              </p>
              <div class="mt-2">
                <a href="<?= url('customer/profile-edit.php') ?>" class="btn btn-coffee btn-sm">
                  <i class="fas fa-camera me-1"></i> Ganti Foto & Profil
                </a>
              </div>
            </div>
          </div>

          <table class="table profile-table mt-4">
            <tr><th style="width:200px;">Nama</th><td><?= e($user['name']) ?></td></tr>
            <tr><th>Username</th><td><?= e($user['username']) ?></td></tr>
            <tr><th>Email</th><td><?= e($user['email']) ?></td></tr>
            <tr><th>Telepon</th><td><?= e($user['phone'] ?: '-') ?></td></tr>
            <tr><th>Alamat</th><td><?= e($user['alamat'] ?: '-') ?></td></tr>
            <tr><th>Foto Profil</th><td>
              <?php if (avatar_url($user['profile_photo'])): ?>
                <span class="badge bg-success-soft text-success"><i class="fas fa-check-circle me-1"></i> Sudah dipasang</span>
              <?php else: ?>
                <span class="badge bg-secondary"><i class="fas fa-circle-info me-1"></i> Belum dipasang</span>
              <?php endif; ?>
            </td></tr>
            <tr><th>Bergabung sejak</th><td><?= format_date($user['created_at']) ?></td></tr>
          </table>
          <a href="<?= url('customer/profile-edit.php') ?>" class="btn btn-coffee"><i class="fas fa-edit me-1"></i> Edit Profil</a>
          <a href="<?= url('customer/change-password.php') ?>" class="btn btn-outline-coffee"><i class="fas fa-key me-1"></i> Ganti Password</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
