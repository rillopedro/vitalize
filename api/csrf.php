<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/iniciar_sessao.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

$_SESSION['csrf_api'] ??= bin2hex(random_bytes(32));
echo json_encode(['csrf_token' => $_SESSION['csrf_api']], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);