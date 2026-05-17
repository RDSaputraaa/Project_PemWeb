<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helper.php';

$user = currentUser();
$db   = getDB();
$id   = (int)($_GET['id'] ?? 0);

$stmt = $db->prepare("SELECT * FROM lapangan WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$lap = $stmt->fetch();
if (!$lap) { http_response_code(404); die('<h2>Lapangan tidak ditemukan.</h2>'); }

$layanan = $db->prepare("SELECT nama FROM layanan_tambahan WHERE lapangan_id = ? ORDER BY id");
$layanan->execute([$id]); $layananList = $layanan->fetchAll(PDO::FETCH_COLUMN);

$aturan = $db->prepare("SELECT aturan FROM aturan_venue WHERE lapangan_id = ? ORDER BY urutan");
$aturan->execute([$id]); $aturanList = $aturan->fetchAll(PDO::FETCH_COLUMN);

$hari = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
$bulanSingkat = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
$activeTanggal = $_GET['tgl'] ?? date('Y-m-d');

$stmt = $db->prepare("SELECT * FROM slot_waktu WHERE lapangan_id = ? AND tanggal = ? ORDER BY jam_mulai");
$stmt->execute([$id, $activeTanggal]);
$slots = $stmt->fetchAll();

$bookMsg = ''; $bookError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['slot_id'])) {
    if (!$user) { redirect('/login.php'); }
    $slotId = (int)$_POST['slot_id'];

    $cek = $db->prepare("SELECT * FROM slot_waktu WHERE id = ? AND status = 'Tersedia' LIMIT 1");
    $cek->execute([$slotId]);
    $slot = $cek->fetch();

    if (!$slot) {
        $bookError = 'Slot sudah tidak tersedia.';
    } else {
        $kode = 'GS-' . date('Ymd') . '-' . str_pad(rand(1,999), 3, '0', STR_PAD_LEFT);
        $db->prepare("INSERT INTO reservasi (kode_reservasi, user_id, lapangan_id, slot_waktu_id, tanggal, jam_mulai, jam_selesai, total_harga)
                      VALUES (?,?,?,?,?,?,?,?)")
           ->execute([$kode, $user['id'], $id, $slotId, $slot['tanggal'],
                      $slot['jam_mulai'], $slot['jam_selesai'], $slot['harga']]);
        $db->prepare("UPDATE slot_waktu SET status = 'Dipesan' WHERE id = ?")
           ->execute([$slotId]);
        $bookMsg = "Booking berhasil! Kode: <strong>$kode</strong>";

        $stmt->execute([$id, $activeTanggal]);
        $slots = $stmt->fetchAll();
    }
}

function tglLabel(int $addDays): array {
    global $hari, $bulanSingkat;
    $ts = strtotime("+$addDays day");
    return [
        'label' => $hari[date('w', $ts)],
        'date'  => date('j', $ts) . ' ' . $bulanSingkat[(int)date('n', $ts)],
        'iso'   => date('Y-m-d', $ts),
    ];
}
$tanggalList = [tglLabel(0), tglLabel(1), tglLabel(2)];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($lap['nama']) ?> - Gelora Sport</title>
    <link rel="stylesheet" href="assets/css/detail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div style="max-width:1200px;margin:20px auto;padding:0 20px;">
    <a href="/index.php" style="text-decoration:none;color:#007c60;font-weight:bold;">
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
            <a href="/detail.php?id=<?= $id ?>&tgl=<?= $t['iso'] ?>"
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
                        <form method="POST" action="/detail.php?id=<?= $id ?>&tgl=<?= $activeTanggal ?>" style="display:contents;">
                            <input type="hidden" name="slot_id" value="<?= $s['id'] ?>">
                            <button type="submit" class="slot-card" style="background:none;border:1px solid #eaeaea;cursor:pointer;text-align:center;"
                                    <?= !$user ? 'onclick="return confirm(\'Login dulu untuk booking!\'); window.location=\'/login.php\'; return false;"' : '' ?>>
                                <span class="duration">60 Menit</span>
                                <span class="time"><?= substr($s['jam_mulai'],0,5) ?> - <?= substr($s['jam_selesai'],0,5) ?></span>
                                <span class="price"><?= formatRupiah((int)$s['harga']) ?></span>
                            </button>
                        </form>
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
