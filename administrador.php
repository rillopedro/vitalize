<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitalize</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="img/logov.png">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <nav class="header">
        <div class="logo-box">
            <img src="img/logov.png" alt="Logo do Vitalize">
        </div>

        <div class="nav-bar">
            <ul class="menu">
                <li><a href="index.php">Início</a></li>
                <li><a href="sobregrupos.php">Grupos</a></li>
                <li><a href="doacao.php">Doação</a></li>
                <li><a href="saude.php">Saúde</a></li>
                <li><a href="relatos.php">Relatos</a></li>
                <li><a href="sobre.php">Sobre</a></li>
            </ul>

            <?php if (!empty($_SESSION['id_usuario'])): ?>
                <a href="pagperfil.php" class="btnav">Perfil</a>
            <?php else: ?>
                <a href="login.php" class="btnav">Entrar</a>
            <?php endif; ?>

            <button class="menu-toggle" type="button" aria-label="Abrir menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <main class="pagina-adm" id="painelAdm">
        <header class="cabecalho-adm">
            <div>
                <h1>Painel administrativo</h1>
                <p>Gerencie os conteúdos enviados para o Vitalize</p>
            </div>
        </header>

        <section class="moderacao-adm">
            <div class="topo-moderacao-adm">
                <div class="abas-adm" role="tablist" aria-label="Tipo de conteúdo">
                    <button class="aba-adm ativa" type="button" role="tab" aria-selected="true" data-aba-adm="depoimentos">Depoimentos <span>(2)</span></button>
                    <button class="aba-adm" type="button" role="tab" aria-selected="false" data-aba-adm="grupos">Grupos de apoio <span>(2)</span></button>
                </div>

                <label class="filtro-adm">
                    <span class="somente-leitor-adm">Filtrar por status</span>
                    <select data-filtro-adm>
                        <option value="todos">Todos os status</option>
                        <option value="pendente">Pendentes</option>
                        <option value="aprovado">Aprovados</option>
                        <option value="recusado">Recusados</option>
                    </select>
                </label>
            </div>

            <div class="lista-adm" data-conteudo-adm="depoimentos">
                <article class="item-adm" data-status-adm="pendente">
                    <div class="corpo-item-adm">
                        <div class="autor-adm">
                            <span class="avatar-adm"></span>
                            <div><strong>Mariana Silva</strong><time datetime="2026-09-16">16/09/2026</time></div>
                            <span class="status-adm status-pendente-adm">Pendente</span>
                        </div>
                        <h2>Força para continuar</h2>
                        <p>O Vitalize me ajudou a encontrar pessoas que realmente me entendem. Aqui me sinto menos sozinha nessa jornada.</p>
                    </div>
                    <div class="acoes-adm">
                        <button class="botao-adm botao-aprovar-adm" type="button" data-acao-adm="aprovar">Aprovar</button>
                        <button class="botao-adm botao-recusar-adm" type="button" data-acao-adm="recusar">Recusar</button>
                    </div>
                </article>

                <article class="item-adm" data-status-adm="pendente">
                    <div class="corpo-item-adm">
                        <div class="autor-adm">
                            <span class="avatar-adm avatar-anonimo-adm"><i class="fa-solid fa-user"></i></span>
                            <div><strong>Anônimo</strong><time datetime="2026-09-14">14/09/2026</time></div>
                            <span class="status-adm status-pendente-adm">Pendente</span>
                        </div>
                        <h2>Diagnóstico e superação</h2>
                        <p>Receber o diagnóstico foi muito difícil, mas os relatos que li aqui me deram esperança. Hoje estou mais confiante.</p>
                    </div>
                    <div class="acoes-adm">
                        <button class="botao-adm botao-aprovar-adm" type="button" data-acao-adm="aprovar">Aprovar</button>
                        <button class="botao-adm botao-recusar-adm" type="button" data-acao-adm="recusar">Recusar</button>
                    </div>
                </article>
            </div>

            <div class="lista-adm" data-conteudo-adm="grupos" hidden>
                <article class="item-adm item-grupo-adm" data-status-adm="pendente">
                    <div class="imagem-grupo-adm"><i class="fa-solid fa-ribbon"></i></div>
                    <div class="corpo-item-adm">
                        <div class="topo-grupo-adm"><span class="categoria-adm">Pacientes em tratamento</span><span class="status-adm status-pendente-adm">Pendente</span></div>
                        <h2>Juntos na Quimioterapia</h2>
                        <p class="detalhes-adm"><i class="fa-regular fa-calendar"></i> 20/09/2026 · 19:00 · Ana Costa</p>
                        <p>Grupo de apoio para pessoas que estão em tratamento quimioterápico.</p>
                    </div>
                    <div class="acoes-adm">
                        <button class="botao-adm botao-aprovar-adm" type="button" data-acao-adm="aprovar">Aprovar</button>
                        <button class="botao-adm botao-recusar-adm" type="button" data-acao-adm="recusar">Recusar</button>
                    </div>
                </article>

                <article class="item-adm item-grupo-adm" data-status-adm="pendente">
                    <div class="imagem-grupo-adm imagem-grupo-roxa-adm"><i class="fa-regular fa-heart"></i></div>
                    <div class="corpo-item-adm">
                        <div class="topo-grupo-adm"><span class="categoria-adm">Familiares e cuidadores</span><span class="status-adm status-pendente-adm">Pendente</span></div>
                        <h2>Cuidar de Quem Cuida</h2>
                        <p class="detalhes-adm"><i class="fa-regular fa-calendar"></i> 23/09/2026 · 18:30 · Beatriz Lima</p>
                        <p>Encontro on-line para troca de experiências entre familiares e cuidadores.</p>
                    </div>
                    <div class="acoes-adm">
                        <button class="botao-adm botao-aprovar-adm" type="button" data-acao-adm="aprovar">Aprovar</button>
                        <button class="botao-adm botao-recusar-adm" type="button" data-acao-adm="recusar">Recusar</button>
                    </div>
                </article>
            </div>

            <p class="vazio-adm" data-vazio-adm hidden>Nenhum conteúdo encontrado com esse status.</p>
        </section>

        <div class="aviso-adm" role="status" aria-live="polite" data-aviso-adm></div>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        (() => {
            'use strict';

            const menuToggle = document.querySelector('.menu-toggle');
            const menu = document.querySelector('.menu');
            if (menuToggle && menu) {
                menuToggle.addEventListener('click', () => menu.classList.toggle('ativo'));
            }

            const painel = document.querySelector('#painelAdm');
            if (!painel) return;

            const abas = [...painel.querySelectorAll('[data-aba-adm]')];
            const listas = [...painel.querySelectorAll('[data-conteudo-adm]')];
            const filtro = painel.querySelector('[data-filtro-adm]');
            const vazio = painel.querySelector('[data-vazio-adm]');
            const aviso = painel.querySelector('[data-aviso-adm]');
            let tipoAtivo = 'depoimentos';
            let tempoAviso;

            const listaAtiva = () => painel.querySelector(`[data-conteudo-adm="${tipoAtivo}"]`);

            function aplicarFiltro() {
                let quantidadeVisivel = 0;
                listaAtiva().querySelectorAll('.item-adm').forEach(item => {
                    const mostrar = filtro.value === 'todos' || item.getAttribute('data-status-adm') === filtro.value;
                    item.hidden = !mostrar;
                    if (mostrar) quantidadeVisivel++;
                });
                vazio.hidden = quantidadeVisivel !== 0;
            }

            function mostrarAviso(mensagem) {
                aviso.textContent = mensagem;
                aviso.classList.add('aviso-visivel-adm');
                clearTimeout(tempoAviso);
                tempoAviso = setTimeout(() => aviso.classList.remove('aviso-visivel-adm'), 2500);
            }

            function alterarStatus(item, novoStatus) {
                const etiqueta = item.querySelector('.status-adm');
                const textos = { aprovado: 'Aprovado', recusado: 'Recusado' };
                item.setAttribute('data-status-adm', novoStatus);
                etiqueta.textContent = textos[novoStatus];
                etiqueta.className = `status-adm status-${novoStatus}-adm`;
                item.querySelectorAll('[data-acao-adm="aprovar"], [data-acao-adm="recusar"]').forEach(botao => botao.remove());
                aplicarFiltro();
                mostrarAviso(novoStatus === 'aprovado' ? 'Conteúdo aprovado com sucesso.' : 'Conteúdo recusado.');

            }

            abas.forEach(aba => aba.addEventListener('click', () => {
                tipoAtivo = aba.getAttribute('data-aba-adm');
                abas.forEach(item => {
                    const ativa = item === aba;
                    item.classList.toggle('ativa', ativa);
                    item.setAttribute('aria-selected', String(ativa));
                });
                listas.forEach(lista => lista.hidden = lista.getAttribute('data-conteudo-adm') !== tipoAtivo);
                aplicarFiltro();
            }));

            filtro.addEventListener('change', aplicarFiltro);

            painel.addEventListener('click', event => {
                const botao = event.target.closest('[data-acao-adm]');
                if (!botao) return;
                const item = botao.closest('.item-adm');
                const acao = botao.getAttribute('data-acao-adm');

                alterarStatus(item, acao === 'aprovar' ? 'aprovado' : 'recusado');
            });

            aplicarFiltro();
        })();
    </script>
</body>

</html>
