<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Gelora Sport</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="profile-page">
    <nav class="navbar">
        <div class="logo">Gelora Sport</div>
        <div class="nav-menu">
            <span style="color:#007c60; font-weight:600;">Halo, <?= e(explode(' ', $user['nama'])[0]) ?></span>
            <a href="index.php?page=beranda" class="login-link"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="index.php?page=dashboard" class="login-link"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a href="index.php?page=riwayat" class="login-link"><i class="fa-solid fa-history"></i> Riwayat</a>
            <?php if ($user['role'] === 'admin'): ?>
                <a href="index.php?page=admin" class="login-link"><i class="fa-solid fa-shield"></i> Admin</a>
            <?php endif; ?>
            <a href="index.php?page=logout" class="register-btn">Keluar</a>
        </div>
    </nav>

    <div style="max-width: 800px; margin: 40px auto; padding: 0 20px;">
        <a href="index.php?page=<?= $user['role'] === 'admin' ? 'admin' : 'beranda' ?>" style="text-decoration:none;color:#007c60;font-weight:bold;display:inline-block;margin-bottom:20px;">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
        </a>

        <?php if ($success): ?>
            <div style="padding:14px 20px;background:#e8f5e9;color:#155724;border:1px solid #c3e6cb;border-radius:10px;margin-bottom:20px;font-weight:500;">
                <i class="fa-solid fa-circle-check"></i> <?= e($success) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div style="padding:14px 20px;background:#fde8e8;color:#c0392b;border:1px solid #f5c6cb;border-radius:10px;margin-bottom:20px;font-weight:500;">
                <i class="fa-solid fa-triangle-exclamation"></i> <?= e($error) ?>
            </div>
        <?php endif; ?>

        <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #eaeaea; overflow: hidden;">
            <div style="background: linear-gradient(135deg, #007c60, #005b48); padding: 30px; color: white; display: flex; align-items: center; gap: 20px;">
                <div style="width: 70px; height: 70px; background-color: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: bold; border: 2px solid white;">
                    <?= strtoupper(substr($user['nama'], 0, 1)) ?>
                </div>
                <div>
                    <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700;"><?= e($user['nama']) ?></h2>
                    <p style="margin: 5px 0 0; opacity: 0.8; font-size: 0.9rem;"><i class="fa-regular fa-envelope"></i> <?= e($user['email']) ?></p>
                </div>
            </div>

            <form id="profileForm" method="POST" action="index.php?page=profile" style="padding: 30px;">
                <input type="hidden" name="action" value="update">
                
                <h3 style="margin-bottom: 20px; color: #222; font-size: 1.2rem; border-bottom: 2px solid #eaeaea; padding-bottom: 8px;">Informasi Pribadi</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.9rem; color: #444; margin-bottom: 8px;">Nama Lengkap</label>
                        <input type="text" name="nama" value="<?= e($user['nama']) ?>" required 
                               style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; outline: none; transition: border-color 0.2s;"
                               onfocus="this.style.borderColor='#007c60'" onblur="this.style.borderColor='#ddd'">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.9rem; color: #444; margin-bottom: 8px;">Email</label>
                        <input type="email" name="email" value="<?= e($user['email']) ?>" required 
                               style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; outline: none; transition: border-color 0.2s;"
                               onfocus="this.style.borderColor='#007c60'" onblur="this.style.borderColor='#ddd'">
                    </div>
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 600; font-size: 0.9rem; color: #444; margin-bottom: 8px;">Nomor HP</label>
                    <input type="text" name="no_hp" value="<?= e($user['no_hp'] ?? '') ?>" placeholder="Contoh: 081234567890"
                           style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; outline: none; transition: border-color 0.2s;"
                           onfocus="this.style.borderColor='#007c60'" onblur="this.style.borderColor='#ddd'">
                </div>

                <h3 style="margin-bottom: 20px; color: #222; font-size: 1.2rem; border-bottom: 2px solid #eaeaea; padding-bottom: 8px; margin-top: 10px;">Ubah Password <span style="font-size: 0.8rem; font-weight: normal; color: #888;">(Kosongkan jika tidak ingin mengubah)</span></h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.9rem; color: #444; margin-bottom: 8px;">Password Baru</label>
                        <input type="password" name="password" placeholder="Minimal 6 karakter"
                               style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; outline: none; transition: border-color 0.2s;"
                               onfocus="this.style.borderColor='#007c60'" onblur="this.style.borderColor='#ddd'">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.9rem; color: #444; margin-bottom: 8px;">Konfirmasi Password Baru</label>
                        <input type="password" name="konfirmasi" placeholder="Ulangi password baru"
                               style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; outline: none; transition: border-color 0.2s;"
                               onfocus="this.style.borderColor='#007c60'" onblur="this.style.borderColor='#ddd'">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <a href="index.php?page=<?= $user['role'] === 'admin' ? 'admin' : 'beranda' ?>" style="text-decoration:none; padding: 12px 24px; border: 1px solid #ddd; border-radius: 8px; color: #555; font-weight: 600; text-align: center; transition: background 0.2s;"
                       onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='none'">Batal</a>
                    <button type="submit" style="padding: 12px 30px; background: #007c60; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s;"
                            onmouseover="this.style.background='#005b48'" onmouseout="this.style.background='#007c60'">Simpan Perubahan</button>
                </div>
            </form>
            <div style="border-top: 1px solid #eaeaea; background-color: #fff8f8; padding: 30px; border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                <h3 style="color: #c0392b; margin-top: 0; margin-bottom: 10px; font-size: 1.1rem;"><i class="fa-solid fa-triangle-exclamation"></i> Zona Bahaya</h3>
                <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 20px;">Menghapus akun Anda akan menghapus data profil beserta seluruh riwayat pemesanan/reservasi Anda secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
                <form id="deleteAccountForm" method="POST" action="index.php?page=profile">
                    <input type="hidden" name="action" value="delete">
                    <button type="button" id="deleteBtn" style="padding: 12px 20px; background: #c0392b; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s;"
                            onmouseover="this.style.background='#a62c1e'" onmouseout="this.style.background='#c0392b'">Hapus Akun Permanen</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        const password = this.querySelector('input[name="password"]').value;
        const konfirmasi = this.querySelector('input[name="konfirmasi"]').value;

        if (password.length > 0) {
            if (password.length < 6) {
                e.preventDefault();
                alert('Validasi Klien: Password baru minimal harus 6 karakter.');
                return false;
            }
            if (password !== konfirmasi) {
                e.preventDefault();
                alert('Validasi Klien: Password baru dan Konfirmasi Password tidak cocok.');
                return false;
            }
        }
    });

    document.getElementById('deleteBtn').addEventListener('click', function() {
        if (confirm('Apakah Anda yakin ingin menghapus akun Anda secara permanen? Semua data reservasi Anda akan dilepaskan dan akun Anda akan dihapus selamanya.')) {
            document.getElementById('deleteAccountForm').submit();
        }
    });
    </script>
</body>
</html>
