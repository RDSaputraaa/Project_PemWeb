<?php

// Session Timeout Configuration (10 detik)
// Session akan logout jika user IDLE (tidak ada aktivitas) selama waktu ini
define('SESSION_TIMEOUT', 10);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check and validate session timeout
function checkSessionTimeout() {
    if (isset($_SESSION['user'])) {
        $current_time = time();
        $last_activity = $_SESSION['last_activity'] ?? $current_time;
        
        // Jika sudah lebih dari SESSION_TIMEOUT detik sejak last activity, logout
        if (($current_time - $last_activity) > SESSION_TIMEOUT) {
            session_destroy();
            header('Location: index.php?page=login&timeout=1');
            exit;
        }
        
        // Update last activity time (timer reset setiap ada request)
        $_SESSION['last_activity'] = $current_time;
    }
}

// Panggil check timeout di awal setiap request
checkSessionTimeout();


function currentUser(): ?array {
    return $_SESSION['user'] ?? null;
}


function requireLogin(): array {
    $user = currentUser();
    if (!$user) {
        header('Location: index.php?page=login');
        exit;
    }
    return $user;
}


function requireAdmin(): array {
    $user = currentUser();
    if (!$user || $user['role'] !== 'admin') {
        header('Location: index.php?page=login');
        exit;
    }
    return $user;
}


function isAdmin(): bool {
    $user = currentUser();
    return $user && $user['role'] === 'admin';
}


function isLoggedIn(): bool {
    return currentUser() !== null;
}


function formatRupiah(int $n): string {
    return 'Rp ' . number_format($n, 0, ',', '.');
}


function formatTanggal(string $date): string {
    $bulan = ['', 'Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($date);
    return date('j', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}


function jsonResponse(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}


function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}


function csrf(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}


function verifyCsrf(string $token): bool {
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}


function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
