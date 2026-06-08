<?php
require_once __DIR__ . '/../config/db.php';

class ReservasiModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO reservasi (kode_reservasi, user_id, lapangan_id, slot_waktu_id, tanggal, jam_mulai, jam_selesai, total_harga, catatan)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['kode'],
            $data['user_id'],
            $data['lapangan_id'],
            $data['slot_waktu_id'],
            $data['tanggal'],
            $data['jam_mulai'],
            $data['jam_selesai'],
            $data['total_harga'],
            $data['catatan'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function generateKode(): string
    {
        return 'GS-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
    }

    public function updateBuktiBayar(int $id, string $path): bool
    {
        $sql = "UPDATE reservasi 
                SET bukti_bayar = ?
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$path, $id]);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT r.*, l.nama as nama_lapangan, u.nama as nama_user, u.email as email_user
             FROM reservasi r
             JOIN lapangan l ON l.id = r.lapangan_id
             JOIN users u ON u.id = r.user_id
             WHERE r.id = ?"
        );
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ? $res : null;
    }

    public function getByUserId(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT r.*, l.nama as nama_lapangan, l.foto_url
             FROM reservasi r
             JOIN lapangan l ON l.id = r.lapangan_id
             WHERE r.user_id = ?
             ORDER BY r.created_at DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

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

    public function countToday(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM reservasi WHERE tanggal = ?");
        $stmt->execute([date('Y-m-d')]);
        return (int) $stmt->fetchColumn();
    }

    public function countPending(): int
    {
        return (int) $this->db->query(
            "SELECT COUNT(*) FROM reservasi WHERE status = 'Menunggu Konfirmasi'"
        )->fetchColumn();
    }

    public function pendapatanBulanIni(): int
    {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(total_harga), 0) FROM reservasi
             WHERE status_bayar = 'Lunas' AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?"
        );
        $stmt->execute([date('n'), date('Y')]);
        return (int) $stmt->fetchColumn();
    }

    public function konfirmasi(int $id): bool
    {
        return $this->db->prepare(
            "UPDATE reservasi SET status = 'Dikonfirmasi', status_bayar = 'Lunas' WHERE id = ?"
        )->execute([$id]);
    }

    public function batalkan(int $id): bool
    {
        return $this->db->prepare(
            "UPDATE reservasi SET status = 'Dibatalkan' WHERE id = ?"
        )->execute([$id]);
    }

    public function getSlotId(int $reservasiId): ?int
    {
        $stmt = $this->db->prepare("SELECT slot_waktu_id FROM reservasi WHERE id = ?");
        $stmt->execute([$reservasiId]);
        $result = $stmt->fetchColumn();
        return $result ? (int) $result : null;
    }

    public function getByUser(int $userId): array
    {
        $sql = "
            SELECT 
                r.*,
                l.nama AS nama_lapangan,
                s.jam_mulai,
                s.jam_selesai,
                s.tanggal
            FROM reservasi r
            JOIN slot_waktu s ON r.slot_waktu_id = s.id
            JOIN lapangan l ON s.lapangan_id = l.id
            WHERE r.user_id = ?
            ORDER BY r.created_at DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
