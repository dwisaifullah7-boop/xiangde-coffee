<?php
/**
 * Konstanta global aplikasi.
 */

// Role user
define('ROLE_ADMIN',     'admin');
define('ROLE_CUSTOMER',  'customer');

// Status produk
define('PRODUCT_AVAILABLE',   'available');
define('PRODUCT_UNAVAILABLE', 'unavailable');

// Payment method
define('PAY_CASH',     'cash');
define('PAY_TRANSFER', 'transfer');
define('PAY_QRIS',     'qris');

// Order type
define('ORDER_DINE_IN',  'dine_in');
define('ORDER_TAKE_AWAY','take_away');
define('ORDER_DELIVERY', 'delivery');

// Order status
define('STATUS_PENDING',    'pending');
define('STATUS_PROCESSING', 'processing');
define('STATUS_READY',      'ready');
define('STATUS_COMPLETED',  'completed');
define('STATUS_CANCELLED',  'cancelled');

// Upload config (produk)
define('UPLOAD_DIR', BASE_PATH . '/assets/uploads/products/');
define('UPLOAD_URL',  BASE_URL . '/assets/uploads/products/');

// Upload config (avatar / foto profil customer)
define('AVATAR_UPLOAD_DIR', BASE_PATH . '/assets/uploads/avatars/');
define('AVATAR_UPLOAD_URL', BASE_URL . '/assets/uploads/avatars/');

// Maksimum upload berlaku untuk produk & avatar
define('MAX_UPLOAD_SIZE', 2 * 1024 * 1024); // 2 MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp']);
