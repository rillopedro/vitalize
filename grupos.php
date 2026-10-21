<?php
require_once __DIR__ . '/iniciar_sessao.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitalize</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="img/logov.png">

    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <nav class="header">

        <div class="logo-box">
            <img src="img/logov.png" alt="">
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
            <button class="menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>

    </nav>

    <section class="grupos">

        <img src="img/logov.png" class="logo-topo">

        <h1 class="h1titulo">Cadastre-se em um grupo de apoio</h1>
        <div class="linha"></div>

        <div class="cardsgrupos" id="listaGrupos" aria-live="polite">
            <p style="width:100%; text-align:center; color:#666;">Carregando grupos...</p>
        </div>



    </section>

    <?php include 'footer.php'; ?>


    <div class="modal" id="modalInfo">

        <div class="modal-conteudo">

            <div class="modal-topo">
                <button class="fechar-modal" type="button">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>

                <h2>Mais Informações</h2>
            </div>
            <img id="modalImagem" src="" alt="Imagem do grupo">
            <div class="modal-corpo">
                <h2 class="h2titulo" id="modalNomeGrupo">Título do grupo</h2>
                <h3 id="modalFoco">Foco: ...</h3>

                <div class="modal-dados">
                    <span id="modalHorario">Horário</span>
                    <span id="modalData">Data</span>
                </div>

                <p class="modal-texto" id="modalDescricao">
                    Descrição do grupo.
                </p>

                <div id="modalContato">
                    <p><strong>Link:</strong> <a href="#" target="_blank" id="modalLink"></a></p>
                    <p><strong>Telefone:</strong> <span id="modalTelefone"></span></p>
                </div>

                <h4>
                    Esperamos você para juntos construirmos uma rede de apoio, cuidado e esperança!
                </h4>


                <div class="inscricao-grupo">
                    <p>
                        Quer participar? Enviaremos o link para o e-mail da sua conta.
                    </p>

                    <button class="botao-inscricao-grupo" id="botaoInscricaoGrupo" type="button">
                        <i class="fa-regular fa-envelope"></i>
                        Receber link por e-mail
                    </button>

                    <p class="mensagem-inscricao-grupo" id="mensagemInscricaoGrupo" aria-live="polite"></p>
                </div>
            </div>

        </div>

    </div>
    <script src="api/cliente.js"></script>
    <script>
        const modal = document.getElementById("modalInfo");
        const modalNome = document.getElementById("modalNomeGrupo");
        const modalFoco = document.getElementById("modalFoco");
        const modalHorario = document.getElementById("modalHorario");
        const modalData = document.getElementById("modalData");
        const modalDescricao = document.getElementById("modalDescricao");
        const modalLink = document.getElementById("modalLink");
        const modalTelefone = document.getElementById("modalTelefone");
        const modalImagem = document.getElementById("modalImagem");

        const botaoFecharModal = document.querySelector(
            ".fechar-modal"
        );

        const botaoInscricao = document.getElementById(
            "botaoInscricaoGrupo"
        );

        const mensagemInscricao = document.getElementById(
            "mensagemInscricaoGrupo"
        );

        const toggle = document.querySelector(".menu-toggle");
        const menu = document.querySelector(".menu");

        let linkGrupoAtual = "#";
        let nomeGrupoAtual = "Grupo de apoio";
        let idGrupoAtual = null;

        function escaparHtml(valor) {
            return String(valor ?? '').replace(/[&<>"']/g, caractere => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[caractere]));
        }
        function dataGrupo(valor) {
            if (!valor) return 'Data a definir';
            const partes = String(valor).slice(0, 10).split('-');
            return partes.length === 3 ? `${partes[2]}/${partes[1]}/${partes[0]}` : 'Data a definir';
        }
        async function carregarGrupos() {
            const lista = document.getElementById('listaGrupos');
            try {
                const resposta = await VitalizeAPI.requisicao('/api/groups');
                const grupos = resposta.grupos || [];
                if (!grupos.length) { lista.innerHTML = '<p style="width:100%; text-align:center; color:#666;">Nenhum grupo cadastrado ainda.</p>'; return; }
                lista.innerHTML = grupos.map(grupo => {
                    const nome = escaparHtml(grupo.nome_grupo);
                    const foco = escaparHtml(grupo.foco || 'Grupo de apoio');
                    const data = dataGrupo(grupo.data_encontro);
                    const horario = grupo.horario ? escaparHtml(String(grupo.horario).slice(0, 5)) : 'Horário a definir';
                    const info = escaparHtml(grupo.mais_info || 'Grupo de apoio para pessoas em tratamento.');
                    const link = escaparHtml(grupo.link || '#');
                    const telefone = escaparHtml(grupo.telefone_grupo || 'Não informado');
                    const imagem = escaparHtml(grupo.imagem || 'img/apoio1.webp');
                    return `<div class="cardgrupos"><img src="${imagem}" alt="${nome}"><div class="conteudocards"><span class="taggrupo">${foco}</span><h3>${nome}</h3><div class="infosgrupo"><span>${data}</span><span>${horario}</span></div><p class="focogrupo">${info}</p><div class="botoescards"><button class="infogrupos" data-id-grupo="${Number(grupo.id_grupo)}" data-nome="${nome}" data-foco="${foco}" data-data="${data}" data-horario="${horario}" data-info="${info}" data-link="${link}" data-telefone="${telefone}" data-imagem="${imagem}">Mais info</button></div></div></div>`;
                }).join('');
            } catch (erro) { lista.innerHTML = `<p role="alert">${escaparHtml(erro.message || 'Não foi possível carregar os grupos.')}</p>`; }
        }
        carregarGrupos();

        function abrirModal() {
            modal.classList.add("ativo");
            document.body.classList.add("modal-aberto");
        }

        function fecharModal() {
            modal.classList.remove("ativo");
            document.body.classList.remove("modal-aberto");

            mensagemInscricao.textContent = "";
            mensagemInscricao.className =
                "mensagem-inscricao-grupo";
        }

        function atualizarBotaoInscricao() {
            const semLink = !linkGrupoAtual || linkGrupoAtual.trim() === "#";

            botaoInscricao.classList.toggle("link-indisponivel", semLink);
            botaoInscricao.disabled = semLink;

            botaoInscricao.style.setProperty(
                "cursor",
                semLink ? "not-allowed" : "pointer",
                "important"
            );

            botaoInscricao.innerHTML = semLink
                ? '<i class="fa-solid fa-link-slash"></i> Link indisponível'
                : '<i class="fa-regular fa-envelope"></i> Receber link por e-mail';
        }

        document.querySelector(".cardsgrupos").addEventListener("click", event => {
            const botao = event.target.closest(".infogrupos");
            if (!botao) return;
                const nome =
                    botao.getAttribute("data-nome") ||
                    "Grupo";

                const foco =
                    botao.getAttribute("data-foco") ||
                    "Grupo de apoio";

                const data =
                    botao.getAttribute("data-data") ||
                    "Data a definir";

                const horario =
                    botao.getAttribute("data-horario") ||
                    "Horário a definir";

                const info =
                    botao.getAttribute("data-info") ||
                    "Descrição não disponível.";

                const link =
                    botao.getAttribute("data-link") ||
                    "#";

                const telefone =
                    botao.getAttribute("data-telefone") ||
                    "Não informado";

                const imagem =
                    botao.getAttribute("data-imagem") ||
                    "img/apoio1.webp";

                linkGrupoAtual = link;
                nomeGrupoAtual = nome;
                idGrupoAtual = Number(botao.dataset.idGrupo);

                modalNome.textContent = nome;
                modalFoco.textContent = "Foco: " + foco;
                modalData.textContent = data;
                modalHorario.textContent = horario;
                modalDescricao.textContent = info;
                modalTelefone.textContent = telefone;

                modalImagem.src = imagem;
                modalImagem.alt =
                    "Imagem do grupo " + nome;

                if (link !== "#") {
                    modalLink.textContent = link;
                    modalLink.href = link;
                    modalLink.target = "_blank";
                    modalLink.rel = "noopener noreferrer";
                } else {
                    modalLink.textContent =
                        "Sem link disponível";

                    modalLink.removeAttribute("href");
                    modalLink.removeAttribute("target");
                    modalLink.removeAttribute("rel");
                }

                mensagemInscricao.textContent = "";
                mensagemInscricao.className =
                    "mensagem-inscricao-grupo";

                atualizarBotaoInscricao();
                abrirModal();
        });

        botaoInscricao.addEventListener("click", async () => {
            if (linkGrupoAtual === "#") {
                return;
            }

            botaoInscricao.disabled = true;

            botaoInscricao.innerHTML =
                '<i class="fa-solid fa-spinner fa-spin"></i> Enviando...';

            mensagemInscricao.textContent = "";
            mensagemInscricao.className =
                "mensagem-inscricao-grupo";

            try {
                const resultado = await VitalizeAPI.requisicao(`/api/groups/${idGrupoAtual}/share`, { method: 'POST', body: {} });

                mensagemInscricao.textContent = resultado.mensagem || "Link enviado para o e-mail da sua conta.";

                mensagemInscricao.classList.add("sucesso");
            } catch (erro) {
                mensagemInscricao.textContent =
                    erro.message ||
                    "Não foi possível enviar o link.";

                mensagemInscricao.classList.add("erro");
            } finally {
                atualizarBotaoInscricao();
            }
        });

        botaoFecharModal.addEventListener(
            "click",
            fecharModal
        );

        modal.addEventListener("click", event => {
            if (event.target === modal) {
                fecharModal();
            }
        });

        document.addEventListener("keydown", event => {
            if (
                event.key === "Escape" &&
                modal.classList.contains("ativo")
            ) {
                fecharModal();
            }
        });

        if (toggle && menu) {
            toggle.addEventListener("click", () => {
                menu.classList.toggle("ativo");
            });
        }
    </script>
</body>

</html>
