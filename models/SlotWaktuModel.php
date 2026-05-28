<?php
// ============================================================
//  models/SlotWaktuModel.php — Model untuk tabel slot_waktu
//  Mengelola query slot waktu lapangan
// ============================================================

require_once __DIR__ . '/../config/db.php';

class SlotWaktuModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    /**
     * Ambil semua slot untuk satu lapangan pada tanggal tertentu
     */
    public function getByLapanganTanggal(int $lapanganId, string $tanggal): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM slot_waktu WHERE lapangan_id = ? AND tanggal = ? ORDER BY jam_mulai"
        );
        $stmt->execute([$lapanganId, $tanggal]);
        return $stmt->fetchAll();
    }

    /**
     * Cari slot yang tersedia berdasarkan ID
     */
    public function findAvailable(int $slotId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM slot_waktu WHERE id = ? AND status = 'Tersedia' LIMIT 1");
        $stmt->execute([$slotId]);
        $slot = $stmt->fetch();
        return $slot ?: null;
    }

    /**
     * Set status slot menjadi 'Dipesan'
     */
    public function setDipesan(int $slotId): bool
    {
        return $this->db->prepare("UPDATE slot_waktu SET status = 'Dipesan' WHERE id = ?")->execute([$slotId]);
    }

    /**
     * Set status slot kembali menjadi 'Tersedia'
     */
    public function setTersedia(int $slotId): bool
    {
        return $this->db->prepare("UPDATE slot_waktu SET status = 'Tersedia' WHERE id = ?")->execute([$slotId]);
    }

    /**
     * Generate slot waktu 7 hari ke depan untuk semua lapangan aktif
     */
    public function generateUpcomingSlots(array $lapangans): array
    {
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
                        $stmt = $this->db->prepare(
                            "INSERT IGNORE INTO slot_waktu (lapangan_id, tanggal, jam_mulai, jam_selesai, harga)
                             VALUES (?,?,?,?,?)"
                        );
                        $stmt->execute([$lap['id'], $tanggal, $mulai, $selesai, $lap['harga_per_jam']]);
                        if ($stmt->rowCount() > 0) {
                            $inserted++;
                        } else {
                            $skipped++;
                        }
                    } catch (Exception $e) {
                        $skipped++;
                    }
                }
            }
        }

        return ['inserted' => $inserted, 'skipped' => $skipped];
    }
}
