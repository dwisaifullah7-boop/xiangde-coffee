-- =====================================================
-- XIANG DE COFFEE - DATABASE SCHEMA
-- Database: xiangde_coffee
-- Engine: InnoDB / UTF8mb4
-- =====================================================

CREATE DATABASE IF NOT EXISTS `xiangde_coffee`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `xiangde_coffee`;

SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Table: users
-- -----------------------------------------------------
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

-- -----------------------------------------------------
-- Table: categories
-- -----------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id`          INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(120) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table: products
-- -----------------------------------------------------
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

-- -----------------------------------------------------
-- Table: cart
-- -----------------------------------------------------
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

-- -----------------------------------------------------
-- Table: orders
-- -----------------------------------------------------
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

-- -----------------------------------------------------
-- Table: order_details
-- -----------------------------------------------------
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

-- -----------------------------------------------------
-- Table: settings
-- -----------------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id`            INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key`   VARCHAR(100) NOT NULL,
  `setting_value` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
