<?php
/**
 * Admin: Hapus Produk
 */
require_once __DIR__ . '/../includes/admin-auth.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { set_flash('error', 'Produk tidak valid.'); redirect('admin/products.php'); }

$stmt = $pdo->prepare('SELECT image FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) { set_flash('error', 'Produk tidak ditemukan.'); redirect('admin/products.php'); }

// Hapus gambar fisik
if ($product['image']) {
    @unlink(UPLOAD_DIR . $product['image']);
}

$stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
$stmt->execute([$id]);
set_flash('success', 'Produk berhasil dihapus.');
redirect('admin/products.php');
