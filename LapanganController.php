<?php





require_once __DIR__ . '/../config/helper.php';
require_once __DIR__ . '/../models/LapanganModel.php';

class LapanganController
{
    private LapanganModel $lapanganModel;

    public function __construct()
    {
        $this->lapanganModel = new LapanganModel();
    }

    
    public function index(): void
    {
        
        $user = requireAdmin();

        $msg = '';

        
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

        
        $msg = $_GET['msg'] ?? '';

        
        $lapangans = $this->lapanganModel->getAll();

        
        $jenisClass  = ['Futsal' => 'jenis-futsal', 'Badminton' => 'jenis-badminton', 'Basketball' => 'jenis-basket'];
        $statusClass = ['Aktif' => 'status-aktif', 'Perawatan' => 'status-perawatan', 'Nonaktif' => 'status-perawatan'];

        
        $activePage = 'manajemen_lapangan';

        
        require __DIR__ . '/../views/admin/manajemen_lapangan.php';
    }

    
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

        
        if (!$data['nama'] || !$data['jenis'] || !$data['lokasi'] || !$data['harga']) {
            $msg = 'Nama, jenis, lokasi, dan harga wajib diisi.';
        } elseif ($editId) {
            
            $this->lapanganModel->update($editId, $data);
            $msg = 'Lapangan berhasil diperbarui.';
        } else {
            
            $this->lapanganModel->create($data);
            $msg = 'Lapangan berhasil ditambahkan.';
        }

        redirect('index.php?page=manajemen_lapangan&msg=' . urlencode($msg));
    }

    
    private function delete(): void
    {
        $delId = (int)($_POST['del_id'] ?? 0);
        $this->lapanganModel->delete($delId);
        $msg = 'Lapangan berhasil dihapus.';
        redirect('index.php?page=manajemen_lapangan&msg=' . urlencode($msg));
    }
}
