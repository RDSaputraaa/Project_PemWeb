<?php ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gelora Sport</title>
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
        <a href="index.php" class="btn-back" title="Kembali">&lt;</a>
        <div class="register-card">
            <h2>LOGIN</h2>
            <p class="subtitle">Silakan masuk ke akun Anda</p>
            <?php if (!empty($_GET['timeout'])): ?>
                <div style="padding:10px 14px;background:#fde8e8;color:#c0392b;border:1px solid #f5c6cb;border-radius:8px;margin-bottom:15px;font-size:.9rem;">
                    ⏱️ Sesi Anda telah berakhir. Silakan login kembali.
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div style="padding:10px 14px;background:#fde8e8;color:#c0392b;border:1px solid #f5c6cb;border-radius:8px;margin-bottom:15px;font-size:.9rem;">
                    <?= e($error) ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="index.php?page=login">
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" placeholder="Masukkan email" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn-register">Masuk</button>
            </form>
            <p class="login-link">Belum punya akun? <a href="index.php?page=register">Daftar di sini</a></p>
        </div>
    </div>
</div>
</body>
</html>
