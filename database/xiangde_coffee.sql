-- =====================================================
-- XIANG DE COFFEE - COMBINED SQL (schema + seed)
-- Import file ini SEKALI saja via phpMyAdmin
-- Database: xiangde_coffee
-- =====================================================

CREATE DATABASE IF NOT EXISTS `xiangde_coffee`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `xiangde_coffee`;

SET FOREIGN_KEY_CHECKS = 0;

-- USERS
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id`            INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`          VARCHAR(150) NOT NULL,
  `username`      VARCHAR(60)  NOT NULL,
  `email`         VARCHAR(150) NOT NULL,
  `phone`         VARCHAR(30)  DEFAULT NULL,
  `password`      VARCHAR(255) NOT NULL,
  `role`          ENUM('admin','customer') NOT NULL DEFAULT 'customer',
  `alamat`        VARCHAR(255) DEFAULT NULL,
  `profile_photo` VARCHAR(255) DEFAULT NULL,
  `created_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_username` (`username`),
  UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CATEGORIES
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id`          INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(120) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- PRODUCTS
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id`          INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` INT(11) UNSIGNED NOT NULL,
  `name`        VARCHAR(180) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `harga`       DECIMAL(12,2) NOT NULL DEFAULT 0,
  `image`       VARCHAR(255) DEFAULT NULL,
  `status`      ENUM('available','unavailable') NOT NULL DEFAULT 'available',
  `featured`    TINYINT(1) NOT NULL DEFAULT 0,
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_products_category` (`category_id`),
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`)
    REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CART
DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `id`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    INT(11) UNSIGNED NOT NULL,
  `product_id` INT(11) UNSIGNED NOT NULL,
  `quantity`   INT(11) NOT NULL DEFAULT 1,
  `note`       TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_cart_user` (`user_id`),
  KEY `idx_cart_product` (`product_id`),
  CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`)
    REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ORDERS
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id`             INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`        INT(11) UNSIGNED NOT NULL,
  `invoice_number` VARCHAR(40) NOT NULL,
  `total_price`    DECIMAL(14,2) NOT NULL DEFAULT 0,
  `payment_method` ENUM('cash','transfer','qris') NOT NULL DEFAULT 'cash',
  `order_type`     ENUM('dine_in','take_away','delivery') NOT NULL DEFAULT 'dine_in',
  `order_status`   ENUM('pending','processing','ready','completed','cancelled') NOT NULL DEFAULT 'pending',
  `note`           TEXT DEFAULT NULL,
  `created_at`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_invoice` (`invoice_number`),
  KEY `idx_orders_user` (`user_id`),
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ORDER DETAILS
DROP TABLE IF EXISTS `order_details`;
CREATE TABLE `order_details` (
  `id`           INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id`     INT(11) UNSIGNED NOT NULL,
  `product_id`   INT(11) UNSIGNED DEFAULT NULL,
  `product_name` VARCHAR(180) NOT NULL,
  `price`        DECIMAL(12,2) NOT NULL DEFAULT 0,
  `quantity`     INT(11) NOT NULL DEFAULT 1,
  `subtotal`     DECIMAL(14,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_od_order` (`order_id`),
  KEY `idx_od_product` (`product_id`),
  CONSTRAINT `fk_od_order` FOREIGN KEY (`order_id`)
    REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_od_product` FOREIGN KEY (`product_id`)
    REFERENCES `products` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SETTINGS
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id`            INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key`   VARCHAR(100) NOT NULL,
  `setting_value` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ===================== SEED =====================

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('business_name', 'Xiang De Coffee'),
('business_short_name', 'XD Coffee'),
('business_address', 'Jl. Saputra 3, Kedungjaya, Kec. Kedawung, Kabupaten Cirebon, Jawa Barat 45153'),
('business_phone', '0812-3456-7890'),
('business_email', 'hello@xiangdecoffee.com'),
('instagram_url', 'https://www.instagram.com/xiangdecoffee'),
('gofood_url', 'https://gofood.co.id/cirebon/restaurant/xiang-de-coffee-12e4e6a7-fc6f-4428-b092-a05629a863ff'),
('opening_hours', 'Senin - Minggu, 10:00 - 23:00 WIB'),
('google_maps_url', 'https://www.google.com/maps/search/?api=1&query=Xiang+De+Coffee+Cirebon'),
('price_range', 'Rp50.000 - Rp75.000 per orang'),
('logo', 'assets/images/logo.png'),
('about_text', 'Xiang De Coffee adalah coffee shop dan kuliner yang berlokasi di Kabupaten Cirebon, Jawa Barat. Dengan konsep dua lantai yang nyaman, kami menyajikan kopi, makanan, dan suasana hangat untuk berkumpul bersama teman maupun keluarga.'),
('hero_tagline', 'Secangkir kopi, sejuta cerita'),
('hero_subtitle', 'Nikmati kopi premium dan kuliner terbaik dengan suasana hangat khas Xiang De Coffee di Cirebon.'),
('footer_text', 'Xiang De Coffee - Coffee Shop & Culinary, Cirebon');

-- Admin login: username = admin | password = admin123
-- Hash di bawah dibuat dengan PHP password_hash('admin123', PASSWORD_BCRYPT)
-- Jika tidak bisa login, gunakan install.php untuk reset password.
INSERT INTO `users` (`name`, `username`, `email`, `phone`, `password`, `role`, `alamat`) VALUES
('Administrator', 'admin', 'admin@xiangdecoffee.com', '0812-3456-7890',
 '$2y$10$V1a1luN0jMVPo7PN7O4.ROvNApOOi/O/ChahPs6W1a.OYRXq.e7Ya',
 'admin', 'Cirebon, Jawa Barat');

INSERT INTO `categories` (`name`, `description`) VALUES
('Coffee',              'Berbagai pilihan kopi hangat dan dingin dari biji pilihan.'),
('Non-Coffee',          'Minuman non-kopi untuk semua kalangan.'),
('Rice & Main Course',  'Menu utama nasi dengan lauk spesial khas Xiang De.'),
('Mie & Pasta',         'Hidangan mie dan pasta andalan Xiang De.'),
('Snacks & Appetizer',  'Cemilan dan pembuka untuk menemani kopi Anda.'),
('Dessert',             'Hidangan penutup manis yang menggoda.');

-- Menu disusun berdasarkan menu nyata XD Coffee (Xiang De Coffee),
-- Jl. Saputra 3, Kedungjaya, Kedawung, Cirebon — item [best seller]
-- terverifikasi dari review publik pelanggan per Agustus 2026.
-- Harga adalah estimasi wajar mengikuti kisaran harga cafe di
-- Cirebon; sesuaikan dengan harga resmi Anda di admin panel.
-- Gambar (.svg) adalah ilustrasi bertema XD Coffee; ganti dengan
-- foto asli produk kapan saja lewat menu Admin > Produk.
INSERT INTO `products` (`category_id`, `name`, `description`, `harga`, `image`, `status`, `featured`) VALUES
(1, 'Espresso', 'Shot espresso pekat dengan crema lembut, aroma biji kopi yang kuat, dan sentuhan aftertaste cokelat.', 18000, 'https://images.unsplash.com/photo-1579992357154-faf4bde95b3d?auto=format&fit=crop&w=900&q=80', 'available', 1),
(1, 'Americano', 'Espresso pilihan yang dipadukan air panas untuk menghasilkan rasa kopi yang clean, ringan, dan tetap bold.', 20000, 'https://images.unsplash.com/photo-1705952285570-113e76f63fb0?auto=format&fit=crop&w=900&q=80', 'available', 0),
(1, 'Cappuccino', 'Perpaduan espresso, steamed milk, dan foam lembut dengan karakter creamy dan aroma kopi yang seimbang.', 23000, 'https://i1.pickpik.com/photos/528/555/750/cafe-caffe-latte-caffelatte-cappuccino-preview.jpg', 'available', 1),
(1, 'Cafe Latte', 'Espresso dan susu steamed dengan foam tipis yang lembut, creamy, dan nyaman dinikmati kapan saja.', 24000, 'https://i1.pickpik.com/photos/528/555/750/cafe-caffe-latte-caffelatte-cappuccino-preview.jpg', 'available', 1),
(1, 'Caramel Macchiato', 'Espresso, susu creamy, dan sentuhan karamel manis yang menghasilkan rasa lembut dengan aroma menggoda.', 28000, 'https://baristaandco.com/cdn/shop/files/iced-caramel-macchiato-coffee_2.png?v=1687961690&width=1080', 'available', 1),
(1, 'Cold Brew', 'Kopi seduh dingin dengan karakter smooth, rendah rasa pahit, dan sensasi menyegarkan.', 26000, 'https://images.unsplash.com/photo-1705952285570-113e76f63fb0?auto=format&fit=crop&w=900&q=80', 'available', 0),
(1, 'Kopi Alpukat Signature', 'Kopi susu creamy berpadu alpukat lembut dan espresso, menghasilkan rasa unik yang kaya dan memorable.', 30000, 'https://images.ctfassets.net/s64jgdakkdiy/3qMVsIFRzwvTPYnh6zVv0w/4018d4785994adec47e3fc14f7617814/webimage-A_NA_NA_Master_Recipe_Image_Avocadoicedcoffee-___Recipe_NA_Drinks_origins_Oat_NA_TetraUHT_1l_HR_-051.jpg', 'available', 1),
(1, 'Butterscotch Coffee', 'Kopi susu dengan perpaduan rasa butterscotch yang manis-gurih, creamy, dan cocok untuk teman santai.', 28000, 'https://baristaandco.com/cdn/shop/files/iced-caramel-macchiato-coffee_2.png?v=1687961690&width=1080', 'available', 0),
(2, 'Matcha Latte', 'Matcha dengan aroma khas dan rasa earthy yang dipadukan susu creamy untuk hasil yang smooth dan lembut.', 28000, 'https://images.unsplash.com/photo-1689358459793-48a913791616?auto=format&fit=crop&w=900&q=80', 'available', 1),
(2, 'Chocolate Cream', 'Minuman cokelat creamy dengan rasa kakao yang kaya dan topping lembut untuk sensasi dessert dalam gelas.', 25000, 'https://images.unsplash.com/photo-1689358459793-48a913791616?auto=format&fit=crop&w=900&q=80', 'available', 0),
(2, 'Iced Lemon Tea', 'Teh hitam dingin dengan lemon segar yang memberikan rasa manis-asam dan sensasi menyegarkan.', 18000, 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=900&q=80', 'available', 1),
(2, 'Strawberry Smoothie', 'Smoothie strawberry creamy dengan rasa buah segar dan tekstur lembut, cocok dinikmati dingin.', 28000, 'https://s.widget-club.com/images/YyiR86zpwIMIfrCZoSs4ulVD9RF3/c09e5bb6dbe1372a5bda452b4573bb26/be9eef36b15e7ad9b0255db242740adb.jpg', 'available', 0),
(3, 'Chicken Sambal Matah', 'Nasi hangat dan ayam crispy dengan sambal matah aromatik yang segar, pedas, dan menggugah selera.', 28000, 'https://radarmukomuko.bacakoran.co/upload/df264ae7e0f92d23807b09f6cd263776.jpg', 'available', 1),
(3, 'Soto Ayam Nusantara', 'Soto ayam berkuah gurih dengan suwiran ayam, sayuran, telur, dan taburan bawang goreng.', 22000, 'https://i.etsystatic.com/52831480/r/il/34964a/7856252377/il_794xN.7856252377_ky12.jpg', 'available', 1),
(3, 'Nasi Goreng Tom Yum', 'Nasi goreng dengan bumbu tom yum yang asam, pedas, dan gurih, disajikan dengan topping pelengkap.', 25000, 'https://pupswithchopsticks.com/wp-content/uploads/nasi-goreng-indonesian-fried-rice-tn.jpg', 'available', 1),
(3, 'Chicken Katsu Mushroom', 'Chicken katsu crispy dengan saus jamur creamy, disajikan dengan nasi untuk menu makan yang mengenyangkan.', 30000, 'https://takestwoeggs.com/wp-content/uploads/2022/11/Chicken-Katsu-Takestwoeggs-FINAL-Photography-sq.jpg', 'available', 1),
(4, 'Spaghetti Carbonara', 'Spaghetti dengan saus creamy gurih, taburan keju, dan sentuhan lada hitam yang harum.', 28000, 'https://images.unsplash.com/photo-1633337474564-1d9478ca4e2e?auto=format&fit=crop&w=900&q=80', 'available', 1),
(4, 'Mie Chili Oil', 'Mie dengan chili oil aromatik yang pedas-gurih, cocok untuk kamu yang suka rasa bold.', 23000, 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=900&q=80', 'available', 0),
(5, 'French Fries', 'Kentang goreng renyah berwarna keemasan dengan tekstur crispy di luar dan lembut di dalam.', 15000, 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?auto=format&fit=crop&w=900&q=80', 'available', 0),
(5, 'Roti Bakar Cokelat Keju', 'Roti panggang hangat dengan olesan cokelat dan topping keju melimpah, manis sekaligus gurih.', 18000, 'https://images.unsplash.com/photo-1484723091739-30a097e8f929?auto=format&fit=crop&w=900&q=80', 'available', 0),
(5, 'Pisang Goreng Keju', 'Pisang goreng renyah dengan topping keju dan sentuhan saus manis untuk camilan yang comforting.', 18000, 'https://images.unsplash.com/photo-1579697096985-41fe941673a0?auto=format&fit=crop&w=900&q=80', 'available', 0),
(5, 'Dimsum Platter', 'Pilihan dimsum dengan tekstur lembut dan gurih, disajikan bersama saus cocolan yang nikmat.', 22000, 'https://mir-s3-cdn-cf.behance.net/project_modules/max_1200/1b80e888062533.5dcafd32c0848.jpg', 'available', 0),
(6, 'Strawberry Cheesecake', 'Cheesecake creamy dengan rasa keju yang lembut dan sentuhan strawberry yang segar.', 22000, 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?auto=format&fit=crop&w=900&q=80', 'available', 1),
(6, 'Classic Tiramisu', 'Dessert klasik berlapis krim lembut dan aroma kopi, ditutup taburan kakao yang harum.', 24000, 'https://images.rawpixel.com/image_social_landscape/cHJpdmF0ZS9sci9pbWFnZXMvd2Vic2l0ZS8yMDI1LTA0L3NyLWltYWdlLTA5MDQyMDI1LW1rbTA0LXMtMzcyXzEuanBn.jpg', 'available', 0),
(6, 'Vanilla Ice Cream', 'Es krim vanilla yang lembut dan creamy dengan rasa manis ringan, cocok sebagai penutup.', 15000, 'https://images.unsplash.com/photo-1501443762994-82bd5dace89a?auto=format&fit=crop&w=900&q=80', 'available', 0);
