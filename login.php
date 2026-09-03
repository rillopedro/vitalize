<?php 
    require_once 'conexao.php';
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
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="icon" type="image/png" href="img/logov.png">


    <link rel="stylesheet" href="css/style.css">

</head>

<body>
    <div class="login-page">
        <div class="login-card">

            <a href="index.php" class="login-btn-voltar">
                <span class="material-symbols-outlined">
                    arrow_back_ios_new
                </span>
            </a>

            <img src="img/logov.png" class="login-logo">

            <h1 class="login-titulo">Login</h1>

            <form class="login-form" method="POST" action="processos/processalogin.php">
                <label>Email:</label>
                <input type="email" class="login-input" name="email">

                <label>Senha:</label>
                <input type="password" class="login-input" name="senha">

                <button class="login-btn">ENTRAR</button>
                <p class="subcadastro">Não possui uma conta? <a class="sublink" href="cadastro.php">Cadastre-se</a> <br>
                 <a class="sublink" href="">Esqueci minha senha</a>
                </p>


            </form>

        </div>
    </div>
    <script>
        const toggle = document.querySelector(".menu-toggle");
        const menu = document.querySelector(".menu");

        toggle.addEventListener("click", () => {
            menu.classList.toggle("ativo");
        });
    </script>
</body>

</html>