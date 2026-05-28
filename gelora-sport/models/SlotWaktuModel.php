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
}
