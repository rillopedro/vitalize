<?php
require_once __DIR__ . '/navegacao.php';
vitalize_exibir_aplicacao('relatos.php');
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
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="img/logov.png">


    <link rel="stylesheet" href="css/style.css?v=9">

</head>

<body>
    <nav class="header">

        <div class="logo-box">
            <img src="img/logov.png" alt="">
        </div>

        <div class="nav-bar">
            <ul class="menu">
                <li><a href="conteudo.php?pagina=index">Início</a></li>
                <li><a href="conteudo.php?pagina=sobregrupos">Grupos</a></li>
                <li><a href="conteudo.php?pagina=doacao">Doação</a></li>
                <li><a href="conteudo.php?pagina=saude">Saúde</a></li>
                <li><a href="conteudo.php?pagina=relatos">Relatos</a></li>
                <li><a href="conteudo.php?pagina=sobre">Sobre</a></li>
            </ul>

            <?php if (!empty($_SESSION['id_usuario'])): ?>
                <a href="conteudo.php?pagina=pagperfil" class="btnav">Perfil</a>
            <?php else: ?>
                <a href="conteudo.php?pagina=login" class="btnav">Entrar</a>
            <?php endif; ?>
            <button class="menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>

    </nav>

    <section class="depoimentos-page">

        <div class="cabecalho-relatos">
            <h1>Compartilhe sua história</h1>
            <p>
                Este é um espaço seguro para compartilhar experiências,
                sentimentos e histórias durante a jornada contra o câncer.
            </p>
        </div>

        <div class="topo-depoimentos">
            <!-- sua caixa de depoimento aqui -->
        </div>


        <!-- Formulário -->
        <div class="topo-depoimentos">

            <form id="formRelato" class="form-relato" method="post" action="api/stories">


                <label>Título do relato</label>
                <input type="text" name="titulo" placeholder="Digite o título" required>

                <label>Seu relato</label>
                <textarea name="conteudo" rows="4" placeholder="Escreva seu relato" required></textarea>

                <div class="anonimo-box">
                    <p>Gostaria que seu relato seja anônimo?</p>

                    <label>
                        <input type="radio" name="anonimo" value="1"> Sim
                    </label>

                    <label>
                        <input type="radio" name="anonimo" value="0" checked> Não
                    </label>
                </div>

                <label class="anonimo-box">
                    <input type="checkbox" name="consentimento_publicacao" value="1" required>
                    Concordo que este relato seja publicado após análise. Entendo que devo remover dados pessoais ou informações médicas que não quero compartilhar.
                </label>

                <button class="btn-relato" type="submit">
                    Enviar Relato
                </button>
            </form>


        </div>

        <?php if (!empty($_SESSION['sucesso_relato'])): ?>
            <p style="color: green; font-weight: bold; margin: 20px 0;">
                <?php echo htmlspecialchars($_SESSION['sucesso_relato']); ?>
            </p>
            <?php unset($_SESSION['sucesso_relato']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['erro_relato'])): ?>
            <p style="color: red; font-weight: bold; margin: 20px 0;">
                <?php echo htmlspecialchars($_SESSION['erro_relato']); ?>
            </p>
            <?php unset($_SESSION['erro_relato']); ?>
        <?php endif; ?>

        <!-- Título -->
        <div class="titulo-depoimentos">
            <h2>Outros Depoimentos</h2>
        </div>

        <!-- Cards -->
        <div class="lista-depoimentos" id="listaDepoimentos" aria-live="polite"></div>
        <div class="rodape-depoimentos">
            <p id="contadorDepoimentos" aria-live="polite"></p>

            <button type="button" id="verMaisDepoimentos" class="btn-relato" hidden>
                Ver mais depoimentos
                <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
            </button>
        </div>


    </section>

    <?php include 'footer.php'; ?>
    <script src="api/cliente.js?v=20261027-2"></script>
    <script>
        const toggle = document.querySelector(".menu-toggle");
        const menu = document.querySelector(".menu");

        if (toggle && menu) {
            toggle.addEventListener("click", () => {
                menu.classList.toggle("ativo");
            });
        }

        let depoimentos = Array.from(
            document.querySelectorAll("[data-depoimento]")
        );

        const botaoVerMais = document.getElementById("verMaisDepoimentos");
        const contador = document.getElementById("contadorDepoimentos");

        const quantidadePorVez = 6;
        let quantidadeVisivel = Math.min(quantidadePorVez, depoimentos.length);

        function textoSeguro(valor) {
            return String(valor ?? '').replace(/[&<>"']/g, caractere => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[caractere]));
        }
        async function carregarRelatos() {
            const lista = document.getElementById('listaDepoimentos');
            try {
                const resultado = await VitalizeAPI.requisicao('/api/stories');
                lista.innerHTML = (resultado.relatos || []).map(relato => {
                    const nome = relato.anonimo ? '@Anônimo' : `@${textoSeguro(relato.nome || 'Usuário')}`;
                    const avatar = !relato.anonimo && relato.foto_perfil
                        ? `<img src="${textoSeguro(relato.foto_perfil)}" alt="Foto de perfil">`
                        : '<span class="avatar-placeholder"><i class="fa-solid fa-user"></i></span>';
                    const data = new Date(String(relato.data_publicacao).replace(' ', 'T')).toLocaleDateString('pt-BR');
                    return `<div class="card-depoimento" data-depoimento><div class="cabecalho-depoimento"><div class="avatar">${avatar}</div><div><h4>${nome}</h4><span>${data}</span></div></div><div class="texto-depoimento"><h3>${textoSeguro(relato.titulo)}</h3><p style="white-space:pre-line">${textoSeguro(relato.conteudo)}</p></div></div>`;
                }).join('');
                depoimentos = Array.from(lista.querySelectorAll('[data-depoimento]'));
                quantidadeVisivel = Math.min(quantidadePorVez, depoimentos.length);
                atualizarDepoimentos();
            } catch (erro) {
                lista.textContent = erro.message || 'Não foi possível carregar os relatos.';
            }
        }
        document.getElementById('formRelato').addEventListener('submit', async event => {
            event.preventDefault();
            const form = event.currentTarget;
            const valores = Object.fromEntries(new FormData(form).entries());
            try {
                const resposta = await VitalizeAPI.requisicao('/api/stories', { method: 'POST', body: valores });
                alert(resposta.mensagem || 'Relato enviado para análise.');
                window.location.reload();
            } catch (erro) { alert(erro.message || 'Não foi possível publicar o relato.'); }
        });

        function atualizarDepoimentos() {
            depoimentos.forEach((card, indice) => {
                card.hidden = indice >= quantidadeVisivel;
            });

            contador.textContent = depoimentos.length
                ? `Mostrando ${quantidadeVisivel} de ${depoimentos.length} depoimentos`
                : "Nenhum depoimento publicado ainda.";

            const restantes = depoimentos.length - quantidadeVisivel;

            botaoVerMais.hidden = restantes === 0;

            if (restantes > 0) {
                const quantidade = Math.min(quantidadePorVez, restantes);

                botaoVerMais.innerHTML = `
                Ver mais ${quantidade} ${quantidade === 1 ? "depoimento" : "depoimentos"}
                <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
            `;
            }
        }

        botaoVerMais.addEventListener("click", () => {
            const primeiroNovo = depoimentos[quantidadeVisivel];

            quantidadeVisivel = Math.min(
                quantidadeVisivel + quantidadePorVez,
                depoimentos.length
            );

            atualizarDepoimentos();

            if (primeiroNovo) {
                primeiroNovo.setAttribute("tabindex", "-1");
                primeiroNovo.focus({ preventScroll: true });

                primeiroNovo.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            }
        });

        atualizarDepoimentos();
        carregarRelatos();
    </script>
</body>

</html>
