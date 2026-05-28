<?php
// ============================================================
//  controllers/CheckoutController.php
//  Controller untuk alur Checkout dan Pembayaran
// ============================================================

require_once __DIR__ . '/../config/helper.php';
require_once __DIR__ . '/../models/LapanganModel.php';
require_once __DIR__ . '/../models/SlotWaktuModel.php';
require_once __DIR__ . '/../models/ReservasiModel.php';

class CheckoutController
{
    private LapanganModel  $lapanganModel;
    private SlotWaktuModel $slotModel;
    private ReservasiModel $reservasiModel;

    public function __construct()
    {
        $this->lapanganModel  = new LapanganModel();
        $this->slotModel      = new SlotWaktuModel();
        $this->reservasiModel = new ReservasiModel();
    }

    /**
     * Halaman Checkout
     */
    public function index(): void
    {
        // Pastikan user sudah login
        $user = currentUser();
        if (!$user) {
            redirect('index.php?page=login');
        }

        $slotId = (int)($_GET['slot_id'] ?? 0);
        
        // Cari slot waktu
        $slot = $this->slotModel->findAvailable($slotId);
        if (!$slot) {
            // Jika slot tidak tersedia (sudah dibooking/ditutup), kembalikan ke beranda
            redirect('index.php?msg=Slot+waktu+sudah+tidak+tersedia+atau+sudah+dipesan.');
        }

        // Cari lapangan
        $lap = $this->lapanganModel->findById($slot['lapangan_id']);
        if (!$lap) {
            redirect('index.php?msg=Lapangan+tidak+ditemukan.');
        }

        // Handle POST saat konfirmasi checkout
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validasi ulang slot
            $reCheck = $this->slotModel->findAvailable($slotId);
            if (!$reCheck) {
                $error = 'Slot sudah diambil orang lain baru saja. Silakan pilih jadwal lain.';
            } else {
                $catatan = $_POST['catatan'] ?? '';
                $kode = $this->reservasiModel->generateKode();

                // Simpan reservasi
                $reservasiId = $this->reservasiModel->create([
                    'kode'          => $kode,
                    'user_id'       => $user['id'],
                    'lapangan_id'   => $lap['id'],
                    'slot_waktu_id' => $slotId,
                    'tanggal'       => $slot['tanggal'],
                    'jam_mulai'     => $slot['jam_mulai'],
                    'jam_selesai'   => $slot['jam_selesai'],
                    'total_harga'   => $slot['harga'],
                    'catatan'       => $catatan
                ]);

                if ($reservasiId) {
                    // Kunci slot waktu
                    $this->slotModel->setDipesan($slotId);
                    
                    // Arahkan ke halaman pembayaran
                    redirect("index.php?page=payment&id={$reservasiId}");
                } else {
                    $error = 'Gagal memproses booking. Coba lagi.';
                }
            }
        }

        // Render View Checkout
        require __DIR__ . '/../views/checkout.php';
    }

    /**
     * Halaman Pembayaran
     */
    public function payment(): void
    {
        $user = currentUser();
        if (!$user) {
            redirect('index.php?page=login');
        }

        $id = (int)($_GET['id'] ?? 0);
        $res = $this->reservasiModel->findById($id);

        if (!$res) {
            redirect('index.php?msg=Data+reservasi+tidak+ditemukan.');
        }

        // Pastikan reservasi ini milik user yang sedang login (kecuali admin)
        if ($res['user_id'] !== $user['id'] && $user['role'] !== 'admin') {
            redirect('index.php?msg=Akses+ditolak.');
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['bukti_bayar']) && $_FILES['bukti_bayar']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['bukti_bayar']['tmp_name'];
                $fileName    = $_FILES['bukti_bayar']['name'];
                $fileSize    = $_FILES['bukti_bayar']['size'];
                
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                // Validasi ekstensi dan MIME type asli
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $allowedMimeTypes  = ['image/jpeg', 'image/png', 'image/gif'];

                // Dapatkan MIME Type asli menggunakan mime_content_type
                $realMimeType = '';
                if (function_exists('mime_content_type')) {
                    $realMimeType = mime_content_type($fileTmpPath);
                } elseif (function_exists('finfo_open')) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $realMimeType = finfo_file($finfo, $fileTmpPath);
                    finfo_close($finfo);
                }

                if (in_array($fileExtension, $allowedExtensions) && in_array($realMimeType, $allowedMimeTypes)) {
                    // Validasi ukuran (maksimal 2MB)
                    if ($fileSize < 2 * 1024 * 1024) {
                        // Folder upload
                        $uploadFileDir = __DIR__ . '/../assets/uploads/bukti_bayar/';
                        if (!is_dir($uploadFileDir)) {
                            mkdir($uploadFileDir, 0755, true);
                        }

                        // Rename file agar unik
                        $newFileName = 'bukti_' . $res['kode_reservasi'] . '_' . time() . '.' . $fileExtension;
                        $dest_path = $uploadFileDir . $newFileName;

                        if (move_uploaded_file($fileTmpPath, $dest_path)) {
                            // Simpan ke DB (path relatif dari root project)
                            $dbPath = 'assets/uploads/bukti_bayar/' . $newFileName;
                            $this->reservasiModel->updateBuktiBayar($id, $dbPath);
                            
                            redirect('index.php?msg=Bukti+pembayaran+berhasil+diunggah.+Tunggu+konfirmasi+dari+admin.');
                        } else {
                            $error = 'Terjadi kesalahan saat menyimpan file ke direktori.';
                        }
                    } else {
                        $error = 'Ukuran file terlalu besar. Maksimal ukuran file adalah 2MB.';
                    }
                } else {
                    $error = 'Format file tidak diizinkan. Hanya menerima: ' . implode(',', $allowedExtensions);
                }
            } else {
                $error = 'Harap pilih file bukti transfer terlebih dahulu.';
            }
        }

        require __DIR__ . '/../views/payment.php';
    }
}
