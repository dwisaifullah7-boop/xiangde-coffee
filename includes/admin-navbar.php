<?php
/**
 * Navbar atas admin (Redesigned).
 */
$adminName = $_SESSION['user_name'] ?? 'Admin';
$adminInitial = strtoupper(substr($adminName, 0, 1));
?>
<header class="admin-topbar">
  <div class="topbar-left">
    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
      <i class="fas fa-bars"></i>
    </button>
    <h1 class="topbar-title"><?= e($pageTitle ?? 'Dashboard') ?></h1>
  </div>

  <div class="topbar-right">
    <a href="<?= url('admin/orders.php') ?>" class="topbar-icon" title="Transaksi" aria-label="Transaksi">
      <i class="fas fa-receipt"></i>
    </a>
    <a href="<?= url('index.php') ?>" class="topbar-icon" title="Lihat Website" target="_blank" aria-label="Lihat Website">
      <i class="fas fa-external-link-alt"></i>
    </a>

    <div class="dropdown">
      <a href="#" class="topbar-user dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="avatar"><?= e($adminInitial) ?></span>
        <span class="d-none d-md-inline"><?= e($adminName) ?></span>
        <i class="fas fa-chevron-down small ms-1 d-none d-md-inline"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li class="dropdown-header d-md-none">
          <strong><?= e($adminName) ?></strong>
        </li>
        <li><a class="dropdown-item" href="<?= url('admin/settings.php') ?>"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="<?= url('logout.php') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
      </ul>
    </div>
  </div>
</header>
