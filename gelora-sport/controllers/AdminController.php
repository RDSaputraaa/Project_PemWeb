<?php
// ============================================================
//  controllers/AdminController.php
//  Controller untuk halaman dashboard admin
// ============================================================

require_once __DIR__ . '/../config/helper.php';
require_once __DIR__ . '/../models/LapanganModel.php';
require_once __DIR__ . '/../models/ReservasiModel.php';

class AdminController
{
    private LapanganModel  $lapanganModel;
    private ReservasiModel $reservasiModel;

    public function __construct()
    {
        $this->lapanganModel  = new LapanganModel();
        $this->reservasiModel = new ReservasiModel();
    }

    /**
     * Tampilkan dashboard admin
     */
    public function dashboard(): void
    {
        // Cek apakah user adalah admin
        $user = requireAdmin();

        // Proses konfirmasi reservasi via POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['konfirmasi_id'])) {
            $rid = (int)$_POST['konfirmasi_id'];
            $this->reservasiModel->konfirmasi($rid);
            redirect('index.php?page=admin');
        }

        // Ambil data metrik dari Model
        $totalLapangan = $this->lapanganModel->countAktif();
        $todayCount    = $this->reservasiModel->countToday();
        $pending       = $this->reservasiModel->countPending();
        $income        = $this->reservasiModel->pendapatanBulanIni();

        // Ambil reservasi terbaru dari Model
        $reservasis = $this->reservasiModel->getRecent(10);

        // Halaman aktif untuk sidebar
        $activePage = 'admin';

        // Render View
        require __DIR__ . '/../views/admin/dashboard.php';
    }
}
