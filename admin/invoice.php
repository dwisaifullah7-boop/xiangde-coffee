<?php
/**
 * Admin: Cetak Invoice (Redesigned v3 - Modern Professional)
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$pageTitle = 'Invoice';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) redirect('admin/orders.php');

$stmt = $pdo->prepare('SELECT o.*, u.name AS customer_name, u.email, u.phone, u.alamat, u.profile_photo
                       FROM orders o JOIN users u ON u.id = o.user_id
                       WHERE o.id = ?');
$stmt->execute([$id]);
$order = $stmt->fetch();
if (!$order) redirect('admin/orders.php');

$stmt = $pdo->prepare('SELECT * FROM order_details WHERE order_id = ?');
$stmt->execute([$id]);
$details = $stmt->fetchAll();

$totalQty    = 0;
$totalSubtotal = 0;
foreach ($details as $d) {
    $totalQty      += $d['quantity'];
    $totalSubtotal += $d['subtotal'];
}

$businessName    = setting('business_name', 'Xiang De Coffee');
$businessAddress = setting('business_address', 'Jl. Saputra 3, Kedungjaya, Kec. Kedawung, Kabupaten Cirebon, Jawa Barat 45153');
$businessPhone   = setting('business_phone', '-');
$businessInsta   = setting('business_instagram', '@xiangdecoffee');

$orderTypeLabels = [
    'dine_in'   => ['label' => 'Dine In',    'icon' => 'fa-utensils'],
    'take_away' => ['label' => 'Take Away',  'icon' => 'fa-shopping-bag'],
    'delivery'  => ['label' => 'Delivery',   'icon' => 'fa-truck'],
];
$otInfo = $orderTypeLabels[$order['order_type']] ?? ['label' => ucfirst($order['order_type']), 'icon' => 'fa-receipt'];

$payLabels = [
    'cash'     => ['label' => 'Cash',     'icon' => 'fa-money-bill-wave'],
    'transfer' => ['label' => 'Transfer', 'icon' => 'fa-building-columns'],
    'qris'     => ['label' => 'QRIS',     'icon' => 'fa-qrcode'],
];
$payInfo = $payLabels[$order['payment_method']] ?? ['label' => strtoupper($order['payment_method']), 'icon' => 'fa-credit-card'];

$statusLabels = [
    'pending'    => 'Menunggu Konfirmasi',
    'processing' => 'Sedang Diproses',
    'ready'      => 'Siap Diambil',
    'completed'  => 'Selesai',
    'cancelled'  => 'Dibatalkan',
];
$statusLabel = $statusLabels[$order['order_status']] ?? ucfirst($order['order_status']);

$adminName = $_SESSION['user_name'] ?? 'Admin';

// Cetak/Simpan PDF hanya bisa dilakukan setelah admin mengkonfirmasi pesanan
// Status sah untuk dicetak: processing, ready, completed. pending & cancelled diblokir.
$canPrint = !in_array($order['order_status'], ['pending', 'cancelled'], true);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice <?= e($order['invoice_number']) ?> &middot; <?= e($businessName) ?> Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
  <link href="<?= url('assets/css/invoice.css') ?>" rel="stylesheet">
</head>
<body>

<div class="invoice-toolbar no-print">
  <div class="toolbar-left">
    <i class="fas fa-mug-hot fa-lg"></i>
    <span>Admin Invoice &middot; <?= e($order['invoice_number']) ?></span>
  </div>
  <div class="toolbar-actions">
    <a href="<?= url('admin/order-detail.php?id=' . $order['id']) ?>" class="invoice-btn invoice-btn-secondary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <?php if ($canPrint): ?>
      <button onclick="window.print()" class="invoice-btn invoice-btn-primary">
        <i class="fas fa-print"></i> Cetak / Simpan PDF
      </button>
    <?php else: ?>
      <button type="button" class="invoice-btn invoice-btn-primary" disabled
              style="opacity:.55; cursor:not-allowed; box-shadow:none;"
              title="Cetak invoice hanya tersedia setelah pesanan dikonfirmasi.">
        <i class="fas fa-lock"></i> Cetak / Simpan PDF
      </button>
    <?php endif; ?>
  </div>
</div>

<?php if (!$canPrint): ?>
<div class="invoice-print-disabled-notice no-print" style="max-width:820px;margin:0 auto 18px;padding:12px 18px;border-radius:12px;background:#fff7ed;border:1px solid #fed7aa;color:#9a3412;font-size:.9rem;display:flex;align-items:center;gap:10px;">
  <i class="fas fa-clock"></i>
  <span><strong>Cetak invoice belum tersedia.</strong> Silakan ubah status pesanan menjadi <em>Diproses</em> / <em>Siap</em> / <em>Selesai</em> terlebih dahulu pada halaman detail transaksi, lalu muat ulang halaman ini untuk mencetak invoice.</span>
</div>
<?php endif; ?>

<div class="invoice-paper">
  <div class="invoice-band"></div>

  <!-- ============ HEADER ============ -->
  <div class="invoice-header">
    <div class="invoice-brand">
      <div class="invoice-logo"><i class="fas fa-mug-hot"></i></div>
      <div class="invoice-brand-info">
        <h3><?= e($businessName) ?></h3>
        <div class="brand-meta">
          <?= e($businessAddress) ?><br>
          <i class="fas fa-phone me-1"></i> <?= e($businessPhone) ?>
          <?php if ($businessInsta): ?>
            &middot; <i class="fab fa-instagram me-1"></i> <?= e($businessInsta) ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="invoice-title-block">
      <div class="invoice-label">Invoice</div>
      <h1>INVOICE</h1>
      <div class="invoice-number"><?= e($order['invoice_number']) ?></div>
    </div>
  </div>

  <!-- ============ META STRIP ============ -->
  <div class="invoice-meta-strip">
    <div class="invoice-meta-item">
      <div class="label">Tanggal Pesanan</div>
      <div class="value"><?= format_date($order['created_at']) ?></div>
    </div>
    <div class="invoice-meta-item">
      <div class="label">Jenis Pesanan</div>
      <div class="value"><i class="fas <?= e($otInfo['icon']) ?> me-1"></i> <?= e($otInfo['label']) ?></div>
    </div>
    <div class="invoice-meta-item">
      <div class="label">Metode Pembayaran</div>
      <div class="value"><i class="fas <?= e($payInfo['icon']) ?> me-1"></i> <?= e($payInfo['label']) ?></div>
    </div>
    <div class="invoice-meta-item">
      <div class="label">Status</div>
      <div class="value">
        <span class="invoice-status-pill <?= e($order['order_status']) ?>"><?= e($statusLabel) ?></span>
      </div>
    </div>
  </div>

  <!-- ============ BILL TO / ORDER DETAIL ============ -->
  <div class="invoice-bill">
    <div class="invoice-bill-section">
      <h6>Pelanggan</h6>
      <p class="bill-name"><?= e($order['customer_name']) ?></p>
      <div class="bill-line"><i class="fas fa-envelope"></i> <?= e($order['email']) ?></div>
      <?php if ($order['phone']): ?>
        <div class="bill-line"><i class="fas fa-phone"></i> <?= e($order['phone']) ?></div>
      <?php endif; ?>
      <?php if ($order['alamat']): ?>
        <div class="bill-line"><i class="fas fa-location-dot"></i> <?= e($order['alamat']) ?></div>
      <?php endif; ?>
    </div>
    <div class="invoice-bill-section right">
      <h6>Detail Pesanan</h6>
      <div class="bill-line"><i class="fas fa-hashtag"></i> <?= e($order['invoice_number']) ?></div>
      <div class="bill-line"><i class="fas fa-<?= e($otInfo['icon']) ?>"></i> <?= e($otInfo['label']) ?></div>
      <div class="bill-line"><i class="fas fa-<?= e($payInfo['icon']) ?>"></i> <?= e($payInfo['label']) ?></div>
      <div class="bill-line"><i class="fas fa-box"></i> <?= $totalQty ?> Item (<?= number_format(count($details)) ?> jenis)</div>
    </div>
  </div>

  <!-- ============ ITEMS ============ -->
  <div class="invoice-items-wrap">
    <p class="invoice-items-title">Rincian Pesanan</p>
    <table class="invoice-table">
      <thead>
        <tr>
          <th class="text-center" style="width:50px;">No</th>
          <th>Produk</th>
          <th class="text-center" style="width:80px;">Qty</th>
          <th class="text-end" style="width:130px;">Harga</th>
          <th class="text-end" style="width:150px;">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; foreach ($details as $d): ?>
          <tr>
            <td class="item-no text-center" data-label="No"><?= $i++ ?></td>
            <td class="item-name" data-label="Produk"><?= e($d['product_name']) ?></td>
            <td class="text-center" data-label="Qty"><?= $d['quantity'] ?></td>
            <td class="text-end item-price" data-label="Harga"><?= rupiah($d['price']) ?></td>
            <td class="text-end item-subtotal" data-label="Subtotal"><?= rupiah($d['subtotal']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- ============ SUMMARY ============ -->
  <div class="invoice-summary">
    <div class="invoice-summary-left">
      <?php if (!empty($order['note'])): ?>
        <div class="summary-block">
          <h6><i class="fas fa-sticky-note me-1"></i> Catatan Pesanan</h6>
          <p class="note-text">"<?= e($order['note']) ?>"</p>
        </div>
      <?php else: ?>
        <div class="summary-block">
          <h6><i class="fas fa-info-circle me-1"></i> Informasi</h6>
          <p>Invoice ini sah dan diterbitkan oleh sistem <?= e($businessName) ?>. Hubungi pelanggan jika diperlukan konfirmasi pembayaran.</p>
        </div>
      <?php endif; ?>
    </div>
    <div class="invoice-summary-right">
      <div class="summary-row">
        <span class="summary-label">Total Subtotal (<?= $totalQty ?> item)</span>
        <span class="summary-value"><?= rupiah($totalSubtotal) ?></span>
      </div>
      <div class="summary-row">
        <span class="summary-label">Biaya Layanan</span>
        <span class="summary-value">Rp 0</span>
      </div>
      <div class="summary-row">
        <span class="summary-label">Ongkos Kirim</span>
        <span class="summary-value">Rp 0</span>
      </div>
      <div class="summary-row grand">
        <span class="summary-label">TOTAL</span>
        <span class="summary-value"><?= rupiah($order['total_price']) ?></span>
      </div>
    </div>
  </div>

  <!-- ============ FOOTER ============ -->
  <div class="invoice-footer">
    <div class="thanks">
      Diterbitkan oleh Admin <?= e($businessName) ?>
      <small>Invoice sah dan tidak memerlukan tanda tangan basah.</small>
    </div>
    <div class="invoice-signature">
      Disetujui oleh,
      <div class="signature-line"></div>
      <div class="signature-name"><?= e($adminName) ?> &middot; <?= e($businessName) ?></div>
    </div>
  </div>
</div>

<?php if (!$canPrint): ?>
<script>
  // Cegah Ctrl+P / menu cetak browser saat pesanan belum dikonfirmasi
  (function () {
    function blockPrint(e) {
      e.preventDefault();
      e.stopImmediatePropagation();
      alert('Cetak invoice belum tersedia. Ubah status pesanan terlebih dahulu, lalu muat ulang halaman ini.');
      return false;
    }
    window.addEventListener('beforeprint', blockPrint);
    document.addEventListener('keydown', function (e) {
      if ((e.ctrlKey || e.metaKey) && (e.key === 'p' || e.key === 'P')) {
        e.preventDefault();
        alert('Cetak invoice belum tersedia. Ubah status pesanan terlebih dahulu, lalu muat ulang halaman ini.');
      }
    });
  })();
</script>
<?php endif; ?>

</body>
</html>
