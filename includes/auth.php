<?php
/**
 * Helper autentikasi: register, login, logout.
 */

require_once __DIR__ . '/functions.php';

/**
 * Proses registrasi customer baru.
 */
function register_customer(array $data): array
{
    global $pdo;
    $name     = clean_input($data['name'] ?? '');
    $username = clean_input($data['username'] ?? '');
    $email    = clean_input($data['email'] ?? '');
    $phone    = clean_input($data['phone'] ?? '');
    $password = $data['password'] ?? '';
    $confirm  = $data['confirm_password'] ?? '';
    $alamat   = clean_input($data['alamat'] ?? '');

    $errors = [];
    if ($name === '')                       $errors[] = 'Nama wajib diisi.';
    if ($username === '' || strlen($username) < 4)
                                            $errors[] = 'Username minimal 4 karakter.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                                            $errors[] = 'Email tidak valid.';
    if (strlen($password) < 6)              $errors[] = 'Password minimal 6 karakter.';
    if ($password !== $confirm)             $errors[] = 'Konfirmasi password tidak cocok.';

    // Cek duplikat username / email
    if ($username && $email) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = 'Username atau email sudah terdaftar.';
        }
    }

    if ($errors) return ['success' => false, 'errors' => $errors];

    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare(
        'INSERT INTO users (name, username, email, phone, password, role, alamat)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$name, $username, $email, $phone, $hash, ROLE_CUSTOMER, $alamat]);

    return ['success' => true, 'user_id' => (int)$pdo->lastInsertId()];
}

/**
 * Proses login user (admin atau customer).
 */
function login_user(string $identifier, string $password): array
{
    global $pdo;
    $identifier = clean_input($identifier);

    $stmt = $pdo->prepare(
        'SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1'
    );
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        return ['success' => false, 'message' => 'Username/email atau password salah.'];
    }

    session_regenerate_id(true);
    $_SESSION['user_id']   = (int)$user['id'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_name'] = $user['name'];

    return ['success' => true, 'user' => $user];
}

/**
 * Logout user.
 */
function logout_user(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

/**
 * Update profil user.
 */
function update_profile(int $userId, array $data): array
{
    global $pdo;
    $name   = clean_input($data['name'] ?? '');
    $phone  = clean_input($data['phone'] ?? '');
    $alamat = clean_input($data['alamat'] ?? '');
    $email  = clean_input($data['email'] ?? '');

    $errors = [];
    if ($name === '') $errors[] = 'Nama wajib diisi.';
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
    if ($email) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id <> ?');
        $stmt->execute([$email, $userId]);
        if ($stmt->fetch()) $errors[] = 'Email sudah digunakan user lain.';
    }
    if ($errors) return ['success' => false, 'errors' => $errors];

    $stmt = $pdo->prepare(
        'UPDATE users SET name = ?, email = ?, phone = ?, alamat = ? WHERE id = ?'
    );
    $stmt->execute([$name, $email, $phone, $alamat, $userId]);
    return ['success' => true];
}

/**
 * Upload / ganti foto profil customer.
 *
 * Langkah:
 * 1. Validasi file upload (ekstensi, ukuran, MIME).
 * 2. Pastikan direktori AVATAR_UPLOAD_DIR ada.
 * 3. Pindahkan file baru ke direktori avatar.
 * 4. Hapus foto lama dari disk (jika ada).
 * 5. Update kolom profile_photo di tabel users.
 *
 * @return array{success:bool, errors?:string[]}
 */
function update_profile_photo(int $userId, string $field = 'profile_photo'): array
{
    global $pdo;

    // Ambil data user saat ini (untuk hapus foto lama)
    $stmt = $pdo->prepare('SELECT profile_photo FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $oldPhoto = $stmt->fetchColumn();

    $upload = validate_avatar_upload($field);
    if ($upload === null) {
        return ['success' => false, 'errors' => ['Tidak ada file yang diunggah.']];
    }
    if (isset($upload['error'])) {
        return ['success' => false, 'errors' => [$upload['error']]];
    }

    // Pastikan direktori upload avatar ada
    if (!is_dir(AVATAR_UPLOAD_DIR)) {
        @mkdir(AVATAR_UPLOAD_DIR, 0775, true);
    }
    if (!is_writable(AVATAR_UPLOAD_DIR)) {
        return ['success' => false, 'errors' => ['Direktori upload tidak dapat ditulis. Hubungi admin.']];
    }

    // Pindahkan file baru
    $targetPath = AVATAR_UPLOAD_DIR . $upload['name'];
    if (!move_uploaded_file($upload['tmp_name'], $targetPath)) {
        return ['success' => false, 'errors' => ['Gagal menyimpan foto profil. Silakan coba lagi.']];
    }

    // Hapus foto lama
    if (!empty($oldPhoto) && $oldPhoto !== $upload['name']) {
        delete_avatar_file($oldPhoto);
    }

    // Update DB
    $stmt = $pdo->prepare('UPDATE users SET profile_photo = ? WHERE id = ?');
    $stmt->execute([$upload['name'], $userId]);

    return ['success' => true, 'filename' => $upload['name']];
}

/**
 * Hapus foto profil customer (reset ke null + hapus file).
 */
function remove_profile_photo(int $userId): array
{
    global $pdo;

    $stmt = $pdo->prepare('SELECT profile_photo FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $photo = $stmt->fetchColumn();

    if (empty($photo)) {
        return ['success' => false, 'errors' => ['Belum ada foto profil untuk dihapus.']];
    }

    delete_avatar_file($photo);

    $stmt = $pdo->prepare('UPDATE users SET profile_photo = NULL WHERE id = ?');
    $stmt->execute([$userId]);

    return ['success' => true];
}

/**
 * Ganti password user.
 */
function change_password(int $userId, array $data): array
{
    global $pdo;
    $current = $data['current_password'] ?? '';
    $new     = $data['new_password'] ?? '';
    $confirm = $data['confirm_password'] ?? '';

    $errors = [];
    if (strlen($new) < 6)         $errors[] = 'Password baru minimal 6 karakter.';
    if ($new !== $confirm)        $errors[] = 'Konfirmasi password tidak cocok.';

    $stmt = $pdo->prepare('SELECT password FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $hash = $stmt->fetchColumn();
    if (!password_verify($current, $hash)) {
        $errors[] = 'Password saat ini salah.';
    }

    if ($errors) return ['success' => false, 'errors' => $errors];

    $newHash = password_hash($new, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
    $stmt->execute([$newHash, $userId]);
    return ['success' => true];
}

/**
 * Admin reset password customer.
 */
function admin_reset_password(int $userId, string $newPassword): array
{
    global $pdo;
    if (strlen($newPassword) < 6) {
        return ['success' => false, 'errors' => ['Password minimal 6 karakter.']];
    }
    $hash = password_hash($newPassword, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ? AND role = ?');
    $stmt->execute([$hash, $userId, ROLE_CUSTOMER]);
    return ['success' => true];
}
