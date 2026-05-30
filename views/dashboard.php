<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gelora Sport</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .welcome-section {
            background: linear-gradient(135deg, #007c60, #009973);
            border-radius: 12px;
            padding: 40px;
            color: white;
            margin-bottom: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 12px rgba(0, 124, 96, 0.12);
        }

        .welcome-content h1 {
            font-size: 2rem;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .welcome-content p {
            opacity: 0.9;
            margin-bottom: 16px;
            font-size: 1rem;
        }

        .welcome-buttons {
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }

        .btn-primary {
            background: white;
            color: #007c60;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            text-align: center;
        }

        .btn-primary:hover {
            background: #f5f5f5;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 20px;
            border: 2px solid white;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            text-align: center;
        }

        .btn-secondary:hover {
            background: white;
            color: #007c60;
        }

        .welcome-icon {
            font-size: 4rem;
            opacity: 0.1;
            margin-left: 40px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            border: 1px solid #eaeaea;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.3s;
        }

        .stat-card:hover {
            box-shadow: 0 6px 20px rgba(0, 124, 96, 0.12);
            border-color: #007c60;
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #007c60, #009973);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-content h3 {
            font-size: 1.8rem;
            color: #007c60;
            margin: 0 0 4px 0;
            font-weight: 700;
        }

        .stat-content p {
            color: #888;
            margin: 0;
            font-size: 0.9rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #222;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i {
            color: #007c60;
        }

        .reservasi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .reservasi-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #eaeaea;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            transition: all 0.3s;
        }

        .reservasi-card:hover {
            box-shadow: 0 6px 20px rgba(0, 124, 96, 0.12);
            border-color: #007c60;
            transform: translateY(-4px);
        }

        .reservasi-header {
            background: linear-gradient(135deg, #007c60, #009973);
            color: white;
            padding: 20px;
            border-bottom: 3px solid #00a389;
        }

        .reservasi-header h3 {
            margin: 0 0 8px 0;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .reservasi-header p {
            margin: 4px 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .reservasi-body {
            padding: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eaeaea;
        }

        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .info-label {
            color: #888;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: #222;
            font-weight: 600;
            text-align: right;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-aktif {
            background: #e8f5e9;
            color: #155724;
        }

        .status-selesai {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status-batal {
            background: #fde8e8;
            color: #c0392b;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            margin-top: 16px;
        }

        .btn-small {
            flex: 1;
            padding: 10px 12px;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.2s;
        }

        .btn-view {
            background: #007c60;
            color: white;
        }

        .btn-view:hover {
            background: #009973;
            transform: translateY(-1px);
        }

        .btn-cancel {
            background: #f5f5f5;
            color: #c0392b;
            border: 1px solid #ddd;
        }

        .btn-cancel:hover {
            background: #fde8e8;
            border-color: #c0392b;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            border: 2px dashed #ddd;
        }

        .empty-state i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 16px;
            display: block;
        }

        .empty-state p {
            color: #888;
            margin-bottom: 16px;
            font-size: 1rem;
        }

        .empty-state a {
            background: #007c60;
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: all 0.2s;
        }

        .empty-state a:hover {
            background: #009973;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 20px 16px;
            }

            .welcome-section {
                flex-direction: column;
                text-align: center;
            }

            .welcome-icon {
                display: none;
            }

            .welcome-content h1 {
                font-size: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .reservasi-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">Gelora Sport</div>
        <div class="nav-menu">
            <span style="color:#007c60; font-weight:600;">Halo, <?= e(explode(' ', $user['nama'])[0]) ?></span>
            <a href="index.php?page=beranda" class="login-link"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="index.php?page=profile" class="login-link"><i class="fa-solid fa-user"></i> Profil</a>
            <?php if ($user['role'] === 'admin'): ?>
                <a href="index.php?page=admin" class="login-link"><i class="fa-solid fa-gauge"></i> Admin</a>
            <?php endif; ?>
            <a href="index.php?page=logout" class="register-btn">Keluar</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-content">
                <h1>Selamat Datang, <?= e(explode(' ', $user['nama'])[0]) ?>! 👋</h1>
                <p>Kelola reservasi lapangan olahraga Anda dengan mudah dan cepat</p>
                <div class="welcome-buttons">
                    <a href="index.php?page=beranda" class="btn-primary">
                        <i class="fa-solid fa-plus"></i> Pesan Lapangan Baru
                    </a>
                    <a href="index.php?page=riwayat" class="btn-secondary">
                        <i class="fa-solid fa-history"></i> Lihat Riwayat
                    </a>
                </div>
            </div>
            <div class="welcome-icon">
                <i class="fa-solid fa-futbol"></i>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['total_reservasi'] ?? 0 ?></h3>
                    <p>Total Reservasi</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-hourglass-end"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['reservasi_aktif'] ?? 0 ?></h3>
                    <p>Reservasi Aktif</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['reservasi_selesai'] ?? 0 ?></h3>
                    <p>Selesai</p>
                </div>
            </div>
        </div>

        <!-- Active Reservations -->
        <h2 class="section-title">
            <i class="fa-solid fa-bookmark"></i> Reservasi Aktif
        </h2>

        <?php if (!empty($reservasi_aktif)): ?>
            <div class="reservasi-grid">
                <?php foreach ($reservasi_aktif as $res): ?>
                    <div class="reservasi-card">
                        <div class="reservasi-header">
                            <h3><?= e($res['nama_lapangan']) ?></h3>
                            <p><i class="fa-solid fa-location-dot"></i> <?= e($res['jenis']) ?></p>
                            <p><i class="fa-solid fa-rupiah-sign"></i> Rp <?= number_format($res['harga'], 0, ',', '.') ?>/jam</p>
                        </div>
                        <div class="reservasi-body">
                            <div class="info-row">
                                <span class="info-label">Tanggal</span>
                                <span class="info-value"><?= date('d M Y', strtotime($res['tanggal'])) ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Jam</span>
                                <span class="info-value"><?= date('H:i', strtotime($res['jam_mulai'])) ?> - <?= date('H:i', strtotime($res['jam_selesai'])) ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Status</span>
                                <span class="status-badge status-aktif">
                                    <i class="fa-solid fa-circle"></i> Aktif
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Total Bayar</span>
                                <span class="info-value" style="color: #007c60; font-size: 1.1rem;">Rp <?= number_format($res['total_harga'], 0, ',', '.') ?></span>
                            </div>
                            <div class="action-buttons">
                                <a href="index.php?page=detail&id=<?= $res['lapangan_id'] ?>" class="btn-small btn-view">
                                    <i class="fa-solid fa-eye"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fa-solid fa-inbox"></i>
                <p>Anda belum memiliki reservasi aktif</p>
                <a href="index.php?page=beranda">Mulai Booking Sekarang</a>
            </div>
        <?php endif; ?>

        <!-- Recent Reservations -->
        <h2 class="section-title" style="margin-top: 40px;">
            <i class="fa-solid fa-history"></i> Reservasi Terbaru
        </h2>

        <?php if (!empty($reservasi_terbaru)): ?>
            <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06); border: 1px solid #eaeaea;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #007c60, #009973); color: white;">
                            <th style="padding: 16px; text-align: left; font-weight: 600;">Lapangan</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600;">Tanggal</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600;">Jam</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600;">Total</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600;">Status</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservasi_terbaru as $res): ?>
                            <tr style="border-bottom: 1px solid #eaeaea;">
                                <td style="padding: 16px; font-weight: 600; color: #222;"><?= e($res['nama_lapangan']) ?></td>
                                <td style="padding: 16px; color: #666;"><?= date('d M Y', strtotime($res['tanggal'])) ?></td>
                                <td style="padding: 16px; color: #666;"><?= date('H:i', strtotime($res['jam_mulai'])) ?> - <?= date('H:i', strtotime($res['jam_selesai'])) ?></td>
                                <td style="padding: 16px; color: #007c60; font-weight: 600;">Rp <?= number_format($res['total_harga'], 0, ',', '.') ?></td>
                                <td style="padding: 16px; text-align: center;">
                                    <span class="status-badge <?= $res['status'] === 'selesai' ? 'status-selesai' : ($res['status'] === 'dibatalkan' ? 'status-batal' : 'status-aktif') ?>">
                                        <?= ucfirst($res['status']) ?>
                                    </span>
                                </td>
                                <td style="padding: 16px; text-align: center;">
                                    <a href="index.php?page=detail&id=<?= $res['lapangan_id'] ?>" style="color: #007c60; text-decoration: none; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#005b48'" onmouseout="this.style.color='#007c60'">
                                        <i class="fa-solid fa-arrow-right"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div style="text-align: center; margin-top: 24px;">
                <a href="index.php?page=riwayat" style="color: #007c60; text-decoration: none; font-weight: 600; display: inline-block; padding: 12px 24px; border: 2px solid #007c60; border-radius: 8px; transition: all 0.2s;" onmouseover="this.style.background='#007c60'; this.style.color='white'" onmouseout="this.style.background='white'; this.style.color='#007c60'">
                    <i class="fa-solid fa-arrow-right"></i> Lihat Semua Riwayat
                </a>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fa-solid fa-inbox"></i>
                <p>Belum ada riwayat reservasi</p>
                <a href="index.php?page=beranda">Pesan Lapangan Sekarang</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
