<?php
/**
 * Admin: Hapus pesanan
 */
require_once __DIR__ . '/../includes/admin-auth.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { set_flash('error', 'Transaksi tidak valid.'); redirect('admin/orders.php'); }

$stmt = $pdo->prepare('DELETE FROM orders WHERE id = ?');
$stmt->execute([$id]);
set_flash('success', 'Transaksi berhasil dihapus.');
redirect('admin/orders.php');
