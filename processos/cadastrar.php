<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../conexao.php';

try {

    // Receber os dados do formulário
    $nome = trim($_POST['nome'] ?? '');
    $sobrenome = trim($_POST['sobrenome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $confirmarSenha = trim($_POST['confirmar_senha'] ?? '');

    // Verificar se as senhas são iguais
    if ($senha !== $confirmarSenha) {

        $_SESSION['erro_cadastro'] = "As senhas não coincidem.";

        header("Location: ../cadastro.php");

        exit();

    }

    // Verificar se o e-mail já existe
    $sql = $pdo->prepare("
        SELECT id_usuario
        FROM usuarios
        WHERE email = :email
    ");

    $sql->bindParam(":email", $email);

    $sql->execute();

    if($sql->rowCount() > 0){

        $_SESSION['erro_cadastro'] = "Este e-mail já está cadastrado.";

        header("Location: ../cadastro.php");

        exit();

    }

    // Criptografar senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserir usuário
    $insert = "
    INSERT INTO usuarios
    (
        nome,
        sobrenome,
        email,
        senha,
        telefone
    )

    VALUES
    (
        :nome,
        :sobrenome,
        :email,
        :senha,
        :telefone
    )
    ";

    $stmt = $pdo->prepare($insert);

    $stmt->bindParam(":nome",$nome);
    $stmt->bindParam(":sobrenome",$sobrenome);
    $stmt->bindParam(":email",$email);
    $stmt->bindParam(":senha",$senhaHash);
    $stmt->bindParam(":telefone",$telefone);

    if($stmt->execute()){

        $id = $pdo->lastInsertId();

        $_SESSION['id_usuario'] = $id;
        $_SESSION['nome'] = $nome;
        $_SESSION['email'] = $email;

        header("Location: ../perfil.php");

        exit();

    }else{

        $_SESSION['erro_cadastro'] = "Erro ao realizar o cadastro.";

        header("Location: ../cadastro.php");

        exit();

    }

}catch(PDOException $e){

    $_SESSION['erro_cadastro'] = "Erro de conexão com o banco.";

    // Durante o desenvolvimento, você pode usar:
    // die($e->getMessage());

    header("Location: ../cadastro.php");

    exit();

}