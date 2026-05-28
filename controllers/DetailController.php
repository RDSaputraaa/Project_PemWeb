<?php





require_once __DIR__ . '/../config/helper.php';
require_once __DIR__ . '/../models/LapanganModel.php';
require_once __DIR__ . '/../models/SlotWaktuModel.php';
require_once __DIR__ . '/../models/ReservasiModel.php';

class DetailController
{
    private LapanganModel  $lapanganModel;
    private SlotWaktuModel $slotModel;
    private ReservasiModel $reservasiModel;

    public function __construct()
    {
        $this->lapanganModel  = new LapanganModel();
        $this->slotModel      = new SlotWaktuModel();
        $this->reservasiModel = new ReservasiModel();
    }

    
    public function show(): void
    {
        $user = currentUser();
        $id   = (int)($_GET['id'] ?? 0);

        
        $lap = $this->lapanganModel->findById($id);
        if (!$lap) {
            http_response_code(404);
            die('<h2>Lapangan tidak ditemukan.</h2>');
        }

        
        $layananList = $this->lapanganModel->getLayanan($id);
        $aturanList  = $this->lapanganModel->getAturan($id);

        
        $hari         = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
        $bulanSingkat = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $activeTanggal = $_GET['tgl'] ?? date('Y-m-d');

        
        $slots = $this->slotModel->getByLapanganTanggal($id, $activeTanggal);

        $bookMsg   = '';
        $bookError = '';

        
        $tanggalList = [];
        for ($i = 0; $i < 3; $i++) {
            $ts = strtotime("+$i day");
            $tanggalList[] = [
                'label' => $hari[date('w', $ts)],
                'date'  => date('j', $ts) . ' ' . $bulanSingkat[(int)date('n', $ts)],
                'iso'   => date('Y-m-d', $ts),
            ];
        }

        
        require __DIR__ . '/../views/detail.php';
    }
}
