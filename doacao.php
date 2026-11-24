<?php
require_once __DIR__ . '/navegacao.php';
vitalize_exibir_aplicacao('doacao.php');
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

    <link rel="stylesheet" href="css/style.css">
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

    <section class="doacao-cabelo">

        <section class="intro-doacao">

            <h2 class="h2titulo">Doação de Cabelo</h2>

            <p class="texto-doacao">
                A doação de cabelo é um gesto simples que pode transformar vidas. Para muitas pessoas em tratamento
                contra o câncer, a perda de cabelo afeta não apenas a aparência, mas também a autoestima e a confiança.
                Ao doar, você ajuda na produção de perucas que proporcionam conforto, acolhimento e esperança durante um
                momento tão delicado.
                Mais do que fios de cabelo, sua doação entrega carinho, empatia e força para quem está enfrentando uma
                batalha difícil. Um pequeno gesto pode fazer uma grande diferença na vida de alguém.
            </p>
            <img src="img/linha.png" class="banner-doacao">

        </section>

        <div class="galeria">
            <img src="img/cabelo (2).jpg">
            <img src="img/cabelo (3).jpg">
            <img src="img/cabelo.jpg">
            <img src="img/cabelo7.png">
            <img src="img/cabelo5.jpg">
            <img src="img/cabelo6.png">
        </div>

    </section>


    <section class="parceiras">

        <h2 class="h2titulo">Parceiros do Vitalize</h2>

        <p class="subtitulo">
            Conheça quem apoia o projeto e visite os perfis dos nossos parceiros.
        </p>

        <div class="cards-parceiras">

            <div class="card-salao">
                <img src="img/parceiro-ircc.jpg" alt="Foto de perfil do Instituto Romatt de Combate ao Câncer">

                <div class="info-salao">
                    <h3>Instituto Romatt de Combate ao Câncer</h3>
                    <span>@ircc.br</span>
                    <p>Instituto com projetos de apoio e combate ao câncer.</p>
                    <div class="tag">Instituto parceiro</div>
                    <a class="botao-parceiro" href="https://www.instagram.com/ircc.br/" target="_blank" rel="noopener noreferrer">Ver no Instagram</a>
                </div>
            </div>

            <div class="card-salao">
                <img src="img/parceiro-doe-lenco.jpg" alt="Foto de perfil do projeto Doe Lenço, Doe Alegria">

                <div class="info-salao">
                    <h3>Doe Lenço, Doe Alegria</h3>
                    <span>@doelencodoealegria</span>
                    <p>Projeto de mobilização e apoio na luta contra o câncer.</p>
                    <div class="tag">Projeto parceiro</div>
                    <a class="botao-parceiro" href="https://www.instagram.com/doelencodoealegria/" target="_blank" rel="noopener noreferrer">Ver no Instagram</a>
                </div>
            </div>

            <div class="card-salao">
                <img src="img/parceiro-absoluto.jpg" alt="Foto de perfil do Salão de Beleza Absoluto">

                <div class="info-salao">
                    <h3>Salão de Beleza Absoluto</h3>
                    <span>@salaodebelezaabsoluto</span>
                    <p>Cuidado, beleza e autoestima, com atendimento com hora marcada.</p>
                    <div class="tag">Salão parceiro</div>
                    <a class="botao-parceiro" href="https://www.instagram.com/salaodebelezaabsoluto/" target="_blank" rel="noopener noreferrer">Ver no Instagram</a>
                </div>
            </div>

        </div>

    </section>
    
      <?php include 'footer.php'; ?>

    <script>
        const toggle = document.querySelector(".menu-toggle");
        const menu = document.querySelector(".menu");

        toggle.addEventListener("click", () => {
            menu.classList.toggle("ativo");
        });
    </script>

</body>

</html>

