<?php
session_start();

require_once __DIR__ . '/../conexao.php';

if (empty($_SESSION['id_usuario'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pagperfil.php');
    exit();
}

$id_usuario = (int) $_SESSION['id_usuario'];

try {
    $pdo->beginTransaction();
    $pdo->prepare('DELETE FROM consultas WHERE id_usuario = :id_usuario')->execute([':id_usuario' => $id_usuario]);
    $pdo->prepare('DELETE FROM relatos WHERE id_usuario = :id_usuario')->execute([':id_usuario' => $id_usuario]);
    $pdo->prepare('UPDATE grupos SET id_criador = NULL WHERE id_criador = :id_usuario')->execute([':id_usuario' => $id_usuario]);
    $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id_usuario = :id_usuario');
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $pdo->commit();

    session_unset();
    session_destroy();

    header('Location: ../login.php');
    exit();
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['erro_perfil'] = 'Não foi possível excluir a conta.';
    header('Location: ../pagperfil.php');
    exit();
}
