<?php
session_start();
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
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="icon" type="image/png" href="img/logov.png">

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

        <div class="cabecalho-painel">
            <h1>Olá, <?= htmlspecialchars($_SESSION['nome'] ?? 'Usuário') ?>!</h1>
            <p>Acompanhe sua rotina hoje</p>
        </div>

        <div class="grade-painel">

            <!-- Humor -->
            <div class="card">
                <h3>Como vai seu humor?</h3>

                <div class="humor">

                    <button class="opcao-humor" data-humor="Muito bem" type="button">
                        <div class="emoji roxo">
                            <i class="fa-regular fa-face-laugh"></i>
                        </div>
                        <span class="texto-emoji">Muito bem</span>
                    </button>

                    <button class="opcao-humor" data-humor="Bem" type="button">
                        <div class="emoji lilas">
                            <i class="fa-regular fa-face-smile"></i>
                        </div>
                        <span class="texto-emoji">Bem</span>
                    </button>

                    <button class="opcao-humor" data-humor="Mais ou menos" type="button">
                        <div class="emoji azul">
                            <i class="fa-regular fa-face-meh"></i>
                        </div>
                        <span class="texto-emoji">Mais ou menos</span>
                    </button>

                    <button class="opcao-humor" data-humor="Cansada" type="button">
                        <div class="emoji cinza">
                            <i class="fa-regular fa-face-frown"></i>
                        </div>
                        <span class="texto-emoji">Cansada</span>
                    </button>

                    <button class="opcao-humor" data-humor="Triste" type="button">
                        <div class="emoji rosa">
                            <i class="fa-regular fa-face-sad-tear"></i>
                        </div>
                        <span class="texto-emoji">Triste</span>
                    </button>

                </div>
            </div>

            <!-- Sintomas -->
            <div class="card">
                <h3>Registro de sintomas</h3>
                <p class="subtitulo">Marque como você se sente hoje</p>

                <div class="sintomas">

                    <button class="sintoma" data-sintoma="Náusea" type="button">
                        <span class="material-symbols-outlined">sick</span>
                        Náusea
                    </button>

                    <button class="sintoma" data-sintoma="Dor" type="button">
                        <span class="material-symbols-outlined">bolt</span>
                        Dor
                    </button>

                    <button class="sintoma" data-sintoma="Sono" type="button">
                        <span class="material-symbols-outlined">bedtime</span>
                        Sono
                    </button>

                    <button class="sintoma" data-sintoma="Apetite" type="button">
                        <span class="material-symbols-outlined">restaurant</span>
                        Apetite
                    </button>

                    <button class="sintoma" data-sintoma="Hidratação" type="button">
                        <span class="material-symbols-outlined">water_drop</span>
                        Hidratação
                    </button>

                    <button class="sintoma" data-sintoma="Disposição" type="button">
                        <span class="material-symbols-outlined">directions_walk</span>
                        Disposição
                    </button>

                </div>
            </div>

            <!-- Cardápio -->
            <div class="card">

                <div class="cabecalho-card">
                    <div>
                        <h3>Seu café da manhã com IA</h3>
                        <p class="subtitulo">Escolha seu humor e, se quiser, os sintomas.</p>
                    </div>
                    <button type="button" id="gerarPlano" class="btn-ia" disabled>
                        <i class="fa-solid fa-wand-magic-sparkles"></i> Gerar sugestão
                    </button>
                </div>

                <div class="restricoes-alimentares">
                    <label for="restricoesAlimentares">
                        <i class="fa-solid fa-utensils"></i> Restrições alimentares <span class="campo-opcional">(opcional)</span>
                    </label>
                    <textarea id="restricoesAlimentares" maxlength="500" rows="3"
                        placeholder="Ex.: alergia a amendoim, intolerância à lactose, alimentação vegetariana..."></textarea>
                    <div class="rodape-restricoes">
                        <small>Você pode deixar este campo em branco.</small>
                        <span id="contadorRestricoes">0/500</span>
                    </div>
                </div>

                <div class="refeicoes" id="resultadoIA" aria-live="polite">
                    <div class="ia-vazio">
                        <i class="fa-regular fa-heart"></i>
                        <p>Conte como você está para receber uma sugestão acolhedora para esta manhã.</p>
                    </div>
                </div>

            </div>

            <!-- Dicas -->
            <div class="card">

                <div class="cabecalho-card">
                    <h3>Dicas de rotina</h3>
                </div>

                <div class="dicas">

                    <div class="dica">
                        <div class="icone-dica">
                            <i class="fa-solid fa-glass-water"></i>
                        </div>
                        <span>Beba bastante água ao longo do dia.</span>
                    </div>

                    <div class="dica">
                        <div class="icone-dica">
                            <i class="fa-solid fa-person-walking"></i>
                        </div>
                        <span>Faça atividades leves e respeite seus limites.</span>
                    </div>

                    <div class="dica">
                        <div class="icone-dica">
                            <i class="fa-solid fa-bed"></i>
                        </div>
                        <span>Descanse sempre que precisar.</span>
                    </div>

                    <div class="dica">
                        <div class="icone-dica">
                            <i class="fa-solid fa-pills"></i>
                        </div>
                        <span>Não se esqueça da sua medicação.</span>
                    </div>

                </div>

            </div>

            <!-- Consultas -->
            <div class="card card-consultas">

                <div class="cabecalho-card">

                    <div>
                        <h3>Próximas consultas e exames</h3>
                        <p class="subtitulo">Acompanhe seus próximos compromissos.</p>
                    </div>

                    <button id="abrirAgenda" class="btn-agenda">
                        <i class="fa-solid fa-calendar-plus"></i>
                        Gerenciar
                    </button>

                </div>

                <div class="consultas">

                    <div class="consulta">

                        <div class="data-consulta">
                            <strong>20</strong>
                            <span>MAI</span>
                        </div>

                        <div class="info-consulta">
                            <h4>Consulta com Oncologista</h4>
                            <p>Dr. Carlos Almeida</p>
                        </div>

                        <div class="horario-consulta">
                            <strong>10:00</strong>
                            <span>Hospital São Lucas</span>
                        </div>

                    </div>

                    <div class="consulta">

                        <div class="data-consulta">
                            <strong>05</strong>
                            <span>JUN</span>
                        </div>

                        <div class="info-consulta">
                            <h4>Exame de Sangue</h4>
                            <p>Dr. João Nascimento</p>
                        </div>

                        <div class="horario-consulta">
                            <strong>08:00</strong>
                            <span>Laboratório Delboni</span>
                        </div>

                    </div>

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

    <div class="modal" id="modalAgenda">

        <div class="modal-conteudo agenda">

            <div class="modal-topo">

                <button class="fechar-modal" id="fecharAgenda">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>

                <h2 id="tituloFormulario">
                    Gerenciar Agenda
                </h2>

            </div>


            <!-- LISTA -->

            <div id="listaAgenda">

                <button class="btn-novo" id="novoItem">

                    <i class="fa-solid fa-plus"></i>

                    Nova consulta ou exame

                </button>


                <div class="item-agenda">

                    <div class="dados-agenda">

                        <h4>Consulta com Oncologista</h4>

                        <p>20/05/2026 • 10:00</p>

                        <span>Hospital São Lucas</span>

                    </div>

                    <div class="acoes">

                        <button class="editar">
                            <i class="fa-solid fa-pen"></i>
                        </button>

                        <button class="excluir">
                            <i class="fa-solid fa-trash"></i>
                        </button>

                    </div>

                </div>


                <div class="item-agenda">

                    <div class="dados-agenda">

                        <h4>Exame de Sangue</h4>

                        <p>05/06/2026 • 08:00</p>

                        <span>Laboratório Delboni</span>

                    </div>

                    <div class="acoes">

                        <button class="editar">
                            <i class="fa-solid fa-pen"></i>
                        </button>

                        <button class="excluir">
                            <i class="fa-solid fa-trash"></i>
                        </button>

                    </div>

                </div>

            </div>



            <!-- FORM -->

            <form id="formAgenda" class="form-consulta" style="display:none;">

                <div class="campo">

                    <label>Tipo</label>

                    <select>

                        <option>Consulta</option>
                        <option>Exame</option>

                    </select>

                </div>

                <div class="campo">

                    <label>Especialidade</label>

                    <input type="text" placeholder="Ex.: Oncologista">

                </div>

                <div class="campo">

                    <label>Médico</label>

                    <input type="text" placeholder="Nome do médico">

                </div>

                <div class="campo">

                    <label>Local</label>

                    <input type="text" placeholder="Hospital ou clínica">

                </div>

                <div class="campo">

                    <label>Data</label>

                    <input type="date">

                </div>

                <div class="campo">

                    <label>Horário</label>

                    <input type="time">

                </div>

                <div class="botoes-modal">

                    <button type="button" id="cancelarEdicao" class="btn-cancelar">
                        Cancelar
                    </button>

                    <button type="button" id="salvarAgenda" class="btn-salvar">
                        Salvar
                    </button>

                </div>

            </form>


        </div>

    </div>


    <script>

        const modal = document.getElementById("modalAgenda");

        const lista = document.getElementById("listaAgenda");

        const formulario = document.getElementById("formAgenda");

        const titulo = document.getElementById("tituloFormulario");
        function voltarInicioModal() {

            formulario.style.display = "none";
            lista.style.display = "block";

            titulo.innerHTML = "Gerenciar Agenda";

        }
        document.getElementById("abrirAgenda").onclick = () => {
            modal.classList.add("ativo");
            document.body.style.overflow = "hidden";
        };

        document.querySelector(".fechar-modal").onclick = () => {

            if (formulario.style.display === "block") {

                voltarInicioModal();

            } else {

                modal.classList.remove("ativo");
                document.body.style.overflow = "auto";

            }

        };

        modal.onclick = (e) => {

            if (e.target === modal) {

                modal.classList.remove("ativo");
                document.body.style.overflow = "auto";

            }

        };

        // NOVO
        document.getElementById("novoItem").onclick = () => {

            titulo.innerHTML = "Nova consulta";

            lista.style.display = "none";
            formulario.style.display = "block";
        };

        // EDITAR
        document.querySelectorAll(".editar").forEach(botao => {

            botao.onclick = () => {

                titulo.innerHTML = "Editar consulta";

                lista.style.display = "none";
                formulario.style.display = "block";

                // depois aqui você preencherá os campos com os dados vindos do banco
            }

        });

        // CANCELAR
        document.getElementById("cancelarEdicao").onclick = () => {

            voltarInicioModal();

        };
        // SALVAR
        document.getElementById("salvarAgenda").onclick = () => {

            alert("Consulta salva!");

            voltarInicioModal();

        };

        // EXCLUIR
        document.querySelectorAll(".excluir").forEach(botao => {

            botao.onclick = () => {

                if (confirm("Deseja excluir esta consulta?")) {

                    botao.closest(".item-agenda").remove();

                }

            }

        });
    </script>
    <script>
        const botaoGerar = document.getElementById('gerarPlano');
        const resultadoIA = document.getElementById('resultadoIA');
        const campoRestricoes = document.getElementById('restricoesAlimentares');
        const contadorRestricoes = document.getElementById('contadorRestricoes');
        let humorSelecionado = '';

        campoRestricoes.value = localStorage.getItem('vitalize_restricoes_alimentares') || '';
        contadorRestricoes.textContent = `${campoRestricoes.value.length}/500`;
        campoRestricoes.addEventListener('input', () => {
            localStorage.setItem('vitalize_restricoes_alimentares', campoRestricoes.value.trim());
            contadorRestricoes.textContent = `${campoRestricoes.value.length}/500`;
        });

        function escapar(texto) {
            const elemento = document.createElement('span');
            elemento.textContent = String(texto ?? '');
            return elemento.innerHTML;
        }

        async function gerarPlanoMatinal() {
            const sintomas = [...document.querySelectorAll('.sintoma.ativo')]
                .map(item => item.dataset.sintoma);
            const restricoes = campoRestricoes.value.trim();
            botaoGerar.disabled = true;
            botaoGerar.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Preparando...';
            resultadoIA.innerHTML = '<div class="ia-vazio"><p>Criando uma sugestão para você...</p></div>';

            try {
                const resposta = await fetch('api/assistente_bem_estar.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({humor: humorSelecionado, sintomas, restricoes})
                });
                const dados = await resposta.json();
                if (!resposta.ok) throw new Error(dados.erro || 'Não foi possível gerar a sugestão.');

                const itens = dados.itens.map(item => `
                    <li><strong>${escapar(item.nome)}</strong><span>${escapar(item.quantidade)}</span></li>
                `).join('');
                resultadoIA.innerHTML = `
                    <div class="plano-ia">
                        <h4>${escapar(dados.titulo)}</h4>
                        <ul>${itens}</ul>
                        <p class="preparo-ia"><strong>Como preparar:</strong> ${escapar(dados.preparo)}</p>
                        <blockquote>${escapar(dados.mensagem)}</blockquote>
                        <small><i class="fa-solid fa-circle-info"></i> ${escapar(dados.observacao)}</small>
                    </div>`;
            } catch (erro) {
                resultadoIA.innerHTML = `<div class="ia-erro"><i class="fa-solid fa-triangle-exclamation"></i><p>${escapar(erro.message)}</p></div>`;
            } finally {
                botaoGerar.disabled = !humorSelecionado;
                botaoGerar.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> Gerar outra sugestão';
            }
        }

        botaoGerar.addEventListener('click', gerarPlanoMatinal);

        // Humor: seleção única
        document.querySelectorAll('.opcao-humor').forEach(btn => {
            btn.addEventListener('click', () => {

                document.querySelectorAll('.opcao-humor').forEach(item => {
                    item.classList.remove('ativo');
                });

                btn.classList.add('ativo');
                humorSelecionado = btn.dataset.humor;
                botaoGerar.disabled = false;
            });
        });
        document.querySelectorAll('.sintoma').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.classList.toggle('ativo');
            });
        });
        const toggle = document.querySelector(".menu-toggle");
        const menu = document.querySelector(".menu");

        toggle.addEventListener("click", () => {
            menu.classList.toggle("ativo");
        });
    </script>

</body>

</html>
