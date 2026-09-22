-- =====================================================
-- MIGRATION: Tambah kolom profile_photo ke tabel users
-- Jalankan JIKA Anda sudah import database xiangde_coffee.sql
-- sebelumnya dan hanya ingin menambahkan kolom profile_photo.
-- =====================================================

USE `xiangde_coffee`;

ALTER TABLE `users`
  ADD COLUMN `profile_photo` VARCHAR(255) DEFAULT NULL
  AFTER `alamat`;
