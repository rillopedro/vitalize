<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

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

        <h2 class="h2titulo">Cabeleireiras Parceiras</h2>

        <p class="subtitulo">
            Conheça profissionais parceiras que ajudam no processo de corte e doação.
        </p>

        <div class="cards-parceiras">

            <div class="card-salao">
                <img src="img/salao.webp">

                <div class="info-salao">
                    <h3>Studio Ana Hair</h3>

                    <span> Ribeirão Pires - SP</span>

                    <div class="tag">Parceira solidária</div>

                    <button>Entrar em contato</button>
                </div>
            </div>

            <div class="card-salao">
                <img src="img/salao2.jpg">

                <div class="info-salao">
                    <h3>Espaço Bella Hair</h3>

                    <span> Santo André - SP</span>

                    <div class="tag">Parceira solidária</div>

                    <button>Entrar em contato</button>
                </div>
            </div>

            <div class="card-salao">
                <img src="img/salao3.jpg">

                <div class="info-salao">
                    <h3>Espaço Sarah Hair</h3>

                    <span> Mauá- SP</span>

                    <div class="tag">Parceira solidária</div>

                    <button>Entrar em contato</button>
                </div>
            </div>

            <div class="card-salao">
                <img src="img/salao4.jpg">

                <div class="info-salao">
                    <h3>Espaço Carla Hair</h3>

                    <span> Ribeirão Pires - SP</span>

                    <div class="tag">Parceira solidária</div>

                    <button>Entrar em contato</button>
                </div>
            </div>

        </div>

    </section>
    <footer class="footer">

        <div class="footer-redes">
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>

        <div class="footer-logo">
            <img src="img/logobrancacomp.png">
        </div>

        <div class="footer-info">
            <p>© 2026 Vitalize — Apoio ao tratamento contra o câncer</p>
            <p>Todos os direitos reservados | CNPJ 00.000.000/0001-00</p>
            <p>SAC 0800 000 0000</p>
        </div>

    </footer>
    <script>
        const toggle = document.querySelector(".menu-toggle");
        const menu = document.querySelector(".menu");

        toggle.addEventListener("click", () => {
            menu.classList.toggle("ativo");
        });
    </script>

</body>

</html>