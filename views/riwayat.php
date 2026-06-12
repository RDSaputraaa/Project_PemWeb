<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembayaran - Gelora Sport</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background-color: #f4f7f6; color: #333;">

    <nav class="navbar">
        <div class="logo">Gelora Sport</div>
        <div class="nav-menu">
            <span style="color:#007c60; font-weight:600;">Halo, <?= e(explode(' ', $user['nama'])[0]) ?></span>
            <a href="index.php?page=beranda" class="login-link"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="index.php?page=dashboard" class="login-link"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a href="index.php?page=profile" class="login-link"><i class="fa-solid fa-user"></i> Profil</a>
            <?php if ($user['role'] === 'admin'): ?>
                <a href="index.php?page=admin" class="login-link"><i class="fa-solid fa-shield"></i> Admin</a>
            <?php endif; ?>
            <a href="index.php?page=logout" class="register-btn">Keluar</a>
        </div>
    </nav>

    <div style="max-width: 1100px; margin: 40px auto; padding: 0 20px;">
        <a href="index.php?page=beranda" style="text-decoration:none;color:#007c60;font-weight:bold;display:inline-block;margin-bottom:20px;">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
        </a>

        <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #eaeaea; padding: 30px;">
            <h1 style="color: #007c60; margin-bottom: 25px; font-size: 1.8rem; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-receipt"></i> Riwayat Pembayaran
            </h1>

            <?php if(empty($riwayat)): ?>
                <div style="text-align: center; padding: 40px 20px; color: #777;">
                    <i class="fa-regular fa-calendar-times" style="font-size: 4rem; color: #ccc; margin-bottom: 15px; display: block;"></i>
                    <p style="font-size: 1.1rem;">Belum ada riwayat pembayaran.</p>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="riwayat-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 2px solid #eaeaea;">
                                <th style="padding: 16px; color: #ffffff; font-weight: 600; font-size: 0.9rem; text-transform: uppercase;">Lapangan</th>
                                <th style="padding: 16px; color: #ffffff; font-weight: 600; font-size: 0.9rem; text-transform: uppercase;">Tanggal</th>
                                <th style="padding: 16px; color: #ffffff; font-weight: 600; font-size: 0.9rem; text-transform: uppercase;">Jam</th>
                                <th style="padding: 16px; color: #ffffff; font-weight: 600; font-size: 0.9rem; text-transform: uppercase;">Total</th>
                                <th style="padding: 16px; color: #ffffff; font-weight: 600; font-size: 0.9rem; text-transform: uppercase;">Status</th>
                                <th style="padding: 16px; color: #ffffff; font-weight: 600; font-size: 0.9rem; text-transform: uppercase; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($riwayat as $r): ?>
                            <tr style="border-bottom: 1px solid #eee; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#f9fbfb'" onmouseout="this.style.backgroundColor='transparent'">
                                <td style="padding: 16px; font-weight: 600; color: #111827;">
                                    <?= e($r['nama_lapangan']) ?>
                                </td>
                                <td style="padding: 16px; color: #4b5563;">
                                    <?= date('d M Y', strtotime($r['tanggal'])) ?>
                                </td>
                                <td style="padding: 16px; color: #4b5563;">
                                    <i class="fa-regular fa-clock" style="color: #888;"></i> 
                                    <?= substr($r['jam_mulai'],0,5) ?> - <?= substr($r['jam_selesai'],0,5) ?>
                                </td>
                                <td style="padding: 16px; font-weight: 700; color: #007c60;">
                                    <?= formatRupiah($r['total_harga']) ?>
                                </td>
                                <td style="padding: 16px;">
                                    <?php 
                                    $status = $r['status'];
                                    $badgeStyle = "padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-block;";
                                    if ($status === 'Lunas' || $status === 'Dikonfirmasi') {
                                        echo "<span style='{$badgeStyle} background-color: #dcfce7; color: #15803d;'><i class='fa-solid fa-circle-check'></i> " . e($status) . "</span>";
                                    } elseif ($status === 'Menunggu Konfirmasi' || $status === 'Pending') {
                                        echo "<span style='{$badgeStyle} background-color: #fef3c7; color: #b45309;'><i class='fa-solid fa-clock'></i> " . e($status) . "</span>";
                                    } else {
                                        echo "<span style='{$badgeStyle} background-color: #fde8e8; color: #c0392b;'><i class='fa-solid fa-circle-xmark'></i> " . e($status) . "</span>";
                                    }
                                    ?>
                                </td>
                                <td style="padding: 16px; text-align: center;">
                                    <?php if ($status === 'Menunggu Konfirmasi' || $status === 'Pending'): ?>
                                        <a href="index.php?page=payment&id=<?= $r['id'] ?>" style="background: #007c60; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: background 0.2s;" onmouseover="this.style.background='#005b48'" onmouseout="this.style.background='#007c60'">
                                            Bayar / Upload
                                        </a>
                                    <?php else: ?>
                                        <span style="color: #9ca3af; font-size: 0.9rem;">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
