# ☕ Xiang De Coffee - Website UMKM

Website Company Profile + Digital Menu + Online Ordering System untuk **Xiang De Coffee**, UMKM coffee shop & kuliner di Kabupaten Cirebon, Jawa Barat.

Dibangun dengan **PHP Native 8 + MySQL + Bootstrap 5** sesuai PRAD & Blueprint Teknis.

> **Versi 3** — menambahkan fitur **upload / ganti / hapus foto profil customer** dan **desain invoice modern profesional**.

---

## 📦 Teknologi

| Bagian        | Teknologi                                    |
|---------------|----------------------------------------------|
| Frontend      | HTML5, CSS3, JavaScript Vanilla, Bootstrap 5 |
| Ikon & Font   | Font Awesome 6, Google Fonts (Poppins, Playfair Display) |
| Notifikasi    | SweetAlert2                                  |
| Chart Admin   | Chart.js                                     |
| Backend       | PHP Native 8.x (PDO)                         |
| Database      | MySQL (via phpMyAdmin)                       |
| Server        | Apache (XAMPP)                               |
| Invoice       | window.print() (bisa upgrade ke Dompdf)     |

---

## 📁 Struktur Project

```text
xiangde-coffee/
│
├── admin/                     # Halaman admin panel
│   ├── dashboard.php          # Dashboard + statistik + chart
│   ├── products.php           # List produk
│   ├── product-add.php        # Tambah produk
│   ├── product-edit.php       # Edit produk
│   ├── product-delete.php     # Hapus produk
│   ├── categories.php         # List kategori
│   ├── category-add.php       # Tambah kategori
│   ├── category-edit.php      # Edit kategori
│   ├── category-delete.php    # Hapus kategori
│   ├── customers.php          # List pelanggan
│   ├── customer-detail.php    # Detail pelanggan
│   ├── customer-reset-password.php
│   ├── orders.php             # List transaksi
│   ├── order-detail.php       # Detail transaksi
│   ├── order-update-status.php
│   ├── order-delete.php
│   ├── invoice.php            # Cetak invoice
│   └── settings.php           # Pengaturan website
│
├── customer/                  # Halaman customer
│   ├── dashboard.php
│   ├── cart.php
│   ├── cart-add.php
│   ├── cart-update.php
│   ├── cart-delete.php
│   ├── checkout.php
│   ├── checkout-process.php
│   ├── order-success.php
│   ├── orders.php
│   ├── order-detail.php
│   ├── invoice.php
│   ├── profile.php
│   ├── profile-edit.php
│   └── change-password.php
│
├── config/                    # Konfigurasi sistem
│   ├── config.php             # Config utama
│   ├── database.php           # Koneksi PDO
│   └── constants.php          # Konstanta global
│
├── includes/                  # Komponen reusable
│   ├── auth.php               # Helper autentikasi
│   ├── admin-auth.php         # Guard admin
│   ├── customer-auth.php      # Guard customer
│   ├── functions.php          # Helper functions
│   ├── validation.php
│   ├── header.php             # Header publik
│   ├── navbar.php             # Navbar publik
│   ├── footer.php             # Footer publik
│   ├── product-card.php       # Komponen kartu produk
│   ├── category-card.php
│   ├── admin-header.php
│   ├── admin-sidebar.php
│   ├── admin-navbar.php
│   └── admin-footer.php
│
├── assets/                    # Asset statis
│   ├── css/
│   │   ├── style.css          # CSS utama (tema coffee)
│   │   ├── responsive.css     # Responsif (320 - 1440px+)
│   │   ├── auth.css           # Halaman login/register
│   │   ├── customer.css       # Halaman customer
│   │   ├── admin.css          # Admin panel
│   │   └── invoice.css        # [BARU] CSS khusus invoice modern
│   ├── js/
│   │   ├── main.js
│   │   ├── auth.js
│   │   ├── cart.js
│   │   ├── checkout.js
│   │   ├── admin.js
│   │   └── notifications.js
│   ├── images/
│   │   ├── (logo, hero, gallery)
│   │   └── placeholders/
│   │       └── product.svg    # Placeholder image produk
│   └── uploads/
│       ├── products/          # Upload gambar produk
│       └── avatars/           # [BARU] Upload foto profil customer
│
├── database/                  # Skema & data SQL
│   ├── schema.sql             # Skema tabel saja
│   ├── seed.sql               # Data seed saja
│   ├── xiangde_coffee.sql     # Gabungan skema + seed (IMPORT INI)
│   └── migration_add_profile_photo.sql  # [BARU] Migration untuk database lama
│
├── docs/                      # Dokumentasi (PRAD, ERD, dll)
├── vendor/                    # Composer (kosong, untuk dompdf opsional)
│
├── index.php                  # Landing page
├── about.php
├── menu.php
├── product-detail.php
├── gallery.php
├── contact.php
├── login.php
├── register.php
├── logout.php
│
├── 404.php, 403.php, 500.php  # Error pages
├── install.php                # Installer (reset admin password)
├── .htaccess
├── composer.json
├── composer.lock
└── README.md
```

---

## 🚀 Cara Instalasi

### 1. Salin project ke folder XAMPP
```bash
# Pindahkan folder xiangde-coffee ke:
C:\xampp\htdocs\xiangde-coffee       # Windows
/opt/lampp/htdocs/xiangde-coffee     # Linux
/Applications/XAMPP/htdocs/xiangde-coffee   # macOS
```

### 2. Import database
1. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`)
2. Klik tab **Import**
3. Pilih file `database/xiangde_coffee.sql`
4. Klik **Go** / **Kirim**

Atau buat manual:
- Buat database `xiangde_coffee`
- Import `database/schema.sql` lalu `database/seed.sql`

> ⚠️ **Untuk pengguna versi lama (v2):** Jika database `xiangde_coffee` sudah ada dan Anda tidak ingin import ulang, jalankan migration: `database/migration_add_profile_photo.sql` untuk menambahkan kolom `profile_photo` ke tabel `users`.

### 3. Sesuaikan konfigurasi database
Edit `config/database.php` jika user/password MySQL Anda berbeda dari default XAMPP:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'xiangde_coffee');
define('DB_USER', 'root');   // ganti jika perlu
define('DB_PASS', '');       // ganti jika perlu
```

### 4. Sesuaikan BASE_URL
Edit `config/config.php` jika project tidak berada di `http://localhost/xiangde-coffee`:
```php
define('BASE_URL', '/xiangde-coffee');
// Contoh lain: '/xd-coffee' jika folder berbeda
```

### 5. Reset password admin (PENTING!)
Buka browser → `http://localhost/xiangde-coffee/install.php`
- Klik tombol **Reset Password Admin**
- Default: `admin123`

### 6. Hapus installer
Setelah selesai, **hapus file `install.php`** dari server.

### 7. Buka website
- Halaman publik: `http://localhost/xiangde-coffee/`
- Login: `http://localhost/xiangde-coffee/login.php`
- Admin panel: `http://localhost/xiangde-coffee/admin/dashboard.php`

---

## 🔐 Login Default

| Role     | Username | Password   |
|----------|----------|------------|
| Admin    | `admin`  | `admin123` |
| Customer | (buat sendiri via halaman Register) | - |

> ⚠️ **Ganti password admin** segera setelah login pertama melalui menu profil.

---

## 🎨 Desain & Tema

**Gaya visual:** Modern, Premium, Minimalist, Cozy, Warm, Elegant, Coffee Shop Aesthetic.

**Palet warna:**
| Nama            | Hex       | Penggunaan            |
|-----------------|-----------|-----------------------|
| Espresso        | `#2b1810` | Heading, footer       |
| Coffee Brown    | `#4a2c1a` | Tombol primary, body  |
| Caramel         | `#b08560` | Accent, link hover    |
| Cream / Beige   | `#f5ebe0` / `#e8d5b7` | Background section |
| Warm White      | `#fdfaf6` | Body background       |
| Dark Charcoal   | `#1c1c1c` | Teks gelap            |

**Typography:**
- Heading: **Playfair Display** (serif, elegan)
- Body: **Poppins** (sans-serif, modern)

**Responsif:** Mendukung 320px, 375px, 425px, 768px, 1024px, 1440px+.

---

## ✨ Fitur

### Public Website
- Landing page (hero, about, kategori, menu unggulan, promo, gallery, testimoni, lokasi, CTA)
- Halaman About, Menu (dengan search, filter kategori, sort, pagination), Product Detail
- Gallery, Contact (dengan peta & form pesan)
- Login & Register

### Customer
- Dashboard dengan statistik & riwayat pesanan
- Keranjang belanja (tambah, update qty, hapus, catatan)
- Checkout (pilihan dine in / take away / delivery, metode cash / transfer / qris)
- Riwayat pesanan & detail pesanan
- Invoice (cetak via `window.print()`) — **desain modern profesional v3**
- Profil & ganti password
- **[BARU]** Upload / ganti / hapus foto profil (avatar) — tampil di sidebar, navbar, dan halaman profil

### Admin
- Dashboard dengan grafik penjualan & kategori (Chart.js)
- CRUD Produk (dengan upload gambar)
- CRUD Kategori
- Manajemen pelanggan (lihat detail, reset password)
- Manajemen transaksi (lihat detail, update status, hapus, cetak invoice)
- Pengaturan website (nama bisnis, alamat, jam buka, link Instagram, GoFood, dll)

---

## 🗄️ Skema Database

Tabel utama (lihat `database/schema.sql` untuk detail):

| Tabel           | Deskripsi                                       |
|-----------------|--------------------------------------------------|
| `users`         | Admin & customer (role enum)                     |
| `categories`    | Kategori produk                                  |
| `products`      | Produk dengan harga, gambar, status, featured    |
| `cart`          | Keranjang belanja customer                       |
| `orders`        | Header transaksi (invoice, total, status, dll)   |
| `order_details` | Item pesanan (snapshot harga & nama produk)      |
| `settings`      | Pengaturan website (key-value)                   |

**Enum values:**
- `users.role`: admin, customer
- `products.status`: available, unavailable
- `orders.payment_method`: cash, transfer, qris
- `orders.order_type`: dine_in, take_away, delivery
- `orders.order_status`: pending, processing, ready, completed, cancelled

---

## 🔒 Keamanan

- ✅ Password di-hash dengan `password_hash()` (bcrypt)
- ✅ Prepared statements (PDO) untuk mencegah SQL injection
- ✅ CSRF token pada semua form POST
- ✅ Session-based authentication
- ✅ Role-based authorization (admin / customer)
- ✅ Output escaping dengan `htmlspecialchars()`
- ✅ Validasi upload gambar (ekstensi, ukuran)

---

## 🛠️ Kustomisasi

### Mengganti warna tema
Edit `assets/css/style.css`, ubah variabel di `:root`:
```css
:root {
  --espresso:      #2b1810;
  --coffee-brown:  #4a2c1a;
  --caramel:       #b08560;
  /* dst. */
}
```

### Menambah kategori / produk
Login sebagai admin → menu **Kategori** atau **Produk** → **Tambah**.

### Mengganti info bisnis
Login sebagai admin → menu **Pengaturan** → edit form → simpan.

### Upload logo
Letakkan file di `assets/images/logo.png`, lalu update di menu **Pengaturan**.

---

## 📞 Kontak Bisnis

- **Nama:** Xiang De Coffee (XD Coffee)
- **Alamat:** Jl. Saputra 3, Kedungjaya, Kec. Kedawung, Kabupaten Cirebon, Jawa Barat 45153
- **Instagram:** [@xiangdecoffee](https://www.instagram.com/xiangdecoffee)
- **GoFood:** [Xiang De Coffee](https://gofood.co.id/cirebon/restaurant/xiang-de-coffee-12e4e6a7-fc6f-4428-b092-a05629a863ff)
- **Kisaran harga:** Rp50.000 - Rp75.000 per orang

---

## 📝 Catatan Pengembangan

- Invoice menggunakan `window.print()` (cetak ke PDF via dialog browser). Untuk generate PDF server-side, install Dompdf: `composer require dompdf/dompdf` lalu integrasikan di `customer/invoice.php` dan `admin/invoice.php`.
- Gambar produk yang diunggah disimpan di `assets/uploads/products/`. Pastikan folder writable (`chmod 775`).
- Foto profil customer disimpan di `assets/uploads/avatars/`. Pastikan folder writable (`chmod 775`).
- `vendor/` masih kosong karena tidak ada dependency wajib. Composer hanya digunakan jika Anda ingin menambah Dompdf.

---

## 🆕 Fitur Baru di v3

### 1. Upload / Ganti / Hapus Foto Profil Customer

Customer kini dapat memasang foto profil pribadi yang akan tampil di:
- Sidebar dashboard customer (kecil)
- Navbar publik (avatar mini)
- Halaman Profil Saya (besar, dengan badge check)
- Halaman Edit Profil (preview + tombol upload/hapus)
- Halaman detail pelanggan di admin panel

**Cara pakai:**
1. Login sebagai customer
2. Buka menu **Edit Profil** di sidebar
3. Pada kartu **Foto Profil**, klik tombol **Unggah Foto** (atau **Ganti Foto**)
4. Pilih gambar (JPG/PNG/WEBP, maks 2 MB), lalu klik **Simpan Foto**
5. Untuk menghapus foto, klik **Hapus** dan konfirmasi

**Keamanan:**
- Validasi ekstensi file (hanya JPG, JPEG, PNG, WEBP)
- Validasi MIME type asli (mencegah file berbahaya yang hanya ganti ekstensi)
- Validasi ukuran (maks 2 MB)
- File disimpan dengan nama acak (`avatar_YYYYMMDD_HHMMSS_xxxxxx.ext`)
- File lama otomatis dihapus saat diganti
- Folder upload dilindungi `.htaccess` (blokir eksekusi PHP)

**Database:**
- Kolom baru `profile_photo VARCHAR(255) DEFAULT NULL` ditambahkan ke tabel `users`
- Jika Anda sudah punya database lama, jalankan: `database/migration_add_profile_photo.sql`

### 2. Desain Invoice Modern Profesional

Invoice customer (`customer/invoice.php`) dan invoice admin (`admin/invoice.php`) didesain ulang dengan:

- **Header band** gradient coffee theme
- **Logo brand** + info bisnis lengkap (nama, alamat, telp, Instagram)
- **Title block** "INVOICE" dengan serif heading
- **Status pill** berwarna untuk status pesanan (pending/processing/ready/completed/cancelled)
- **Meta strip** 4 kolom: Tanggal, Jenis Pesanan, Metode Pembayaran, Status
- **Bill-to section** dengan ikon (email, telepon, lokasi)
- **Tabel items** dengan header gelap, zebra striping, tabular numerals
- **Summary card gelap** dengan total dalam warna accent gold
- **Catatan pesanan** ditampilkan dalam dashed box
- **Footer** dengan ucapan terima kasih + signature line
- **Responsive**: tabel berubah jadi card stack di mobile (≤480px)
- **Print-friendly**: CSS print dengan `@page margin`, warna tetap konsisten

CSS invoice terpisah di `assets/css/invoice.css` agar mudah dikustomisasi.

---

## 📄 Lisensi

MIT License — bebas digunakan untuk keperluan UMKM Xiang De Coffee dan pengembangan lebih lanjut.

---

**Dibuat berdasarkan PRAD & Blueprint Teknis Xiang De Coffee.**
