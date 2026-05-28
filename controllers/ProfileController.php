<?php
// ============================================================
//  controllers/ProfileController.php
//  Controller untuk manajemen profil user (CRUD)
// ============================================================

require_once __DIR__ . '/../config/helper.php';
require_once __DIR__ . '/../models/UserModel.php';

class ProfileController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Tampilkan halaman profil & handle aksi Update/Delete
     */
    public function show(): void
    {
        // Pastikan user sudah login
        $userSession = requireLogin();

        // Ambil data user terbaru dari database
        $user = $this->userModel->findById($userSession['id']);
        if (!$user) {
            session_destroy();
            redirect('index.php?page=login');
        }

        $error = '';
        $success = '';

        // Tangani form submission jika POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'update') {
                $nama = trim($_POST['nama'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $no_hp = trim($_POST['no_hp'] ?? '');
                $password = $_POST['password'] ?? '';
                $konfirmasi = $_POST['konfirmasi'] ?? '';

                // Validasi input
                if (!$nama || !$email) {
                    $error = 'Nama dan Email wajib diisi.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Format email tidak valid.';
                } elseif ($this->userModel->emailExistsForOther($email, $user['id'])) {
                    $error = 'Email sudah digunakan oleh pengguna lain.';
                } elseif (!empty($password) && strlen($password) < 6) {
                    $error = 'Password minimal 6 karakter.';
                } elseif (!empty($password) && $password !== $konfirmasi) {
                    $error = 'Password dan konfirmasi password tidak cocok.';
                } else {
                    // Update ke database
                    $updateData = [
                        'nama' => $nama,
                        'email' => $email,
                        'no_hp' => $no_hp ?: null,
                        'password' => $password
                    ];

                    if ($this->userModel->update($user['id'], $updateData)) {
                        // Perbarui data session
                        $_SESSION['user']['nama'] = $nama;
                        $_SESSION['user']['email'] = $email;
                        
                        $success = 'Profil Anda berhasil diperbarui.';
                        // Refresh data user
                        $user = $this->userModel->findById($user['id']);
                    } else {
                        $error = 'Gagal memperbarui profil. Silakan coba lagi.';
                    }
                }
            } elseif ($action === 'delete') {
                // Hapus akun secara permanen
                if ($this->userModel->delete($user['id'])) {
                    session_destroy();
                    redirect('index.php?page=beranda&msg=' . urlencode('Akun Anda telah dihapus secara permanen.'));
                } else {
                    $error = 'Gagal menghapus akun. Silakan hubungi admin.';
                }
            }
        }

        // Render View profil
        require __DIR__ . '/../views/profile.php';
    }
}
