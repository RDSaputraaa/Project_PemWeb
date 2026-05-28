<?php
// ============================================================
//  controllers/ReservasiController.php
//  Controller untuk data reservasi (admin)
// ============================================================

require_once __DIR__ . '/../config/helper.php';
require_once __DIR__ . '/../models/ReservasiModel.php';
require_once __DIR__ . '/../models/SlotWaktuModel.php';

class ReservasiController
{
    private ReservasiModel $reservasiModel;
    private SlotWaktuModel $slotModel;

    public function __construct()
    {
        $this->reservasiModel = new ReservasiModel();
        $this->slotModel      = new SlotWaktuModel();
    }

    /**
     * Tampilkan semua data reservasi & handle aksi
     */
    public function index(): void
    {
        // Cek apakah user adalah admin
        $user = requireAdmin();

        // Proses aksi jika POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rid = (int)$_POST['reservasi_id'];
            $act = $_POST['action'] ?? '';

            if ($act === 'konfirmasi') {
                $this->reservasiModel->konfirmasi($rid);
            } elseif ($act === 'batal') {
                $this->reservasiModel->batalkan($rid);
                // Kembalikan slot menjadi tersedia
                $slotId = $this->reservasiModel->getSlotId($rid);
                if ($slotId) {
                    $this->slotModel->setTersedia($slotId);
                }
            }

            redirect('index.php?page=data_reservasi');
        }

        // Ambil semua reservasi dari Model
        $reservasis = $this->reservasiModel->getAll();

        // Mapping class CSS
        $statusClass = [
            'Menunggu Konfirmasi' => 'pending',
            'Dikonfirmasi'        => 'confirmed',
            'Selesai'             => 'confirmed',
            'Dibatalkan'          => 'pending',
        ];

        // Halaman aktif untuk sidebar
        $activePage = 'data_reservasi';

        // Render View
        require __DIR__ . '/../views/admin/data_reservasi.php';
    }
}
