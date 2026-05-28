<?php





require_once __DIR__ . '/../config/helper.php';
require_once __DIR__ . '/../models/UserModel.php';

class ProfileController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    
    public function show(): void
    {
        
        $userSession = requireLogin();

        
        $user = $this->userModel->findById($userSession['id']);
        if (!$user) {
            session_destroy();
            redirect('index.php?page=login');
        }

        $error = '';
        $success = '';

        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'update') {
                $nama = trim($_POST['nama'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $no_hp = trim($_POST['no_hp'] ?? '');
                $password = $_POST['password'] ?? '';
                $konfirmasi = $_POST['konfirmasi'] ?? '';

                
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
                    
                    $updateData = [
                        'nama' => $nama,
                        'email' => $email,
                        'no_hp' => $no_hp ?: null,
                        'password' => $password
                    ];

                    if ($this->userModel->update($user['id'], $updateData)) {
                        
                        $_SESSION['user']['nama'] = $nama;
                        $_SESSION['user']['email'] = $email;
                        
                        $success = 'Profil Anda berhasil diperbarui.';
                        
                        $user = $this->userModel->findById($user['id']);
                    } else {
                        $error = 'Gagal memperbarui profil. Silakan coba lagi.';
                    }
                }
            } elseif ($action === 'delete') {
                
                if ($this->userModel->delete($user['id'])) {
                    session_destroy();
                    redirect('index.php?page=beranda&msg=' . urlencode('Akun Anda telah dihapus secara permanen.'));
                } else {
                    $error = 'Gagal menghapus akun. Silakan hubungi admin.';
                }
            }
        }

        
        require __DIR__ . '/../views/profile.php';
    }
}
