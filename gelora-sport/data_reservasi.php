<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helper.php';

$user = requireAdmin();
$db   = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rid = (int)$_POST['reservasi_id'];
    $act = $_POST['action'] ?? '';
    if ($act === 'konfirmasi') {
        $db->prepare("UPDATE reservasi SET status='Dikonfirmasi', status_bayar='Lunas' WHERE id=?")->execute([$rid]);
    } elseif ($act === 'batal') {
        $db->prepare("UPDATE reservasi SET status='Dibatalkan' WHERE id=?")->execute([$rid]);
        $slotId = $db->prepare("SELECT slot_waktu_id FROM reservasi WHERE id=?");
        $slotId->execute([$rid]);
        $s = $slotId->fetchColumn();
        if ($s) $db->prepare("UPDATE slot_waktu SET status='Tersedia' WHERE id=?")->execute([$s]);
    }
    redirect('/data_reservasi.php');
}

$stmt = $db->query("SELECT r.*, u.nama as nama_user, l.nama as nama_lapangan
    FROM reservasi r
    JOIN users u ON u.id=r.user_id
    JOIN lapangan l ON l.id=r.lapangan_id
    ORDER BY r.created_at DESC");
$reservasis = $stmt->fetchAll();

$statusClass = [
    'Menunggu Konfirmasi' => 'pending',
    'Dikonfirmasi'        => 'confirmed',
    'Selesai'             => 'confirmed',
    'Dibatalkan'          => 'pending',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Reservasi - Gelora Sport</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
    <aside class="sidebar">
        <div class="sidebar-header"><h2>Gelora Sport</h2><p>Admin Panel</p></div>
        <ul class="sidebar-menu">
            <li><a href="admin.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
            <li><a href="manajemen_lapangan.php"><i class="fa-solid fa-layer-group"></i> Manajemen Lapangan</a></li>
            <li class="active"><a href="data_reservasi.php"><i class="fa-solid fa-clipboard-list"></i> Data Reservasi</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="logout.php" class="logout-btn"><i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar</a>
        </div>
    </aside>
    <main class="main-content">
        <header class="top-header">
            <div class="header-title">
                <h1>Data Reservasi</h1>
                <p>Semua reservasi pelanggan</p>
            </div>
        </header>
        <section class="recent-reservations">
            <div class="section-header">
                <h2>Semua Reservasi</h2>
                <span style="color:#888;font-size:14px;"><?= count($reservasis) ?> total reservasi</span>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr><th>Kode</th><th>Pelanggan</th><th>Lapangan</th><th>Tanggal</th><th>Waktu</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reservasis)): ?>
                            <tr><td colspan="8" style="text-align:center;color:#888;padding:30px;">Belum ada reservasi.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($reservasis as $r): ?>
                        <tr>
                            <td style="font-size:12px;color:#888;"><?= e($r['kode_reservasi']) ?></td>
                            <td><?= e($r['nama_user']) ?></td>
                            <td><?= e($r['nama_lapangan']) ?></td>
                            <td><?= formatTanggal($r['tanggal']) ?></td>
                            <td><?= substr($r['jam_mulai'],0,5) ?> - <?= substr($r['jam_selesai'],0,5) ?></td>
                            <td class="fw-bold"><?= formatRupiah((int)$r['total_harga']) ?></td>
                            <td><span class="badge <?= $statusClass[$r['status']] ?? 'pending' ?>">
                                <?= e($r['status']) ?>
                            </span></td>
                            <td style="display:flex;gap:6px;">
                                <?php if ($r['status'] === 'Menunggu Konfirmasi'): ?>
                                    <form method="POST">
                                        <input type="hidden" name="reservasi_id" value="<?= $r['id'] ?>">
                                        <input type="hidden" name="action" value="konfirmasi">
                                        <button class="btn-action" type="submit">Konfirmasi</button>
                                    </form>
                                    <form method="POST" onsubmit="return confirm('Batalkan reservasi ini?')">
                                        <input type="hidden" name="reservasi_id" value="<?= $r['id'] ?>">
                                        <input type="hidden" name="action" value="batal">
                                        <button class="btn-action" type="submit" style="background:#dc3545;">Batalkan</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn-action disabled" disabled><?= e($r['status']) ?></button>
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
