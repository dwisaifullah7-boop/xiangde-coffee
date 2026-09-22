<?php
/**
 * Admin: Hapus Kategori (juga menghapus produk di dalamnya via FK CASCADE)
 */
require_once __DIR__ . '/../includes/admin-auth.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { set_flash('error', 'Kategori tidak valid.'); redirect('admin/categories.php'); }

// Hapus gambar produk sebelum kategori dihapus (CASCADE)
$stmt = $pdo->prepare('SELECT image FROM products WHERE category_id = ?');
$stmt->execute([$id]);
foreach ($stmt->fetchAll() as $p) {
    if ($p['image']) @unlink(UPLOAD_DIR . $p['image']);
}

$stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
$stmt->execute([$id]);
set_flash('success', 'Kategori berhasil dihapus.');
redirect('admin/categories.php');
