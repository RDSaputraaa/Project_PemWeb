<?php
// ============================================================
//  views/register.php — View form register
//  Variabel dari Controller: $error, $success
// ============================================================
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Gelora Sport</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="register-page">
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
                    <?= e($success) ?> <a href="index.php?page=login">Login sekarang</a>
                </div>
            <?php endif; ?>
            <form method="POST" action="index.php?page=register">
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
            <p class="login-link">Sudah punya akun? <a href="index.php?page=login">Masuk di sini</a></p>
        </div>
    </div>
</div>
</body>
</html>
