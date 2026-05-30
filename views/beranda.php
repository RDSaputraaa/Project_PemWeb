<?php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelora Sport - Beranda</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">Gelora Sport</div>
        <div class="nav-menu">
            <?php if ($user): ?>
                <span style="color:#007c60; font-weight:600;">Halo, <?= e(explode(' ', $user['nama'])[0]) ?></span>
                <a href="index.php?page=dashboard" class="login-link"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                <a href="index.php?page=profile" class="login-link"><i class="fa-regular fa-user"></i> Profil</a>
                <a href="index.php?page=riwayat" class="login-link"><i class="fa-solid fa-history"></i> Riwayat</a>
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="index.php?page=admin" class="login-link"><i class="fa-solid fa-shield"></i> Admin</a>
                <?php endif; ?>
                <a href="index.php?page=logout" class="register-btn">Keluar</a>
            <?php else: ?>
                <a href="index.php?page=login" class="login-link">Masuk</a>
                <a href="index.php?page=register" class="register-btn">Daftar</a>
            <?php endif; ?>
        </div>
    </nav>

    <?php if (!empty($_GET['msg'])): ?>
    <div style="max-width:1200px;margin:20px auto -10px;padding:0 20px;">
        <div style="padding:12px 18px;background:#e8f5e9;color:#155724;border:1px solid #c3e6cb;border-radius:8px;text-align:center;font-weight:500;">
            <?= e($_GET['msg']) ?>
        </div>
    </div>
    <?php endif; ?>


    <section class="search-section">
        <form method="GET" action="index.php" style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;width:100%;">
            <input type="hidden" name="page" value="beranda">
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
            <a href="index.php?page=detail&id=<?= $l['id'] ?>" class="card">
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
