<?php
/**
 * Hapus item dari keranjang.
 */
require_once __DIR__ . '/../includes/customer-auth.php';

if (!is_customer()) {
    redirect('login.php');
}

$cartId = (int)($_GET['id'] ?? 0);
if ($cartId <= 0) {
    set_flash('error', 'Item tidak valid.');
    redirect('customer/cart.php');
}

$stmt = $pdo->prepare('DELETE FROM cart WHERE id = ? AND user_id = ?');
$stmt->execute([$cartId, $_SESSION['user_id']]);
set_flash('success', 'Item dihapus dari keranjang.');
redirect('customer/cart.php');
