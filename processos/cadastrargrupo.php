<?php

session_start();

require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/upload_helper.php';

try {

    // Receber dados do formulário
    $nome = trim($_POST['nome'] ?? '');
    $foco = trim($_POST['foco'] ?? '');
    $mais_info = trim($_POST['mais_info'] ?? '');
    $responsavel = trim($_POST['responsavel'] ?? '');
    $telefone_grupo = trim($_POST['telefone_grupo'] ?? '');
    $link = trim($_POST['link'] ?? '');
    $data_encontro_input = trim($_POST['data_encontro'] ?? '');
    $horario_input = trim($_POST['horario'] ?? '');

    $data_encontro = $data_encontro_input !== '' ? date('Y-m-d', strtotime($data_encontro_input)) : null;
    $horario = $horario_input !== '' ? date('H:i:s', strtotime($horario_input)) : null;

    if ($nome === '') {
        throw new InvalidArgumentException('Informe o nome do grupo.');
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
        responsavel,
        mais_info,
        foco,
        data_encontro,
        horario,
        link,
        contato,
        imagem
    )

    VALUES
    (
        :nome,
        :responsavel,
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
    $stmt->bindParam(':responsavel', $responsavel);
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

} catch (Throwable $e) {

    error_log('Erro ao cadastrar grupo: ' . $e->getMessage());
    $_SESSION['erro_grupo'] = 'Não foi possível cadastrar o grupo. Verifique os dados e tente novamente.';

    header("Location: ../cadastrar_grupo.php");

    exit();

}
?>