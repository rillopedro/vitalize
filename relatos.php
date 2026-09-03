<?php
session_start();
require_once 'conexao.php';

try {
   $sql = "SELECT
            r.titulo,
            r.relato AS conteudo,
            r.data_publicacao,
            r.anonimo,
            u.nome,
            u.foto_perfil
        FROM relatos r
        LEFT JOIN usuarios u
            ON u.id_usuario = r.id_usuario
        ORDER BY r.data_publicacao DESC";
    $stmt = $pdo->query($sql);
    $relatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $relatos = [];
}
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

    <section class="depoimentos-page">

        <!-- Formulário -->
        <div class="topo-depoimentos">

            <form class="form-relato" method="post" action="processos/processarelato.php">
                <div class="icone-usuario"></div>

                <label>Título do relato</label>
                <input type="text" name="titulo" placeholder="Digite o título" required>

                <label>Seu relato</label>
                <textarea name="conteudo" rows="4" placeholder="Escreva seu relato" required></textarea>

                <div class="anonimo-box">
                    <p>Gostaria que seu relato seja anônimo?</p>

                    <label>
                        <input type="radio" name="anonimo" value="1"> Sim
                    </label>

                    <label>
                        <input type="radio" name="anonimo" value="0" checked> Não
                    </label>
                </div>

                <input class="btnav" type="submit" value="Enviar Relato">
            </form>

            <!-- sua imagem -->
            <div class="img-abraco">
                <img src="img/abraço.png" alt="">
            </div>

        </div>

        <?php if (!empty($_SESSION['sucesso_relato'])): ?>
            <p style="color: green; font-weight: bold; margin: 20px 0;">
                <?php echo htmlspecialchars($_SESSION['sucesso_relato']); ?>
            </p>
            <?php unset($_SESSION['sucesso_relato']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['erro_relato'])): ?>
            <p style="color: red; font-weight: bold; margin: 20px 0;">
                <?php echo htmlspecialchars($_SESSION['erro_relato']); ?>
            </p>
            <?php unset($_SESSION['erro_relato']); ?>
        <?php endif; ?>

        <!-- Título -->
        <div class="titulo-depoimentos">
            <h2>Outros Depoimentos</h2>
        </div>

        <!-- Cards -->
      <?php foreach($relatos as $relato){ ?>

<div class="card-depoimento">

    <div class="cabecalho-depoimento">

        <div class="avatar">
            <?php if (!$relato['anonimo'] && !empty($relato['foto_perfil'])): ?>
                <img src="<?= htmlspecialchars($relato['foto_perfil']) ?>" alt="Foto de perfil">
            <?php else: ?>
                <span class="avatar-placeholder"><i class="fa-solid fa-user"></i></span>
            <?php endif; ?>
        </div>

        <div>

            <h4>
                <?= $relato['anonimo'] ? '@Anônimo' : '@' . htmlspecialchars($relato['nome']) ?>
            </h4>

            <span>
                <?= date('d/m/Y', strtotime($relato['data_publicacao'])) ?>
            </span>

        </div>

    </div>

    <div class="texto-depoimento">

        <h3><?= htmlspecialchars($relato['titulo']) ?></h3>

        <p><?= nl2br(htmlspecialchars($relato['conteudo'])) ?></p>

    </div>

</div>

<?php } ?>

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