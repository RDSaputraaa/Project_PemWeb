<?php
// ============================================================
//  models/ReservasiModel.php — Model untuk tabel reservasi
//  Mengelola semua query yang berhubungan dengan reservasi
// ============================================================

require_once __DIR__ . '/../config/db.php';

class ReservasiModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    /**
     * Buat reservasi baru
     */
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO reservasi (kode_reservasi, user_id, lapangan_id, slot_waktu_id, tanggal, jam_mulai, jam_selesai, total_harga)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['kode'],
            $data['user_id'],
            $data['lapangan_id'],
            $data['slot_waktu_id'],
            $data['tanggal'],
            $data['jam_mulai'],
            $data['jam_selesai'],
            $data['total_harga'],
        ]);
    }

    /**
     * Generate kode reservasi unik
     */
    public function generateKode(): string
    {
        return 'GS-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
    }

    /**
     * Ambil semua reservasi (admin) dengan join users & lapangan
     */
    public function getAll(): array
    {
        return $this->db->query(
            "SELECT r.*, u.nama as nama_user, l.nama as nama_lapangan
             FROM reservasi r
             JOIN users u ON u.id = r.user_id
             JOIN lapangan l ON l.id = r.lapangan_id
             ORDER BY r.created_at DESC"
        )->fetchAll();
    }

    /**
     * Ambil reservasi terbaru (admin dashboard)
     */
    public function getRecent(int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            "SELECT r.*, u.nama as nama_user, l.nama as nama_lapangan
             FROM reservasi r
             JOIN users u ON u.id = r.user_id
             JOIN lapangan l ON l.id = r.lapangan_id
             ORDER BY r.created_at DESC
             LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Hitung reservasi hari ini
     */
    public function countToday(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM reservasi WHERE tanggal = ?");
        $stmt->execute([date('Y-m-d')]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Hitung reservasi yang menunggu konfirmasi
     */
    public function countPending(): int
    {
        return (int) $this->db->query(
            "SELECT COUNT(*) FROM reservasi WHERE status = 'Menunggu Konfirmasi'"
        )->fetchColumn();
    }

    /**
     * Hitung total pendapatan bulan ini
     */
    public function pendapatanBulanIni(): int
    {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(total_harga), 0) FROM reservasi
             WHERE status_bayar = 'Lunas' AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?"
        );
        $stmt->execute([date('n'), date('Y')]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Konfirmasi reservasi (set status dikonfirmasi + lunas)
     */
    public function konfirmasi(int $id): bool
    {
        return $this->db->prepare(
            "UPDATE reservasi SET status = 'Dikonfirmasi', status_bayar = 'Lunas' WHERE id = ?"
        )->execute([$id]);
    }

    /**
     * Batalkan reservasi
     */
    public function batalkan(int $id): bool
    {
        return $this->db->prepare(
            "UPDATE reservasi SET status = 'Dibatalkan' WHERE id = ?"
        )->execute([$id]);
    }

    /**
     * Ambil slot_waktu_id dari reservasi
     */
    public function getSlotId(int $reservasiId): ?int
    {
        $stmt = $this->db->prepare("SELECT slot_waktu_id FROM reservasi WHERE id = ?");
        $stmt->execute([$reservasiId]);
        $result = $stmt->fetchColumn();
        return $result ? (int) $result : null;
    }
}
