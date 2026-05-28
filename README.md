# Gelora Sport - Sistem Informasi Reservasi Lapangan (MVC PHP)

Aplikasi Reservasi Lapangan Olahraga dinamis yang dibangun dengan menerapkan pola arsitektur **Model-View-Controller (MVC)** menggunakan PHP Native dan database MySQL (PDO).

---

## 📂 Struktur Direktori Proyek

Proyek ini telah direstrukturisasi sepenuhnya ke pola MVC dengan struktur folder sebagai berikut:

```text
Project_PemWeb/
│
├── config/             # Konfigurasi database & helper global
│   ├── db.php          # Koneksi PDO ke database MySQL
│   └── helper.php      # Fungsi pembantu (redirect, format mata uang, CSRF, dll.)
│
├── controllers/        # Logika Bisnis & Penghubung Model-View
│   ├── AdminController.php
│   ├── AuthController.php
│   ├── BerandaController.php
│   ├── DetailController.php
│   ├── LapanganController.php
│   └── ReservasiController.php
│
├── models/             # Query Database & Pengolahan Data
│   ├── LapanganModel.php
│   ├── ReservasiModel.php
│   ├── SlotWaktuModel.php
│   └── UserModel.php
│
├── views/              # Desain Tampilan (HTML & Output PHP)
│   ├── admin/          # Tampilan khusus halaman panel admin
│   ├── components/     # Komponen UI reusable (seperti sidebar)
│   ├── beranda.php     # Halaman utama katalog lapangan
│   ├── detail.php      # Detail lapangan & jadwal slot waktu
│   ├── login.php       # Form login
│   └── register.php    # Form registrasi akun
│
├── assets/             # Aset statis terpusat
│   └── css/
│       └── style.css   # Berkas CSS gabungan & scoped
│
├── database.sql        # Skema & data awal database MySQL
├── generate_slots.php  # Utilitas otomatisasi slot jadwal lapangan
├── index.php           # Front Controller / Router Utama
└── README.md           # Panduan dokumentasi ini
```

---

## 🛠️ Instalasi & Menjalankan Aplikasi

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di server lokal (Laragon / XAMPP):

1. **Clone atau Ekstrak Folder Proyek**
   Letakkan folder `Project_PemWeb` di direktori server lokal Anda (misalnya di `C:\laragon\www\` atau `C:\xampp\htdocs\`).

2. **Impor Database**
   * Buat database baru bernama `gelora_sport` di MySQL phpMyAdmin Anda.
   * Impor berkas database [database.sql](file:///d:/UASWEB/Project_PemWeb/database.sql) yang terletak di root direktori proyek ke dalam database baru tersebut.

3. **Konfigurasi Koneksi Database**
   Buka berkas [config/db.php](file:///d:/UASWEB/Project_PemWeb/config/db.php) dan sesuaikan konfigurasi koneksi MySQL lokal Anda (host, username, password, dbname).

4. **Jalankan Slot Waktu Otomatis (Opsional)**
   Akses `http://localhost/Project_PemWeb/generate_slots.php` sekali di browser untuk mengotomatiskan pembuatan slot jadwal lapangan selama 7 hari ke depan.

5. **Akses Aplikasi**
   Buka browser Anda dan akses alamat berikut:
   ```text
   http://localhost/Project_PemWeb/index.php
   ```

---

## 🔑 Akun Login Uji Coba (Demo)

Untuk mempermudah pengujian, berikut kredensial login default yang telah terdaftar di database:

### 👤 Akun Admin
* **Email:** `admin@gelorasport.com`
* **Password:** `admin123`

### 👥 Akun Pelanggan (User)
* **Email:** `budi@gmail.com`
* **Password:** `budi123`