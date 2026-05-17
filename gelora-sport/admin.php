<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helper.php';

$user = requireAdmin();
$db   = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['konfirmasi_id'])) {
    $rid = (int)$_POST['konfirmasi_id'];
    $db->prepare("UPDATE reservasi SET status='Dikonfirmasi', status_bayar='Lunas' WHERE id=?")->execute([$rid]);
    redirect('/admin.php');
}

$totalLapangan = $db->query("SELECT COUNT(*) FROM lapangan WHERE status='Aktif'")->fetchColumn();
$reservasiHariIni = $db->prepare("SELECT COUNT(*) FROM reservasi WHERE tanggal=?");
$reservasiHariIni->execute([date('Y-m-d')]); $todayCount = $reservasiHariIni->fetchColumn();
$pending = $db->query("SELECT COUNT(*) FROM reservasi WHERE status='Menunggu Konfirmasi'")->fetchColumn();
$pendapatan = $db->prepare("SELECT COALESCE(SUM(total_harga),0) FROM reservasi WHERE status_bayar='Lunas' AND MONTH(tanggal)=? AND YEAR(tanggal)=?");
$pendapatan->execute([date('n'), date('Y')]); $income = $pendapatan->fetchColumn();

$stmt = $db->query("SELECT r.*, u.nama as nama_user, l.nama as nama_lapangan
    FROM reservasi r
    JOIN users u ON u.id=r.user_id
    JOIN lapangan l ON l.id=r.lapangan_id
    ORDER BY r.created_at DESC LIMIT 10");
$reservasis = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gelora Sport</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Gelora Sport</h2>
            <p>Admin Panel</p>
        </div>
        <ul class="sidebar-menu">
            <li class="active"><a href="/admin.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
            <li><a href="/manajemen_lapangan.php"><i class="fa-solid fa-layer-group"></i> Manajemen Lapangan</a></li>
            <li><a href="/data_reservasi.php"><i class="fa-solid fa-clipboard-list"></i> Data Reservasi</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="/logout.php" class="logout-btn"><i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar</a>
        </div>
    </aside>
    <main class="main-content">
        <header class="top-header">
            <div class="header-title"><h1>Dashboard Overview</h1></div>
            <div class="admin-profile">
                <span class="admin-name">Halo, <?= e(explode(' ', $user['nama'])[0]) ?></span>
                <div class="avatar"><i class="fa-solid fa-user"></i></div>
            </div>
        </header>

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
                <a href="/data_reservasi.php"><button class="btn-primary">Lihat Semua</button></a>
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
                                    <form method="POST" action="/admin.php" style="display:inline;">
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
