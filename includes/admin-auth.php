<?php
/**
 * Guard untuk halaman admin.
 * Letakkan di atas file admin/*.php sebelum output.
 *
 * Pengecekan dua lapis:
 *   1. is_admin() — cek session role (cepat, tanpa query DB)
 *   2. current_user() !== null — cek user benar-benar ada di DB
 *      (menangani kasus database di-reset / user dihapus setelah login)
 */
require_once __DIR__ . '/auth.php';

if (!is_admin()) {
    set_flash('warning', 'Akses ditolak. Silakan login sebagai admin.');
    redirect('login.php');
}

// Verifikasi user masih ada di DB.
if (current_user() === null) {
    set_flash('warning', 'Sesi Anda tidak valid. Silakan login kembali.');
    redirect('login.php');
}
