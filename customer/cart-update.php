<?php
/**
 * Update quantity / note item cart.
 */
require_once __DIR__ . '/../includes/customer-auth.php';

if (!is_customer() || !is_post() || !csrf_verify()) {
    set_flash('error', 'Permintaan tidak valid.');
    redirect('customer/cart.php');
}

$cartId    = (int)($_POST['cart_id'] ?? 0);
$quantity  = max(1, (int)($_POST['quantity']  ?? 1));
$note      = trim($_POST['note'] ?? '');

if ($cartId <= 0) {
    set_flash('error', 'Item tidak valid.');
    redirect('customer/cart.php');
}

// Pastikan item milik user
$stmt = $pdo->prepare('SELECT id FROM cart WHERE id = ? AND user_id = ?');
$stmt->execute([$cartId, $_SESSION['user_id']]);
if (!$stmt->fetch()) {
    set_flash('error', 'Item tidak ditemukan.');
    redirect('customer/cart.php');
}

$stmt = $pdo->prepare('UPDATE cart SET quantity = ?, note = ? WHERE id = ?');
$stmt->execute([$quantity, $note ?: null, $cartId]);
set_flash('success', 'Keranjang diperbarui.');
redirect('customer/cart.php');
