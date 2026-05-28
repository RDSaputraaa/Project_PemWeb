<?php
// ============================================================
//  views/admin/data_reservasi.php — View data reservasi
//  Variabel dari Controller: $user, $reservasis, $statusClass, $activePage
// ============================================================
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
<body class="admin-page">
<div class="admin-container">
    <?php require __DIR__ . '/../components/sidebar.php'; ?>
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
                                    <form method="POST" action="index.php?page=data_reservasi">
                                        <input type="hidden" name="reservasi_id" value="<?= $r['id'] ?>">
                                        <input type="hidden" name="action" value="konfirmasi">
                                        <button class="btn-action" type="submit">Konfirmasi</button>
                                    </form>
                                    <form method="POST" action="index.php?page=data_reservasi" onsubmit="return confirm('Batalkan reservasi ini?')">
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
