<?php
// ============================================================
//  views/login.php — View form login
//  Variabel dari Controller: $error
// ============================================================
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gelora Sport</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
<div class="container">
    <div class="left-side">
        <div class="overlay">
            <div class="hero-content">
                <h1>Gelora Sport</h1>
                <p>Sewa lapangan olahraga dengan mudah, cepat, dan terpercaya.</p>
            </div>
        </div>
    </div>
    <div class="right-side">
        <div class="form-card">
            <h2>LOGIN</h2>
            <p class="subtitle">Silakan masuk ke akun Anda</p>
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
                <button type="submit" class="btn-submit">Masuk</button>
            </form>
            <p class="login-link">Belum punya akun? <a href="index.php?page=register">Daftar di sini</a></p>
        </div>
    </div>
</div>
</body>
</html>
