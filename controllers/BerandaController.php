<?php





require_once __DIR__ . '/../config/helper.php';
require_once __DIR__ . '/../models/LapanganModel.php';

class BerandaController
{
    private LapanganModel $lapanganModel;

    public function __construct()
    {
        $this->lapanganModel = new LapanganModel();
    }

    
    public function index(): void
    {
        
        $user = currentUser();

        
        $q     = trim($_GET['q'] ?? '');
        $jenis = $_GET['jenis'] ?? '';

        
        $lapangans = $this->lapanganModel->getAktif($q, $jenis);

        
        $ikon = ['Futsal' => '⚽', 'Badminton' => '🏸', 'Basketball' => '🏀'];

        
        require __DIR__ . '/../views/beranda.php';
    }
}
