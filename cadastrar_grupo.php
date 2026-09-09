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

    <section class="cadastro-grupo">

        <img src="img/logov.png" class="logo-topo">

        <h2 class="h2titulo"> Cadastre um grupo de apoio</h2>


        <div class="linha"></div>

        <?php if (!empty($_SESSION['sucesso_grupo'])): ?>
            <p style="color: green; font-weight: bold; margin-bottom: 15px;">
                <?php echo htmlspecialchars($_SESSION['sucesso_grupo']); ?>
            </p>
            <?php unset($_SESSION['sucesso_grupo']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['erro_grupo'])): ?>
            <p style="color: red; font-weight: bold; margin-bottom: 15px;">
                <?php echo htmlspecialchars($_SESSION['erro_grupo']); ?>
            </p>
            <?php unset($_SESSION['erro_grupo']); ?>
        <?php endif; ?>

        <form action="processos/cadastrargrupo.php"
            method="post" class="login-form"
            enctype="multipart/form-data">
            <div class="container-grupo">

                <div class="upload-cx">

                    <input
                        type="file"
                        id="imagem"
                        name="imagem"
                        accept="image/png, image/jpeg, image/webp"
                        hidden>
                    <label for="imagem" class="btn-upload">+</label>

                    <img id="preview" style="display:none;">

                    <p id="texto">INSIRA UMA<br>IMAGEM AQUI</p>

                </div>
                <div class="dados-grupo">

                    <h2>Dados do Grupo</h2>

                    <label>Nome do grupo*</label>
                    <input type="text" name="nome" required>

                    <div class="linha-inputs">
                        <div>
                            <label>Data*</label>
                            <input type="date" name="data_encontro">
                        </div>

                        <div>
                            <label>Horário*</label>
                            <input type="time" name="horario">
                        </div>
                    </div>

                    <div class="linha-inputs">
                        <div>
                            <label>Link do grupo</label>
                            <input type="text" name="link">
                        </div>
                    </div>

                </div>

            </div>

            <div class="info-adicionais">

                <h2>Informações Adicionais</h2>

                <label>Foco em:</label>
                <input type="text" name="foco" placeholder="(ex: Idosos, Pacientes com...)">

                <label>Responsável pelo grupo*</label>
                <input type="text" name="responsavel">

                <label>Telefone do grupo</label>
                <input type="text" name="telefone_grupo" placeholder="(00)00000-0000">

                <label>Mais informações:</label>
                <textarea name="mais_info"></textarea>

            </div>

            <button type="submit" class="cadastrogrp-btn">CONFIRMAR</button>
        </form>
        <img src="img/borboleta.png" class="borboleta-direita">

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