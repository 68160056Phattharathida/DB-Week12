<?php
// Shared authentication helper. Keep on every private page.
if (session_status() !== PHP_SESSION_ACTIVE) {
    // กำหนด lifetime, path, domain, secure, httponly แบบมาตรฐานที่ไม่ทำให้เกิด Error
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params(0, '/', '', $secure, true);
    session_start();
}
function require_login(): void {
    if (empty($_SESSION['user_id']) || empty($_SESSION['profile_id'])) {
        header('Location: login.php'); exit;
    }
}
function auth_escape($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function auth_token(): string {
    if (empty($_SESSION['auth_csrf'])) $_SESSION['auth_csrf']=bin2hex(random_bytes(32));
    return $_SESSION['auth_csrf'];
}
function check_auth_token(): void {
    if (!hash_equals(auth_token(), (string)($_POST['auth_csrf']??''))) {
        http_response_code(403); exit('Invalid CSRF token');
    }
}

