<?php

require_once __DIR__ . '/../models/ReservasiModel.php';
require_once __DIR__ . '/../config/helper.php';

class RiwayatController
{
    private $reservasiModel;

    public function __construct()
    {
        $this->reservasiModel = new ReservasiModel();
    }

    public function index()
    {
        $user = currentUser();

        if (!$user) {
            header('Location: index.php?page=login');
            exit;
        }
        $riwayat = $this->reservasiModel->getByUser($user['id']);
        require __DIR__ . '/../views/riwayat.php';
    }
}