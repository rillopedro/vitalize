<?php
session_start();

require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/upload_helper.php';

if (empty($_SESSION['id_usuario'])) {
    header('Location: ../login.php');
    exit();
}

$id_usuario = (int) $_SESSION['id_usuario'];
$nome = trim($_POST['nome'] ?? '');
$sobrenome = trim($_POST['sobrenome'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$senha = trim($_POST['senha'] ?? '');
$confirmar_senha = trim($_POST['confirmar_senha'] ?? '');

if ($senha !== '' && $senha !== $confirmar_senha) {
    $_SESSION['erro_perfil'] = 'As senhas não coincidem.';
    header('Location: ../pagperfil.php');
    exit();
}

if ($email === '') {
    $_SESSION['erro_perfil'] = 'O e-mail é obrigatório.';
    header('Location: ../pagperfil.php');
    exit();
}

$sql_verifica = "SELECT id_usuario FROM usuarios WHERE email = :email AND id_usuario != :id_usuario";
$stmt_verifica = $pdo->prepare($sql_verifica);
$stmt_verifica->bindParam(':email', $email);
$stmt_verifica->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt_verifica->execute();

if ($stmt_verifica->rowCount() > 0) {
    $_SESSION['erro_perfil'] = 'Este e-mail já está sendo usado por outro usuário.';
    header('Location: ../pagperfil.php');
    exit();
}

$sql = "UPDATE usuarios SET nome = :nome, sobrenome = :sobrenome, email = :email, telefone = :telefone";

$foto_perfil_path = null;
if (!empty($_FILES['foto_perfil']['name'])) {
    $foto_perfil_path = salvar_arquivo_upload($_FILES['foto_perfil'], 'usuarios');
    $sql .= ", foto_perfil = :foto_perfil";
}

if ($senha !== '') {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $sql .= ", senha = :senha";
}

$sql .= " WHERE id_usuario = :id_usuario";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':sobrenome', $sobrenome);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':telefone', $telefone);

if ($senha !== '') {
    $stmt->bindParam(':senha', $senha_hash);
}

if ($foto_perfil_path !== null) {
    $stmt->bindParam(':foto_perfil', $foto_perfil_path);
}

$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

if ($stmt->execute()) {
    $_SESSION['nome'] = $nome;
    $_SESSION['email'] = $email;
    $_SESSION['sobrenome'] = $sobrenome;
    $_SESSION['telefone'] = $telefone;

    if ($foto_perfil_path !== null) {
        $_SESSION['foto_perfil'] = $foto_perfil_path;
    }

    $_SESSION['sucesso_perfil'] = 'Dados atualizados com sucesso.';
    header('Location: ../pagperfil.php');
    exit();
}

$_SESSION['erro_perfil'] = 'Não foi possível atualizar os dados.';
header('Location: ../pagperfil.php');
exit();
