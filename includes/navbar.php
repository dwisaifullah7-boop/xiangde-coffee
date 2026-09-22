<?php
/**
 * Navbar publik (Redesigned).
 */
$currentPage     = $currentPage ?? '';
$businessName    = setting('business_name', 'Xiang De Coffee');
$businessShort   = setting('business_short_name', 'XD Coffee');
$navLinks = [
    'home'    => ['label' => 'Home',    'url' => 'index.php'],
    'about'   => ['label' => 'About',   'url' => 'about.php'],
    'menu'    => ['label' => 'Menu',    'url' => 'menu.php'],
    'gallery' => ['label' => 'Gallery', 'url' => 'gallery.php'],
    'contact' => ['label' => 'Contact', 'url' => 'contact.php'],
];
?>
<nav class="navbar navbar-expand-lg xd-navbar" id="mainNavbar">
  <div class="container">
    <a class="navbar-brand" href="<?= url('index.php') ?>">
      <span class="brand-icon"><i class="fas fa-mug-hot"></i></span>
      <span class="brand-text">
        <span class="brand-name"><?= e($businessShort) ?></span>
        <small>Coffee &middot; Cirebon</small>
      </span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-bars"></i>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <?php foreach ($navLinks as $key => $link): ?>
          <li class="nav-item">
            <a class="nav-link <?= $currentPage===$key?'active':'' ?>" href="<?= url($link['url']) ?>">
              <?= e($link['label']) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>

      <div class="d-flex align-items-center gap-2 xd-nav-actions">
        <?php if (is_logged_in()): ?>
          <?php if (is_admin()): ?>
            <a href="<?= url('admin/dashboard.php') ?>" class="btn btn-outline-light btn-sm">
              <i class="fas fa-user-shield me-1"></i> <span class="d-none d-sm-inline">Admin</span>
            </a>
          <?php else: ?>
            <a href="<?= url('customer/cart.php') ?>" class="btn btn-outline-light btn-sm position-relative" title="Keranjang" aria-label="Keranjang">
              <i class="fas fa-shopping-cart"></i>
              <?php $cc = cart_count(); ?>
              <span id="cartCount" class="cart-badge" style="<?= $cc > 0 ? '' : 'display:none;' ?>"><?= $cc ?></span>
            </a>
            <?php
              $navUser = current_user();
              $navAvatarUrl = $navUser ? avatar_url($navUser['profile_photo'] ?? null) : null;
              $navDisplayName = $navUser ? ($navUser['name'] ?? '') : ($_SESSION['user_name'] ?? 'Akun');
            ?>
            <a href="<?= url('customer/dashboard.php') ?>" class="btn btn-outline-light btn-sm d-inline-flex align-items-center gap-2">
              <?php if ($navAvatarUrl): ?>
                <img src="<?= e($navAvatarUrl) ?>" alt="" class="nav-avatar-img">
              <?php else: ?>
                <i class="fas fa-user"></i>
              <?php endif; ?>
              <span class="d-none d-sm-inline"><?= e($navDisplayName ?: 'Akun') ?></span>
            </a>
          <?php endif; ?>
          <a href="<?= url('logout.php') ?>" class="btn btn-light btn-sm" title="Logout" aria-label="Logout">
            <i class="fas fa-sign-out-alt"></i>
          </a>
        <?php else: ?>
          <a href="<?= url('login.php') ?>" class="btn btn-outline-light btn-sm">Login</a>
          <a href="<?= url('register.php') ?>" class="btn btn-light btn-sm">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
