<?php




?>
<aside class="sidebar">
    <div class="sidebar-header">
        <h2>Gelora Sport</h2>
        <p>Admin Panel</p>
    </div>
    <ul class="sidebar-menu">
        <li class="<?= ($activePage ?? '') === 'admin' ? 'active' : '' ?>">
            <a href="index.php?page=admin"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
        </li>
        <li class="<?= ($activePage ?? '') === 'manajemen_lapangan' ? 'active' : '' ?>">
            <a href="index.php?page=manajemen_lapangan"><i class="fa-solid fa-layer-group"></i> Manajemen Lapangan</a>
        </li>
        <li class="<?= ($activePage ?? '') === 'data_reservasi' ? 'active' : '' ?>">
            <a href="index.php?page=data_reservasi"><i class="fa-solid fa-clipboard-list"></i> Data Reservasi</a>
        </li>
    </ul>
    <div class="sidebar-footer">
        <a href="index.php?page=logout" class="logout-btn"><i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar</a>
    </div>
</aside>
