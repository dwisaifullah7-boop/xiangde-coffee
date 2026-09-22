<?php
/**
 * Admin: Reset Password Customer
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$activeMenu = 'customers';
$pageTitle  = 'Reset Password Pelanggan';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { set_flash('error', 'Pelanggan tidak ditemukan.'); redirect('admin/customers.php'); }

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'customer'");
$stmt->execute([$id]);
$customer = $stmt->fetch();
if (!$customer) { set_flash('error', 'Pelanggan tidak ditemukan.'); redirect('admin/customers.php'); }

if (is_post() && csrf_verify()) {
    $new = $_POST['new_password'] ?? '';
    $result = admin_reset_password($id, $new);
    if ($result['success']) {
        set_flash('success', 'Password pelanggan berhasil direset.');
        redirect('admin/customer-detail.php?id=' . $id);
    } else {
        $errors = $result['errors'];
    }
}

require __DIR__ . '/../includes/admin-header.php';
?>

<div class="admin-card" style="max-width:600px;">
  <div class="admin-card-header">
    <h5>Reset Password: <?= e($customer['name']) ?></h5>
    <a href="<?= url('admin/customer-detail.php?id=' . $id) ?>" class="btn btn-admin-secondary btn-sm">Kembali</a>
  </div>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger"><ul class="mb-0 ps-3"><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul></div>
  <?php endif; ?>

  <div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle me-1"></i> Password baru akan langsung aktif. Pelanggan disarankan menggantinya setelah login.
  </div>

  <form method="post" class="admin-form">
    <?= csrf_field() ?>
    <div class="mb-3">
      <label class="form-label">Password Baru</label>
      <input type="text" name="new_password" class="form-control" required minlength="6" value="<?= e($_POST['new_password'] ?? 'customer123') ?>">
      <small class="text-muted">Minimal 6 karakter. Default: customer123</small>
    </div>
    <button type="submit" class="btn btn-admin-primary"><i class="fas fa-key me-1"></i> Reset Password</button>
    <a href="<?= url('admin/customer-detail.php?id=' . $id) ?>" class="btn btn-admin-secondary">Batal</a>
  </form>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
