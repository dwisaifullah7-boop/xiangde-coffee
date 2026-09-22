<?php
/**
 * Helper functions umum untuk website Xiang De Coffee.
 */

require_once __DIR__ . '/../config/config.php';

/**
 * Redirect ke URL relatif terhadap BASE_URL.
 */
function redirect(string $path = ''): void
{
    if (preg_match('#^https?://#', $path)) {
        header('Location: ' . $path);
    } else {
        $path = ltrim($path, '/');
        header('Location: ' . BASE_URL . '/' . $path);
    }
    exit;
}

/**
 * Buat URL absolut dari path relatif.
 */
function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Escape HTML.
 */
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/**
 * Format harga ke format Rupiah.
 */
function rupiah($number): string
{
    return 'Rp ' . number_format((float)$number, 0, ',', '.');
}

/**
 * Ambil nilai setting dari tabel settings (dengan cache statis).
 */
function setting(string $key, string $default = ''): string
{
    static $cache = null;
    global $pdo;
    if ($cache === null) {
        $stmt = $pdo->query('SELECT setting_key, setting_value FROM settings');
        $cache = [];
        foreach ($stmt->fetchAll() as $row) {
            $cache[$row['setting_key']] = $row['setting_value'];
        }
    }
    return $cache[$key] ?? $default;
}

/**
 * Cek apakah user sudah login.
 */
function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * Cek apakah user adalah admin.
 */
function is_admin(): bool
{
    return is_logged_in() && ($_SESSION['user_role'] ?? '') === ROLE_ADMIN;
}

/**
 * Cek apakah user adalah customer.
 */
function is_customer(): bool
{
    return is_logged_in() && ($_SESSION['user_role'] ?? '') === ROLE_CUSTOMER;
}

/**
 * Ambil data user yang sedang login.
 *
 * Jika user_id di session tidak ditemukan di DB (mis. database di-reset
 * atau user dihapus), session otomatis dibersihkan dan user dianggap
 * sudah logout — agar tidak terjadi fatal error di halaman yang
 * mengandalkan data user (mis. render_avatar()).
 *
 * @param bool $refresh  Set true untuk memaksa re-fetch dari DB
 *                       (mis. setelah upload foto profil).
 */
function current_user(bool $refresh = false): ?array
{
    global $pdo;
    if (!is_logged_in()) return null;
    static $user = null;
    if ($user === null || $refresh) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch() ?: null;

        // Session valid tapi user sudah tidak ada di DB → invalidate session
        // supaya halaman customer/admin tidak crash.
        if ($user === null) {
            unset($_SESSION['user_id'], $_SESSION['user_role'], $_SESSION['user_name']);
            return null;
        }
    }
    return $user;
}

/**
 * Flash message (sekali tampil).
 */
function set_flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function get_flashes(): array
{
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

/**
 * CSRF token.
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function csrf_verify(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

/**
 * Generate nomor invoice unik.
 */
function generate_invoice_number(): string
{
    return 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

/**
 * Format tanggal Indonesia.
 */
function format_date($datetime, string $format = 'd M Y, H:i'): string
{
    $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    $dt = strtotime((string)$datetime);
    return date('d ', $dt) . $months[(int)date('m', $dt) - 1] . date(' Y, H:i', $dt);
}

/**
 * Sanitasi input.
 */
function clean_input(string $value): string
{
    return trim($value);
}

/**
 * Ambil label berwarna untuk status order.
 */
function status_badge(string $status): string
{
    $map = [
        'pending'    => ['bg-secondary', 'Pending'],
        'processing' => ['bg-primary',   'Diproses'],
        'ready'      => ['bg-info',      'Siap'],
        'completed'  => ['bg-success',   'Selesai'],
        'cancelled'  => ['bg-danger',    'Dibatalkan'],
    ];
    [$class, $label] = $map[$status] ?? ['bg-secondary', ucfirst($status)];
    return '<span class="badge ' . $class . '">' . $label . '</span>';
}

/**
 * Hitung jumlah item di cart user saat ini.
 */
function cart_count(): int
{
    global $pdo;
    if (!is_logged_in()) return 0;
    $stmt = $pdo->prepare('SELECT COALESCE(SUM(quantity),0) FROM cart WHERE user_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return (int)$stmt->fetchColumn();
}

/**
 * Ambil semua kategori.
 */
function get_categories(): array
{
    global $pdo;
    return $pdo->query('SELECT * FROM categories ORDER BY name ASC')->fetchAll();
}

/**
 * Ambil produk featured untuk landing page.
 */
function get_featured_products(int $limit = 8): array
{
    global $pdo;
    $stmt = $pdo->prepare(
        'SELECT p.*, c.name AS category_name
         FROM products p JOIN categories c ON c.id = p.category_id
         WHERE p.featured = 1 AND p.status = ?
         ORDER BY p.created_at DESC LIMIT ' . (int)$limit
    );
    $stmt->execute([PRODUCT_AVAILABLE]);
    return $stmt->fetchAll();
}

/**
 * Image URL produk (fallback placeholder).
 */
function product_image_url(?string $image): string
{
    if (!empty($image)) {
        // Support external menu images in addition to local product uploads.
        if (preg_match('#^https?://#i', $image)) {
            return $image;
        }
        if (file_exists(BASE_PATH . '/assets/uploads/products/' . $image)) {
            return UPLOAD_URL . $image;
        }
    }
    return BASE_URL . '/assets/images/placeholders/product.svg';
}

/**
 * URL foto profil customer. Jika belum ada, kembalikan null
 * (UI akan menampilkan inisial nama sebagai fallback).
 */
function avatar_url(?string $photo): ?string
{
    if (!empty($photo) && file_exists(AVATAR_UPLOAD_DIR . $photo)) {
        return AVATAR_UPLOAD_URL . $photo;
    }
    return null;
}

/**
 * Render blok avatar customer.
 * - Jika user punya foto profil, tampilkan <img>.
 * - Jika tidak, tampilkan inisial nama.
 * - Jika $user NULL (mis. session valid tapi user tidak ada di DB),
 *   tampilkan placeholder default — TIDAK melempar fatal error.
 *
 * @param array|null $user        Data user (harus ada 'name' & 'profile_photo')
 * @param string     $sizeClass   Class ukuran: '' (default), 'avatar-sm', 'avatar-lg'
 */
function render_avatar(?array $user, string $sizeClass = ''): string
{
    $name     = $user['name'] ?? '';
    $initial  = $name !== '' ? strtoupper(mb_substr($name, 0, 1, 'UTF-8')) : '?';
    $url      = $user ? avatar_url($user['profile_photo'] ?? null) : null;
    $cls      = trim('customer-avatar ' . $sizeClass);
    if ($url) {
        return '<div class="' . $cls . ' avatar-has-image">'
            . '<img src="' . e($url) . '" alt="Foto Profil ' . e($name) . '">'
            . '</div>';
    }
    return '<div class="' . $cls . '">' . e($initial) . '</div>';
}

/**
 * Validasi upload file gambar (produk).
 */
function validate_image_upload(string $field): ?array
{
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['error' => 'Gagal mengunggah file (kode ' . $file['error'] . ').'];
    }
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return ['error' => 'Ukuran file melebihi 2 MB.'];
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_EXTENSIONS, true)) {
        return ['error' => 'Ekstensi tidak diizinkan. Gunakan JPG, PNG, atau WEBP.'];
    }
    return [
        'tmp_name' => $file['tmp_name'],
        'ext'      => $ext,
        'name'     => 'product_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext,
    ];
}

/**
 * Validasi upload foto profil (avatar).
 * Mirip validate_image_upload() tapi prefix nama file berbeda.
 */
function validate_avatar_upload(string $field): ?array
{
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['error' => 'Gagal mengunggah foto (kode ' . $file['error'] . ').'];
    }
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return ['error' => 'Ukuran foto melebihi 2 MB.'];
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_EXTENSIONS, true)) {
        return ['error' => 'Ekstensi tidak diizinkan. Gunakan JPG, PNG, atau WEBP.'];
    }

    // Verifikasi MIME asli untuk mencegah file berbahaya yang hanya
    // mengganti ekstensi. Gunakan deteksi berlapis supaya tidak fatal
    // error meski PHP extension fileinfo tidak aktif (umum di XAMPP).
    $mime = detect_image_mime($file['tmp_name']);
    if ($mime === null) {
        return ['error' => 'Tidak dapat memverifikasi tipe file. Hubungi administrator untuk mengaktifkan ekstensi fileinfo pada PHP.'];
    }
    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($mime, $allowedMimes, true)) {
        return ['error' => 'Tipe file tidak valid. Pastikan file adalah gambar.'];
    }

    return [
        'tmp_name' => $file['tmp_name'],
        'ext'      => $ext,
        'name'     => 'avatar_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext,
    ];
}

/**
 * Deteksi MIME type file gambar dengan fallback berlapis.
 *
 * Urutan percobaan:
 *   1. finfo (PHP extension fileinfo) - paling akurat
 *   2. mime_content_type()            - fungsi built-in (kadang butuh extension)
 *   3. getimagesize()                 - baca signature file gambar
 *
 * Mengembalikan string MIME jika berhasil, null jika semua metode gagal.
 * Tidak pernah melempar fatal error — aman dipanggil di server tanpa fileinfo.
 */
function detect_image_mime(string $filePath): ?string
{
    // 1. finfo (preferred)
    if (function_exists('finfo_open')) {
        $finfo = @finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo !== false) {
            $mime = @finfo_file($finfo, $filePath);
            finfo_close($finfo);
            if (is_string($mime) && $mime !== '') {
                return $mime;
            }
        }
    }

    // 2. mime_content_type()
    if (function_exists('mime_content_type')) {
        $mime = @mime_content_type($filePath);
        if (is_string($mime) && $mime !== '' && $mime !== 'application/octet-stream') {
            return $mime;
        }
    }

    // 3. getimagesize() - baca signature file
    if (function_exists('getimagesize')) {
        $info = @getimagesize($filePath);
        if (is_array($info) && !empty($info['mime'])) {
            return $info['mime'];
        }
    }

    return null;
}

/**
 * Hapus file foto profil dari disk jika ada.
 */
function delete_avatar_file(?string $photo): void
{
    if (!empty($photo)) {
        $path = AVATAR_UPLOAD_DIR . $photo;
        if (is_file($path)) {
            @unlink($path);
        }
    }
}

/**
 * Helper untuk method HTTP.
 */
function is_post(): bool { return $_SERVER['REQUEST_METHOD'] === 'POST'; }
function is_get(): bool  { return $_SERVER['REQUEST_METHOD'] === 'GET'; }

/**
 * Old input value.
 */
function old(string $key, $default = '')
{
    return e($_SESSION['old_input'][$key] ?? $default);
}

function save_old_input(): void
{
    $_SESSION['old_input'] = $_POST;
}

function clear_old_input(): void
{
    unset($_SESSION['old_input']);
}
