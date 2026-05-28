<?php
// ============================================================
//  controllers/LapanganController.php
//  Controller untuk manajemen lapangan (CRUD)
// ============================================================

require_once __DIR__ . '/../config/helper.php';
require_once __DIR__ . '/../models/LapanganModel.php';

class LapanganController
{
    private LapanganModel $lapanganModel;

    public function __construct()
    {
        $this->lapanganModel = new LapanganModel();
    }

    /**
     * Tampilkan daftar lapangan & handle CRUD
     */
    public function index(): void
    {
        // Cek apakah user adalah admin
        $user = requireAdmin();

        $msg = '';

        // Proses form jika POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $act = $_POST['action'] ?? '';

            if ($act === 'delete') {
                $this->delete();
                return;
            } else {
                $this->save();
                return;
            }
        }

        // Ambil pesan dari redirect
        $msg = $_GET['msg'] ?? '';

        // Ambil semua lapangan dari Model
        $lapangans = $this->lapanganModel->getAll();

        // Mapping class CSS
        $jenisClass  = ['Futsal' => 'jenis-futsal', 'Badminton' => 'jenis-badminton', 'Basketball' => 'jenis-basket'];
        $statusClass = ['Aktif' => 'status-aktif', 'Perawatan' => 'status-perawatan', 'Nonaktif' => 'status-perawatan'];

        // Halaman aktif untuk sidebar
        $activePage = 'manajemen_lapangan';

        // Render View
        require __DIR__ . '/../views/admin/manajemen_lapangan.php';
    }

    /**
     * Proses tambah/edit lapangan
     */
    private function save(): void
    {
        $editId = (int)($_POST['edit_id'] ?? 0);
        $data   = [
            'nama'      => trim($_POST['nama'] ?? ''),
            'jenis'     => $_POST['jenis'] ?? '',
            'tipe'      => $_POST['tipe'] ?? 'indoor',
            'lokasi'    => trim($_POST['lokasi'] ?? ''),
            'harga'     => (int)($_POST['harga'] ?? 0),
            'status'    => $_POST['status'] ?? 'Aktif',
            'foto'      => trim($_POST['foto'] ?? ''),
            'deskripsi' => trim($_POST['deskripsi'] ?? ''),
        ];

        // Validasi
        if (!$data['nama'] || !$data['jenis'] || !$data['lokasi'] || !$data['harga']) {
            $msg = 'Nama, jenis, lokasi, dan harga wajib diisi.';
        } elseif ($editId) {
            // Update via Model
            $this->lapanganModel->update($editId, $data);
            $msg = 'Lapangan berhasil diperbarui.';
        } else {
            // Create via Model
            $this->lapanganModel->create($data);
            $msg = 'Lapangan berhasil ditambahkan.';
        }

        redirect('index.php?page=manajemen_lapangan&msg=' . urlencode($msg));
    }

    /**
     * Proses hapus lapangan
     */
    private function delete(): void
    {
        $delId = (int)($_POST['del_id'] ?? 0);
        $this->lapanganModel->delete($delId);
        $msg = 'Lapangan berhasil dihapus.';
        redirect('index.php?page=manajemen_lapangan&msg=' . urlencode($msg));
    }
}
