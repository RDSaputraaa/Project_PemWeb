<?php





require_once __DIR__ . '/../config/helper.php';
require_once __DIR__ . '/../models/UserModel.php';

class AuthController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    
    public function login(): void
    {
        
        if (currentUser()) {
            redirect('index.php?page=' . (isAdmin() ? 'admin' : 'beranda'));
        }

        $error = '';

        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!$email || !$password) {
                $error = 'Email dan password wajib diisi.';
            } else {
                
                $user = $this->userModel->findByEmail($email);

                if ($user && password_verify($password, $user['password'])) {
                    
                    $_SESSION['user'] = [
                        'id'    => $user['id'],
                        'nama'  => $user['nama'],
                        'email' => $user['email'],
                        'role'  => $user['role'],
                    ];
                    redirect('index.php?page=' . ($user['role'] === 'admin' ? 'admin' : 'beranda'));
                } else {
                    $error = 'Email atau password salah.';
                }
            }
        }

        
        require __DIR__ . '/../views/login.php';
    }

    
    public function register(): void
    {
        
        if (currentUser()) {
            redirect('index.php?page=beranda');
        }

        $error   = '';
        $success = '';

        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama       = trim($_POST['nama'] ?? '');
            $email      = trim($_POST['email'] ?? '');
            $password   = $_POST['password'] ?? '';
            $konfirmasi = $_POST['konfirmasi'] ?? '';

            if (!$nama || !$email || !$password) {
                $error = 'Semua field wajib diisi.';
            } elseif ($password !== $konfirmasi) {
                $error = 'Password dan konfirmasi tidak cocok.';
            } elseif (strlen($password) < 6) {
                $error = 'Password minimal 6 karakter.';
            } elseif ($this->userModel->emailExists($email)) {
                $error = 'Email sudah terdaftar.';
            } else {
                
                $this->userModel->create($nama, $email, $password);
                $success = 'Akun berhasil dibuat! Silakan login.';
            }
        }

        
        require __DIR__ . '/../views/register.php';
    }

    
    public function logout(): void
    {
        session_destroy();
        redirect('index.php?page=login');
    }
}
