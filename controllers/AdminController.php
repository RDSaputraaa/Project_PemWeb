<?php





require_once __DIR__ . '/../config/helper.php';
require_once __DIR__ . '/../models/LapanganModel.php';
require_once __DIR__ . '/../models/ReservasiModel.php';
require_once __DIR__ . '/../models/SlotWaktuModel.php';

class AdminController
{
    private LapanganModel  $lapanganModel;
    private ReservasiModel $reservasiModel;
    private SlotWaktuModel $slotWaktuModel;

    public function __construct()
    {
        $this->lapanganModel  = new LapanganModel();
        $this->reservasiModel = new ReservasiModel();
        $this->slotWaktuModel = new SlotWaktuModel();
    }

    
    public function dashboard(): void
    {
        
        $user = requireAdmin();

        
        $flashMsg = null;
        if (isset($_SESSION['flash_msg'])) {
            $flashMsg = $_SESSION['flash_msg'];
            unset($_SESSION['flash_msg']);
        }

        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['konfirmasi_id'])) {
            $rid = (int)$_POST['konfirmasi_id'];
            $this->reservasiModel->konfirmasi($rid);
            redirect('index.php?page=admin');
        }

        
        $totalLapangan = $this->lapanganModel->countAktif();
        $todayCount    = $this->reservasiModel->countToday();
        $pending       = $this->reservasiModel->countPending();
        $income        = $this->reservasiModel->pendapatanBulanIni();

        
        $reservasis = $this->reservasiModel->getRecent(10);

        
        $activePage = 'admin';

        
        require __DIR__ . '/../views/admin/dashboard.php';
    }

    
    public function generateSlots(): void
    {
        $user = requireAdmin();

        
        $lapangans = $this->lapanganModel->getAll();
        $activeLapangans = [];
        foreach ($lapangans as $lap) {
            if ($lap['status'] === 'Aktif') {
                $activeLapangans[] = $lap;
            }
        }

        $res = $this->slotWaktuModel->generateUpcomingSlots($activeLapangans);

        
        $_SESSION['flash_msg'] = "Jadwal 7 hari ke depan berhasil diperbarui! Ditambahkan: <strong>" . $res['inserted'] . "</strong> slot baru, Dilewati: <strong>" . $res['skipped'] . "</strong> slot (sudah ada).";

        redirect('index.php?page=admin');
    }
}
