<?php
if (file_exists(__DIR__ . '/config/config.php')) {
    require_once __DIR__ . '/config/config.php';
    if (function_exists('url')) {
        $homeUrl = url('index.php');
    } else {
        $homeUrl = '/xiangde-coffee/';
    }
} else {
    $homeUrl = '/';
}
$pageTitle = '500 - Kesalahan Server';
http_response_code(500);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>500 - Xiang De Coffee</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Serif+Display&display=swap" rel="stylesheet">
  <style>
    :root {
      --deep: #1c1917;
      --primary: #292524;
      --accent: #c8a97e;
      --accent-hover: #b8956a;
      --accent-light: #f5e6d0;
      --cream: #f0ebe3;
      --warm-white: #faf8f5;
      --border: #e7e0d6;
      --text-secondary: #57534e;
      --text-muted: #a8a29e;
      --font-heading: 'DM Serif Display', serif;
      --font-body: 'DM Sans', sans-serif;
      --transition: .35s cubic-bezier(.25,.8,.25,1);
      --radius-full: 999px;
    }
    body {
      font-family: var(--font-body);
      background: linear-gradient(160deg, var(--warm-white) 0%, var(--cream) 100%);
      margin: 0;
    }
    h1,h2,h3,h4,h5,h6 { font-family: var(--font-heading); }
    .err-wrap {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 20px;
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
    .btn-coffee {
      background: var(--primary);
      color: #fff;
      border: none;
      border-radius: var(--radius-full);
      padding: 14px 30px;
      font-weight: 600;
      font-size: .9rem;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: var(--transition);
    }
    .btn-coffee:hover { background: var(--deep); color: #fff; transform: translateY(-2px); }
    .err-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
  </style>
</head>
<body>
  <div class="err-wrap">
    <div>
      <i class="fas fa-tools err-icon"></i>
      <h1 class="err-code">5<span class="accent">0</span>0</h1>
      <h3 style="margin: 16px 0 8px;">Terjadi Kesalahan Server</h3>
      <p class="text-muted mb-4" style="max-width: 480px; margin: 0 auto 24px;">
        Maaf, terjadi kesalahan pada sistem. Silakan coba beberapa saat lagi atau hubungi kami.
      </p>
      <div class="err-actions">
        <a href="<?= htmlspecialchars($homeUrl) ?>" class="btn-coffee"><i class="fas fa-home me-1"></i> Kembali ke Beranda</a>
      </div>
    </div>
  </div>
</body>
</html>
