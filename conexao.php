<?php

$host = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: '127.0.0.1';
$porta = getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: '3306';
$banco = getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: 'vitalize';
$usuario = getenv('DB_USER') ?: getenv('MYSQLUSER') ?: 'root';
$senha = getenv('DB_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: '';

if ($host === '' || $banco === '' || $usuario === '') {
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
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log("Erro MySQL: " . $e->getMessage());
    http_response_code(500);
    exit("Erro interno ao conectar ao banco.");
}