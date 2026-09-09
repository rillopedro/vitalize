<?php
session_start();
require_once __DIR__ . '/conexao.php';

$sql = "SELECT id_grupo, nome_grupo, mais_info, foco, data_encontro, horario, link, contato AS telefone_grupo, imagem FROM grupos ORDER BY id_grupo DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$grupos = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    <section class="grupos">

        <img src="img/logov.png" class="logo-topo">

        <h1 class="h1titulo">Cadastre-se em um grupo de apoio</h1>
        <div class="linha"></div>

        <div class="cardsgrupos">
            <?php if (empty($grupos)): ?>
                <p style="width:100%; text-align:center; color:#666;">Nenhum grupo cadastrado ainda.</p>
            <?php else: ?>
                <?php foreach ($grupos as $grupo): ?>
                    <div class="cardgrupos">
                        <img src="<?= htmlspecialchars($grupo['imagem'] ?: 'img/apoio1.webp') ?>" alt="<?= htmlspecialchars($grupo['nome_grupo']) ?>">

                        <div class="conteudocards">
                            <span class="taggrupo"><?= htmlspecialchars($grupo['foco'] ?: 'Grupo de apoio') ?></span>
                            <h3><?= htmlspecialchars($grupo['nome_grupo']) ?></h3>

                            <div class="infosgrupo">
                                <span><?= !empty($grupo['data_encontro']) ? htmlspecialchars(date('d/m/Y', strtotime($grupo['data_encontro']))) : 'Data a definir' ?></span>
                                <span><?= !empty($grupo['horario']) ? htmlspecialchars(substr($grupo['horario'], 0, 5)) : 'Horário a definir' ?></span>
                            </div>

                            <p class="focogrupo">
                                <?= htmlspecialchars($grupo['mais_info'] ?: 'Grupo de apoio para pessoas em tratamento.') ?>
                            </p>

                            <div class="botoescards">
                                <button class="infogrupos"
                                    data-nome="<?= htmlspecialchars($grupo['nome_grupo'], ENT_QUOTES) ?>"
                                    data-foco="<?= htmlspecialchars($grupo['foco'], ENT_QUOTES) ?>"
                                    data-data="<?= !empty($grupo['data_encontro']) ? htmlspecialchars(date('d/m/Y', strtotime($grupo['data_encontro']))) : 'Data a definir' ?>"
                                    data-horario="<?= !empty($grupo['horario']) ? htmlspecialchars(substr($grupo['horario'], 0, 5)) : 'Horário a definir' ?>"
                                    data-info="<?= htmlspecialchars($grupo['mais_info'] ?: 'Sem descrição disponível.', ENT_QUOTES) ?>"
                                    data-link="<?= htmlspecialchars($grupo['link'] ?: '#', ENT_QUOTES) ?>"
                                    data-telefone="<?= htmlspecialchars($grupo['telefone_grupo'] ?: 'Não informado', ENT_QUOTES) ?>"
                                    data-imagem="<?= htmlspecialchars($grupo['imagem'] ?: 'img/apoio1.webp', ENT_QUOTES) ?>"
                                >Mais info</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        </div>

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

    <div class="modal" id="modalInfo">

        <div class="modal-conteudo">

            <div class="modal-topo">
                <button class="fechar-modal">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>

                <h2>Mais Informações</h2>
            </div>

            <div class="modal-corpo">
                <h2 class="h2titulo" id="modalNomeGrupo">Título do grupo</h2>
                <h3 id="modalFoco">Foco: ...</h3>

                <div class="modal-dados">
                    <span id="modalHorario">Horário</span>
                    <span id="modalData">Data</span>
                </div>

                <p class="modal-texto" id="modalDescricao">
                    Descrição do grupo.
                </p>

                <div id="modalContato">
                    <p><strong>Link:</strong> <a href="#" target="_blank" id="modalLink"></a></p>
                    <p><strong>Telefone:</strong> <span id="modalTelefone"></span></p>
                </div>

                <h4>
                    Esperamos você para juntos construirmos uma rede de apoio, cuidado e esperança!
                </h4>

                <img id="modalImagem" src="" alt="Imagem do grupo">
            </div>

        </div>

    </div>

    <script>

        const modal = document.getElementById("modalInfo");
        const modalNome = document.getElementById("modalNomeGrupo");
        const modalFoco = document.getElementById("modalFoco");
        const modalHorario = document.getElementById("modalHorario");
        const modalData = document.getElementById("modalData");
        const modalDescricao = document.getElementById("modalDescricao");
        const modalLink = document.getElementById("modalLink");
        const modalTelefone = document.getElementById("modalTelefone");

        document.querySelectorAll(".infogrupos").forEach(botao => {
            botao.addEventListener("click", () => {
                const nome = botao.getAttribute("data-nome") || "Grupo";
                const foco = botao.getAttribute("data-foco") || "Grupo de apoio";
                const data = botao.getAttribute("data-data") || "Data a definir";
                const horario = botao.getAttribute("data-horario") || "Horário a definir";
                const info = botao.getAttribute("data-info") || "Descrição não disponível.";
                const link = botao.getAttribute("data-link") || "#";
                const telefone = botao.getAttribute("data-telefone") || "Não informado";
                const imagem = botao.getAttribute("data-imagem") || "img/apoio1.webp";

                modalNome.textContent = nome;
                modalFoco.textContent = "Foco: " + foco;
                modalData.textContent = data;
                modalHorario.textContent = horario;
                modalDescricao.textContent = info;
                modalLink.textContent = link !== "#" ? link : "Sem link disponível";
                modalLink.href = link !== "#" ? link : "javascript:void(0);";
                modalTelefone.textContent = telefone;
                document.getElementById('modalImagem').src = imagem;
                document.getElementById('modalImagem').alt = "Imagem do grupo " + nome;

                modal.classList.add("ativo");
            });
        });

        document.querySelector(".fechar-modal").addEventListener("click", () => {
            modal.classList.remove("ativo");
        });

        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.classList.remove("ativo");
            }
        });

    </script>
    <script>
        const toggle = document.querySelector(".menu-toggle");
        const menu = document.querySelector(".menu");

        toggle.addEventListener("click", () => {
            menu.classList.toggle("ativo");
        });
    </script>

</body>

</html>