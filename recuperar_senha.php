<?php
require_once __DIR__ . '/navegacao.php';
vitalize_exibir_aplicacao('recuperar_senha.php');
require_once __DIR__ . '/iniciar_sessao.php';
$_SESSION['csrf_recuperacao'] ??= bin2hex(random_bytes(32));
$mensagem = $_SESSION['mensagem_recuperacao'] ?? '';
unset($_SESSION['mensagem_recuperacao']);
$etapa = $_GET['etapa'] ?? 'email';
if (!in_array($etapa, ['email', 'codigo'], true)) $etapa = 'email';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar senha | Vitalize</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="img/logov.png">
</head>
<body>
    <main class="recuperacao-page">
        <section class="recuperacao-card">
            <a href="conteudo.php?pagina=login" class="recuperacao-voltar">← Voltar ao login</a>
            <img src="img/logov.png" alt="Vitalize" class="login-logo">
            <h1>Recuperar senha</h1>
            <?php if ($mensagem !== ''): ?><p class="mensagem-recuperacao" role="status"><?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
            <?php if ($etapa === 'email'): ?>
                <p>Informe seu e-mail cadastrado. Enviaremos um código para você criar uma nova senha.</p>
                <form id="formSolicitarCodigo" method="post" action="api/password/forgot" class="login-form">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf_recuperacao'], ENT_QUOTES, 'UTF-8') ?>">
                    <label for="emailRecuperacao">E-mail</label>
                    <input id="emailRecuperacao" class="login-input" type="email" name="email" maxlength="150" autocomplete="email" required>
                    <button class="login-btn" type="submit">Enviar código</button>
                </form>
            <?php else: ?>
                <p>Se o e-mail estiver cadastrado, você receberá um código de seis dígitos. Ele vale por 10 minutos.</p>
                <form id="formRedefinirSenha" method="post" action="api/password/reset" class="login-form">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf_recuperacao'], ENT_QUOTES, 'UTF-8') ?>">
                    <label for="emailRecuperacao">E-mail</label>
                    <input id="emailRecuperacao" class="login-input" type="email" name="email" maxlength="150" autocomplete="email" required>
                    <label for="codigoRecuperacao">Código recebido</label>
                    <input id="codigoRecuperacao" class="login-input" type="text" name="codigo" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code" required>
                    <label for="novaSenha">Nova senha</label>
                    <input id="novaSenha" class="login-input" type="password" name="senha" minlength="6" maxlength="72" autocomplete="new-password" required>
                    <label for="confirmarSenha">Confirme a nova senha</label>
                    <input id="confirmarSenha" class="login-input" type="password" name="confirmar_senha" minlength="6" maxlength="72" autocomplete="new-password" required>
                    <button class="login-btn" type="submit">Redefinir senha</button>
                </form>
                <p class="subcadastro"><a class="sublink" href="conteudo.php?pagina=recuperar_senha">Solicitar outro código</a></p>
            <?php endif; ?>
        </section>
    </main>
    <script src="api/cliente.js?v=20261027-2"></script>
    <script>
        document.getElementById('formSolicitarCodigo')?.addEventListener('submit', async event => {
            event.preventDefault();
            const form = event.currentTarget;
            try {
                const dados = Object.fromEntries(new FormData(form).entries());
                const resposta = await VitalizeAPI.requisicao('/api/password/forgot', { method: 'POST', body: dados });
                sessionStorage.setItem('vitalize_mensagem_senha', resposta.mensagem || 'Confira seu e-mail e a caixa de spam.');
                window.location.assign('conteudo.php?pagina=recuperar_senha&etapa=codigo');
            } catch (erro) { alert(erro.message || 'Não foi possível solicitar o código.'); }
        });
        document.getElementById('formRedefinirSenha')?.addEventListener('submit', async event => {
            event.preventDefault();
            const form = event.currentTarget;
            const dados = Object.fromEntries(new FormData(form).entries());
            if (dados.senha !== dados.confirmar_senha) { alert('As senhas não coincidem.'); return; }
            try {
                const resposta = await VitalizeAPI.requisicao('/api/password/reset', { method: 'POST', body: dados });
                sessionStorage.setItem('vitalize_mensagem_login', resposta.mensagem || 'Senha alterada.');
                window.location.assign('conteudo.php?pagina=login');
            } catch (erro) { alert(erro.message || 'Não foi possível redefinir a senha.'); }
        });
        const mensagemRecuperacao = sessionStorage.getItem('vitalize_mensagem_senha');
        if (mensagemRecuperacao) {
            const aviso = document.querySelector('.mensagem-recuperacao');
            if (aviso) aviso.textContent = mensagemRecuperacao;
            sessionStorage.removeItem('vitalize_mensagem_senha');
        }
    </script>
</body>
</html>
