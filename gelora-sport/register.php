<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helper.php';

if (currentUser()) redirect('/index.php');

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = trim($_POST['nama'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi'] ?? '';

    if (!$nama || !$email || !$password) {
        $error = 'Semua field wajib diisi.';
    } elseif ($password !== $konfirmasi) {
        $error = 'Password dan konfirmasi tidak cocok.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        $db   = getDB();
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email sudah terdaftar.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $db->prepare("INSERT INTO users (nama, email, password, role) VALUES (?,?,?,'user')")
               ->execute([$nama, $email, $hash]);
            $success = 'Akun berhasil dibuat! Silakan login.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Gelora Sport</title>
    <link rel="stylesheet" href="assets/css/register.css">
</head>
<body>
<div class="register-wrapper">
    <div class="register-left">
        <div class="branding">
            <h1>Gelora Sport</h1>
            <p>Bergabung dan nikmati kemudahan reservasi lapangan olahraga favoritmu.</p>
        </div>
    </div>
    <div class="register-right">
        <div class="register-card">
            <h2>Daftar Akun</h2>
            <p class="subtitle">Isi data berikut untuk membuat akun baru</p>
            <?php if ($error): ?>
                <div style="padding:10px 14px;background:#fde8e8;color:#c0392b;border:1px solid #f5c6cb;border-radius:8px;margin-bottom:15px;font-size:.9rem;"><?= e($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div style="padding:10px 14px;background:#e8f5e9;color:#155724;border:1px solid #c3e6cb;border-radius:8px;margin-bottom:15px;font-size:.9rem;">
                    <?= e($success) ?> <a href="/login.php">Login sekarang</a>
                </div>
            <?php endif; ?>
            <form method="POST" action="/register.php">
                <div class="input-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= e($_POST['nama'] ?? '') ?>" placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" placeholder="Masukkan email" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Buat password (min 6 karakter)" required>
                </div>
                <div class="input-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="konfirmasi" placeholder="Ulangi password" required>
                </div>
                <button type="submit" class="btn-register">Daftar Sekarang</button>
            </form>
            <p class="login-link">Sudah punya akun? <a href="/login.php">Masuk di sini</a></p>
        </div>
    </div>
</div>
</body>
</html>
