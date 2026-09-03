<?php
session_start();

if (empty($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Vitalize</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-page">
        <div class="login-card">
            <h1 class="login-titulo">Bem-vindo(a)</h1>
            <p>Você entrou com sucesso.</p>
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($_SESSION['nome'] ?? ''); ?></p>
            <p><strong>E-mail:</strong> <?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></p>
            <a class="login-btn" href="index.php">Voltar para o início</a>
        </div>
    </div>
</body>
</html>
