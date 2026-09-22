<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = '403 - Akses Ditolak';
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>403 - Xiang De Coffee</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Serif+Display&display=swap" rel="stylesheet">
  <link href="<?= url('assets/css/style.css') ?>" rel="stylesheet">
  <style>
    body { padding-top: 0; }
    .err-wrap {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 20px;
      background: linear-gradient(160deg, var(--warm-white) 0%, var(--cream) 100%);
    }
    .err-code {
      font-family: var(--font-heading);
      font-size: clamp(6rem, 18vw, 10rem);
      color: var(--primary);
      line-height: 1;
      margin: 0;
      letter-spacing: -0.04em;
    }
    .err-code .accent { color: var(--accent); }
    .err-icon {
      font-size: 3rem;
      color: var(--accent);
      margin-bottom: 16px;
    }
    .err-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
  </style>
</head>
<body>
  <div class="err-wrap">
    <div>
      <i class="fas fa-lock err-icon"></i>
      <h1 class="err-code">4<span class="accent">0</span>3</h1>
      <h3 style="font-family: var(--font-heading); margin: 16px 0 8px;">Akses Ditolak</h3>
      <p class="text-muted mb-4" style="max-width: 480px; margin: 0 auto 24px;">
        Anda tidak memiliki izin untuk mengakses halaman ini. Silakan login dengan akun yang sesuai.
      </p>
      <div class="err-actions">
        <a href="<?= url('index.php') ?>" class="btn btn-coffee"><i class="fas fa-home me-1"></i> Kembali ke Beranda</a>
        <a href="<?= url('login.php') ?>" class="btn btn-outline-coffee"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
      </div>
    </div>
  </div>
</body>
</html>
