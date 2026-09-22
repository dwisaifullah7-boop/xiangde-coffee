<?php
/**
 * Admin: Pengaturan Website
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$activeMenu = 'settings';
$pageTitle  = 'Pengaturan Website';

$keys = [
    'business_name', 'business_short_name', 'business_address', 'business_phone',
    'business_email', 'instagram_url', 'gofood_url', 'opening_hours',
    'google_maps_url', 'price_range', 'hero_tagline', 'hero_subtitle',
    'about_text', 'footer_text'
];

if (is_post() && csrf_verify()) {
    foreach ($keys as $k) {
        if (array_key_exists($k, $_POST)) {
            $value = trim($_POST[$k]);
            $stmt = $pdo->prepare('UPDATE settings SET setting_value = ? WHERE setting_key = ?');
            $stmt->execute([$value, $k]);
        }
    }
    set_flash('success', 'Pengaturan berhasil disimpan.');
    redirect('admin/settings.php');
}

require __DIR__ . '/../includes/admin-header.php';
?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5>Pengaturan Website</h5>
  </div>

  <form method="post" class="admin-form">
    <?= csrf_field() ?>
    <h6 class="text-muted mb-3"><i class="fas fa-store me-1"></i> Informasi Bisnis</h6>
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <label class="form-label">Nama Bisnis</label>
        <input type="text" name="business_name" class="form-control" value="<?= e(setting('business_name')) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Nama Singkat</label>
        <input type="text" name="business_short_name" class="form-control" value="<?= e(setting('business_short_name')) ?>">
      </div>
      <div class="col-12">
        <label class="form-label">Alamat</label>
        <textarea name="business_address" class="form-control" rows="2"><?= e(setting('business_address')) ?></textarea>
      </div>
      <div class="col-md-4">
        <label class="form-label">Telepon</label>
        <input type="text" name="business_phone" class="form-control" value="<?= e(setting('business_phone')) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Email</label>
        <input type="email" name="business_email" class="form-control" value="<?= e(setting('business_email')) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Jam Buka</label>
        <input type="text" name="opening_hours" class="form-control" value="<?= e(setting('opening_hours')) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Kisaran Harga</label>
        <input type="text" name="price_range" class="form-control" value="<?= e(setting('price_range')) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Google Maps URL</label>
        <input type="text" name="google_maps_url" class="form-control" value="<?= e(setting('google_maps_url')) ?>">
      </div>
    </div>

    <h6 class="text-muted mb-3"><i class="fab fa-instagram me-1"></i> Media Sosial</h6>
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <label class="form-label">Instagram URL</label>
        <input type="text" name="instagram_url" class="form-control" value="<?= e(setting('instagram_url')) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">GoFood URL</label>
        <input type="text" name="gofood_url" class="form-control" value="<?= e(setting('gofood_url')) ?>">
      </div>
    </div>

    <h6 class="text-muted mb-3"><i class="fas fa-home me-1"></i> Konten Halaman</h6>
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <label class="form-label">Hero Tagline</label>
        <input type="text" name="hero_tagline" class="form-control" value="<?= e(setting('hero_tagline')) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Hero Subtitle</label>
        <textarea name="hero_subtitle" class="form-control" rows="2"><?= e(setting('hero_subtitle')) ?></textarea>
      </div>
      <div class="col-12">
        <label class="form-label">About Text</label>
        <textarea name="about_text" class="form-control" rows="3"><?= e(setting('about_text')) ?></textarea>
      </div>
      <div class="col-12">
        <label class="form-label">Footer Text</label>
        <input type="text" name="footer_text" class="form-control" value="<?= e(setting('footer_text')) ?>">
      </div>
    </div>

    <button type="submit" class="btn btn-admin-primary"><i class="fas fa-save me-1"></i> Simpan Pengaturan</button>
  </form>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
