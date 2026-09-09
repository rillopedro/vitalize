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

    $tamanhoMaximo = 5 * 1024 * 1024;
    if (($arquivo['size'] ?? 0) > $tamanhoMaximo) {
        throw new RuntimeException('A imagem deve ter no máximo 5 MB.');
    }

    $tipo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = $tipo ? finfo_file($tipo, $arquivo['tmp_name']) : false;
    if ($tipo) {
        finfo_close($tipo);
    }

    $mimesPermitidos = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];
    if (!isset($mimesPermitidos[$mime])) {
        throw new RuntimeException('O arquivo enviado não é uma imagem válida.');
    }

    $extensao = $mimesPermitidos[$mime];
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
