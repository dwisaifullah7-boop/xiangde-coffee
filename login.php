<?php
/**
 * Halaman Login (Redesigned)
 */
require_once __DIR__ . '/includes/auth.php';
if (is_logged_in()) {
    redirect(is_admin() ? 'admin/dashboard.php' : 'customer/dashboard.php');
}

$pageTitle = 'Login';
$errors = [];

if (is_post()) {
    if (!csrf_verify()) { $errors[] = 'Token CSRF tidak valid.'; }
    else {
        $identifier = $_POST['identifier'] ?? '';
        $password   = $_POST['password']    ?? '';
        $result = login_user($identifier, $password);
        if ($result['success']) {
            set_flash('success', 'Selamat datang, ' . $result['user']['name'] . '!');
            redirect($result['user']['role'] === ROLE_ADMIN ? 'admin/dashboard.php' : 'customer/dashboard.php');
        } else {
            $errors[] = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login &middot; Xiang De Coffee</title>
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
      <h3>Selamat Datang</h3>
      <p>Masuk ke akun Xiang De Coffee Anda</p>
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
        <label class="form-label">Username atau Email</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" name="identifier" class="form-control" required autofocus
                 value="<?= e($_POST['identifier'] ?? '') ?>" placeholder="Masukkan username atau email">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" name="password" id="password" class="form-control" required placeholder="Masukkan password">
          <span class="input-group-text password-toggle"><i class="fas fa-eye"></i></span>
        </div>
      </div>

      <button type="submit" class="btn btn-coffee"><i class="fas fa-sign-in-alt me-2"></i> Login</button>
    </form>

    <div class="auth-divider">atau</div>
    <div class="auth-footer">
      Belum punya akun? <a href="<?= url('register.php') ?>">Daftar sekarang</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= url('assets/js/auth.js') ?>"></script>
</body>
</html>
