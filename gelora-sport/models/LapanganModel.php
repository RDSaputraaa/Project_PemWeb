<?php
// ============================================================
//  models/LapanganModel.php — Model untuk tabel lapangan
//  Termasuk layanan_tambahan dan aturan_venue
// ============================================================

require_once __DIR__ . '/../config/db.php';

class LapanganModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    /**
     * Ambil semua lapangan aktif, dengan filter opsional
     */
    public function getAktif(string $search = '', string $jenis = ''): array
    {
        $sql    = "SELECT * FROM lapangan WHERE status = 'Aktif'";
        $params = [];

        if ($search) {
            $sql .= " AND nama LIKE :q";
            $params[':q'] = '%' . $search . '%';
        }
        if ($jenis) {
            $sql .= " AND jenis = :jenis";
            $params[':jenis'] = $jenis;
        }
        $sql .= " ORDER BY jenis, nama";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Cari lapangan berdasarkan ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM lapangan WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $lap = $stmt->fetch();
        return $lap ?: null;
    }

    /**
     * Ambil semua lapangan (untuk admin)
     */
    public function getAll(): array
    {
        return $this->db->query("SELECT * FROM lapangan ORDER BY jenis, nama")->fetchAll();
    }

    /**
     * Hitung jumlah lapangan aktif
     */
    public function countAktif(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM lapangan WHERE status='Aktif'")->fetchColumn();
    }

    /**
     * Tambah lapangan baru
     */
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO lapangan (nama, jenis, tipe, lokasi, harga_per_jam, status, foto_url, deskripsi)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['nama'],
            $data['jenis'],
            $data['tipe'],
            $data['lokasi'],
            $data['harga'],
            $data['status'],
            $data['foto'] ?: null,
            $data['deskripsi'],
        ]);
    }

    /**
     * Update lapangan berdasarkan ID
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE lapangan SET nama=?, jenis=?, tipe=?, lokasi=?, harga_per_jam=?, status=?, foto_url=?, deskripsi=? WHERE id=?"
        );
        return $stmt->execute([
            $data['nama'],
            $data['jenis'],
            $data['tipe'],
            $data['lokasi'],
            $data['harga'],
            $data['status'],
            $data['foto'] ?: null,
            $data['deskripsi'],
            $id,
        ]);
    }

    /**
     * Hapus lapangan berdasarkan ID
     */
    public function delete(int $id): bool
    {
        return $this->db->prepare("DELETE FROM lapangan WHERE id=?")->execute([$id]);
    }

    /**
     * Ambil layanan tambahan untuk satu lapangan
     */
    public function getLayanan(int $lapanganId): array
    {
        $stmt = $this->db->prepare("SELECT nama FROM layanan_tambahan WHERE lapangan_id = ? ORDER BY id");
        $stmt->execute([$lapanganId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Ambil aturan venue untuk satu lapangan
     */
    public function getAturan(int $lapanganId): array
    {
        $stmt = $this->db->prepare("SELECT aturan FROM aturan_venue WHERE lapangan_id = ? ORDER BY urutan");
        $stmt->execute([$lapanganId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
