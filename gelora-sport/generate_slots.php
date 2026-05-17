<?php
// ============================================================
//  generate_slots.php
//  Jalankan sekali untuk generate slot 7 hari ke depan.
//  Akses: http://localhost/gelora-sport/generate_slots.php
// ============================================================
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helper.php';

requireAdmin();

$db = getDB();
$lapangans = $db->query("SELECT id, harga_per_jam FROM lapangan WHERE status='Aktif'")->fetchAll();

$times = [
    ['08:00','09:00'],['09:00','10:00'],['10:00','11:00'],['11:00','12:00'],
    ['12:00','13:00'],['13:00','14:00'],['14:00','15:00'],['15:00','16:00'],
    ['16:00','17:00'],['17:00','18:00'],['18:00','19:00'],['19:00','20:00'],
    ['20:00','21:00'],['21:00','22:00'],
];

$inserted = 0;
$skipped  = 0;

for ($d = 0; $d < 7; $d++) {
    $tanggal = date('Y-m-d', strtotime("+$d day"));
    foreach ($lapangans as $lap) {
        foreach ($times as [$mulai, $selesai]) {
            try {
                $db->prepare("INSERT IGNORE INTO slot_waktu (lapangan_id, tanggal, jam_mulai, jam_selesai, harga)
                              VALUES (?,?,?,?,?)")
                   ->execute([$lap['id'], $tanggal, $mulai, $selesai, $lap['harga_per_jam']]);
                $inserted++;
            } catch (Exception $e) {
                $skipped++;
            }
        }
    }
}

echo "<h2>✅ Generate Slot Selesai</h2>";
echo "<p>Inserted: <b>$inserted</b> slot</p>";
echo "<p>Skipped (sudah ada): <b>$skipped</b></p>";
echo '<p><a href="/admin.php">← Kembali ke Dashboard</a></p>';
