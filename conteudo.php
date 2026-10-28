<?php
declare(strict_types=1);
require_once __DIR__ . '/navegacao.php';
$pagina = $_GET['pagina'] ?? 'index';
if (!is_string($pagina) || !in_array($pagina, VITALIZE_PAGINAS, true)) {
    http_response_code(404);
    header('Content-Type: text/html; charset=utf-8');
    exit('<!doctype html><html lang="pt-BR"><meta charset="utf-8"><title>Página não encontrada</title><p>Página não encontrada.</p></html>');
}
// Se um link interno for aberto em outra aba, ele recebe sua própria tela principal.
if (($_SERVER['HTTP_SEC_FETCH_DEST'] ?? '') === 'document') {
    vitalize_exibir_aplicacao($pagina . '.php');
}
define('VITALIZE_CONTEUDO_INTERNO', true);
header('X-Frame-Options: SAMEORIGIN');
header('Cache-Control: no-store');
require __DIR__ . '/' . $pagina . '.php';
