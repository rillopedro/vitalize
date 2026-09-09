<?php

session_start();

require_once __DIR__ . '/../conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

$id_usuario = (int) $_SESSION['id_usuario'];
$titulo = trim($_POST['titulo'] ?? '');
$conteudo = trim($_POST['conteudo'] ?? '');
$anonimo = isset($_POST['anonimo']) && $_POST['anonimo'] == '1' ? 1 : 0;

if ($titulo === '' || $conteudo === '') {
    $_SESSION['erro_relato'] = 'Preencha título e conteúdo para publicar o relato.';
    header("Location: ../relatos.php");
    exit();
}

$sql = "INSERT INTO relatos (id_usuario, titulo, conteudo, data_publicacao, anonimo)
VALUES (:id_usuario, :titulo, :conteudo, NOW(), :anonimo)";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->bindParam(':titulo', $titulo);
$stmt->bindParam(':conteudo', $conteudo);
$stmt->bindParam(':anonimo', $anonimo, PDO::PARAM_INT);
$stmt->execute();

$_SESSION['sucesso_relato'] = 'Relato enviado com sucesso!';
header("Location: ../relatos.php");
exit();
?>