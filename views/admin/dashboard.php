<?php
// ============================================================
//  views/admin/dashboard.php — View dashboard admin
//  Variabel dari Controller: $user, $totalLapangan, $todayCount,
//    $pending, $income, $reservasis, $activePage
// ============================================================
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gelora Sport</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-page">
<div class="admin-container">
    <?php require __DIR__ . '/../components/sidebar.php'; ?>
    <main class="main-content">
        <header class="top-header">
            <div class="header-title"><h1>Dashboard Overview</h1></div>
            <div style="display:flex; align-items:center; gap:20px;">
                <a href="index.php?page=generate_slots" class="btn-primary" style="text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-calendar-plus"></i> Generate Slot Jadwal
                </a>
                <div class="admin-profile">
                    <span class="admin-name">Halo, <?= e(explode(' ', $user['nama'])[0]) ?></span>
                    <div class="avatar"><i class="fa-solid fa-user"></i></div>
                </div>
            </div>
        </header>

        <?php if ($flashMsg): ?>
            <div style="padding:12px 16px;background:#e8f5e9;color:#155724;border:1px solid #c3e6cb;border-radius:8px;margin-bottom:20px;display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-circle-check"></i>
                <span><?= $flashMsg ?></span>
            </div>
        <?php endif; ?>

        <section class="metrics-grid">
            <div class="metric-card">
                <div class="metric-icon"><i class="fa-solid fa-futbol"></i></div>
                <div class="metric-info"><p>Total Lapangan Aktif</p><h3><?= $totalLapangan ?></h3></div>
            </div>
            <div class="metric-card">
                <div class="metric-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <div class="metric-info"><p>Reservasi Hari Ini</p><h3><?= $todayCount ?></h3></div>
            </div>
            <div class="metric-card">
                <div class="metric-icon warning"><i class="fa-solid fa-clock-rotate-left"></i></div>
                <div class="metric-info"><p>Menunggu Konfirmasi</p><h3><?= $pending ?></h3></div>
            </div>
            <div class="metric-card">
                <div class="metric-icon success"><i class="fa-solid fa-wallet"></i></div>
                <div class="metric-info"><p>Pendapatan Bulan Ini</p><h3><?= formatRupiah((int)$income) ?></h3></div>
            </div>
        </section>

        <section class="recent-reservations">
            <div class="section-header">
                <h2>Reservasi Terbaru</h2>
                <a href="index.php?page=data_reservasi"><button class="btn-primary">Lihat Semua</button></a>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th><th>Pelanggan</th><th>Lapangan</th>
                            <th>Tanggal</th><th>Waktu</th><th>Status</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reservasis)): ?>
                            <tr><td colspan="7" style="text-align:center;color:#888;">Belum ada reservasi.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($reservasis as $r): ?>
                        <tr>
                            <td style="font-size:12px;color:#888;"><?= e($r['kode_reservasi']) ?></td>
                            <td><?= e($r['nama_user']) ?></td>
                            <td><?= e($r['nama_lapangan']) ?></td>
                            <td><?= formatTanggal($r['tanggal']) ?></td>
                            <td><?= substr($r['jam_mulai'],0,5) ?> - <?= substr($r['jam_selesai'],0,5) ?></td>
                            <td><span class="badge <?= $r['status_bayar']==='Lunas'?'confirmed':'pending' ?>">
                                <?= $r['status_bayar']==='Lunas'?'Lunas':'Menunggu' ?>
                            </span></td>
                            <td>
                                <?php if ($r['status'] === 'Menunggu Konfirmasi'): ?>
                                    <form method="POST" action="index.php?page=admin" style="display:inline;">
                                        <input type="hidden" name="konfirmasi_id" value="<?= $r['id'] ?>">
                                        <button type="submit" class="btn-action">Konfirmasi</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn-action disabled" disabled>Selesai</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
</body>
</html>
