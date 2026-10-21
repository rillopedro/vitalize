<?php
    require_once __DIR__ . '/iniciar_sessao.php';
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

            <?php if (!empty($_SESSION['erro_login'])): ?>
                <p class="mensagem-erro"><?= htmlspecialchars($_SESSION['erro_login']) ?></p>
                <?php unset($_SESSION['erro_login']); ?>
            <?php endif; ?>
            <?php if (!empty($_SESSION['mensagem_login'])): ?>
                <p class="mensagem-sucesso"><?= htmlspecialchars($_SESSION['mensagem_login'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php unset($_SESSION['mensagem_login']); ?>
            <?php endif; ?>

            <form class="login-form" method="POST" action="api/auth/login">
                <label>Email:</label>
                <input type="email" class="login-input" name="email" required autocomplete="email">

                <label>Senha:</label>
                <input type="password" class="login-input" name="senha" required autocomplete="current-password">

                <button class="login-btn">ENTRAR</button>
                <p class="subcadastro">Não possui uma conta? <a class="sublink" href="cadastro.php">Cadastre-se</a> <br>
                 <a class="sublink" href="recuperar_senha.php">Esqueci minha senha</a>
                </p>


            </form>

        </div>
    </div>
    <script src="api/cliente.js"></script>
    <script>
        const formularioLogin = document.querySelector('.login-form');
        formularioLogin.addEventListener('submit', async event => {
            event.preventDefault();
            const botao = formularioLogin.querySelector('button[type="submit"], button:not([type])');
            if (botao) botao.disabled = true;
            try {
                const dados = Object.fromEntries(new FormData(formularioLogin).entries());
                await VitalizeAPI.requisicao('/api/auth/login', { method: 'POST', body: dados });
                window.location.assign('pagperfil.php');
            } catch (erro) {
                alert(erro.message || 'Não foi possível entrar.');
            } finally {
                if (botao) botao.disabled = false;
            }
        });
        const mensagemLoginApi = sessionStorage.getItem('vitalize_mensagem_login');
        if (mensagemLoginApi) {
            const aviso = document.createElement('p');
            aviso.className = 'mensagem-sucesso';
            aviso.textContent = mensagemLoginApi;
            formularioLogin.before(aviso);
            sessionStorage.removeItem('vitalize_mensagem_login');
        }
    </script>
    <script>
        const toggle = document.querySelector(".menu-toggle");
        const menu = document.querySelector(".menu");

        if (toggle && menu) {
            toggle.addEventListener("click", () => {
                menu.classList.toggle("ativo");
            });
        }
    </script>
</body>

</html>
