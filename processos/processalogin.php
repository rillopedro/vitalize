<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../conexao.php';

// Receber dados do formulário
$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

// Verificar se os campos foram preenchidos
if (empty($email) || empty($senha)) {

    $_SESSION['erro_login'] = "Preencha e-mail e senha.";

    header("Location: ../login.php");

    exit();

}

// Buscar usuário pelo e-mail
$sql = "SELECT
            id_usuario,
            nome,
            email,
            senha,
            sobrenome,
            telefone,
            foto_perfil
        FROM usuarios
        WHERE email = :email";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se encontrou o usuário
if (!$usuario) {

    $_SESSION['erro_login'] = "E-mail não encontrado.";

    header("Location: ../login.php");

    exit();

}

// Verifica a senha
if (!password_verify($senha, $usuario['senha'])) {

    $_SESSION['erro_login'] = "Senha incorreta.";

    header("Location: ../login.php");

    exit();

}

// Criar sessão
$_SESSION['id_usuario'] = $usuario['id_usuario'];
$_SESSION['nome'] = $usuario['nome'];
$_SESSION['email'] = $usuario['email'];
$_SESSION['sobrenome'] = $usuario['sobrenome'] ?? '';
$_SESSION['telefone'] = $usuario['telefone'] ?? '';
$_SESSION['foto_perfil'] = $usuario['foto_perfil'] ?? '';

// Redirecionar para a área restrita
header("Location: ../pagperfil.php");
exit();

?>
