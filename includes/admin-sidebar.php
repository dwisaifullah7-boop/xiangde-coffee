<?php
/**
 * Sidebar admin (Redesigned).
 */
$activeMenu = $activeMenu ?? '';
$adminName  = $_SESSION['user_name'] ?? 'Admin';

$menuItems = [
    'dashboard'  => ['label' => 'Dashboard',  'icon' => 'fa-tachometer-alt', 'url' => 'admin/dashboard.php'],
    'products'   => ['label' => 'Produk',     'icon' => 'fa-box',            'url' => 'admin/products.php'],
    'categories' => ['label' => 'Kategori',   'icon' => 'fa-tags',           'url' => 'admin/categories.php'],
    'orders'     => ['label' => 'Transaksi',  'icon' => 'fa-receipt',        'url' => 'admin/orders.php'],
    'customers'  => ['label' => 'Pelanggan',  'icon' => 'fa-users',          'url' => 'admin/customers.php'],
    'settings'   => ['label' => 'Pengaturan', 'icon' => 'fa-cog',            'url' => 'admin/settings.php'],
];
?>
<aside class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-brand">
    <span class="brand-icon"><i class="fas fa-mug-hot"></i></span>
    <div class="brand-info">
      <div class="brand-name">XD Coffee</div>
      <small>Admin Panel</small>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">MENU UTAMA</div>
    <?php foreach ($menuItems as $key => $item): ?>
      <a href="<?= url($item['url']) ?>" class="sidebar-link <?= $activeMenu===$key?'active':'' ?>">
        <i class="fas <?= e($item['icon']) ?>"></i>
        <span><?= e($item['label']) ?></span>
      </a>
    <?php endforeach; ?>
  </nav>

  <div class="sidebar-footer">
    <a href="<?= url('index.php') ?>" class="btn btn-outline-light btn-sm w-100 mb-2">
      <i class="fas fa-globe me-1"></i> Lihat Website
    </a>
    <a href="<?= url('logout.php') ?>" class="btn btn-danger btn-sm w-100">
      <i class="fas fa-sign-out-alt me-1"></i> Logout
    </a>
  </div>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>
