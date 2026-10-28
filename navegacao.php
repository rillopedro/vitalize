<?php
declare(strict_types=1);

// Apenas estas telas podem ser carregadas pelo ponto de entrada interno.
const VITALIZE_PAGINAS = ['index','sobregrupos','grupos','cadastrar_grupo','doacao','saude','relatos','sobre','login','login_admin','cadastro','recuperar_senha','pagperfil','administrador'];

function vitalize_url_pagina(string $pagina): string
{
    [$arquivo, $consulta] = array_pad(explode('?', $pagina, 2), 2, '');
    $nome = basename($arquivo, '.php');
    if (!in_array($nome, VITALIZE_PAGINAS, true)) throw new InvalidArgumentException('Página inválida.');
    return 'conteudo.php?pagina=' . rawurlencode($nome) . ($consulta !== '' ? '&' . $consulta : '');
}

function vitalize_exibir_aplicacao(string $pagina): void
{
    if (defined('VITALIZE_CONTEUDO_INTERNO')) return;
    $consulta = $_GET;
    unset($consulta['pagina']);
    $destino = vitalize_url_pagina($pagina . ($consulta ? '?' . http_build_query($consulta) : ''));
    $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')), '/') . '/';
    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: no-store');
    header('X-Frame-Options: SAMEORIGIN');
    $config = json_encode(['base' => $base, 'inicial' => $destino, 'paginas' => VITALIZE_PAGINAS], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    ?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vitalize</title>
    <link rel="icon" href="img/logov.png">
    <style>html,body{margin:0;width:100%;height:100%;overflow:hidden}#vitalize-pagina{display:block;width:100%;height:100%;border:0}</style>
</head>
<body>
    <iframe id="vitalize-pagina" title="Conteúdo do Vitalize"></iframe>
    <noscript>Ative o JavaScript para navegar no Vitalize.</noscript>
    <script id="vitalize-config" type="application/json"><?= $config ?></script>
    <script src="navegacao.js"></script>
</body>
</html>
    <?php
    exit;
}
