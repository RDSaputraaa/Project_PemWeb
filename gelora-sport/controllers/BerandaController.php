<?php
// ============================================================
//  controllers/BerandaController.php
//  Controller untuk halaman beranda (katalog lapangan)
// ============================================================

require_once __DIR__ . '/../config/helper.php';
require_once __DIR__ . '/../models/LapanganModel.php';

class BerandaController
{
    private LapanganModel $lapanganModel;

    public function __construct()
    {
        $this->lapanganModel = new LapanganModel();
    }

    /**
     * Tampilkan halaman beranda dengan daftar lapangan
     */
    public function index(): void
    {
        // Ambil data user yang sedang login (jika ada)
        $user = currentUser();

        // Ambil parameter pencarian & filter dari GET
        $q     = trim($_GET['q'] ?? '');
        $jenis = $_GET['jenis'] ?? '';

        // Panggil Model untuk ambil data lapangan
        $lapangans = $this->lapanganModel->getAktif($q, $jenis);

        // Mapping ikon per jenis lapangan
        $ikon = ['Futsal' => '⚽', 'Badminton' => '🏸', 'Basketball' => '🏀'];

        // Render View
        require __DIR__ . '/../views/beranda.php';
    }
}
