<?php
/**
 * Header publik (public website).
 * Variabel:
 *   $pageTitle    (opsional) - title halaman
 *   $bodyClass    (opsional) - class tambahan untuk <body>
 *   $extraCss     (array)    - daftar URL CSS tambahan untuk halaman ini
 */
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config/config.php';
}
$pageTitle = $pageTitle ?? setting('business_name', 'Xiang De Coffee');
$currentPage = $currentPage ?? '';
$bodyClass  = $bodyClass  ?? '';
$extraCss   = $extraCss   ?? [];

// Tentukan apakah halaman ini home (untuk navbar transparent over hero)
$isHomePage = $currentPage === 'home';
if ($isHomePage) {
    $bodyClass = trim($bodyClass . ' page-home');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> &middot; Xiang De Coffee</title>
    <meta name="description" content="Xiang De Coffee - Coffee Shop & Kuliner di Cirebon, Jawa Barat.">

    <!-- Google Fonts: DM Serif Display + DM Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Serif+Display&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <link href="<?= url('assets/css/style.css') ?>" rel="stylesheet">
    <link href="<?= url('assets/css/responsive.css') ?>" rel="stylesheet">

    <?php foreach ($extraCss as $cssUrl): ?>
        <link href="<?= e($cssUrl) ?>" rel="stylesheet">
    <?php endforeach; ?>

    <!-- Expose BASE_URL ke JavaScript untuk AJAX calls -->
    <script>
      window.XD_BASE_URL = <?= json_encode(BASE_URL) ?>;
    </script>
</head>
<body class="<?= e($bodyClass) ?>">

<?php require __DIR__ . '/navbar.php'; ?>

<main>
