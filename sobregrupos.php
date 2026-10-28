<?php
require_once __DIR__ . '/navegacao.php';
vitalize_exibir_aplicacao('sobregrupos.php');
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

                    <a href="conteudo.php?pagina=grupos" class="adecorado">
                        Cadastre-se em um Grupo
                    </a>

                    <a href="conteudo.php?pagina=cadastrar_grupo" class="adecorado">
                        Cadastrar um Novo Grupo
                    </a>

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
