<?php

$host = getenv('DB_HOST');
$porta = getenv('DB_PORT') ?: '3306';
$banco = getenv('DB_NAME');
$usuario = getenv('DB_USER');
$senha = getenv('DB_PASSWORD');

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