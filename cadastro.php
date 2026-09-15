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
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"><!--icone voltar-->
    <link rel="icon" type="image/png" href="img/logov.png">

    <link rel="stylesheet" href="css/style.css">

</head>

<body>
    <div class="cadastro-page">
        <div class="cadastro-card">

            <a href="index.php" class="login-btn-voltar">
                <span class="material-symbols-outlined">
                    arrow_back_ios_new
                </span>
            </a>

            <img src="img/logov.png" class="login-logo">

            <h1 class="login-titulo">Cadastre-Se</h1>

            <?php if (!empty($_SESSION['erro_cadastro'])): ?>
                <p class="mensagem-erro"><?= htmlspecialchars($_SESSION['erro_cadastro']) ?></p>
                <?php unset($_SESSION['erro_cadastro']); ?>
            <?php endif; ?>

            <form action="processos/cadastrar.php" class="login-form" method="post">
                <label>Nome:</label>
                <input type="text" class="login-input" name="nome" placeholder="Digite seu nome" required
                    autocomplete="given-name">

                <label>Sobrenome:</label>
                <input type="text" class="login-input" name="sobrenome" placeholder="Digite seu sobrenome"
                    autocomplete="family-name">

                <label>Email:</label>
                <input type="email" class="login-input" name="email" placeholder="exemplo@gmail.com" required
                    autocomplete="email">

                <label>Telefone:</label>
                <input type="tel" class="login-input" name="telefone" id="telefone" placeholder="(11) 99999-9999"
                    maxlength="15" autocomplete="tel">

                <label>Crie uma Senha:</label>
                <input type="password" class="login-input" name="senha" id="senha" placeholder="Digite sua senha"
                    required autocomplete="new-password">

                <label>Repita a Senha:</label>
                <input type="password" class="login-input" name="confirmar_senha" id="confirmar_senha"
                    placeholder="Repita sua senha" required autocomplete="new-password">

                <button class="login-btn">Cadastrar</button>

                <p class="subcadastro">Já tem uma conta? <a class="sublink" href="login.php">Clique Aqui</a> </p>

            </form>

        </div>
    </div>

    <script>
        const toggle = document.querySelector(".menu-toggle");
        const menu = document.querySelector(".menu");

        if (toggle && menu) {
            toggle.addEventListener("click", () => {
                menu.classList.toggle("ativo");
            });
        }


        // FORMULÁRIO
        const form = document.querySelector(".login-form");
        const telefone = document.getElementById("telefone");
        const senha = document.getElementById("senha");
        const confirmarSenha = document.getElementById("confirmar_senha");


        // MÁSCARA DO TELEFONE
        telefone.addEventListener("input", function () {

            let valor = telefone.value.replace(/\D/g, "");

            if (valor.length > 11) {
                valor = valor.substring(0, 11);
            }

            if (valor.length <= 2) {

                valor = valor.replace(
                    /^(\d{0,2})/,
                    "($1"
                );

            } else if (valor.length <= 6) {

                valor = valor.replace(
                    /^(\d{2})(\d{0,4})/,
                    "($1) $2"
                );

            } else {

                valor = valor.replace(
                    /^(\d{2})(\d{5})(\d{0,4})/,
                    "($1) $2-$3"
                );
            }

            telefone.value = valor;
        });


        // VALIDAÇÃO DO FORMULÁRIO
        form.addEventListener("submit", function (event) {

            const nome = document.querySelector('[name="nome"]');
            const sobrenome = document.querySelector('[name="sobrenome"]');
            const email = document.querySelector('[name="email"]');


            if (!nome.checkValidity()) {
                event.preventDefault();
                alert("Digite um nome válido.");
                nome.focus();
                return;
            }


            if (sobrenome.value.trim() !== "" && !sobrenome.checkValidity()) {
                event.preventDefault();
                alert("Digite um sobrenome válido.");
                sobrenome.focus();
                return;
            }


            if (!email.checkValidity()) {
                event.preventDefault();
                alert("Digite um e-mail válido.");
                email.focus();
                return;
            }


            const numerosTelefone = telefone.value.replace(/\D/g, "");

            if (numerosTelefone.length > 0 && numerosTelefone.length !== 11) {
                event.preventDefault();
                alert("Digite um telefone válido.");
                telefone.focus();
                return;
            }


            if (senha.value.length < 6) {
                event.preventDefault();
                alert("A senha deve ter pelo menos 6 caracteres.");
                senha.focus();
                return;
            }


            if (senha.value !== confirmarSenha.value) {
                event.preventDefault();
                alert("As senhas não coincidem.");
                confirmarSenha.focus();
                return;
            }

        });

    </script>

</body>

</html>