<?php
/**
 * Halaman Register (Redesigned)
 */
require_once __DIR__ . '/includes/auth.php';
if (is_logged_in()) {
    redirect(is_admin() ? 'admin/dashboard.php' : 'customer/dashboard.php');
}

$pageTitle = 'Register';
$errors = [];

if (is_post()) {
    if (!csrf_verify()) {
        $errors[] = 'Token CSRF tidak valid.';
    } else {
        $result = register_customer($_POST);
        if ($result['success']) {
            // Auto-login
            $_SESSION['user_id']   = $result['user_id'];
            $_SESSION['user_role'] = ROLE_CUSTOMER;
            $_SESSION['user_name'] = $_POST['name'];
            set_flash('success', 'Pendaftaran berhasil! Selamat datang di Xiang De Coffee.');
            redirect('customer/dashboard.php');
        } else {
            $errors = $result['errors'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register &middot; Xiang De Coffee</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Serif+Display&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="<?= url('assets/css/auth.css') ?>" rel="stylesheet">
</head>
<body class="auth-page">
  <div class="auth-deco"></div>
  <a href="<?= url('index.php') ?>" class="auth-back">
    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
  </a>

  <div class="auth-card">
    <div class="auth-logo">
      <span class="brand-icon"><i class="fas fa-mug-hot"></i></span>
      <h3>Daftar Akun Baru</h3>
      <p>Bergabung dengan komunitas Xiang De Coffee</p>
    </div>

    <?php if ($errors): ?>
      <div class="alert alert-danger auth-alert">
        <ul class="mb-0 ps-3">
          <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" class="auth-form">
      <?= csrf_field() ?>

      <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-id-card"></i></span>
          <input type="text" name="name" class="form-control" required value="<?= e($_POST['name'] ?? '') ?>" placeholder="Nama lengkap Anda">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Username</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" name="username" class="form-control" required minlength="4" value="<?= e($_POST['username'] ?? '') ?>" placeholder="Min. 4 karakter">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" name="email" class="form-control" required value="<?= e($_POST['email'] ?? '') ?>" placeholder="email@contoh.com">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">No. Telepon</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-phone"></i></span>
          <input type="text" name="phone" class="form-control" value="<?= e($_POST['phone'] ?? '') ?>" placeholder="08xxxxxxxxxx">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" name="password" id="password" class="form-control" required minlength="6" placeholder="Min. 6 karakter">
          <span class="input-group-text password-toggle"><i class="fas fa-eye"></i></span>
        </div>
        <div class="pw-strength"><div id="pwStrength" class="pw-strength-bar"></div></div>
      </div>

      <div class="mb-3">
        <label class="form-label">Konfirmasi Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" name="confirm_password" id="confirm_password" class="form-control" required placeholder="Ulangi password">
          <span class="input-group-text password-toggle"><i class="fas fa-eye"></i></span>
        </div>
      </div>

      <button type="submit" class="btn btn-coffee"><i class="fas fa-user-plus me-2"></i> Daftar Sekarang</button>
    </form>

    <div class="auth-divider">atau</div>
    <div class="auth-footer">
      Sudah punya akun? <a href="<?= url('login.php') ?>">Login di sini</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= url('assets/js/auth.js') ?>"></script>
</body>
</html>
