<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Pemesanan - Gelora Sport</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="checkout-page" style="background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; min-height: 100vh;">

    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">Gelora Sport</div>
        <div class="nav-menu">
            <span style="color:#007c60; font-weight:600;">Halo, <?= e(explode(' ', $user['nama'])[0]) ?></span>
            <a href="index.php?page=beranda" class="login-link"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="index.php?page=dashboard" class="login-link"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a href="index.php?page=profile" class="login-link"><i class="fa-solid fa-user"></i> Profil</a>
            <a href="index.php?page=logout" class="register-btn">Keluar</a>
        </div>
    </nav>

    <div style="max-width: 650px; margin: 40px auto; padding: 0 20px;">
        <a href="index.php?page=detail&id=<?= $lap['id'] ?>" style="text-decoration:none;color:#007c60;font-weight:bold;display:inline-block;margin-bottom:20px;">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Lapangan
        </a>

        <?php if (!empty($error)): ?>
            <div style="padding:14px 20px;background:#fde8e8;color:#c0392b;border:1px solid #f5c6cb;border-radius:10px;margin-bottom:20px;font-weight:500;">
                <i class="fa-solid fa-triangle-exclamation"></i> <?= e($error) ?>
            </div>
        <?php endif; ?>

        <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #eaeaea; overflow: hidden;">
            
            <!-- Header Section -->
            <div style="background: linear-gradient(135deg, #007c60, #005b48); padding: 30px; color: white;">
                <h2 style="margin: 0; font-size: 1.4rem; font-weight: 700;"><i class="fa-solid fa-file-invoice-dollar"></i> Konfirmasi Booking</h2>
                <p style="margin: 5px 0 0; opacity: 0.8; font-size: 0.9rem;">Periksa kembali rincian jadwal lapangan pilihan Anda sebelum melanjutkan.</p>
            </div>

            <!-- Content Form -->
            <form method="POST" action="index.php?page=checkout&slot_id=<?= $slot['id'] ?>" style="padding: 30px;">
                
                <!-- Rincian Lapangan -->
                <div style="display: flex; gap: 15px; border-bottom: 1px solid #eaeaea; padding-bottom: 20px; margin-bottom: 20px;">
                    <div style="width: 100px; height: 75px; border-radius: 8px; overflow: hidden; background: #eee;">
                        <img src="<?= e($lap['foto_url'] ?? '') ?>" alt="<?= e($lap['nama']) ?>" 
                             style="width:100%; height:100%; object-fit:cover;"
                             onerror="this.src='https://via.placeholder.com/100x75?text=No+Image'">
                    </div>
                    <div>
                        <h4 style="margin: 0 0 5px; font-size: 1.1rem; color: #222;"><?= e($lap['nama']) ?></h4>
                        <span style="font-size: 0.85rem; color: #7f8c8d; display: block; margin-bottom: 5px;"><i class="fa-solid fa-location-dot"></i> <?= e($lap['lokasi']) ?></span>
                        <div style="display: flex; gap: 8px;">
                            <span style="background: #e8f5e9; color: #007c60; font-size: 0.75rem; padding: 2px 8px; border-radius: 4px; font-weight: 600;"><?= e($lap['jenis']) ?></span>
                            <span style="background: #e3f2fd; color: #1e88e5; font-size: 0.75rem; padding: 2px 8px; border-radius: 4px; font-weight: 600;"><?= e($lap['tipe']) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Rincian Jadwal -->
                <h3 style="margin-bottom: 15px; color: #222; font-size: 1.1rem; padding-bottom: 5px;">Rincian Waktu</h3>
                
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border: 1px dashed #ddd; margin-bottom: 25px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: #7f8c8d; font-size: 0.9rem;">Tanggal Main</span>
                        <strong style="color: #2c3e50; font-size: 0.95rem;"><?= formatTanggal($slot['tanggal']) ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: #7f8c8d; font-size: 0.9rem;">Durasi</span>
                        <strong style="color: #2c3e50; font-size: 0.95rem;">60 Menit (1 Jam)</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: #7f8c8d; font-size: 0.9rem;">Jam Main</span>
                        <strong style="color: #007c60; font-size: 0.95rem;"><i class="fa-regular fa-clock"></i> <?= substr($slot['jam_mulai'], 0, 5) ?> - <?= substr($slot['jam_selesai'], 0, 5) ?> WIB</strong>
                    </div>
                    <hr style="border: 0; border-top: 1px solid #eaeaea; margin: 12px 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #222; font-weight: 600; font-size: 1rem;">Total Tagihan</span>
                        <strong style="color: #e67e22; font-size: 1.3rem; font-weight: 700;"><?= formatRupiah((int)$slot['harga']) ?></strong>
                    </div>
                </div>

                <!-- Catatan Tambahan -->
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 600; font-size: 0.9rem; color: #444; margin-bottom: 8px;">Catatan untuk Admin (Opsional)</label>
                    <textarea name="catatan" placeholder="Contoh: Tolong siapkan bola cadangan atau rompi..." rows="3"
                              style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95rem; outline: none; transition: border-color 0.2s; resize: none;"
                              onfocus="this.style.borderColor='#007c60'" onblur="this.style.borderColor='#ddd'"></textarea>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <a href="index.php?page=detail&id=<?= $lap['id'] ?>" style="text-decoration:none; padding: 12px 24px; border: 1px solid #ddd; border-radius: 8px; color: #555; font-weight: 600; text-align: center; transition: background 0.2s;"
                       onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='none'">Batal</a>
                    <button type="submit" style="padding: 12px 30px; background: #007c60; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s;"
                            onmouseover="this.style.background='#005b48'" onmouseout="this.style.background='#007c60'">Konfirmasi & Bayar</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
