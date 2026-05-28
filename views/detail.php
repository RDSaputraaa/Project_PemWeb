<?php
// ============================================================
//  views/detail.php — View detail lapangan & booking
//  Variabel dari Controller: $user, $id, $lap, $layananList,
//    $aturanList, $activeTanggal, $slots, $bookMsg, $bookError, $tanggalList
// ============================================================
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($lap['nama']) ?> - Gelora Sport</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="detail-page">

<div style="max-width:1200px;margin:20px auto;padding:0 20px;">
    <a href="index.php?page=beranda" style="text-decoration:none;color:#007c60;font-weight:bold;">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
    </a>
</div>

<?php if ($bookMsg): ?>
<div style="max-width:1200px;margin:0 auto 16px;padding:0 20px;">
    <div style="padding:12px 18px;background:#e8f5e9;color:#155724;border:1px solid #c3e6cb;border-radius:8px;">
        ✅ <?= $bookMsg ?>
    </div>
</div>
<?php endif; ?>
<?php if ($bookError): ?>
<div style="max-width:1200px;margin:0 auto 16px;padding:0 20px;">
    <div style="padding:12px 18px;background:#fde8e8;color:#c0392b;border:1px solid #f5c6cb;border-radius:8px;">
        ⚠️ <?= e($bookError) ?>
    </div>
</div>
<?php endif; ?>

<div class="booking-container">
    <header class="top-nav">
        <div class="date-scroll">
            <?php foreach ($tanggalList as $t): ?>
            <a href="index.php?page=detail&id=<?= $id ?>&tgl=<?= $t['iso'] ?>"
               class="date-item <?= $t['iso'] === $activeTanggal ? 'active' : '' ?>"
               style="text-decoration:none; color:inherit;">
                <span class="day"><?= $t['label'] ?></span>
                <span class="date"><?= $t['date'] ?></span>
            </a>
            <?php endforeach; ?>
        </div>
        <div class="filter-actions">
            <span style="font-size:13px;color:#888;"><?= formatTanggal($activeTanggal) ?></span>
        </div>
    </header>

    <main class="main-content">
        <section class="image-section">
            <div class="image-wrapper">
                <img src="<?= e($lap['foto_url'] ?? '') ?>" alt="<?= e($lap['nama']) ?>"
                     onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
            </div>
            <div class="card-body" style="padding:16px 0 0;">
                <h2><?= e($lap['nama']) ?></h2>
                <p>⭐ <?= $lap['rating'] ?> · Kota Bandar Lampung, Lampung</p>
            </div>
        </section>
        <section class="details-section">
            <div class="field-info">
                <h1><?= e($lap['nama']) ?></h1>
                <p class="subtitle"><?= e($lap['material'] ? 'Lapangan menggunakan ' . $lap['material'] : ($lap['deskripsi'] ?? '')) ?></p>
                <div class="tags">
                    <span><i class="fa-regular fa-futbol"></i> <?= e($lap['jenis']) ?></span>
                    <span><i class="fa-solid fa-<?= $lap['tipe'] === 'indoor' ? 'person-shelter' : 'sun' ?>"></i> <?= e($lap['tipe']) ?></span>
                    <span><i class="fa-solid fa-money-bill"></i> <?= formatRupiah((int)$lap['harga_per_jam']) ?> / jam</span>
                </div>
            </div>
            <div class="schedule-header">
                <button class="availability-badge" id="toggleSchedule">
                    Jadwal Tersedia <i class="fa-solid fa-chevron-down" id="arrowIcon"></i>
                </button>
            </div>
            <div class="time-slots-grid" id="scheduleBox" style="display:none;">
                <?php if (empty($slots)): ?>
                    <p style="color:#888;grid-column:1/-1;">Tidak ada slot untuk tanggal ini.</p>
                <?php endif; ?>
                <?php foreach ($slots as $s): ?>
                    <?php if ($s['status'] === 'Dipesan'): ?>
                        <div class="slot-card booked">
                            <span class="duration">60 Menit</span>
                            <span class="time"><?= substr($s['jam_mulai'],0,5) ?> - <?= substr($s['jam_selesai'],0,5) ?></span>
                            <span class="status" style="color:#aaa;">Booked</span>
                        </div>
                    <?php else: ?>
                        <a href="index.php?page=checkout&slot_id=<?= $s['id'] ?>" class="slot-card" style="text-decoration:none; color:inherit; display:flex; flex-direction:column; align-items:center;"
                           <?= !$user ? 'onclick="alert(\'Login dulu untuk booking!\'); window.location=\'index.php?page=login\'; return false;"' : '' ?>>
                            <span class="duration">60 Menit</span>
                            <span class="time"><?= substr($s['jam_mulai'],0,5) ?> - <?= substr($s['jam_selesai'],0,5) ?></span>
                            <span class="price"><?= formatRupiah((int)$s['harga']) ?></span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <section class="description-section">
        <h2>Tentang <?= e($lap['nama']) ?></h2>
        <p><?= e($lap['deskripsi'] ?? '') ?></p>
        <?php if ($layananList): ?>
            <h3>Layanan Tambahan</h3>
            <ul><?php foreach ($layananList as $lv): ?><li><?= e($lv) ?></li><?php endforeach; ?></ul>
        <?php endif; ?>
        <?php if ($aturanList): ?>
            <h3>Aturan Venue</h3>
            <ul><?php foreach ($aturanList as $at): ?><li><?= e($at) ?></li><?php endforeach; ?></ul>
        <?php endif; ?>
    </section>
</div>

<script>
const btn=document.getElementById("toggleSchedule"),box=document.getElementById("scheduleBox"),arr=document.getElementById("arrowIcon");
btn.addEventListener("click",()=>{
    const open=box.style.display==="grid";
    box.style.display=open?"none":"grid";
    arr.style.transform=open?"rotate(0deg)":"rotate(180deg)";
});
</script>
</body>
</html>
