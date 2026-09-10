<?php

$urlBanco = getenv('DATABASE_URL') ?: getenv('MYSQL_URL') ?: '';
$configUrl = $urlBanco !== '' ? parse_url($urlBanco) : false;

$host = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: ($configUrl['host'] ?? '127.0.0.1');
$porta = getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: ($configUrl['port'] ?? '3306');
$banco = getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: (!empty($configUrl['path']) ? ltrim($configUrl['path'], '/') : 'vitalize');
$usuario = getenv('DB_USER') ?: getenv('MYSQLUSER') ?: ($configUrl['user'] ?? 'root');
$senha = getenv('DB_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: ($configUrl['pass'] ?? '');

$porta = filter_var($porta, FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1, 'max_range' => 65535]
]);

if ($host === '' || $banco === '' || $usuario === '' || $porta === false) {
    error_log('Configuração de banco incompleta. Defina DB_HOST, DB_NAME e DB_USER.');
    http_response_code(500);
    exit('Erro interno ao configurar o banco.');
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
    error_log("Erro MySQL: " . $e->getMessage());
    http_response_code(500);
    exit("Erro interno ao conectar ao banco.");
}