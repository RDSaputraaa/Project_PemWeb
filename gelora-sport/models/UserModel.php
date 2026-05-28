<?php
// ============================================================
//  models/UserModel.php — Model untuk tabel users
//  Mengelola semua query yang berhubungan dengan data user
// ============================================================

require_once __DIR__ . '/../config/db.php';

class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    /**
     * Cari user berdasarkan email
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Cari user berdasarkan ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Cek apakah email sudah terdaftar
     */
    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return (bool) $stmt->fetch();
    }

    /**
     * Buat user baru (role default: 'user')
     */
    public function create(string $nama, string $email, string $password): bool
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            "INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'user')"
        );
        return $stmt->execute([$nama, $email, $hash]);
    }
}
