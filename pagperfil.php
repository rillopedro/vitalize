<?php
require_once __DIR__ . '/iniciar_sessao.php';

if (empty($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$nome = $_SESSION['nome'] ?? '';
$email = $_SESSION['email'] ?? '';
$sobrenome = $_SESSION['sobrenome'] ?? '';
$telefone = $_SESSION['telefone'] ?? '';
$fotoPerfil = $_SESSION['foto_perfil'] ?? '';
$erroPerfil = $_SESSION['erro_perfil'] ?? '';
$sucessoPerfil = $_SESSION['sucesso_perfil'] ?? '';

unset($_SESSION['erro_perfil'], $_SESSION['sucesso_perfil']);
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

    <link rel="stylesheet" href="css/style.css?v=8">
    <style>
        
/* =========================
   MODAL EXCLUIR CONTA
========================= */

        .modal-excluir {
            width: 500px !important;
            max-width: calc(100% - 30px);
            padding: 0 !important;
            overflow: hidden;
            background: #fff !important;
        }

        /* Cabeçalho */

        .modal-excluir .modal-topo {
            height: 90px;
            padding: 0 28px;
            background: linear-gradient(135deg, #b189e8, #9d7bd4) !important;

            display: flex;
            align-items: center;
            justify-content: center;

            position: relative;
        }

        .modal-excluir .modal-topo h2 {
            margin: 0;
            color: white !important;
            font-size: 24px;
            font-weight: 600;
        }

        .modal-excluir .fechar-modal {
            position: absolute;
            left: 25px;
            top: 50%;
            transform: translateY(-50%);

            width: 38px;
            height: 38px;

            border: none;
            background: transparent !important;

            color: white !important;
            font-size: 18px;

            display: flex;
            align-items: center;
            justify-content: center;

            cursor: pointer;
        }

        .modal-excluir .fechar-modal i {
            color: white !important;
        }

        .modal-excluir .fechar-modal:hover {
            transform: translateY(-50%) scale(1.08);
        }

        /* Conteúdo */

        .excluir-conteudo {
            padding: 35px 40px 40px !important;
            text-align: center;
            background: white;
        }

        /* Ícone */

        .excluir-icone {
            width: 58px !important;
            height: 58px !important;

            margin: 0 auto 20px !important;

            border-radius: 50% !important;

            background: #f3eafb !important;
            color: #9b78c5 !important;

            display: flex !important;
            align-items: center;
            justify-content: center;

            font-size: 22px;
        }

        .excluir-icone i {
            color: #9b78c5 !important;
        }

        /* Título */

        .excluir-conteudo h3 {
            margin: 0 auto 10px !important;

            max-width: 380px;

            color: #444 !important;

            font-size: 19px;
            font-weight: 600;
        }

        /* Texto */

        .excluir-conteudo p {
            margin: 0 auto 28px !important;

            max-width: 370px;

            color: #777 !important;

            font-size: 14px;
            line-height: 1.5;
        }

        /* Botões */

        .botoes-excluir {
            display: flex !important;
            justify-content: center;
            align-items: center;
            gap: 12px;
        }

        .btn-cancelar-excluir,
        .btn-confirmar-excluir {
            height: 42px !important;
            min-width: 135px;

            padding: 0 20px !important;

            border-radius: 10px !important;
            border: none !important;

            font-family: "Jost", sans-serif !important;
            font-size: 14px !important;
            font-weight: 500;

            cursor: pointer;

            transition: all .3s ease;
        }

        /* Cancelar */

        .btn-cancelar-excluir {
            background: #f1f1f1 !important;
            color: #555 !important;
        }

        .btn-cancelar-excluir:hover {
            background: #e5e5e5 !important;
            transform: translateY(-2px);
        }

        /* Confirmar */

        .btn-confirmar-excluir {
            background: #b61010 !important;
            color: white !important;
        }

        .btn-confirmar-excluir:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 18px rgba(177, 137, 232, .25);
        }

        .btn-confirmar-excluir:active,
        .btn-cancelar-excluir:active {
            transform: scale(.97);
        }

        /* Celular */

        @media (max-width: 500px) {

            .modal-excluir {
                width: calc(100% - 25px) !important;
            }

            .excluir-conteudo {
                padding: 30px 20px 35px !important;
            }

            .botoes-excluir {
                flex-direction: column !important;
            }

            .btn-cancelar-excluir,
            .btn-confirmar-excluir {
                width: 100%;
            }
        }
    </style>
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

    <div class="painel">

        <div class="cabecalho-perfil">
            <h1>Meu Perfl</h1>
            <p>Visualize e altere suas informações pessoais.</p>

            <?php if ($sucessoPerfil !== ''): ?>
                <p role="status" style="color: #287542;">
                    <?= htmlspecialchars($sucessoPerfil, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="perfil-card">

            <div class="perfil-topo">

                <div class="foto-perfil">

                    <?php $fotoPerfilSrc = $fotoPerfil ?: 'img/usuario.png'; ?>
                    <img src="<?= htmlspecialchars($fotoPerfilSrc) ?>" id="fotoPerfil" alt="Foto de perfil">



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
                    <form method="POST" action="api/auth/logout" id="formLogout" style="margin:0;">
                        <button type="submit" class="btn-sairconta">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>Sair</span>
                        </button>
                    </form>
                    <form method="POST" action="api/me" id="formExcluir" style="margin:0;">
                        <button type="button" class="btn-excluirconta" id="abrirExcluir">
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

    <?php include 'footer.php'; ?>



    <div class="modal" id="modalPerfil">

        <div class="modal-conteudo agenda">

            <div class="modal-topo">

                <h2>Editar Perfil</h2>

            </div>

            <form id="formPerfil" class="form-consulta" method="POST" action="api/me"
                enctype="multipart/form-data">



                <?php if ($erroPerfil !== ''): ?>
                    <div class="campo full" role="alert">
                        <p style="margin: 0; color: #b61010;">
                            <?= htmlspecialchars($erroPerfil, ENT_QUOTES, 'UTF-8') ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="campo full">

                    <label>Foto de Perfil</label>

                    <input type="file" name="foto_perfil" accept="image/png, image/jpeg, image/webp">

                </div>

                <div class="campo">
                    <label for="nomePerfil">Nome</label>
                    <input type="text" id="nomePerfil" name="nome" value="<?= htmlspecialchars($nome) ?>" required>
                    <small class="erro-campo"></small>
                </div>

                <div class="campo">
                    <label for="sobrenomePerfil">Sobrenome</label>
                    <input type="text" id="sobrenomePerfil" name="sobrenome"
                        value="<?= htmlspecialchars($sobrenome) ?>">
                    <small class="erro-campo"></small>
                </div>

                <div class="campo">
                    <label for="emailPerfil">Email</label>
                    <input type="email" id="emailPerfil" name="email" value="<?= htmlspecialchars($email) ?>" required>
                    <small class="erro-campo"></small>
                </div>

                <div class="campo">
                    <label for="telefonePerfil">Telefone</label>
                    <input type="tel" id="telefonePerfil" name="telefone" value="<?= htmlspecialchars($telefone) ?>"
                        placeholder="(11) 99999-9999" maxlength="15" inputmode="numeric">
                    <small class="erro-campo"></small>
                </div>

                <div class="campo">
                    <label for="senhaPerfil">Nova senha</label>
                    <div class="campo-senha">
                        <input type="password" id="senhaPerfil" name="senha" placeholder="Digite a nova senha"
                            minlength="6" autocomplete="new-password">
                        <button type="button" class="olho-senha" data-alvo="senhaPerfil" aria-label="Mostrar senha">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <small class="erro-campo"></small>
                </div>

                <div class="campo">
                    <label for="senhaAtualPerfil">Senha atual (necessária para trocar a senha)</label>
                    <input type="password" id="senhaAtualPerfil" name="senha_atual" autocomplete="current-password">
                    <small class="erro-campo"></small>
                </div>

                <div class="campo">
                    <label for="confirmarSenhaPerfil">Confirmar senha</label>
                    <div class="campo-senha">
                        <input type="password" id="confirmarSenhaPerfil" name="confirmar_senha"
                            placeholder="Repita a nova senha" autocomplete="new-password">
                        <button type="button" class="olho-senha" data-alvo="confirmarSenhaPerfil"
                            aria-label="Mostrar senha">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <small class="erro-campo"></small>
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

    <div class="modal" id="modalExcluir">

        <div class="modal-conteudo modal-excluir">

            <div class="modal-topo">
                <button type="button" class="fechar-modal" id="cancelarExcluir">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>

                <h2>Excluir conta</h2>
            </div>

            <div class="excluir-conteudo">

                <div class="excluir-icone">
                    <i class="fa-solid fa-trash-can"></i>
                </div>

                <h3>Tem certeza que deseja excluir sua conta?</h3>

                <p>
                    Ao continuar, sua conta e seus dados serão excluídos permanentemente.
                </p>

                <div class="botoes-excluir">

                    <button type="button" class="btn-cancelar-excluir" id="voltarExcluir">
                        Cancelar
                    </button>

                    <button type="submit" form="formExcluir" class="btn-confirmar-excluir">
                        Excluir conta
                    </button>

                </div>

            </div>

        </div>

    </div>

    <script src="api/cliente.js"></script>
    <script>
        const modalPerfil = document.getElementById("modalPerfil");
        const formPerfil = document.getElementById("formPerfil");
        const abrirPerfil = document.getElementById("abrirPerfil");
        const cancelarPerfil = document.getElementById("cancelarPerfil");

        const nomeInput = document.getElementById("nomePerfil");
        const emailInput = document.getElementById("emailPerfil");
        const telefoneInput = document.getElementById("telefonePerfil");
        const senhaInput = document.getElementById("senhaPerfil");
        const confirmarInput = document.getElementById("confirmarSenhaPerfil");

        let overflowAnterior = "";
        let tentouSalvar = false;

        // O formulário terá mensagens próprias, sem balões do navegador.
        formPerfil.noValidate = true;

        // DDDs existentes no Brasil.
        const dddsValidos = new Set([
            "11", "12", "13", "14", "15", "16", "17", "18", "19",
            "21", "22", "24", "27", "28",
            "31", "32", "33", "34", "35", "37", "38",
            "41", "42", "43", "44", "45", "46", "47", "48", "49",
            "51", "53", "54", "55",
            "61", "62", "63", "64", "65", "66", "67", "68", "69",
            "71", "73", "74", "75", "77", "79",
            "81", "82", "83", "84", "85", "86", "87", "88", "89",
            "91", "92", "93", "94", "95", "96", "97", "98", "99"
        ]);

        function mostrarErro(input, mensagem) {
            input.classList.add("erro-input");
            input.setAttribute("aria-invalid", "true");

            const campo = input.closest(".campo");
            const aviso = campo.querySelector(".erro-campo");

            aviso.textContent = mensagem;
            aviso.classList.add("visivel");
        }

        function limparErro(input) {
            input.classList.remove("erro-input");
            input.removeAttribute("aria-invalid");

            const campo = input.closest(".campo");
            const aviso = campo.querySelector(".erro-campo");

            aviso.textContent = "";
            aviso.classList.remove("visivel");
        }

        function validarCampo(input) {
            limparErro(input);

            const valor = input.value.trim();

            if (input === nomeInput && !valor) {
                mostrarErro(input, "Informe seu nome.");
                return false;
            }

            if (input === emailInput) {
                if (!valor) {
                    mostrarErro(input, "Informe seu e-mail.");
                    return false;
                }

                if (input.validity.typeMismatch) {
                    mostrarErro(input, "Digite um e-mail válido.");
                    return false;
                }
            }

            if (input === telefoneInput && valor) {
                const numeros = valor.replace(/\D/g, "");

                if (numeros.length !== 10 && numeros.length !== 11) {
                    mostrarErro(input, "Digite um telefone com DDD e 10 ou 11 números.");
                    return false;
                }

                if (!dddsValidos.has(numeros.substring(0, 2))) {
                    mostrarErro(input, "Digite um DDD válido.");
                    return false;
                }

                const numero = numeros.substring(2);

                if (numero.length === 9 && numero[0] !== "9") {
                    mostrarErro(input, "Confira o número do celular.");
                    return false;
                }

                if (numero.length === 8 && /^[0-9]{8}$/.test(numero)) {
                    // Telefone fixo: formato de 8 dígitos.
                } else if (numero.length === 9 && /^[0-9]{9}$/.test(numero)) {
                    // Celular: formato de 9 dígitos.
                } else {
                    mostrarErro(input, "Confira o número de telefone.");
                    return false;
                }
            }

            if (input === senhaInput || input === confirmarInput) {
                if (senhaInput.value && senhaInput.value.length < 6) {
                    mostrarErro(senhaInput, "A senha deve ter pelo menos 6 caracteres.");
                    return false;
                }

                if (!senhaInput.value && confirmarInput.value) {
                    mostrarErro(senhaInput, "Digite a nova senha.");
                    mostrarErro(confirmarInput, "Confirme a nova senha.");
                    return false;
                }

                if (senhaInput.value && !confirmarInput.value) {
                    mostrarErro(confirmarInput, "Confirme a nova senha.");
                    return false;
                }

                if (senhaInput.value !== confirmarInput.value) {
                    mostrarErro(confirmarInput, "As senhas não coincidem.");
                    return false;
                }

                limparErro(senhaInput);
                limparErro(confirmarInput);
            }

            return true;
        }

        function validarFormulario() {
            let valido = true;

            [nomeInput, emailInput, telefoneInput, senhaInput, confirmarInput]
                .forEach(input => {
                    if (!validarCampo(input)) {
                        valido = false;
                    }
                });

            return valido;
        }

        // Máscara automática para o telefone.
        telefoneInput.addEventListener("input", () => {
            let numeros = telefoneInput.value.replace(/\D/g, "").slice(0, 11);

            if (numeros.length > 10) {
                numeros = numeros.replace(
                    /^(\d{2})(\d{5})(\d{0,4}).*/,
                    "($1) $2-$3"
                );
            } else if (numeros.length > 6) {
                numeros = numeros.replace(
                    /^(\d{2})(\d{4})(\d{0,4}).*/,
                    "($1) $2-$3"
                );
            } else if (numeros.length > 2) {
                numeros = numeros.replace(/^(\d{2})(\d+)/, "($1) $2");
            } else if (numeros.length > 0) {
                numeros = "(" + numeros;
            }

            telefoneInput.value = numeros;

            if (tentouSalvar) {
                validarCampo(telefoneInput);
            } else {
                limparErro(telefoneInput);
            }
        });

        // Validação enquanto a pessoa digita.
        [nomeInput, emailInput, senhaInput, confirmarInput].forEach(input => {
            input.addEventListener("input", () => {
                if (tentouSalvar || input === senhaInput || input === confirmarInput) {
                    validarCampo(input);
                } else {
                    limparErro(input);
                }
            });

            input.addEventListener("blur", () => {
                if (tentouSalvar) {
                    validarCampo(input);
                }
            });
        });

        // Mostrar ou ocultar cada senha pelo ícone de olho.
        document.querySelectorAll("#formPerfil .olho-senha").forEach(botao => {
            botao.addEventListener("click", () => {
                const input = document.getElementById(botao.dataset.alvo);
                const icone = botao.querySelector("i");
                const mostrar = input.type === "password";

                input.type = mostrar ? "text" : "password";
                icone.classList.toggle("fa-eye", !mostrar);
                icone.classList.toggle("fa-eye-slash", mostrar);

                botao.setAttribute(
                    "aria-label",
                    mostrar ? "Ocultar senha" : "Mostrar senha"
                );
            });
        });

        function limparValidacoes() {
            [nomeInput, emailInput, telefoneInput, senhaInput, confirmarInput]
                .forEach(limparErro);
        }

        function restaurarFormulario() {
            formPerfil.reset();
            limparValidacoes();

            // Restaura os valores atuais do perfil após cancelar.
            nomeInput.value = <?= json_encode($nome) ?>;
            emailInput.value = <?= json_encode($email) ?>;
            telefoneInput.value = <?= json_encode($telefone) ?>;

            senhaInput.value = "";
            confirmarInput.value = "";
            senhaInput.type = "password";
            confirmarInput.type = "password";

            formPerfil.querySelectorAll(".olho-senha i").forEach(icone => {
                icone.classList.remove("fa-eye-slash");
                icone.classList.add("fa-eye");
            });

            formPerfil.querySelectorAll(".olho-senha").forEach(botao => {
                botao.setAttribute("aria-label", "Mostrar senha");
            });

            tentouSalvar = false;
        }

        abrirPerfil.addEventListener("click", () => {
            restaurarFormulario();

            overflowAnterior = document.body.style.overflow;
            modalPerfil.classList.add("ativo");
            document.body.style.overflow = "hidden";
        });

        cancelarPerfil.addEventListener("click", () => {
            restaurarFormulario();
            modalPerfil.classList.remove("ativo");
            document.body.style.overflow = overflowAnterior;
            abrirPerfil.focus();
        });

        formPerfil.addEventListener("submit", async event => {
            tentouSalvar = true;

            if (!validarFormulario()) {
                event.preventDefault();

                const primeiroErro = formPerfil.querySelector(".erro-input");
                if (primeiroErro) {
                    primeiroErro.focus();
                }
                return;
            }
            event.preventDefault();
            try {
                const dados = new FormData(formPerfil);
                await VitalizeAPI.requisicao('/api/me', { method: 'POST', body: dados });
                window.location.reload();
            } catch (erro) {
                alert(erro.message || 'Não foi possível atualizar o perfil.');
            }
        });

        document.getElementById('formExcluir').addEventListener('submit', async event => {
            event.preventDefault();
            try {
                await VitalizeAPI.requisicao('/api/me', { method: 'DELETE' });
                window.location.assign('login.php');
            } catch (erro) { alert(erro.message || 'Não foi possível excluir a conta.'); }
        });
        document.getElementById('formLogout').addEventListener('submit', async event => {
            event.preventDefault();
            try {
                await VitalizeAPI.requisicao('/api/auth/logout', { method: 'POST', body: {} });
                window.location.assign('login.php');
            } catch (erro) { alert(erro.message || 'Não foi possível encerrar a sessão.'); }
        });

        <?php if ($erroPerfil !== ''): ?>
            abrirPerfil.click();
        <?php endif; ?>
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

    <script>
        const modalExcluir = document.getElementById("modalExcluir");
        const abrirExcluir = document.getElementById("abrirExcluir");
        const cancelarExcluir = document.getElementById("cancelarExcluir");
        const voltarExcluir = document.getElementById("voltarExcluir");

        let overflowAnteriorExcluir = "";

        abrirExcluir.onclick = () => {
            overflowAnteriorExcluir = document.body.style.overflow;

            modalExcluir.classList.add("ativo");
            document.body.style.overflow = "hidden";
        };

        cancelarExcluir.onclick = fecharExcluir;
        voltarExcluir.onclick = fecharExcluir;

        function fecharExcluir() {
            modalExcluir.classList.remove("ativo");
            document.body.style.overflow = overflowAnteriorExcluir;

            abrirExcluir.focus();
        }

    </script>
</body>

</html>
