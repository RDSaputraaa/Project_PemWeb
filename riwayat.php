<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pembayaran</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="riwayat-container">
    <h1>Riwayat Pembayaran</h1>
    <?php if(empty($riwayat)): ?>
        <p>Belum ada riwayat pembayaran.</p>
    <?php else: ?>
        <table class="riwayat-table">
            <thead>
                <tr>
                    <th>Lapangan</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($riwayat as $r): ?>
                <tr>
                    <td>
                        <?= e($r['nama_lapangan']) ?>
                    </td>
                    <td>
                        <?= e($r['tanggal']) ?>
                    </td>
                    <td>
                        <?= substr($r['jam_mulai'],0,5) ?>
                        -
                        <?= substr($r['jam_selesai'],0,5) ?>
                    </td>
                    <td>
                        <?= formatRupiah($r['total_harga']) ?>
                    </td>
                    <td>
                        <?= e($r['status']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>