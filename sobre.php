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

    <section>
        <h2 class="h2titulo">Sobre Nós</h2>
        <div class="sobre-card">
            <p>O Vitalize é uma plataforma digital criada com o objetivo de apoiar pessoas em tratamento contra o
                câncer,
                oferecendo acolhimento, informação e suporte em um só lugar. O projeto foi desenvolvido no ambiente
                escolar
                como Trabalho de Conclusão de Curso, unindo tecnologia e cuidado com o próximo.</p>

            <p> A plataforma reúne recursos importantes para auxiliar durante essa jornada, como notícias positivas,
                apoio à
                saúde, agendamento de grupos de apoio, além de iniciativas solidárias como a doação de cabelo para a
                produção de perucas.</p>

            <p>O Vitalize busca facilitar o acesso a informações e serviços, promovendo bem-estar, empatia e conexão
                entre
                pessoas que estão passando por situações semelhantes. Mais do que um site, é um espaço pensado para
                acolher,
                informar e apoiar em todos os momentos.</p>
        </div>
    </section>

    <section class="integrantesec">
        <h2 class="h2titulo">Integrantes</h2>

        <div class="integrantes-container">

            <div class="card-integrante">
                <img src="img/katarina.jpeg" alt="Integrante 1">
                <h3>Katarina Silva</h3>
                <p>17 Anos</p>
            </div>

            <div class="card-integrante">
                <img src="img/leticia.jpeg" alt="Integrante 2">
                <h3>Letícia Vasconcelos</h3>
                <p>17 Anos</p>
            </div>

            <div class="card-integrante">
                <img src="img/mayara.jpeg" alt="Integrante 3">
                <h3>Mayara Sant'Anna</h3>
                <p>17 Anos</p>
            </div>
            <div class="card-integrante">
                <img src="img/pedro.jpeg" alt="Integrante 2">
                <h3>Pedro Rillo</h3>
                <p>17 Anos</p>
            </div>

            <div class="card-integrante">
                <img src="img/vitoria.jpeg" alt="Integrante 3">
                <h3>Vitória Guedes</h3>
                <p>17 Anos</p>
            </div>

        </div>
    </section>

    <section class="agradecimentosprof">
        <h2 class="h2titulo">Agradecimentos</h2>

        <div class="prof-container">
            <div class="texto-prof">
                <img src="img/cintia.jpg">
                <h3>Cíntia Pinho</h3>
            </div>

            <div class="card-agradecimento">
                <p>
                    À nossa professora orientadora, Cíntia Pinho, agradeçemos pela dedicação, paciência e apoio durante
                    todo o desenvolvimento deste trabalho. Sua orientação é essencial para a realização deste projeto e
                    para o nosso crescimento. Muito obrigada por todo incentivo e confiança.
                </p>
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
        });</script>
</body>

</html>