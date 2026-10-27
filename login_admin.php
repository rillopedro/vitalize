<?php
declare(strict_types=1);
require_once __DIR__ . '/iniciar_sessao.php';
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login administrativo | Vitalize</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="img/logov.png">
</head>
<body>
    <main class="login-page">
        <section class="login-card login-admin-card" aria-labelledby="tituloLoginAdmin">
            <a href="login.php" class="login-btn-voltar" aria-label="Voltar ao login"><span class="material-symbols-outlined">arrow_back_ios_new</span></a>
            <img src="img/logov.png" class="login-logo" alt="Vitalize">
            <p class="login-admin-etiqueta">Área restrita</p>
            <h1 class="login-titulo" id="tituloLoginAdmin">Acesso administrativo</h1>
            <p class="login-admin-descricao">Entre com uma conta autorizada para acessar o painel de moderação.</p>

            <form class="login-form" id="formLoginAdmin" novalidate>
                <label for="emailAdmin">E-mail</label>
                <input type="email" class="login-input" id="emailAdmin" name="email" maxlength="150" required autocomplete="username">

                <label for="senhaAdmin">Senha</label>
                <input type="password" class="login-input" id="senhaAdmin" name="senha" maxlength="72" required autocomplete="current-password">

                <p class="login-admin-mensagem" id="mensagemLoginAdmin" role="status" aria-live="polite" hidden></p>
                <button class="login-btn" type="submit">Entrar no painel</button>
                <p class="subcadastro"><a class="sublink" href="login.php">Voltar ao login de usuário</a></p>
            </form>
        </section>
    </main>
    <script src="api/cliente.js?v=20261027-2"></script>
    <script>
    (() => {
        const formulario = document.querySelector('#formLoginAdmin');
        const mensagem = document.querySelector('#mensagemLoginAdmin');
        const botao = formulario.querySelector('button[type="submit"]');

        function informar(texto, erro = true) {
            mensagem.textContent = texto;
            mensagem.className = `login-admin-mensagem ${erro ? 'mensagem-erro' : 'mensagem-sucesso'}`;
            mensagem.hidden = false;
        }

        VitalizeAPI.requisicao('/api/me')
            .then(({ usuario }) => {
                if (usuario?.tipo === 'admin') window.location.replace('administrador.php');
            })
            .catch(() => {});

        formulario.addEventListener('submit', async evento => {
            evento.preventDefault();
            if (!formulario.reportValidity()) return;
            mensagem.hidden = true;
            botao.disabled = true;
            try {
                const dados = Object.fromEntries(new FormData(formulario).entries());
                const resposta = await VitalizeAPI.requisicao('/api/auth/login', { method: 'POST', body: dados });
                if (resposta.usuario?.tipo !== 'admin') {
                    try { await VitalizeAPI.requisicao('/api/auth/logout', { method: 'POST' }); } catch {}
                    informar('Esta conta não tem acesso administrativo. Use uma conta autorizada.');
                    return;
                }
                window.location.assign('administrador.php');
            } catch (erro) {
                informar(erro.message || 'Não foi possível entrar. Confira seus dados e tente novamente.');
            } finally {
                botao.disabled = false;
            }
        });
    })();
    </script>
</body>
</html>