<?php
// ============================================================
//  config/helper.php — Helper & Session
//  Fungsi-fungsi utilitas yang digunakan di seluruh aplikasi
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Ambil data user yang sedang login dari session
 */
function currentUser(): ?array {
    return $_SESSION['user'] ?? null;
}

/**
 * Paksa user harus login, redirect ke login jika belum
 */
function requireLogin(): array {
    $user = currentUser();
    if (!$user) {
        header('Location: index.php?page=login');
        exit;
    }
    return $user;
}

/**
 * Paksa user harus admin, redirect ke login jika bukan
 */
function requireAdmin(): array {
    $user = currentUser();
    if (!$user || $user['role'] !== 'admin') {
        header('Location: index.php?page=login');
        exit;
    }
    return $user;
}

/**
 * Cek apakah user adalah admin
 */
function isAdmin(): bool {
    $user = currentUser();
    return $user && $user['role'] === 'admin';
}

/**
 * Cek apakah user sudah login
 */
function isLoggedIn(): bool {
    return currentUser() !== null;
}

/**
 * Format angka menjadi format Rupiah
 */
function formatRupiah(int $n): string {
    return 'Rp ' . number_format($n, 0, ',', '.');
}

/**
 * Format tanggal menjadi format Indonesia
 */
function formatTanggal(string $date): string {
    $bulan = ['', 'Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($date);
    return date('j', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

/**
 * Kirim response JSON
 */
function jsonResponse(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Redirect ke URL lain
 */
function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

/**
 * Generate CSRF token
 */
function csrf(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/**
 * Verifikasi CSRF token
 */
function verifyCsrf(string $token): bool {
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

/**
 * Escape string untuk output HTML (mencegah XSS)
 */
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
