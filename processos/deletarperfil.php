<?php
session_start();

require_once __DIR__ . '/../conexao.php';

if (empty($_SESSION['id_usuario'])) {
    header('Location: ../login.php');
    exit();
}

$id_usuario = (int) $_SESSION['id_usuario'];

try {
    $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id_usuario = :id_usuario');
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();

    session_unset();
    session_destroy();

    header('Location: ../login.php');
    exit();
} catch (PDOException $e) {
    $_SESSION['erro_perfil'] = 'Não foi possível excluir a conta.';
    header('Location: ../pagperfil.php');
    exit();
}
