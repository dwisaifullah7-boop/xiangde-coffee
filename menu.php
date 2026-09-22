<?php
/**
 * Halaman Menu - menampilkan semua produk dengan filter & search.
 */
require_once __DIR__ . '/includes/functions.php';
$currentPage = 'menu';
$pageTitle   = 'Menu';

// Filters
$search     = trim($_GET['search'] ?? '');
$categoryId = (int)($_GET['category'] ?? 0);
$sort       = $_GET['sort'] ?? 'newest';
$page       = max(1, (int)($_GET['page'] ?? 1));
$perPage    = 9;

$where  = ['p.status = ?'];
$params = [PRODUCT_AVAILABLE];
if ($search !== '') {
    $where[] = '(p.name LIKE ? OR p.description LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($categoryId > 0) {
    $where[] = 'p.category_id = ?';
    $params[] = $categoryId;
}
$whereSql = implode(' AND ', $where);

// Sort (whitelist to prevent injection)
$sortMap = [
    'price_asc'  => 'p.harga ASC',
    'price_desc' => 'p.harga DESC',
    'name_asc'   => 'p.name ASC',
    'newest'     => 'p.created_at DESC',
];
$orderSql = $sortMap[$sort] ?? $sortMap['newest'];

// Count total
$stmt = $pdo->prepare("SELECT COUNT(*) FROM products p WHERE $whereSql");
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();
$totalPages = max(1, ceil($total / $perPage));
$offset = ($page - 1) * $perPage;

// Fetch products
$stmt = $pdo->prepare(
    "SELECT p.*, c.name AS category_name
     FROM products p JOIN categories c ON c.id = p.category_id
     WHERE $whereSql
     ORDER BY $orderSql
     LIMIT $perPage OFFSET $offset"
);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = get_categories();

require __DIR__ . '/includes/header.php';
?>

<section class="breadcrumb-section">
  <div class="container">
    <h1>Menu Kami</h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li>
      <li class="breadcrumb-item active">Menu</li>
    </ol></nav>
  </div>
</section>

<section class="pt-5">
  <div class="container">
    <!-- Filter Bar -->
    <form id="filterForm" method="get" class="filter-bar mb-4">
      <div class="row g-2 align-items-center">
        <div class="col-lg-5 col-md-12">
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" name="search" value="<?= e($search) ?>" class="form-control" placeholder="Cari menu...">
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <select name="category" class="form-select">
            <option value="0">Semua Kategori</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= $categoryId === (int)$cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-lg-3 col-md-6">
          <select name="sort" class="form-select">
            <option value="newest"     <?= $sort==='newest'?'selected':'' ?>>Terbaru</option>
            <option value="price_asc"  <?= $sort==='price_asc'?'selected':'' ?>>Harga Termurah</option>
            <option value="price_desc" <?= $sort==='price_desc'?'selected':'' ?>>Harga Termahal</option>
            <option value="name_asc"   <?= $sort==='name_asc'?'selected':'' ?>>Nama A-Z</option>
          </select>
        </div>
        <input type="hidden" name="page" value="1">
      </div>
      <?php if ($search || $categoryId): ?>
        <div class="mt-3">
          <a href="<?= url('menu.php') ?>" class="btn btn-outline-coffee btn-sm">
            <i class="fas fa-times me-1"></i> Reset Filter
          </a>
        </div>
      <?php endif; ?>
    </form>

    <!-- Result count -->
    <p class="text-muted mb-4">Menampilkan <strong><?= count($products) ?></strong> dari <strong><?= $total ?></strong> menu.</p>

    <?php if (empty($products)): ?>
      <div class="empty-state">
        <i class="fas fa-mug-saucer"></i>
        <h4>Menu tidak ditemukan</h4>
        <p>Coba kata kunci atau filter kategori lain.</p>
      </div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($products as $p): ?>
          <div class="col-lg-4 col-md-6">
            <?php $product = $p; require __DIR__ . '/includes/product-card.php'; ?>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
        <nav class="mt-5 d-flex justify-content-center">
          <ul class="pagination">
            <li class="page-item <?= $page<=1?'disabled':'' ?>">
              <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page'=>$page-1])) ?>" aria-label="Previous">&laquo;</a>
            </li>
            <?php for ($i=1; $i<=$totalPages; $i++): ?>
              <li class="page-item <?= $i===$page?'active':'' ?>">
                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page'=>$i])) ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
            <li class="page-item <?= $page>=$totalPages?'disabled':'' ?>">
              <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page'=>$page+1])) ?>" aria-label="Next">&raquo;</a>
            </li>
          </ul>
        </nav>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
