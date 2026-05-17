-- ============================================================
--  gelora_sport.sql
--  Import via: phpMyAdmin atau
--  mysql -u root gelora_sport < gelora_sport.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS gelora_sport CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gelora_sport;

-- ------------------------------------------------------------
-- USERS
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id         INT PRIMARY KEY AUTO_INCREMENT,
    nama       VARCHAR(100)  NOT NULL,
    email      VARCHAR(150)  NOT NULL UNIQUE,
    password   VARCHAR(255)  NOT NULL,
    role       ENUM('user','admin') NOT NULL DEFAULT 'user',
    no_hp      VARCHAR(20),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- LAPANGAN
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS lapangan (
    id            INT PRIMARY KEY AUTO_INCREMENT,
    nama          VARCHAR(100) NOT NULL,
    jenis         ENUM('Futsal','Badminton','Basketball') NOT NULL,
    lokasi        VARCHAR(150) NOT NULL,
    tipe          ENUM('indoor','outdoor') NOT NULL DEFAULT 'indoor',
    material      VARCHAR(100),
    deskripsi     TEXT,
    harga_per_jam DECIMAL(10,0) NOT NULL,
    status        ENUM('Aktif','Perawatan','Nonaktif') NOT NULL DEFAULT 'Aktif',
    foto_url      VARCHAR(500),
    rating        DECIMAL(2,1) DEFAULT 5.0,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- LAYANAN TAMBAHAN
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS layanan_tambahan (
    id          INT PRIMARY KEY AUTO_INCREMENT,
    lapangan_id INT NOT NULL,
    nama        VARCHAR(100) NOT NULL,
    harga       DECIMAL(10,0) NOT NULL DEFAULT 0,
    FOREIGN KEY (lapangan_id) REFERENCES lapangan(id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- ATURAN VENUE
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS aturan_venue (
    id          INT PRIMARY KEY AUTO_INCREMENT,
    lapangan_id INT NOT NULL,
    aturan      VARCHAR(255) NOT NULL,
    urutan      TINYINT DEFAULT 0,
    FOREIGN KEY (lapangan_id) REFERENCES lapangan(id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- SLOT WAKTU
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS slot_waktu (
    id          INT PRIMARY KEY AUTO_INCREMENT,
    lapangan_id INT NOT NULL,
    tanggal     DATE NOT NULL,
    jam_mulai   TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    harga       DECIMAL(10,0) NOT NULL,
    status      ENUM('Tersedia','Dipesan','Ditutup') NOT NULL DEFAULT 'Tersedia',
    UNIQUE KEY uq_slot (lapangan_id, tanggal, jam_mulai),
    FOREIGN KEY (lapangan_id) REFERENCES lapangan(id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- RESERVASI
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS reservasi (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    kode_reservasi VARCHAR(20) NOT NULL UNIQUE,
    user_id        INT NOT NULL,
    lapangan_id    INT NOT NULL,
    slot_waktu_id  INT NOT NULL,
    tanggal        DATE NOT NULL,
    jam_mulai      TIME NOT NULL,
    jam_selesai    TIME NOT NULL,
    total_harga    DECIMAL(10,0) NOT NULL,
    status         ENUM('Menunggu Konfirmasi','Dikonfirmasi','Selesai','Dibatalkan')
                   NOT NULL DEFAULT 'Menunggu Konfirmasi',
    status_bayar   ENUM('Menunggu','Lunas','Gagal') NOT NULL DEFAULT 'Menunggu',
    catatan        TEXT,
    created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)       REFERENCES users(id),
    FOREIGN KEY (lapangan_id)   REFERENCES lapangan(id),
    FOREIGN KEY (slot_waktu_id) REFERENCES slot_waktu(id)
);

-- ============================================================
-- SEED DATA
-- ============================================================

-- Users (password = bcrypt dari "admin123" / "user123")
INSERT INTO users (nama, email, password, role) VALUES
('Admin Gelora',    'admin@gelora.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Budi Santoso',    'budi@email.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('Andi Wijaya',     'andi@email.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Lapangan
INSERT INTO lapangan (nama, jenis, lokasi, tipe, material, deskripsi, harga_per_jam, status, foto_url, rating) VALUES
('Lapangan Futsal A1',    'Futsal',     'Building A - Floor 1', 'indoor',  'Karpet Rumput Sintetis', 'Lapangan futsal indoor dengan material karpet rumput sintetis ukuran standar futsal.', 150000, 'Aktif',     'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSzwzU3_KVkLO1n8bcG6APpPR1XpHDiPhqZ5A&s', 5.0),
('Lapangan Futsal A2',    'Futsal',     'Building A - Floor 1', 'indoor',  'Karpet Rumput Sintetis', 'Lapangan futsal indoor dengan material karpet rumput sintetis.', 150000, 'Aktif',     'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSzwzU3_KVkLO1n8bcG6APpPR1XpHDiPhqZ5A&s', 4.8),
('Lapangan Futsal A3',    'Futsal',     'Building A - Floor 2', 'indoor',  'Karpet Rumput Sintetis', 'Lapangan futsal premium di lantai 2.', 160000, 'Aktif',     'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSzwzU3_KVkLO1n8bcG6APpPR1XpHDiPhqZ5A&s', 4.9),
('Lapangan Badminton B1', 'Badminton',  'Building B - Floor 2', 'indoor',  NULL, 'Lapangan badminton indoor standar dengan lantai kayu.', 80000, 'Aktif',     'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSYAe3t5oFAZMB5TDmZpOaw5Sy1J9c7NsIhRA&s', 5.0),
('Lapangan Badminton B2', 'Badminton',  'Building B - Floor 2', 'indoor',  NULL, 'Lapangan badminton sedang dalam perawatan.', 80000, 'Perawatan', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSYAe3t5oFAZMB5TDmZpOaw5Sy1J9c7NsIhRA&s', 4.7),
('Lapangan Basketball C1','Basketball', 'Building C - Outdoor', 'outdoor', NULL, 'Lapangan basket outdoor dengan permukaan aspal halus dan ring standar.', 120000, 'Aktif', 'https://asset.ayo.co.id/image/venue/170003399361601.image_cropper_246D9558-9BCD-4B7E-BD8B-BC836A3F7C4E-66032-000009DC36E81169_large.jpg', 4.9);

-- Layanan Tambahan
INSERT INTO layanan_tambahan (lapangan_id, nama, harga) VALUES
(1, 'Sewa Sepatu Futsal', 15000),(1, 'Sewa Bola Futsal', 10000),(1, 'Sewa Rompi Tim', 20000),
(2, 'Sewa Sepatu Futsal', 15000),(2, 'Sewa Bola Futsal', 10000),
(3, 'Sewa Sepatu Futsal', 15000),(3, 'Sewa Bola Futsal', 10000),(3, 'Sewa Rompi Tim', 20000),
(4, 'Sewa Raket', 20000),(4, 'Sewa Shuttlecock', 10000),
(6, 'Sewa Bola Basket', 15000);

-- Aturan Venue
INSERT INTO aturan_venue (lapangan_id, aturan, urutan) VALUES
(1,'Dilarang merokok.',1),(1,'Dilarang meludah di area lapangan.',2),(1,'Disarankan menggunakan sepatu khusus Futsal.',3),(1,'Dilarang membuang sampah sembarangan.',4),
(2,'Dilarang merokok.',1),(2,'Disarankan menggunakan sepatu khusus Futsal.',2),
(3,'Dilarang merokok.',1),(3,'Dilarang membuang sampah sembarangan.',2),
(4,'Dilarang merokok.',1),(4,'Dilarang menggunakan sepatu berlumpur.',2),
(6,'Dilarang merokok.',1),(6,'Dilarang parkir di area lapangan.',2);
