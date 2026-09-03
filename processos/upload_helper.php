<?php

function criar_pasta_upload(string $caminho): void
{
    if (!is_dir($caminho) && !mkdir($caminho, 0777, true) && !is_dir($caminho)) {
        throw new RuntimeException('Não foi possível criar a pasta de uploads.');
    }
}

function salvar_arquivo_upload(array $arquivo, string $subpasta): string
{
    if (!isset($arquivo['name']) || $arquivo['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Nenhum arquivo válido foi enviado.');
    }

    if (!is_uploaded_file($arquivo['tmp_name'])) {
        throw new RuntimeException('Arquivo inválido para upload.');
    }

    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extensao, $permitidas, true)) {
        throw new RuntimeException('Formato de imagem não suportado.');
    }

    $nomeArquivo = uniqid('img_', true) . '.' . $extensao;
    $baseDir = dirname(__DIR__);
    $pastaDestino = $baseDir . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . trim($subpasta, '/');

    criar_pasta_upload($pastaDestino);

    $destino = $pastaDestino . DIRECTORY_SEPARATOR . $nomeArquivo;

    if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
        throw new RuntimeException('Não foi possível salvar a imagem no servidor.');
    }

    return 'img/uploads/' . trim($subpasta, '/') . '/' . $nomeArquivo;
}
