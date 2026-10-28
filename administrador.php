<?php
declare(strict_types=1);
require_once __DIR__ . '/api/bootstrap.php';
$idUsuario = filter_var($_SESSION['id_usuario'] ?? null, FILTER_VALIDATE_INT);
if (!$idUsuario || $idUsuario < 1) {
    header('Location: login_admin.php', true, 303);
    exit;
}
try {
    $stmt = $pdo->prepare('SELECT tipo, versao_sessao FROM usuarios WHERE id_usuario = ? LIMIT 1');
    $stmt->execute([$idUsuario]);
    $usuarioAtual = $stmt->fetch();
} catch (Throwable $e) {
    error_log('Vitalize: não foi possível validar o painel administrativo: ' . $e->getMessage());
    http_response_code(500);
    exit('Não foi possível validar o acesso administrativo.');
}
if (!$usuarioAtual || (int) ($_SESSION['versao_sessao'] ?? -1) !== (int) $usuarioAtual['versao_sessao'] || $usuarioAtual['tipo'] !== 'admin') {
    header('Location: login_admin.php', true, 303);
    exit;
}
header_remove('Content-Type');
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Moderação | Vitalize</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logov.png">
</head>
<body>
<main class="pagina-adm" id="painelAdm">
    <header class="cabecalho-adm"><div><h1>Painel administrativo</h1><p>Revise relatos e grupos antes da publicação.</p></div><a href="index.php">Voltar ao início</a></header>
    <section class="moderacao-adm">
        <div class="topo-moderacao-adm">
            <div class="abas-adm" role="tablist" aria-label="Conteúdo pendente">
                <button class="aba-adm ativa" type="button" role="tab" aria-selected="true" data-tipo="stories">Relatos <span data-total="stories">0</span></button>
                <button class="aba-adm" type="button" role="tab" aria-selected="false" data-tipo="groups">Grupos <span data-total="groups">0</span></button>
            </div>
        </div>
        <div class="lista-adm" data-lista="stories" aria-live="polite"></div>
        <div class="lista-adm" data-lista="groups" hidden aria-live="polite"></div>
        <p class="vazio-adm" data-vazio hidden>Nenhum conteúdo pendente.</p>
        <p role="status" aria-live="polite" data-aviso></p>
    </section>
</main>
<script src="api/cliente.js?v=20261027-2"></script>
<script>
(() => {
    const painel = document.querySelector('#painelAdm');
    const aviso = painel.querySelector('[data-aviso]');
    let tipo = 'stories';
    let conteudos = { stories: [], groups: [] };
    const escapar = valor => String(valor ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    function renderizar() {
        for (const chave of ['stories', 'groups']) {
            painel.querySelector(`[data-total="${chave}"]`).textContent = conteudos[chave].length;
            const lista = painel.querySelector(`[data-lista="${chave}"]`);
            lista.innerHTML = conteudos[chave].map(item => `<article class="item-adm"><div class="corpo-item-adm"><div class="autor-adm"><strong>${escapar(item.autor || 'Anônimo')}</strong><time>${escapar(item.data || '')}</time></div><h2>${escapar(item.titulo)}</h2><p>${escapar(item.conteudo || '')}</p></div><div class="acoes-adm"><button class="botao-adm botao-aprovar-adm" type="button" data-status="aprovado" data-id="${Number(item.id)}">Aprovar</button><button class="botao-adm botao-recusar-adm" type="button" data-status="recusado" data-id="${Number(item.id)}">Recusar</button></div></article>`).join('');
            lista.hidden = chave !== tipo;
        }
        painel.querySelector('[data-vazio]').hidden = conteudos[tipo].length > 0;
    }
    async function carregar() {
        try {
            const resposta = await VitalizeAPI.requisicao('/api/admin/moderation');
            conteudos = {
                stories: Array.isArray(resposta.relatos) ? resposta.relatos : (Array.isArray(resposta.stories) ? resposta.stories : []),
                groups: Array.isArray(resposta.grupos) ? resposta.grupos : (Array.isArray(resposta.groups) ? resposta.groups : [])
            };
            renderizar();
        }
        catch (erro) { aviso.textContent = erro.message || 'Não foi possível carregar a moderação.'; }
    }
    painel.querySelectorAll('[data-tipo]').forEach(botao => botao.addEventListener('click', () => {
        tipo = botao.dataset.tipo;
        painel.querySelectorAll('[data-tipo]').forEach(item => { const ativa = item === botao; item.classList.toggle('ativa', ativa); item.setAttribute('aria-selected', String(ativa)); });
        renderizar();
    }));
    painel.addEventListener('click', async evento => {
        const botao = evento.target.closest('[data-status][data-id]');
        if (!botao) return;
        const tipoAcao = tipo;
        const idAcao = botao.dataset.id;
        const statusAcao = botao.dataset.status;
        const botoesAcao = botao.closest('.acoes-adm').querySelectorAll('button');
        botoesAcao.forEach(item => { item.disabled = true; });
        try {
            await VitalizeAPI.requisicao(`/api/admin/moderation/${tipoAcao}/${idAcao}`, {method:'PATCH', body:{status:statusAcao}});
            conteudos[tipoAcao] = conteudos[tipoAcao].filter(item => String(item.id) !== idAcao);
            aviso.textContent = statusAcao === 'aprovado' ? 'Conteúdo aprovado e publicado.' : 'Conteúdo recusado.';
            renderizar();
        } catch (erro) { botoesAcao.forEach(item => { item.disabled = false; }); aviso.textContent = erro.message || 'Não foi possível atualizar o conteúdo.'; }
    });
    carregar();
})();
</script>
</body>
</html>
