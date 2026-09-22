# Struktur Database - Xiang De Coffee

Database: `xiangde_coffee`

## Daftar Tabel

### 1. `users`
Menyimpan data admin & customer.

| Field       | Tipe                  | Keterangan                         |
|-------------|-----------------------|------------------------------------|
| id          | INT PK AUTO_INCREMENT | Primary key                        |
| name        | VARCHAR(150)          | Nama lengkap                       |
| username    | VARCHAR(60) UNIQUE    | Username login                     |
| email       | VARCHAR(150) UNIQUE   | Email                              |
| phone       | VARCHAR(30)           | Nomor telepon                      |
| password    | VARCHAR(255)          | Hash bcrypt                        |
| role        | ENUM('admin','customer') | Hak akses                       |
| alamat      | VARCHAR(255)          | Alamat                             |
| created_at  | TIMESTAMP             | Tanggal dibuat                     |
| updated_at  | TIMESTAMP             | Tanggal diperbarui                 |

### 2. `categories`
Kategori produk.

| Field       | Tipe           | Keterangan |
|-------------|----------------|------------|
| id          | INT PK         |            |
| name        | VARCHAR(120)   |            |
| description | TEXT           |            |
| created_at  | TIMESTAMP      |            |
| updated_at  | TIMESTAMP      |            |

### 3. `products`
Menu produk.

| Field       | Tipe                          | Keterangan |
|-------------|-------------------------------|------------|
| id          | INT PK                        |            |
| category_id | INT FK → categories.id        |            |
| name        | VARCHAR(180)                  |            |
| description | TEXT                          |            |
| harga       | DECIMAL(12,2)                 |            |
| image       | VARCHAR(255)                  | Nama file di assets/uploads/products |
| status      | ENUM('available','unavailable') |          |
| featured    | TINYINT(1)                    | 1 = unggulan |
| created_at  | TIMESTAMP                     |            |
| updated_at  | TIMESTAMP                     |            |

### 4. `cart`
Keranjang belanja customer.

| Field       | Tipe                   |
|-------------|------------------------|
| id          | INT PK                 |
| user_id     | INT FK → users.id      |
| product_id  | INT FK → products.id   |
| quantity    | INT                    |
| note        | TEXT                   |
| created_at  | TIMESTAMP              |

### 5. `orders`
Header transaksi.

| Field          | Tipe                                                                |
|----------------|---------------------------------------------------------------------|
| id             | INT PK                                                              |
| user_id        | INT FK → users.id                                                   |
| invoice_number | VARCHAR(40) UNIQUE                                                  |
| total_price    | DECIMAL(14,2)                                                       |
| payment_method | ENUM('cash','transfer','qris')                                      |
| order_type     | ENUM('dine_in','take_away','delivery')                              |
| order_status   | ENUM('pending','processing','ready','completed','cancelled')        |
| note           | TEXT                                                                |
| created_at     | TIMESTAMP                                                           |
| updated_at     | TIMESTAMP                                                           |

### 6. `order_details`
Item pesanan (snapshot harga & nama produk agar invoice lama tetap akurat).

| Field        | Tipe                  |
|--------------|-----------------------|
| id           | INT PK                |
| order_id     | INT FK → orders.id    |
| product_id   | INT FK → products.id  |
| product_name | VARCHAR(180)          |
| price        | DECIMAL(12,2)         |
| quantity     | INT                   |
| subtotal     | DECIMAL(14,2)         |

### 7. `settings`
Konfigurasi website (key-value).

| Field         | Tipe          |
|---------------|---------------|
| id            | INT PK        |
| setting_key   | VARCHAR(100)  |
| setting_value | TEXT          |

**Keys yang digunakan:**
- business_name, business_short_name, business_address
- business_phone, business_email, opening_hours
- instagram_url, gofood_url, google_maps_url
- price_range, hero_tagline, hero_subtitle
- about_text, footer_text, logo

## Relasi (ERD)

```
users ──< orders ──< order_details >── products >── categories
users ──< cart ──> products
```

- 1 user → banyak orders
- 1 order → banyak order_details
- 1 product → 1 category
- 1 user → banyak cart items
