<?php
/**
 * Header admin (Redesigned).
 * Variabel: $pageTitle
 */
if (!defined('BASE_URL')) require_once __DIR__ . '/../config/config.php';
$pageTitle = $pageTitle ?? 'Dashboard';
$adminName = $_SESSION['user_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?> &middot; Admin Xiang De Coffee</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Serif+Display&display=swap" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

  <link href="<?= url('assets/css/admin.css') ?>" rel="stylesheet">

  <!-- Expose BASE_URL ke JavaScript untuk AJAX calls -->
  <script>
    window.XD_BASE_URL = <?= json_encode(BASE_URL) ?>;
  </script>
</head>
<body class="admin-body">

<div class="admin-wrapper">
  <?php require __DIR__ . '/admin-sidebar.php'; ?>

  <div class="admin-main">
    <?php require __DIR__ . '/admin-navbar.php'; ?>

    <div class="admin-content">
