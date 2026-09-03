<?php
session_start();

require_once __DIR__ . '/conexao.php';

if (empty($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$id_usuario = (int) $_SESSION['id_usuario'];

$sql = "SELECT id_usuario, nome, email, sobrenome, telefone, foto_perfil FROM usuarios WHERE id_usuario = :id_usuario";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    session_destroy();
    header('Location: login.php');
    exit();
}

$nome = $usuario['nome'] ?? '';
$email = $usuario['email'] ?? '';
$sobrenome = $usuario['sobrenome'] ?? '';
$telefone = $usuario['telefone'] ?? '';
$fotoPerfil = $usuario['foto_perfil'] ?? '';

if (empty($fotoPerfil) && !empty($_SESSION['foto_perfil'])) {
    $fotoPerfil = $_SESSION['foto_perfil'];
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

    <link rel="stylesheet" href="/vitalize/css/style.css?v=3">
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
                <li><a href="pagperfil.php"><?= htmlspecialchars(!empty($nome) ? $nome : 'Perfil') ?></a></li>

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

    <div class="painel">

        <div class="cabecalho-perfil">
            <h1>Meu Perfil</h1>
            <p>Visualize e altere suas informações pessoais.</p>
        </div>

        <div class="perfil-card">

            <div class="perfil-topo">

                <div class="foto-perfil">

                    <?php $fotoPerfilSrc = $fotoPerfil; ?>
                    <img src="<?= htmlspecialchars($fotoPerfilSrc) ?>" id="fotoPerfil">

                    <label for="novaFoto" class="editar-foto">
                        <i class="fa-solid fa-camera"></i>
                    </label>

                    <input type="file" id="novaFoto" hidden>

                </div>

                <div class="perfil-nome">

                    <h2><?php echo htmlspecialchars($nome ?: 'Usuário'); ?></h2>

                </div>

                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <button id="abrirPerfil" class="btn-agenda">
                        <i class="fa-solid fa-pen"></i>
                        Alterar dados
                    </button>
                    <a href="processos/processalogout.php" class="btn-agenda" style="text-decoration:none; background:#d9534f; display:inline-flex; align-items:center; gap:8px;">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Sair</span>
                    </a>
                    <form method="POST" action="processos/deletarperfil.php" onsubmit="return confirm('Tem certeza que deseja excluir sua conta?');" style="margin:0;">
                        <button type="submit" class="btn-agenda" style="background:#b91c1c; border:none; cursor:pointer; display:inline-flex; align-items:center; gap:8px;">
                            <i class="fa-solid fa-trash"></i>
                            <span>Excluir conta</span>
                        </button>
                    </form>
                </div>

            </div>

            <div class="perfil-info">

                <div class="info">
                    <span>Nome</span>
                    <strong><?php echo htmlspecialchars($nome ?: 'Não informado'); ?></strong>
                </div>

                <div class="info">
                    <span>Sobrenome</span>
                    <strong><?php echo htmlspecialchars(!empty($sobrenome) ? $sobrenome : 'Não informado'); ?></strong>
                </div>

                <div class="info">
                    <span>Email</span>
                    <strong><?php echo htmlspecialchars($email ?: 'Não informado'); ?></strong>
                </div>

                <div class="info">
                    <span>Telefone</span>
                    <strong><?php echo htmlspecialchars(!empty($telefone) ? $telefone : 'Não informado'); ?></strong>
                </div>

            </div>

        </div>

    </div>

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


    <div class="modal" id="modalPerfil">

    <div class="modal-conteudo agenda">

        <div class="modal-topo">

            <button class="fechar-modal" id="fecharPerfil">
                <i class="fa-solid fa-arrow-left"></i>
            </button>

            <h2>Editar Perfil</h2>

        </div>

        <form id="formPerfil" class="form-consulta" method="POST" action="processos/atualizaperfil.php" enctype="multipart/form-data">

            <div class="campo full">

                <label>Foto de Perfil</label>

                <input type="file" name="foto_perfil" accept="image/png, image/jpeg, image/webp">

            </div>

            <div class="campo">
                <label>Nome</label>
                <input type="text" name="nome" value="<?php echo htmlspecialchars($nome); ?>">
            </div>

            <div class="campo">
                <label>Sobrenome</label>
                <input type="text" name="sobrenome" value="<?php echo htmlspecialchars($sobrenome); ?>">
            </div>

            <div class="campo">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            </div>

            <div class="campo">
                <label>Telefone</label>
                <input type="tel" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>">
            </div>

            <div class="campo">
                <label>Nova senha</label>
                <input type="password" name="senha">
            </div>

            <div class="campo">
                <label>Confirmar senha</label>
                <input type="password" name="confirmar_senha">
            </div>

            <div class="botoes-modal">

                <button type="button" class="btn-cancelar" id="cancelarPerfil">
                    Cancelar
                </button>

                <button type="submit" class="btn-salvar">
                    Salvar
                </button>

            </div>

        </form>

    </div>

</div>

    <script>
        const modalPerfil = document.getElementById("modalPerfil");

        document.getElementById("abrirPerfil").onclick = () => {

            modalPerfil.classList.add("ativo");
            document.body.style.overflow = "hidden";

        };

        document.getElementById("fecharPerfil").onclick = fecharPerfil;
        document.getElementById("cancelarPerfil").onclick = fecharPerfil;

        function fecharPerfil() {

            modalPerfil.classList.remove("ativo");
            document.body.style.overflow = "auto";

        }

        modalPerfil.onclick = (e) => {

            if (e.target == modalPerfil) {

                fecharPerfil();

            }

        };
    </script>


</body>


</html>