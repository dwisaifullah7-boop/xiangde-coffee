<?php
/**
 * Proses checkout: pindah cart -> orders + order_details, kosongkan cart.
 */
require_once __DIR__ . '/../includes/customer-auth.php';

if (!is_customer() || !is_post() || !csrf_verify()) {
    set_flash('error', 'Permintaan tidak valid.');
    redirect('customer/cart.php');
}

$userId        = $_SESSION['user_id'];
$paymentMethod = $_POST['payment_method'] ?? PAY_CASH;
$orderType     = $_POST['order_type']     ?? ORDER_DINE_IN;
$note          = trim($_POST['note'] ?? '');

// Validasi enum
$validPay  = [PAY_CASH, PAY_TRANSFER, PAY_QRIS];
$validType = [ORDER_DINE_IN, ORDER_TAKE_AWAY, ORDER_DELIVERY];
if (!in_array($paymentMethod, $validPay, true) || !in_array($orderType, $validType, true)) {
    set_flash('error', 'Pilihan tidak valid.');
    redirect('customer/checkout.php');
}

// Ambil item cart
$stmt = $pdo->prepare(
    'SELECT c.*, p.name, p.harga, p.status, p.id AS product_id
     FROM cart c JOIN products p ON p.id = c.product_id
     WHERE c.user_id = ?'
);
$stmt->execute([$userId]);
$items = $stmt->fetchAll();

if (empty($items)) {
    set_flash('warning', 'Keranjang Anda kosong.');
    redirect('customer/cart.php');
}

$total = 0;
foreach ($items as $it) {
    if ($it['status'] === PRODUCT_AVAILABLE) {
        $total += $it['harga'] * $it['quantity'];
    }
}
if ($total <= 0) {
    set_flash('error', 'Tidak ada item yang dapat diproses.');
    redirect('customer/cart.php');
}

$invoice = generate_invoice_number();

try {
    $pdo->beginTransaction();

    // Insert order
    $stmt = $pdo->prepare(
        'INSERT INTO orders (user_id, invoice_number, total_price, payment_method, order_type, order_status, note)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$userId, $invoice, $total, $paymentMethod, $orderType, STATUS_PENDING, $note ?: null]);
    $orderId = (int)$pdo->lastInsertId();

    // Insert order details (snapshot harga & nama)
    $stmtDetail = $pdo->prepare(
        'INSERT INTO order_details (order_id, product_id, product_name, price, quantity, subtotal)
         VALUES (?, ?, ?, ?, ?, ?)'
    );
    foreach ($items as $it) {
        if ($it['status'] !== PRODUCT_AVAILABLE) continue;
        $subtotal = $it['harga'] * $it['quantity'];
        $stmtDetail->execute([
            $orderId,
            $it['product_id'],
            $it['name'],
            $it['harga'],
            $it['quantity'],
            $subtotal,
        ]);
    }

    // Kosongkan cart user
    $stmt = $pdo->prepare('DELETE FROM cart WHERE user_id = ?');
    $stmt->execute([$userId]);

    $pdo->commit();

    // Redirect ke halaman sukses
    $_SESSION['last_order_id'] = $orderId;
    redirect('customer/order-success.php?id=' . $orderId);

} catch (Throwable $e) {
    $pdo->rollBack();
    if (ENVIRONMENT === 'development') {
        die('Checkout error: ' . $e->getMessage());
    }
    set_flash('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
    redirect('customer/checkout.php');
}
