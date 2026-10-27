<?php

declare(strict_types=1);

function vitalize_conexao_falha(string $mensagem, string $detalhe): never
{
    error_log($detalhe);
    http_response_code(500);

    if (defined('VITALIZE_API_REQUEST')) {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode(['erro' => $mensagem], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } else {
        echo $mensagem;
    }

    exit;
}

$urlBanco = getenv('DATABASE_URL') ?: getenv('MYSQL_URL') ?: '';
$configUrl = $urlBanco !== '' ? parse_url($urlBanco) : false;

$host = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: ($configUrl['host'] ?? '127.0.0.1');
$porta = getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: ($configUrl['port'] ?? '3306');
$banco = getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: (!empty($configUrl['path']) ? rawurldecode(ltrim($configUrl['path'], '/')) : 'vitalize');
$usuario = getenv('DB_USER') ?: getenv('MYSQLUSER') ?: (isset($configUrl['user']) ? rawurldecode($configUrl['user']) : 'root');
$senha = getenv('DB_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: (isset($configUrl['pass']) ? rawurldecode($configUrl['pass']) : '');

$porta = filter_var($porta, FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1, 'max_range' => 65535]
]);

if ($host === '' || $banco === '' || $usuario === '' || $porta === false) {
    vitalize_conexao_falha('Erro interno ao configurar o banco.', 'Configuração de banco incompleta. Defina DB_HOST, DB_NAME e DB_USER.');
}

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$porta;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_TIMEOUT => 10
        ]
    );
} catch (PDOException $e) {
    vitalize_conexao_falha('Erro interno ao conectar ao banco.', 'Erro MySQL: ' . $e->getMessage());
}
