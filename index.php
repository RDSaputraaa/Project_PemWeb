<?php

















require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helper.php';


$page = $_GET['page'] ?? 'beranda';


switch ($page) {

    

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

    case 'profile':
        require_once __DIR__ . '/controllers/ProfileController.php';
        $controller = new ProfileController();
        $controller->show();
        break;

    case 'checkout':
        require_once __DIR__ . '/controllers/CheckoutController.php';
        $controller = new CheckoutController();
        $controller->index();
        break;

    case 'payment':
        require_once __DIR__ . '/controllers/CheckoutController.php';
        $controller = new CheckoutController();
        $controller->payment();
        break;

    


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

    

    default:
        http_response_code(404);
        echo '<h1>404 - Halaman tidak ditemukan</h1>';
        echo '<p><a href="index.php">Kembali ke Beranda</a></p>';
        break;
}
