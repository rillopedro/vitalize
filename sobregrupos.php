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

    <section class="banner-home">
        <img src="img/sobregrupos.jpg">
    </section>
    <section class="apoio-section">


        <div class="apoio-content">

            <div class="apoio-galeria">

                <div class="galeria-card">
                    <img src="img/apoio1.jpg" alt="">
                </div>

                <div class="galeria-card">
                    <img src="img/apoio2.jpg" alt="">
                </div>

            </div>

            <div class="apoio-texto">

                <h2>Grupos de Apoio</h2>

                <p>
                    Os grupos de apoio são fundamentais para oferecer acolhimento,
                    suporte emocional e troca de experiências entre pessoas que
                    enfrentam o câncer. Eles ajudam a reduzir a sensação de
                    isolamento, fortalecem a saúde mental e mostram que ninguém
                    precisa passar por essa jornada sozinho.
                </p>

                <div class="apoio-botoes">

                    <a href="grupos.php" class="adecorado">
                        Cadastre-se em um Grupo
                    </a>

                    <a href="cadastrar_grupo.php" class="adecorado">
                        Cadastrar um Novo Grupo
                    </a>

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