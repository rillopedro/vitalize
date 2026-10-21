<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    $httpsDireto = !empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off';
    $httpsProxy = strtolower(trim(explode(',', (string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''))[0])) === 'https';
    session_set_cookie_params([
        'httponly' => true,
        'secure' => $httpsDireto || $httpsProxy,
        'samesite' => 'Lax',
        'path' => '/',
    ]);
    session_start();
}
