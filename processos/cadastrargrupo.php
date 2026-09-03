<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/upload_helper.php';

try {

    // Receber dados do formulário
    $nome = trim($_POST['nome'] ?? '');
    $foco = trim($_POST['foco'] ?? '');
    $mais_info = trim($_POST['mais_info'] ?? '');
    $telefone_grupo = trim($_POST['telefone_grupo'] ?? '');
    $link = trim($_POST['link'] ?? '');
    $data_encontro_input = trim($_POST['data_encontro'] ?? '');
    $horario_input = trim($_POST['horario'] ?? '');

    $data_encontro = null;
    if (!empty($data_encontro_input)) {
        $data_encontro = date('Y-m-d H:i:s', strtotime($data_encontro_input));
    }

    $horario = null;
    if (!empty($horario_input)) {
        $horario = date('H:i', strtotime($horario_input));
    }

    // Verificar se já existe um grupo com o mesmo nome
    $verifica = $pdo->prepare("
        SELECT id_grupo
        FROM grupos
        WHERE nome_grupo = :nome
    ");

    $verifica->bindParam(':nome', $nome);
    $verifica->execute();

    if($verifica->rowCount() > 0){

        $_SESSION['erro_grupo'] = "Já existe um grupo com esse nome.";

        header("Location: ../cadastrar_grupo.php");

        exit();

    }

    $imagem_path = null;
    if (!empty($_FILES['imagem']['name'])) {
        $imagem_path = salvar_arquivo_upload($_FILES['imagem'], 'grupos');
    }

    // Inserção
    $sql = "INSERT INTO grupos
    (
        nome_grupo,
        mais_info,
        foco,
        data_encontro,
        horario,
        link,
        telefone_grupo,
        imagem
    )

    VALUES
    (
        :nome,
        :mais_info,
        :foco,
        :data_encontro,
        :horario,
        :link,
        :telefone_grupo,
        :imagem
    )";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':mais_info', $mais_info);
    $stmt->bindParam(':foco', $foco);
    $stmt->bindParam(':data_encontro', $data_encontro);
    $stmt->bindParam(':horario', $horario);
    $stmt->bindParam(':link', $link);
    $stmt->bindParam(':telefone_grupo', $telefone_grupo);
    $stmt->bindParam(':imagem', $imagem_path);

    if($stmt->execute()){

        $_SESSION['sucesso_grupo'] = "Grupo cadastrado com sucesso!";

        header("Location: ../cadastrar_grupo.php");

        exit();

    }else{

        $_SESSION['erro_grupo'] = "Erro ao cadastrar o grupo.";

        header("Location: ../cadastrar_grupo.php");

        exit();

    }

}catch(PDOException $e){

    $_SESSION['erro_grupo'] = "Erro ao cadastrar o grupo: " . $e->getMessage();

    header("Location: ../cadastrar_grupo.php");

    exit();

}
?>