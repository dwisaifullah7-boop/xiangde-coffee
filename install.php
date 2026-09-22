<?php
/**
 * INSTALLER OTOMATIS - Xiang De Coffee
 * ----------------------------------------------------
 * Jalankan SEKALI setelah import database.
 * Akses via browser: http://localhost/xiangde-coffee/install.php
 *
 * Fungsi:
 *   1. Verifikasi koneksi database.
 *   2. Pastikan tabel utama ada.
 *   3. Reset password admin menjadi "admin123" (hash valid bcrypt).
 *   4. Tampilkan info login.
 *
 * Setelah selesai, HAPUS file ini untuk keamanan!
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

$step = $_GET['step'] ?? 'check';
$msg  = '';
$err  = '';

if ($step === 'reset' && is_post()) {
    $newPass = $_POST['admin_password'] ?? 'admin123';
    if (strlen($newPass) < 6) {
        $err = 'Password minimal 6 karakter.';
    } else {
        $hash = password_hash($newPass, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
        $stmt->execute([$hash]);

        if ($stmt->rowCount() > 0) {
            $msg = "Password admin berhasil direset ke: <strong>$newPass</strong>";
        } else {
            // Buat admin baru jika tidak ada
            $stmt = $pdo->prepare(
                "INSERT INTO users (name, username, email, phone, password, role, alamat)
                 VALUES ('Administrator', 'admin', 'admin@xiangdecoffee.com', '081234567890', ?, 'admin', 'Cirebon')
                 ON DUPLICATE KEY UPDATE password = VALUES(password)"
            );
            $stmt->execute([$hash]);
            $msg = "Admin user dibuat / direset. Password: <strong>$newPass</strong>";
        }
    }
}

// Cek tabel
$tablesOk = true;
$missing  = [];
foreach (['users','categories','products','cart','orders','order_details','settings'] as $t) {
    try {
        $pdo->query("SELECT 1 FROM $t LIMIT 1");
    } catch (Throwable $e) {
        $tablesOk = false;
        $missing[] = $t;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Installer - Xiang De Coffee</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'DM Sans', sans-serif; background: linear-gradient(160deg,#1c1917,#292524); min-height: 100vh; padding: 40px 15px; }
    .install-card { max-width: 640px; margin: 0 auto; background: #fff; border-radius: 22px; padding: 40px; box-shadow: 0 20px 60px rgba(0,0,0,.3); }
    .install-logo { text-align: center; margin-bottom: 24px; }
    .install-logo i { font-size: 2.5rem; color: #c8a97e; }
    .install-logo h2 { font-family: 'DM Serif Display', serif; margin: 8px 0 0; color: #1c1917; }
    .check-item { padding: 10px 14px; border-radius: 10px; margin-bottom: 8px; display: flex; align-items: center; gap: 10px; }
    .check-item.ok { background: #d4edda; color: #155724; }
    .check-item.fail { background: #f8d7da; color: #721c24; }
  </style>
</head>
<body>
  <div class="install-card">
    <div class="install-logo">
      <i class="fas fa-mug-hot"></i>
      <h2>Xiang De Coffee - Installer</h2>
      <small class="text-muted">Setup awal website</small>
    </div>

    <?php if ($msg): ?>
      <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>
    <?php if ($err): ?>
      <div class="alert alert-danger"><?= $err ?></div>
    <?php endif; ?>

    <h6>Checklist Sistem</h6>
    <div class="check-item ok"><i class="fas fa-check-circle"></i> Koneksi database berhasil</div>
    <?php if ($tablesOk): ?>
      <div class="check-item ok"><i class="fas fa-check-circle"></i> Semua tabel utama tersedia</div>
    <?php else: ?>
      <div class="check-item fail">
        <i class="fas fa-times-circle"></i>
        Tabel berikut belum ada: <strong><?= implode(', ', $missing) ?></strong>.
        Import <code>database/xiangde_coffee.sql</code> via phpMyAdmin terlebih dahulu.
      </div>
    <?php endif; ?>

    <?php if (PHP_VERSION_ID >= 80000): ?>
      <div class="check-item ok"><i class="fas fa-check-circle"></i> PHP <?= PHP_VERSION ?> (memenuhi syarat ≥ 8.0)</div>
    <?php else: ?>
      <div class="check-item fail"><i class="fas fa-times-circle"></i> PHP <?= PHP_VERSION ?> - butuh minimal 8.0</div>
    <?php endif; ?>

    <hr class="my-4">

    <h6>Reset Password Admin</h6>
    <p class="text-muted small">Gunakan form ini untuk reset password admin dengan hash bcrypt yang valid (sangat disarankan setelah import SQL baru).</p>
    <form method="post" action="?step=reset">
      <div class="mb-3">
        <label class="form-label small">Password Baru Admin</label>
        <input type="text" name="admin_password" class="form-control" value="admin123" required>
        <small class="text-muted">Default: admin123 — ganti setelah login pertama.</small>
      </div>
      <button type="submit" class="btn btn-coffee w-100" style="background:#292524;color:#fff;border:none;padding:12px;border-radius:999px;font-weight:600;letter-spacing:.03em;">
        <i class="fas fa-key me-1"></i> Reset Password Admin
      </button>
    </form>

    <hr class="my-4">

    <div class="alert alert-info">
      <strong>Info Login Default:</strong><br>
      URL Admin: <code><?= url('admin/dashboard.php') ?></code><br>
      URL Customer: <code><?= url('customer/dashboard.php') ?></code><br>
      Username: <code>admin</code><br>
      Password: <code>admin123</code> (setelah reset di atas)
    </div>

    <a href="<?= url('index.php') ?>" class="btn btn-outline-coffee w-100" style="border:2px solid #292524;color:#292524;border-radius:999px;padding:10px;font-weight:600;letter-spacing:.03em;">
      <i class="fas fa-home me-1"></i> Buka Website
    </a>

    <div class="alert alert-warning mt-4">
      <i class="fas fa-exclamation-triangle me-1"></i>
      <strong>Penting:</strong> Hapus file <code>install.php</code> setelah instalasi selesai demi keamanan.
    </div>
  </div>
</body>
</html>
