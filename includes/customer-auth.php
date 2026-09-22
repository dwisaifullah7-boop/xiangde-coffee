<?php
/**
 * Guard untuk halaman customer.
 * Letakkan di atas file customer/*.php sebelum output.
 *
 * Pengecekan dua lapis:
 *   1. is_customer() — cek session role (cepat, tanpa query DB)
 *   2. current_user() !== null — cek user benar-benar ada di DB
 *      (menangani kasus database di-reset / user dihapus setelah login)
 *
 * Jika user tidak valid, session dibersihkan dan user diarahkan ke login
 * dengan pesan flash — bukan fatal error di halaman tujuan.
 */
require_once __DIR__ . '/auth.php';

if (!is_customer()) {
    set_flash('warning', 'Silakan login terlebih dahulu.');
    redirect('login.php');
}

// Verifikasi user masih ada di DB. current_user() otomatis membersihkan
// session jika user tidak ditemukan.
if (current_user() === null) {
    set_flash('warning', 'Sesi Anda tidak valid. Silakan login kembali.');
    redirect('login.php');
}
