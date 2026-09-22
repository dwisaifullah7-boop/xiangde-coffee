<?php
/**
 * Tambah produk ke keranjang (AJAX / form POST).
 */
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json');

// Cek login + verifikasi user masih ada di DB.
// current_user() otomatis invalidate session bila user tidak ditemukan.
if (!is_customer() || current_user() === null) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login sebagai customer.', 'require_login' => true]);
    exit;
}

if (!is_post() || !csrf_verify()) {
    echo json_encode(['success' => false, 'message' => 'Permintaan tidak valid.']);
    exit;
}

$productId = (int)($_POST['product_id'] ?? 0);
$quantity  = max(1, (int)($_POST['quantity']  ?? 1));
$note      = trim($_POST['note'] ?? '');

if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Produk tidak valid.']);
    exit;
}

// Cek produk
$stmt = $pdo->prepare('SELECT id, status FROM products WHERE id = ?');
$stmt->execute([$productId]);
$product = $stmt->fetch();
if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan.']);
    exit;
}
if ($product['status'] !== PRODUCT_AVAILABLE) {
    echo json_encode(['success' => false, 'message' => 'Produk sedang tidak tersedia.']);
    exit;
}

// Cek apakah sudah ada di cart (tanpa note) -> update quantity
$stmt = $pdo->prepare('SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND (note = ? OR (note IS NULL AND ? = "")) LIMIT 1');
$stmt->execute([$_SESSION['user_id'], $productId, $note, $note]);
$existing = $stmt->fetch();

if ($existing) {
    $newQty = $existing['quantity'] + $quantity;
    $stmt = $pdo->prepare('UPDATE cart SET quantity = ? WHERE id = ?');
    $stmt->execute([$newQty, $existing['id']]);
} else {
    $stmt = $pdo->prepare('INSERT INTO cart (user_id, product_id, quantity, note) VALUES (?, ?, ?, ?)');
    $stmt->execute([$_SESSION['user_id'], $productId, $quantity, $note ?: null]);
}

echo json_encode([
    'success'    => true,
    'message'    => 'Produk ditambahkan ke keranjang.',
    'cart_count' => cart_count(),
]);
