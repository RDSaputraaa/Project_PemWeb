<?php
// ============================================================
//  index.php — Front Controller / Router
//  Semua request masuk melalui file ini,
//  lalu diarahkan ke Controller yang sesuai.
//
//  Contoh URL:
//    index.php                        → BerandaController::index()
//    index.php?page=login             → AuthController::login()
//    index.php?page=register          → AuthController::register()
//    index.php?page=detail&id=1       → DetailController::show()
//    index.php?page=admin             → AdminController::dashboard()
//    index.php?page=manajemen_lapangan → LapanganController::index()
//    index.php?page=data_reservasi    → ReservasiController::index()
//    index.php?page=logout            → AuthController::logout()
// ============================================================

// Load konfigurasi dasar
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helper.php';

// Ambil parameter halaman dari URL
$page = $_GET['page'] ?? 'beranda';

// Routing: arahkan ke Controller yang sesuai
switch ($page) {

    // ---- Halaman Publik ----

    case 'beranda':
        require_once __DIR__ . '/controllers/BerandaController.php';
        $controller = new BerandaController();
        $controller->index();
        break;

    case 'login':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;

    case 'register':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->register();
        break;

    case 'logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'detail':
        require_once __DIR__ . '/controllers/DetailController.php';
        $controller = new DetailController();
        $controller->show();
        break;

    // ---- Halaman Admin ----

    case 'admin':
        require_once __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->dashboard();
        break;

    case 'generate_slots':
        require_once __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->generateSlots();
        break;

    case 'manajemen_lapangan':
        require_once __DIR__ . '/controllers/LapanganController.php';
        $controller = new LapanganController();
        $controller->index();
        break;

    case 'data_reservasi':
        require_once __DIR__ . '/controllers/ReservasiController.php';
        $controller = new ReservasiController();
        $controller->index();
        break;

    // ---- Halaman Tidak Ditemukan ----

    default:
        http_response_code(404);
        echo '<h1>404 - Halaman tidak ditemukan</h1>';
        echo '<p><a href="index.php">Kembali ke Beranda</a></p>';
        break;
}
