<?php
require_once __DIR__ . '/navegacao.php';
vitalize_exibir_aplicacao('saude.php');
require_once __DIR__ . '/iniciar_sessao.php';
$consultas = [];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitalize</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=12">
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
                <li><a href="conteudo.php?pagina=index">Início</a></li>
                <li><a href="conteudo.php?pagina=sobregrupos">Grupos</a></li>
                <li><a href="conteudo.php?pagina=doacao">Doação</a></li>
                <li><a href="conteudo.php?pagina=saude">Saúde</a></li>
                <li><a href="conteudo.php?pagina=relatos">Relatos</a></li>
                <li><a href="conteudo.php?pagina=sobre">Sobre</a></li>
            </ul>
            <?php if (!empty($_SESSION['id_usuario'])): ?>
                <a href="conteudo.php?pagina=pagperfil" class="btnav">Perfil</a>
            <?php else: ?>
                <a href="conteudo.php?pagina=login" class="btnav">Entrar</a>
            <?php endif; ?>
            <button class="menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>
    <div class="painel">
        <div class="cabecalho-painel">
            <h1>Olá,
                <?= htmlspecialchars($_SESSION['nome'] ?? 'Usuário') ?>!
            </h1>
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
                        <h3>Seu cardapio diario com IA</h3>
                        <p class="subtitulo">Escolha seu humor e, se quiser, os sintomas.</p>
                    </div>
                    <button type="button" id="gerarPlano" class="btn-ia" disabled>
                        <i class="fa-solid fa-wand-magic-sparkles"></i> Gerar sugestão
                    </button>
                </div>
                <div class="restricoes-alimentares">
                    <label for="restricoesAlimentares">
                        <i class="fa-solid fa-utensils"></i> Restrições alimentares <span
                            class="campo-opcional">(opcional)</span>
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
            <div class="card card-dicas">
                <div class="cabecalho-card">
                    <div>
                        <h3>Dicas de rotina</h3>
                        <p class="subtitulo">Pequenos cuidados para hoje</p>
                    </div>
                    <span class="dicas-etiqueta"><i class="fa-regular fa-heart" aria-hidden="true"></i> Seu bem-estar</span>
                </div>
                <div class="dicas">
                    <article class="dica">
                        <div class="icone-dica"><i class="fa-solid fa-glass-water" aria-hidden="true"></i></div>
                        <div class="texto-dica"><strong>Hidratação</strong><span>Beba água ao longo do dia.</span></div>
                    </article>
                    <article class="dica">
                        <div class="icone-dica"><i class="fa-solid fa-person-walking" aria-hidden="true"></i></div>
                        <div class="texto-dica"><strong>Movimento</strong><span>Faça atividades leves e respeite seus limites.</span></div>
                    </article>
                    <article class="dica">
                        <div class="icone-dica"><i class="fa-solid fa-bed" aria-hidden="true"></i></div>
                        <div class="texto-dica"><strong>Pausa</strong><span>Descanse sempre que precisar.</span></div>
                    </article>
                    <article class="dica">
                        <div class="icone-dica"><i class="fa-solid fa-pills" aria-hidden="true"></i></div>
                        <div class="texto-dica"><strong>Medicação</strong><span>Não se esqueça da sua medicação.</span></div>
                    </article>
                </div>
                <p class="dicas-rodape"><i class="fa-regular fa-sun" aria-hidden="true"></i> Escolha uma dica para hoje e respeite o seu ritmo.</p>
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
                        Nova consulta ou exame
                    </button>
                </div>
                <div class="consultas" id="resumoConsultas">
                    <?php if (!$consultas): ?>
                        <p class="agenda-vazia">Nenhuma consulta ou exame cadastrado.</p>
                    <?php else: ?>
                        <?php foreach ($consultas as $consulta): ?>
                            <div class="consulta">
                                <div class="data-consulta">
                                    <strong>
                                        <?= htmlspecialchars(date('d', strtotime($consulta['data']))) ?>
                                    </strong>
                                    <span>
                                        <?= htmlspecialchars(strtoupper(date('M', strtotime($consulta['data'])))) ?>
                                    </span>
                                </div>
                                <div class="info-consulta">
                                    <h4>
                                        <?= htmlspecialchars($consulta['tipo'] . ' de ' . $consulta['especialidade']) ?>
                                    </h4>
                                    <p>
                                        <?= htmlspecialchars($consulta['medico'] ?: 'Profissional não informado') ?>
                                    </p>
                                </div>
                                <div class="horario-consulta">
                                    <strong>
                                        <?= htmlspecialchars(substr($consulta['horario'], 0, 5)) ?>
                                    </strong>
                                    <span>
                                        <?= htmlspecialchars($consulta['nome_local'] ?: 'Local não informado') ?>
                                    </span>
                                    <?php if (!empty($consulta['link_reuniao']) && filter_var($consulta['link_reuniao'], FILTER_VALIDATE_URL) && in_array(strtolower((string) parse_url($consulta['link_reuniao'], PHP_URL_SCHEME)), ['https', 'http'], true)): ?>
                                        <a class="link-reuniao" href="<?= htmlspecialchars($consulta['link_reuniao'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">Entrar na reunião</a>
                                    <?php endif; ?>
                                </div>
                                <div class="acoes-evento">
                                    <button type="button" class="acao-evento editar-evento" data-acao="editar"
                                        data-id="<?= (int) $consulta['id_consulta'] ?>" aria-label="Editar consulta ou exame"
                                        title="Editar"><i class="fa-solid fa-pen" aria-hidden="true"></i></button>
                                    <button type="button" class="acao-evento excluir-evento" data-acao="excluir"
                                        data-id="<?= (int) $consulta['id_consulta'] ?>" aria-label="Excluir consulta ou exame"
                                        title="Excluir"><i class="fa-solid fa-trash" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <div class="modal" id="modalAgenda">
        <div class="modal-conteudo agenda" role="dialog" aria-modal="true" aria-labelledby="tituloFormulario">
            <div class="modal-topo">
                <button type="button" class="fechar-modal" id="fecharAgenda" aria-label="Fechar formulário">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <h2 id="tituloFormulario">
                    Nova consulta ou exame
                </h2>
            </div>
            <!-- FORM -->
            <form id="formAgenda" class="form-consulta" novalidate>
                <input type="hidden" name="id_consulta" id="idConsulta">
                <div class="campo">
                    <label>Tipo</label>
                    <select name="tipo" id="tipoConsulta" required>
                        <option>Consulta</option>
                        <option>Exame</option>
                    </select>
                </div>
                <div class="campo">
                    <label>Especialidade</label>
                    <input type="text" name="especialidade" id="especialidade" maxlength="150" placeholder="Ex.: Oncologista" required>
                </div>
                <div class="campo">
                    <label>Médico</label>
                    <input type="text" name="medico" id="medico" maxlength="150" placeholder="Nome do médico">
                </div>
                <div class="campo">
                    <label>Local</label>
                    <input type="text" name="nome_local" id="nomeLocal" maxlength="150" placeholder="Hospital ou clínica">
                </div>
                <div class="campo">
                    <label>Data</label>
                    <input type="date" name="data" id="dataConsulta" required>
                </div>
                <div class="campo">
                    <label>Horário</label>
                    <input type="time" name="horario" id="horarioConsulta" required>
                </div>
                <div class="campo">
                    <label for="linkReuniao">Link da reunião (opcional)</label>
                    <input type="url" name="link_reuniao" id="linkReuniao" maxlength="2048" placeholder="https://...">
                </div>
                <div class="botoes-modal">
                    <button type="button" id="cancelarEdicao" class="btn-cancelar">
                        Cancelar
                    </button>
                    <button type="submit" id="salvarAgenda" class="btn-salvar">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="modalExcluirEvento">
        <div class="modal-conteudo confirmar-evento" role="alertdialog" aria-modal="true"
            aria-labelledby="tituloExcluirEvento" aria-describedby="descricaoExcluirEvento">
            <div class="icone-excluir-evento"><i class="fa-solid fa-trash" aria-hidden="true"></i></div>
            <h2 id="tituloExcluirEvento">Excluir compromisso?</h2>
            <p id="descricaoExcluirEvento"></p>
            <p id="erroExcluirEvento" role="alert" hidden></p>
            <div class="botoes-confirmar-evento">
                <button type="button" id="cancelarExclusaoAgenda" class="btn-cancelar">Cancelar</button>
                <button type="button" id="confirmarExcluirAgenda" class="btn-excluir-agenda">Excluir</button>
            </div>
        </div>
    </div>

    <script src="api/cliente.js?v=20261027-2"></script>
    <script>
        (() => {
            const modal = document.getElementById('modalAgenda');
            const modalExcluir = document.getElementById('modalExcluirEvento');
            const formulario = document.getElementById('formAgenda');
            const titulo = document.getElementById('tituloFormulario');
            const resumoConsultas = document.getElementById('resumoConsultas');
            const abrirAgenda = document.getElementById('abrirAgenda');
            const botaoSalvar = document.getElementById('salvarAgenda');
            const cancelarEdicao = document.getElementById('cancelarEdicao');
            const fecharAgenda = document.getElementById('fecharAgenda');
            const confirmarExcluir = document.getElementById('confirmarExcluirAgenda');
            const cancelarExclusao = document.getElementById('cancelarExclusaoAgenda');
            const erroExcluir = document.getElementById('erroExcluirEvento');
            const descricaoExcluir = document.getElementById('descricaoExcluirEvento');
            let consultas = <?= json_encode($consultas, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            let tentouSalvarAgenda = false;
            let salvandoAgenda = false;
            let excluindoAgenda = false;
            let consultaParaExcluir = null;
            let origemModal = null;
            let overflowAnterior = '';
            let modalAtual = null;
            formulario.noValidate = true;
            const camposAgenda = Array.from(
                formulario.querySelectorAll(
                    'input:not([type="hidden"]), select, textarea'
                )
            );
            const mensagensAgenda = new Map();
            camposAgenda.forEach(campo => {
                const mensagem = document.createElement("small");
                mensagem.id = `${campo.id}-erro`;
                mensagem.className = "erro-campo";
                mensagem.setAttribute("aria-live", "polite");
                mensagem.hidden = true;
                campo.insertAdjacentElement("afterend", mensagem);
                const descricao = campo.getAttribute("aria-describedby") || "";
                campo.setAttribute(
                    "aria-describedby",
                    `${descricao} ${mensagem.id}`.trim()
                );
                mensagensAgenda.set(campo, mensagem);
            });
            const erroAgenda = document.createElement("p");
            erroAgenda.className = "erro-agenda";
            erroAgenda.setAttribute("role", "alert");
            erroAgenda.hidden = true;
            formulario.prepend(erroAgenda);
            function mensagemDoCampo(campo) {
                const mensagensObrigatorias = {
                    tipoConsulta: "Selecione o tipo.",
                    especialidade: "Informe a especialidade.",
                    dataConsulta: "Informe a data.",
                    horarioConsulta: "Informe o horário."
                };
                if (campo.validity.badInput) {
                    return "Informe um valor válido.";
                }
                if (campo.required && !campo.value.trim()) {
                    return mensagensObrigatorias[campo.id] || "Preencha este campo.";
                }
                if (!campo.validity.valid) {
                    return campo.validationMessage || "Confira este campo.";
                }
                return "";
            }
            function validarCampoAgenda(campo) {
                const texto = mensagemDoCampo(campo);
                const mensagem = mensagensAgenda.get(campo);
                campo.classList.toggle("input-erro", Boolean(texto));
                mensagem.textContent = texto;
                mensagem.hidden = !texto;
                if (texto) {
                    campo.setAttribute("aria-invalid", "true");
                } else {
                    campo.removeAttribute("aria-invalid");
                }
                return !texto;
            }

            camposAgenda.forEach(campo => {
                function atualizarValidacao() {
                    if (tentouSalvarAgenda) {
                        validarCampoAgenda(campo);
                    }
                    erroAgenda.textContent = "";
                    erroAgenda.hidden = true;
                }
                campo.addEventListener("input", atualizarValidacao);
                campo.addEventListener("change", atualizarValidacao);
            });

            function limparErrosAgenda() {
                tentouSalvarAgenda = false;
                erroAgenda.textContent = '';
                erroAgenda.hidden = true;
                camposAgenda.forEach(campo => {
                    campo.classList.remove('input-erro');
                    campo.removeAttribute('aria-invalid');
                    mensagensAgenda.get(campo).textContent = '';
                    mensagensAgenda.get(campo).hidden = true;
                });
            }
            function limparFormulario() {
                formulario.reset();
                document.getElementById('idConsulta').value = '';
                limparErrosAgenda();
            }
            function abrirModal(elemento, origem, foco) {
                if (modalAtual) return;
                origemModal = origem;
                overflowAnterior = document.body.style.overflow;
                modalAtual = elemento;
                elemento.classList.add('ativo');
                document.body.style.overflow = 'hidden';
                foco.focus();
            }
            function fecharModal(forcar = false) {
                if (!modalAtual || (!forcar && (salvandoAgenda || excluindoAgenda))) return;
                modalAtual.classList.remove('ativo');
                modalAtual = null;
                document.body.style.overflow = overflowAnterior;
                limparFormulario();
                consultaParaExcluir = null;
                erroExcluir.textContent = '';
                erroExcluir.hidden = true;
                if (origemModal?.isConnected) origemModal.focus();
                else abrirAgenda.focus();
            }
            // Mantém a navegação por Tab dentro do modal; clique no fundo não fecha.
            document.addEventListener('keydown', event => {
                if (!modalAtual || event.key !== 'Tab') return;
                const focaveis = Array.from(modalAtual.querySelectorAll('button:not(:disabled), input:not([type="hidden"]):not(:disabled), select:not(:disabled), textarea:not(:disabled), [tabindex="0"]'));
                if (!focaveis.length) { event.preventDefault(); return; }
                const primeiro = focaveis[0];
                const ultimo = focaveis[focaveis.length - 1];
                if (event.shiftKey && document.activeElement === primeiro) { event.preventDefault(); ultimo.focus(); }
                else if (!event.shiftKey && document.activeElement === ultimo) { event.preventDefault(); primeiro.focus(); }
            });
            function escaparAgenda(texto) {
                const el = document.createElement('span');
                el.textContent = String(texto ?? '');
                return el.innerHTML;
            }
            function urlReuniaoSegura(valor) {
                try {
                    const url = new URL(String(valor ?? ''));
                    return ['http:', 'https:'].includes(url.protocol) ? url.href : '';
                } catch { return ''; }
            }
            function renderizarResumo() {
                if (!consultas.length) {
                    resumoConsultas.innerHTML = '<p class="agenda-vazia">Nenhuma consulta ou exame cadastrado.</p>';
                    return;
                }
                resumoConsultas.innerHTML = consultas.map(consulta => {
                    const mes = new Date(`${consulta.data}T00:00:00`).toLocaleDateString('pt-BR', { month: 'short' }).replace('.', '').toUpperCase();
                    const id = escaparAgenda(consulta.id_consulta);
                    const linkReuniao = urlReuniaoSegura(consulta.link_reuniao);
                    return `<div class="consulta">
                <div class="data-consulta"><strong>${escaparAgenda(consulta.data.slice(8, 10))}</strong><span>${escaparAgenda(mes)}</span></div>
                <div class="info-consulta"><h4>${escaparAgenda(`${consulta.tipo} de ${consulta.especialidade}`)}</h4><p>${escaparAgenda(consulta.medico || 'Profissional não informado')}</p></div>
                <div class="horario-consulta"><strong>${escaparAgenda(consulta.horario.slice(0, 5))}</strong><span>${escaparAgenda(consulta.nome_local || 'Local não informado')}</span>${linkReuniao ? `<a class="link-reuniao" href="${escaparAgenda(linkReuniao)}" target="_blank" rel="noopener noreferrer">Entrar na reunião</a>` : ''}</div>
                <div class="acoes-evento">
                    <button type="button" class="acao-evento editar-evento" data-acao="editar" data-id="${id}" aria-label="Editar consulta ou exame" title="Editar"><i class="fa-solid fa-pen" aria-hidden="true"></i></button>
                    <button type="button" class="acao-evento excluir-evento" data-acao="excluir" data-id="${id}" aria-label="Excluir consulta ou exame" title="Excluir"><i class="fa-solid fa-trash" aria-hidden="true"></i></button>
                </div>
            </div>`;
                }).join('');
            }
            async function enviarAgenda(dados) {
                const valores = Object.fromEntries(dados.entries());
                const acao = valores.acao;
                const id = valores.id_consulta;
                delete valores.acao;
                delete valores.id_consulta;
                if (acao === 'excluir') {
                    await VitalizeAPI.requisicao(`/api/appointments/${encodeURIComponent(id)}`, { method: 'DELETE' });
                } else {
                    await VitalizeAPI.requisicao(id ? `/api/appointments/${encodeURIComponent(id)}` : '/api/appointments', {
                        method: id ? 'PUT' : 'POST', body: valores
                    });
                }
                const resultado = await VitalizeAPI.requisicao('/api/appointments');
                if (!Array.isArray(resultado.consultas)) throw new Error('Resposta inesperada da API.');
                consultas = resultado.consultas;
                renderizarResumo();
            }
            abrirAgenda.addEventListener('click', () => {
                if (modalAtual) return;
                limparFormulario();
                titulo.textContent = 'Nova consulta ou exame';
                abrirModal(modal, abrirAgenda, document.getElementById('tipoConsulta'));
            });
            fecharAgenda.addEventListener('click', () => fecharModal());
            cancelarEdicao.addEventListener('click', () => fecharModal());
            cancelarExclusao.addEventListener('click', () => fecharModal());
            resumoConsultas.addEventListener('click', event => {
                const botao = event.target.closest('button[data-acao]');
                if (!botao || modalAtual) return;
                const consulta = consultas.find(item => String(item.id_consulta) === botao.dataset.id);
                if (!consulta) return;
                if (botao.dataset.acao === 'editar') {
                    limparFormulario();
                    titulo.textContent = 'Editar consulta ou exame';
                    document.getElementById('idConsulta').value = consulta.id_consulta;
                    document.getElementById('tipoConsulta').value = consulta.tipo;
                    document.getElementById('especialidade').value = consulta.especialidade;
                    document.getElementById('medico').value = consulta.medico || '';
                    document.getElementById('nomeLocal').value = consulta.nome_local || '';
                    document.getElementById('dataConsulta').value = consulta.data;
                    document.getElementById('horarioConsulta').value = consulta.horario.slice(0, 5);
                    document.getElementById('linkReuniao').value = consulta.link_reuniao || '';
                    abrirModal(modal, botao, document.getElementById('especialidade'));
                } else if (botao.dataset.acao === 'excluir') {
                    consultaParaExcluir = consulta.id_consulta;
                    const data = new Date(`${consulta.data}T00:00:00`).toLocaleDateString('pt-BR');
                    descricaoExcluir.textContent = `Tem certeza que deseja excluir ${consulta.tipo.toLowerCase()} de ${consulta.especialidade}, em ${data} às ${consulta.horario.slice(0, 5)}? Esta ação não pode ser desfeita.`;
                    erroExcluir.hidden = true;
                    erroExcluir.textContent = '';
                    abrirModal(modalExcluir, botao, cancelarExclusao);
                }
            });
            formulario.addEventListener('submit', async event => {
                event.preventDefault();
                if (salvandoAgenda) return;
                tentouSalvarAgenda = true;
                erroAgenda.hidden = true;
                let primeiroInvalido = null;
                camposAgenda.forEach(campo => {
                    if (!validarCampoAgenda(campo) && !primeiroInvalido) primeiroInvalido = campo;
                });
                if (primeiroInvalido) { primeiroInvalido.focus(); return; }
                const dados = new FormData(formulario);
                dados.append('acao', 'salvar');
                salvandoAgenda = true;
                [botaoSalvar, cancelarEdicao, fecharAgenda].forEach(b => b.disabled = true);
                botaoSalvar.textContent = 'Salvando...';
                try { await enviarAgenda(dados); fecharModal(true); }
                catch (erro) { erroAgenda.textContent = erro.message || 'Não foi possível salvar. Tente novamente.'; erroAgenda.hidden = false; }
                finally {
                    salvandoAgenda = false;
                    [botaoSalvar, cancelarEdicao, fecharAgenda].forEach(b => b.disabled = false);
                    botaoSalvar.textContent = 'Salvar';
                }
            });
            confirmarExcluir.addEventListener('click', async () => {
                if (consultaParaExcluir === null || excluindoAgenda) return;
                const dados = new FormData();
                dados.append('acao', 'excluir');
                dados.append('id_consulta', consultaParaExcluir);
                excluindoAgenda = true;
                confirmarExcluir.disabled = cancelarExclusao.disabled = true;
                confirmarExcluir.textContent = 'Excluindo...';
                erroExcluir.hidden = true;
                try { await enviarAgenda(dados); fecharModal(true); }
                catch (erro) { erroExcluir.textContent = erro.message || 'Não foi possível excluir. Tente novamente.'; erroExcluir.hidden = false; }
                finally { excluindoAgenda = false; confirmarExcluir.disabled = cancelarExclusao.disabled = false; confirmarExcluir.textContent = 'Excluir'; }
            });
            renderizarResumo();
            VitalizeAPI.requisicao('/api/appointments').then(resultado => {
                consultas = Array.isArray(resultado.consultas) ? resultado.consultas : [];
                renderizarResumo();
            }).catch(() => {});
        })();

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
                const dados = await VitalizeAPI.requisicao('/api/assistant', {
                    method: 'POST', body: { humor: humorSelecionado, sintomas, restricoes }
                });
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
            btn.setAttribute('aria-pressed', btn.classList.contains('ativo') ? 'true' : 'false');
            btn.addEventListener('click', () => {
                const ativo = btn.classList.toggle('ativo');
                btn.setAttribute('aria-pressed', ativo ? 'true' : 'false');
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
