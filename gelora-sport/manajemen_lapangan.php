<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helper.php';

$user = requireAdmin();
$db   = getDB();

$msg = ''; $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act     = $_POST['action'] ?? '';
    $editId  = (int)($_POST['edit_id'] ?? 0);
    $nama    = trim($_POST['nama'] ?? '');
    $jenis   = $_POST['jenis'] ?? '';
    $tipe    = $_POST['tipe'] ?? 'indoor';
    $lokasi  = trim($_POST['lokasi'] ?? '');
    $harga   = (int)($_POST['harga'] ?? 0);
    $status  = $_POST['status'] ?? 'Aktif';
    $foto    = trim($_POST['foto'] ?? '');
    $desk    = trim($_POST['deskripsi'] ?? '');

    if ($act === 'delete') {
        $db->prepare("DELETE FROM lapangan WHERE id=?")->execute([(int)$_POST['del_id']]);
        $msg = 'Lapangan berhasil dihapus.';
    } elseif (!$nama || !$jenis || !$lokasi || !$harga) {
        $error = 'Nama, jenis, lokasi, dan harga wajib diisi.';
    } elseif ($editId) {
        $db->prepare("UPDATE lapangan SET nama=?,jenis=?,tipe=?,lokasi=?,harga_per_jam=?,status=?,foto_url=?,deskripsi=? WHERE id=?")
           ->execute([$nama,$jenis,$tipe,$lokasi,$harga,$status,$foto?:null,$desk,$editId]);
        $msg = 'Lapangan berhasil diperbarui.';
    } else {
        $db->prepare("INSERT INTO lapangan (nama,jenis,tipe,lokasi,harga_per_jam,status,foto_url,deskripsi) VALUES (?,?,?,?,?,?,?,?)")
           ->execute([$nama,$jenis,$tipe,$lokasi,$harga,$status,$foto?:null,$desk]);
        $msg = 'Lapangan berhasil ditambahkan.';
    }
    redirect('manajemen_lapangan.php?msg=' . urlencode($msg ?: $error));
}

$msg = $_GET['msg'] ?? '';
$lapangans = $db->query("SELECT * FROM lapangan ORDER BY jenis, nama")->fetchAll();
$jenisClass  = ['Futsal'=>'jenis-futsal','Badminton'=>'jenis-badminton','Basketball'=>'jenis-basket'];
$statusClass = ['Aktif'=>'status-aktif','Perawatan'=>'status-perawatan','Nonaktif'=>'status-perawatan'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Lapangan - Gelora Sport</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
    <aside class="sidebar">
        <div class="sidebar-header"><h2>Gelora Sport</h2><p>Admin Panel</p></div>
        <ul class="sidebar-menu">
            <li><a href="admin.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
            <li class="active"><a href="manajemen_lapangan.php"><i class="fa-solid fa-layer-group"></i> Manajemen Lapangan</a></li>
            <li><a href="data_reservasi.php"><i class="fa-solid fa-clipboard-list"></i> Data Reservasi</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="logout.php" class="logout-btn"><i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar</a>
        </div>
    </aside>
    <main class="main-content">
        <header class="top-header">
            <div class="header-title">
                <h1>Manajemen Lapangan</h1>
                <p>Kelola semua lapangan olahraga Anda</p>
            </div>
            <button class="btn-primary" onclick="bukaModal()">
                <i class="fa-solid fa-plus"></i> Tambah Lapangan
            </button>
        </header>

        <?php if ($msg): ?>
            <div style="padding:10px 16px;background:#e8f5e9;color:#155724;border:1px solid #c3e6cb;border-radius:8px;margin-bottom:20px;">
                <?= e($msg) ?>
            </div>
        <?php endif; ?>

        <section class="recent-reservations">
            <div class="section-header">
                <h2>Daftar Lapangan</h2>
                <span style="color:#888;font-size:14px;"><?= count($lapangans) ?> lapangan terdaftar</span>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr><th>NAMA LAPANGAN</th><th>JENIS</th><th>LOKASI</th><th>STATUS</th><th>HARGA/JAM</th><th>AKSI</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lapangans)): ?>
                            <tr><td colspan="6" style="text-align:center;color:#888;padding:30px;">Belum ada lapangan.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($lapangans as $l): ?>
                        <tr>
                            <td class="fw-bold"><?= e($l['nama']) ?></td>
                            <td><span class="badge <?= $jenisClass[$l['jenis']] ?? '' ?>"><?= e($l['jenis']) ?></span></td>
                            <td class="text-gray"><?= e($l['lokasi']) ?></td>
                            <td><span class="badge <?= $statusClass[$l['status']] ?? '' ?>"><?= e($l['status']) ?></span></td>
                            <td class="fw-bold"><?= formatRupiah((int)$l['harga_per_jam']) ?></td>
                            <td class="action-icons">
                                <button class="btn-icon edit" onclick='bukaEdit(<?= json_encode($l) ?>)' title="Edit">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <form method="POST" action="manajemen_lapangan.php" style="display:inline;"
                                      onsubmit="return confirm('Hapus lapangan <?= e($l['nama']) ?>?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="del_id" value="<?= $l['id'] ?>">
                                    <button type="submit" class="btn-icon delete" title="Hapus">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<div id="modalOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;padding:32px;max-width:500px;width:90%;max-height:90vh;overflow-y:auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 id="modalTitle">Tambah Lapangan</h3>
            <button onclick="tutupModal()" style="background:none;border:none;font-size:20px;cursor:pointer;color:#888;">✕</button>
        </div>
        <form method="POST" action="manajemen_lapangan.php">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="edit_id" id="editId">
            <div style="margin-bottom:14px;">
                <label style="display:block;font-weight:600;margin-bottom:5px;font-size:13px;">Nama Lapangan *</label>
                <input id="fNama" name="nama" type="text" required placeholder="Lapangan Futsal A1"
                       style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:8px;font-size:14px;outline:none;">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                <div>
                    <label style="display:block;font-weight:600;margin-bottom:5px;font-size:13px;">Jenis *</label>
                    <select id="fJenis" name="jenis" required style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:8px;font-size:14px;outline:none;">
                        <option value="">Pilih</option>
                        <option value="Futsal">Futsal</option>
                        <option value="Badminton">Badminton</option>
                        <option value="Basketball">Basketball</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-weight:600;margin-bottom:5px;font-size:13px;">Tipe</label>
                    <select id="fTipe" name="tipe" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:8px;font-size:14px;outline:none;">
                        <option value="indoor">Indoor</option>
                        <option value="outdoor">Outdoor</option>
                    </select>
                </div>
            </div>
            <div style="margin-bottom:14px;">
                <label style="display:block;font-weight:600;margin-bottom:5px;font-size:13px;">Lokasi *</label>
                <input id="fLokasi" name="lokasi" type="text" required placeholder="Building A - Floor 1"
                       style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:8px;font-size:14px;outline:none;">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                <div>
                    <label style="display:block;font-weight:600;margin-bottom:5px;font-size:13px;">Harga/Jam (Rp) *</label>
                    <input id="fHarga" name="harga" type="number" required min="0" placeholder="150000"
                           style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:8px;font-size:14px;outline:none;">
                </div>
                <div>
                    <label style="display:block;font-weight:600;margin-bottom:5px;font-size:13px;">Status</label>
                    <select id="fStatus" name="status" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:8px;font-size:14px;outline:none;">
                        <option value="Aktif">Aktif</option>
                        <option value="Perawatan">Perawatan</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div style="margin-bottom:14px;">
                <label style="display:block;font-weight:600;margin-bottom:5px;font-size:13px;">URL Foto</label>
                <input id="fFoto" name="foto" type="url" placeholder="https://..."
                       style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:8px;font-size:14px;outline:none;">
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block;font-weight:600;margin-bottom:5px;font-size:13px;">Deskripsi</label>
                <textarea id="fDesk" name="deskripsi" rows="3" placeholder="Deskripsi lapangan..."
                          style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:8px;font-size:14px;outline:none;resize:vertical;"></textarea>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="button" onclick="tutupModal()" style="flex:1;padding:12px;border:1px solid #ddd;border-radius:8px;background:#fff;cursor:pointer;font-weight:600;">Batal</button>
                <button type="submit" style="flex:1;padding:12px;border:none;border-radius:8px;background:#007c60;color:#fff;cursor:pointer;font-weight:700;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function bukaModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Lapangan';
    document.getElementById('editId').value = '';
    ['fNama','fLokasi','fHarga','fFoto','fDesk'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('fJenis').value = '';
    document.getElementById('fTipe').value = 'indoor';
    document.getElementById('fStatus').value = 'Aktif';
    document.getElementById('modalOverlay').style.display = 'flex';
}
function bukaEdit(l) {
    document.getElementById('modalTitle').textContent = 'Edit Lapangan';
    document.getElementById('editId').value  = l.id;
    document.getElementById('fNama').value   = l.nama;
    document.getElementById('fJenis').value  = l.jenis;
    document.getElementById('fTipe').value   = l.tipe;
    document.getElementById('fLokasi').value = l.lokasi;
    document.getElementById('fHarga').value  = l.harga_per_jam;
    document.getElementById('fStatus').value = l.status;
    document.getElementById('fFoto').value   = l.foto_url || '';
    document.getElementById('fDesk').value   = l.deskripsi || '';
    document.getElementById('modalOverlay').style.display = 'flex';
}
function tutupModal() {
    document.getElementById('modalOverlay').style.display = 'none';
}
</script>
</body>
</html>
