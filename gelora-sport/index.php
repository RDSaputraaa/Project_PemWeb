<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helper.php';

$user  = currentUser();
$db    = getDB();

$q     = trim($_GET['q'] ?? '');
$jenis = $_GET['jenis'] ?? '';

$sql    = "SELECT * FROM lapangan WHERE status = 'Aktif'";
$params = [];

if ($q) {
    $sql .= " AND nama LIKE :q";
    $params[':q'] = '%' . $q . '%';
}
if ($jenis) {
    $sql .= " AND jenis = :jenis";
    $params[':jenis'] = $jenis;
}
$sql .= " ORDER BY jenis, nama";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$lapangans = $stmt->fetchAll();

$ikon = ['Futsal' => '⚽', 'Badminton' => '🏸', 'Basketball' => '🏀'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelora Sport - Beranda</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">Gelora Sport</div>
        <div class="nav-menu">
            <?php if ($user): ?>
                <span style="color:#007c60; font-weight:600;">Halo, <?= e(explode(' ', $user['nama'])[0]) ?></span>
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="/admin.php" class="login-link">Dashboard</a>
                <?php endif; ?>
                <a href="/logout.php" class="register-btn">Keluar</a>
            <?php else: ?>
                <a href="/login.php" class="login-link">Masuk</a>
                <a href="/register.php" class="register-btn">Daftar</a>
            <?php endif; ?>
        </div>
    </nav>

    <section class="search-section">
        <form method="GET" action="/index.php" style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;width:100%;">
            <input type="text" name="q" value="<?= e($q) ?>" placeholder="Cari nama lapangan..." class="search-bar">
            <select name="jenis" class="search-bar" style="max-width:180px;cursor:pointer;">
                <option value="">Semua Jenis</option>
                <option value="Futsal"     <?= $jenis==='Futsal'     ?'selected':'' ?>>Futsal</option>
                <option value="Badminton"  <?= $jenis==='Badminton'  ?'selected':'' ?>>Badminton</option>
                <option value="Basketball" <?= $jenis==='Basketball' ?'selected':'' ?>>Basketball</option>
            </select>
            <button type="submit" class="search-btn">Cari</button>
        </form>
    </section>

    <main class="catalog">
        <p class="subtitle">Menampilkan <?= count($lapangans) ?> lapangan tersedia</p>
        <div class="card-container">
            <?php if (empty($lapangans)): ?>
                <p style="color:#888;padding:20px;">Tidak ada lapangan ditemukan.</p>
            <?php endif; ?>
            <?php foreach ($lapangans as $l): ?>
            <a href="/detail.php?id=<?= $l['id'] ?>" class="card">
                <img src="<?= e($l['foto_url'] ?? '') ?>" alt="<?= e($l['nama']) ?>"
                     onerror="this.src='https://via.placeholder.com/400x250?text=No+Image'">
                <div class="card-body">
                    <h3><?= e($l['nama']) ?></h3>
                    <p class="info">⭐ <?= $l['rating'] ?> • Bandar Lampung</p>
                    <p class="category"><?= $ikon[$l['jenis']] ?? '🏟' ?> <?= e($l['jenis']) ?></p>
                    <p class="price">Mulai <strong><?= formatRupiah((int)$l['harga_per_jam']) ?></strong> <span>/jam</span></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
