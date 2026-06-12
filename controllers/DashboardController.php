<?php

require_once __DIR__ . '/../config/helper.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/ReservasiModel.php';

class DashboardController
{
    private UserModel $userModel;
    private ReservasiModel $reservasiModel;
    private PDO $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->reservasiModel = new ReservasiModel();
        $this->db = getDB();
    }

    public function index(): void
    {
        
        $userSession = requireLogin();

        
        $user = $this->userModel->findById($userSession['id']);
        if (!$user) {
            session_destroy();
            redirect('index.php?page=login');
        }

        
        $allReservations = $this->reservasiModel->getByUserId($userSession['id']);

        
        $reservasi_aktif = array_filter($allReservations, function ($r) {
            return $r['status'] === 'Dikonfirmasi';
        });
        $reservasi_aktif = array_slice($reservasi_aktif, 0, 3); 

        
        $reservasi_terbaru = array_slice($allReservations, 0, 5); 

        
        $stats = [
            'total_reservasi' => count($allReservations),
            'reservasi_aktif' => count(array_filter($allReservations, function ($r) {
                return $r['status'] === 'Dikonfirmasi';
            })),
            'reservasi_selesai' => count(array_filter($allReservations, function ($r) {
                return $r['status'] === 'Selesai';
            }))
        ];

        
        foreach ($reservasi_aktif as &$res) {
            $lapanganInfo = $this->db->prepare(
                "SELECT jenis, harga_per_jam as harga FROM lapangan WHERE id = ?"
            );
            $lapanganInfo->execute([$res['lapangan_id']]);
            $info = $lapanganInfo->fetch();
            if ($info) {
                $res['jenis'] = $info['jenis'];
                $res['harga'] = $info['harga'];
            }
        }

        foreach ($reservasi_terbaru as &$res) {
            $lapanganInfo = $this->db->prepare(
                "SELECT jenis, harga_per_jam as harga FROM lapangan WHERE id = ?"
            );
            $lapanganInfo->execute([$res['lapangan_id']]);
            $info = $lapanganInfo->fetch();
            if ($info) {
                $res['jenis'] = $info['jenis'];
                $res['harga'] = $info['harga'];
            }
        }

        require __DIR__ . '/../views/dashboard.php';
    }
}
