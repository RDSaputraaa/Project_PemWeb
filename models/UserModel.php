<?php





require_once __DIR__ . '/../config/db.php';

class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    
    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return (bool) $stmt->fetch();
    }

    
    public function create(string $nama, string $email, string $password): bool
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            "INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'user')"
        );
        return $stmt->execute([$nama, $email, $hash]);
    }

    
    public function emailExistsForOther(string $email, int $currentUserId): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $currentUserId]);
        return (bool) $stmt->fetch();
    }

    
    public function update(int $id, array $data): bool
    {
        if (!empty($data['password'])) {
            $hash = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt = $this->db->prepare(
                "UPDATE users SET nama = ?, email = ?, no_hp = ?, password = ? WHERE id = ?"
            );
            return $stmt->execute([$data['nama'], $data['email'], $data['no_hp'], $hash, $id]);
        } else {
            $stmt = $this->db->prepare(
                "UPDATE users SET nama = ?, email = ?, no_hp = ? WHERE id = ?"
            );
            return $stmt->execute([$data['nama'], $data['email'], $data['no_hp'], $id]);
        }
    }

    
    public function delete(int $id): bool
    {
        $this->db->beginTransaction();
        try {
            
            $stmt = $this->db->prepare("SELECT slot_waktu_id FROM reservasi WHERE user_id = ?");
            $stmt->execute([$id]);
            $slots = $stmt->fetchAll(PDO::FETCH_COLUMN);

            
            if (!empty($slots)) {
                $inQuery = implode(',', array_fill(0, count($slots), '?'));
                $stmt = $this->db->prepare("UPDATE slot_waktu SET status = 'Tersedia' WHERE id IN ($inQuery)");
                $stmt->execute($slots);
            }

            
            $stmt = $this->db->prepare("DELETE FROM reservasi WHERE user_id = ?");
            $stmt->execute([$id]);

            
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);

            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            return false;
        }
    }
}

