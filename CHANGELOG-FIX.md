# Changelog — xiangde-coffee-v3 (perbaikan)

## v2 — Perbaikan error tambahan (Fatal error render_avatar + modal AJAX)

Setelah zip pertama di-deploy, user menemukan 2 error baru:

### Screenshot 1: Modal "Gagal — Tidak dapat terhubung ke server"
Muncul saat klik tombol "Add to Cart" di halaman produk. Penyebab:
cart.js gagal parse JSON response dari `customer/cart-add.php` (kemungkinan
endpoint mereturn HTML fatal error).

### Screenshot 2: `Fatal error: Uncaught TypeError: render_avatar(): Argument #1 ($user) must be of type array, null given`
Muncul di `customer/dashboard.php` line 43. Penyebab: session browser masih
valid (cookie `XD_COFFEE` masih ada), tapi user_id di session sudah tidak
ada di database (mis. user login di DB lama → import DB baru → session
tidak ter-reset). `current_user()` return NULL, lalu `render_avatar(NULL)`
meledak karena type hint `array`.

### Fix v2:

#### 1. `render_avatar()` null safety
**File:** `includes/functions.php`

Ubah signature dari `array $user` → `?array $user = null`. Bila user NULL,
tampilkan avatar placeholder (icon "?") — TIDAK melempar fatal error.
Defensive coding untuk menangani edge case apa pun yang menyebabkan
current_user() return NULL.

#### 2. `current_user()` invalidate session bila user tidak ada di DB
**File:** `includes/functions.php`

Setelah query DB, bila user tidak ditemukan:
- Bersihkan `$_SESSION['user_id']`, `user_role`, `user_name`
- Return NULL

Ini mencegah halaman customer/admin crash bila session "ghost" (valid di
session tapi user sudah tidak ada di DB — kasus umum saat database
di-reset atau di-import ulang).

#### 3. Guard `customer-auth.php` & `admin-auth.php` cek DB
**Files:** `includes/customer-auth.php`, `includes/admin-auth.php`

Sebelumnya hanya cek `is_customer()` / `is_admin()` (session-based, tanpa
query DB). Sekarang juga cek `current_user() !== null`. Bila user tidak
valid, redirect ke login dengan pesan "Sesi Anda tidak valid" — bukan
fatal error di halaman tujuan.

#### 4. `cart-add.php` cek user valid + return `require_login` flag
**File:** `customer/cart-add.php`

Sebelumnya hanya cek `is_customer()` (session-based). Sekarang juga cek
`current_user() !== null`. Bila user tidak valid, return JSON dengan
flag `require_login: true` supaya frontend bisa arahkan ke login.

#### 5. `cart.js` handle non-JSON response + auto-redirect ke login
**File:** `assets/js/cart.js`

- Cek content-type response. Bila bukan JSON (mis. PHP fatal error HTML),
  tampilkan pesan "Server mengembalikan respons tidak valid" + log ke
  console — bukan pesan generik "Tidak dapat terhubung ke server".
- Bila response JSON punya `require_login: true`, tampilkan modal
  "Login Diperlukan" dengan tombol yang arahkan ke halaman login.

#### 6. Expose `XD_BASE_URL` ke JavaScript
**Files:** `includes/header.php`, `includes/admin-header.php`

Tambah `<script>window.XD_BASE_URL = "..."</script>` supaya file JS
(cart.js, dll.) bisa akses BASE_URL untuk redirect & AJAX call.

#### 7. Navbar null safety
**File:** `includes/navbar.php`

`current_user()` bisa return NULL (setelah fix #2). Tambah null check
eksplisit di navbar supaya nama & avatar tidak crash.

#### 8. Placeholder produk SVG diperbaiki
**File:** `assets/images/placeholders/product.svg`

Sebelumnya: teks "No image available" yang terlihat seperti error.
Sekarang: logo "XD Coffee" + label "XIANG DE COFFEE" yang terlihat
sebagai placeholder branding (bukan error message).

#### 9. `onerror` fallback di semua `<img>` produk
**Files:** `includes/product-card.php`, `product-detail.php`,
`admin/products.php`, `admin/product-edit.php`

Tambah `onerror="this.onerror=null; this.src='placeholder.svg';"` di
semua tag `<img>` produk. Bila gambar gagal load (404, corrupt, dll.),
otomatis swap ke placeholder SVG — bukan broken image icon.

---

## v1 — Perbaikan awal (bug avatar + portabilitas + responsif)

### 1. Bug avatar — `Fatal error: Call to undefined function finfo_open()`
**File:** `includes/functions.php`

**Akar masalah:**
`validate_avatar_upload()` memanggil `finfo_open()` yang butuh PHP extension
`fileinfo` — tidak aktif default di banyak XAMPP.

**Fix:**
Tambah helper `detect_image_mime()` dengan fallback berlapis:
1. `finfo_open()` (preferred)
2. `mime_content_type()` (built-in)
3. `getimagesize()` (baca signature file)

### 2. Portabilitas folder — `BASE_URL` auto-detect
**File:** `config/config.php`

`BASE_URL` di-hardcode `/xiangde-coffee`. Sekarang dihitung otomatis dari
lokasi folder project. Bisa di-override via env `XD_BASE_URL`.

### 3. Responsif — customer sidebar reorder di mobile
**Files:** 8 file di `customer/`

Sidebar muncul duluan di mobile (Bootstrap `order-*` utility).

### 4. Responsif — customer sidebar kompak di mobile
**File:** `assets/css/customer.css`

Navigation jadi baris horizontal flex-wrap dengan tap target besar.

### 5. Responsif — admin panel tap target & tabel
**File:** `assets/css/admin.css`

Tombol aksi 40×40px, tabel horizontal scroll di layar sempit.

### 6. Bug — `nav-avatar-img` tidak ada CSS di halaman publik
**File:** `assets/css/style.css`

Pindah definisi dari `customer.css` ke `style.css` (global).

### 7. Responsif — `100vh` di mobile browsers
**Files:** `assets/css/style.css`, `assets/css/responsive.css`

Tambah fallback `100dvh`/`100svh` di hero, auth-page, page-bg.

---

## Cara test

1. Extract zip ke folder web server (XAMPP `htdocs`, Laragon `www`, dll).
   Nama folder bebas — `BASE_URL` auto-detect.
2. Import database dari `database/xiangde_coffee.sql` via PhpMyAdmin.
3. Login sebagai admin: username `admin`, password `admin123`.
   (Bila hash tidak cocok, jalankan `install.php` di browser untuk reset.)
4. Test skenario "session ghost":
   - Login sebagai customer → tutup browser
   - Hapus user tersebut dari DB via admin atau PhpMyAdmin
   - Buka browser lagi → akses `customer/dashboard.php`
   - Seharusnya redirect ke login dengan pesan "Sesi tidak valid",
     BUKAN fatal error.
5. Test add to cart: pastikan modal sukses muncul, bukan "Gagal".
6. Test di HP / DevTools mobile view untuk verifikasi responsif.

## File yang berubah (v2)

```
includes/functions.php              # render_avatar null-safe + current_user invalidate session
includes/customer-auth.php          # guard current_user() !== null
includes/admin-auth.php             # guard current_user() !== null
includes/navbar.php                 # null safety untuk current_user()
includes/header.php                 # expose XD_BASE_URL ke JS
includes/admin-header.php           # expose XD_BASE_URL ke JS
includes/product-card.php           # onerror fallback untuk <img>
customer/cart-add.php               # cek current_user() + flag require_login
product-detail.php                  # onerror fallback untuk <img>
admin/products.php                  # onerror fallback untuk <img>
admin/product-edit.php              # onerror fallback untuk <img>
assets/js/cart.js                   # handle non-JSON + auto-redirect login
assets/images/placeholders/product.svg  # placeholder lebih menarik
```

## File yang berubah (v1)

```
config/config.php                  # BASE_URL auto-detect
includes/functions.php             # detect_image_mime() helper
assets/css/style.css               # nav-avatar-img global + dvh fallback
assets/css/responsive.css          # dvh fallback di 3 breakpoint hero
assets/css/customer.css            # sidebar kompak mobile
assets/css/admin.css               # tap target admin + tabel scroll
customer/*.php (8 file)            # order utility class
```
