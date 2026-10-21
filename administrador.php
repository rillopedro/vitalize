<?php
declare(strict_types=1);
require_once __DIR__ . '/api/bootstrap.php';
try {
    api_exigir_admin($pdo);
} catch (Throwable $e) {
    http_response_code(500);
    exit('Não foi possível validar o acesso administrativo.');
}
header_remove('Content-Type');
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
<script src="api/cliente.js"></script>
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
        try { conteudos = await VitalizeAPI.requisicao('/api/admin/moderation'); renderizar(); }
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
        botao.disabled = true;
        try {
            await VitalizeAPI.requisicao(`/api/admin/moderation/${tipo}/${botao.dataset.id}`, {method:'PATCH', body:{status:botao.dataset.status}});
            conteudos[tipo] = conteudos[tipo].filter(item => String(item.id) !== botao.dataset.id);
            aviso.textContent = botao.dataset.status === 'aprovado' ? 'Conteúdo aprovado e publicado.' : 'Conteúdo recusado.';
            renderizar();
        } catch (erro) { botao.disabled = false; aviso.textContent = erro.message || 'Não foi possível atualizar o conteúdo.'; }
    });
    carregar();
})();
</script>
</body>
</html>
