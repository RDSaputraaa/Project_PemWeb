<?php
// ============================================================
//  controllers/AuthController.php
//  Controller untuk autentikasi: login, register, logout
// ============================================================

require_once __DIR__ . '/../config/helper.php';
require_once __DIR__ . '/../models/UserModel.php';

class AuthController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Tampilkan form login atau proses login
     */
    public function login(): void
    {
        // Jika sudah login, redirect
        if (currentUser()) {
            redirect('index.php?page=' . (isAdmin() ? 'admin' : 'beranda'));
        }

        $error = '';

        // Proses form login jika POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!$email || !$password) {
                $error = 'Email dan password wajib diisi.';
            } else {
                // Panggil Model untuk cari user
                $user = $this->userModel->findByEmail($email);

                if ($user && password_verify($password, $user['password'])) {
                    // Set session
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

        // Render View login
        require __DIR__ . '/../views/login.php';
    }

    /**
     * Tampilkan form register atau proses registrasi
     */
    public function register(): void
    {
        // Jika sudah login, redirect
        if (currentUser()) {
            redirect('index.php?page=beranda');
        }

        $error   = '';
        $success = '';

        // Proses form register jika POST
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
                // Panggil Model untuk buat user baru
                $this->userModel->create($nama, $email, $password);
                $success = 'Akun berhasil dibuat! Silakan login.';
            }
        }

        // Render View register
        require __DIR__ . '/../views/register.php';
    }

    /**
     * Proses logout
     */
    public function logout(): void
    {
        session_destroy();
        redirect('index.php?page=login');
    }
}
