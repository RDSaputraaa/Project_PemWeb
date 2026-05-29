<?php
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
    public function index(): void
    {  
        $user = requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rid = (int)$_POST['reservasi_id'];
            $act = $_POST['action'] ?? '';

            if ($act === 'konfirmasi') {
                $this->reservasiModel->konfirmasi($rid);
            } elseif ($act === 'batal') {
                $this->reservasiModel->batalkan($rid);
                
                $slotId = $this->reservasiModel->getSlotId($rid);
                if ($slotId) {
                    $this->slotModel->setTersedia($slotId);
                }
            }

            redirect('index.php?page=data_reservasi');
        }
        $reservasis = $this->reservasiModel->getAll();
        $statusClass = [
            'Menunggu Konfirmasi' => 'pending',
            'Dikonfirmasi'        => 'confirmed',
            'Selesai'             => 'confirmed',
            'Dibatalkan'          => 'pending',
        ]; 
        $activePage = 'data_reservasi';
        require __DIR__ . '/../views/admin/data_reservasi.php';
    }
}
