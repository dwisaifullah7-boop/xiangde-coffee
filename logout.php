<?php
/**
 * Logout - hapus session dan redirect ke home.
 */
require_once __DIR__ . '/includes/auth.php';
logout_user();
set_flash('success', 'Anda telah berhasil logout.');
redirect('index.php');
