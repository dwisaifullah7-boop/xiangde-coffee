<?php
/**
 * Admin: Update status pesanan
 */
require_once __DIR__ . '/../includes/admin-auth.php';

if (!is_post() || !csrf_verify()) {
    set_flash('error', 'Permintaan tidak valid.');
    redirect('admin/orders.php');
}

$orderId = (int)($_POST['order_id'] ?? 0);
$status  = $_POST['order_status'] ?? '';

$valid = ['pending','processing','ready','completed','cancelled'];
if ($orderId <= 0 || !in_array($status, $valid, true)) {
    set_flash('error', 'Data tidak valid.');
    redirect('admin/orders.php');
}

$stmt = $pdo->prepare('UPDATE orders SET order_status = ? WHERE id = ?');
$stmt->execute([$status, $orderId]);
set_flash('success', 'Status pesanan berhasil diperbarui.');
redirect('admin/order-detail.php?id=' . $orderId);
