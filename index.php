<?php
require_once __DIR__ . '/navegacao.php';
vitalize_exibir_aplicacao('index.php');
require_once __DIR__ . '/iniciar_sessao.php';
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
    <link rel="icon" type="image/png" href="img/logov.png">

    <link rel="stylesheet" href="css/style.css?v=13">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="anonymous">
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




    <section class="banner-home">

        <img src="img/banner-home.png">

    </section>



    <section class="sobre-home">

        <div class="logo-sobre">
            <img src="img/logobolha.png">
        </div>

        <div class="texto-sobre">


            <p>
                Um espaço criado para acolher, informar e apoiar pessoas em tratamento contra o câncer. No Vitalize,
                você encontra notícias positivas, apoio à saúde, grupos de apoio e iniciativas solidárias, tudo em um só
                lugar.
            </p>

        </div>

    </section>



    <section class="clinicas">

        <h2 class="h2titulo">
            Rede de atendimento <span>e parceiros</span>
        </h2>

        <div class="conteudo-clinicas">

            <div class="mapa">

                <p class="mapa-hint">Selecione uma unidade ou parceiro para destacar a localização. Quando o endereço exato não é público, o mapa mostra apenas uma referência do município.</p>
                <div id="mapaVitalize" role="region" aria-label="Mapa com os locais de atendimento e parceiros do Vitalize"></div>

            </div>

            <div class="lista-clinicas" id="listaLocaisVitalize" aria-live="polite"></div>

        </div>

    </section>

    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="anonymous"></script>
    <script>
        (() => {
            const elementoMapa = document.getElementById('mapaVitalize');
            const listaLocais = document.getElementById('listaLocaisVitalize');
            const locaisMapa = {
                cem: {
                    nome: 'CEM + CAISM', tipo: 'Unidade pública de saúde',
                    endereco: 'Avenida Humberto de Campos, 70 — Suíssa, Ribeirão Pires — SP, 09424-600',
                    detalhe: 'Atendimento especializado. O acesso é feito por encaminhamento da UBS/USF.',
                    consulta: 'Centro de Especialidades Médicas, Avenida Humberto de Campos 70, Ribeirão Pires, SP',
                    coords: [-23.71196, -46.41790],
                    fonte: 'https://www.ribeiraopires.sp.gov.br/portal/secretarias-paginas/16/enderecos-uteis/',
                    fonteTexto: 'Endereço oficial'
                },
                hemc: {
                    nome: 'Hospital Estadual Mário Covas', tipo: 'Hospital público · Oncologia',
                    endereco: 'Rua Dr. Henrique Calderazzo, 321 — Santo André — SP, 09190-615',
                    detalhe: 'Consulte o hospital para confirmar os serviços e o fluxo de atendimento.',
                    consulta: 'Hospital Estadual Mário Covas, Rua Doutor Henrique Calderazzo 321, Santo André, SP',
                    coords: [-23.67093, -46.53299],
                    fonte: 'https://hemc.org.br/fale-conosco/',
                    fonteTexto: 'Contato oficial'
                },
                maua: {
                    nome: 'Referência municipal — Mauá', tipo: 'Parceiros Vitalize · localização aproximada',
                    endereco: 'Mauá — SP. Este ponto indica somente o município, não o endereço de atendimento dos parceiros.',
                    detalhe: 'Confirme o endereço e o local da ação diretamente com cada parceiro antes de se deslocar.',
                    consulta: 'Mauá, SP, Brasil', coords: [-23.66667, -46.46111],
                    fonte: null, fonteTexto: ''
                }
            };
            const parceiros = [
                { nome: 'Salão de Beleza Absoluto', categoria: 'Parceiro Vitalize', descricao: 'Endereço completo não confirmado. O marcador indica apenas Mauá.', instagram: 'https://www.instagram.com/salaodebelezaabsoluto/' },
                { nome: 'Instituto Romatt de Combate ao Câncer', categoria: 'Parceiro de apoio', descricao: 'O marcador indica Mauá como referência municipal; confirme o local de atendimento antes de ir.', instagram: 'https://www.instagram.com/ircc.br/' },
                { nome: 'Doe Lenço, Doe Alegria', categoria: 'Parceiro de apoio', descricao: 'Ações e pontos de coleta podem variar. O marcador é uma referência municipal, não uma sede fixa.', instagram: 'https://www.instagram.com/doelencodoealegria/' }
            ];
            const escapar = valor => String(valor ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
            const urlMapa = local => `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(local.consulta)}`;

            function cardLocal(localId, local, categoria, nome, descricao, fonte, fonteTexto) {
                return `<article class="card-clinica ${categoria.includes('Parceiro') ? 'parceiro-local' : ''}"><span class="tag-local">${escapar(categoria)}</span><h3>${escapar(nome)}</h3><p class="endereco-local">${escapar(local.endereco)}</p><p class="descricao-local">${escapar(descricao)}</p><div class="acoes-clinica"><button class="acao-clinica" type="button" data-location-id="${escapar(localId)}">Destacar no mapa</button><a class="acao-clinica secundaria" href="${urlMapa(local)}" target="_blank" rel="noopener noreferrer">Abrir no Google Maps</a>${fonte ? `<a class="acao-clinica secundaria" href="${escapar(fonte)}" target="_blank" rel="noopener noreferrer">${escapar(fonteTexto)}</a>` : ''}</div></article>`;
            }

            function popupLocal(local) {
                const caixa = document.createElement('div');
                caixa.className = 'popup-local';
                const etiqueta = document.createElement('span');
                etiqueta.className = 'popup-local__tipo'; etiqueta.textContent = local.tipo;
                const titulo = document.createElement('strong');
                titulo.className = 'popup-local__titulo'; titulo.textContent = local.nome;
                const endereco = document.createElement('p');
                endereco.className = 'popup-local__endereco'; endereco.textContent = local.endereco;
                const detalhe = document.createElement('p');
                detalhe.className = 'popup-local__detalhe'; detalhe.textContent = local.detalhe;
                const link = document.createElement('a');
                link.href = urlMapa(local); link.target = '_blank'; link.rel = 'noopener noreferrer';
                link.className = 'popup-local__link'; link.textContent = 'Abrir no Google Maps';
                caixa.append(etiqueta, titulo, endereco, detalhe);
                if (local.fonte) {
                    const fonte = document.createElement('a');
                    fonte.href = local.fonte; fonte.target = '_blank'; fonte.rel = 'noopener noreferrer';
                    fonte.className = 'popup-local__fonte'; fonte.textContent = local.fonteTexto;
                    caixa.append(fonte);
                }
                caixa.append(link);
                if (localIdMaua(local)) {
                    const lista = document.createElement('ul'); lista.className = 'popup-local__parceiros';
                    parceiros.forEach(parceiro => {
                        const item = document.createElement('li'); item.textContent = parceiro.nome;
                        lista.append(item);
                    });
                    caixa.append(lista);
                }
                return caixa;
            }

            function localIdMaua(local) { return local === locaisMapa.maua; }
            function mostrarErroMapa(mensagem) {
                elementoMapa.innerHTML = `<p class="mapa-erro" role="status">${escapar(mensagem)}</p>`;
            }

            listaLocais.innerHTML = [
                cardLocal('cem', locaisMapa.cem, locaisMapa.cem.tipo, locaisMapa.cem.nome, locaisMapa.cem.detalhe, locaisMapa.cem.fonte, locaisMapa.cem.fonteTexto),
                cardLocal('hemc', locaisMapa.hemc, locaisMapa.hemc.tipo, locaisMapa.hemc.nome, locaisMapa.hemc.detalhe, locaisMapa.hemc.fonte, locaisMapa.hemc.fonteTexto),
                ...parceiros.map(parceiro => cardLocal('maua', locaisMapa.maua, parceiro.categoria, parceiro.nome, parceiro.descricao, parceiro.instagram, 'Instagram do parceiro'))
            ].join('');

            if (typeof L === 'undefined') {
                mostrarErroMapa('O mapa não carregou. Use os links “Abrir no Google Maps” ao lado para consultar as localizações.');
                return;
            }

            try {
                const mapaVitalize = L.map(elementoMapa, { scrollWheelZoom: false });
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(mapaVitalize);
                Object.values(locaisMapa).forEach(local => {
                    local.marker = L.marker(local.coords).addTo(mapaVitalize).bindPopup(popupLocal(local), { maxWidth: 320 });
                });
                mapaVitalize.fitBounds(Object.values(locaisMapa).map(local => local.coords), { padding: [28, 28] });
                requestAnimationFrame(() => requestAnimationFrame(() => mapaVitalize.invalidateSize()));
                listaLocais.addEventListener('click', event => {
                    const botao = event.target.closest('[data-location-id]');
                    if (!botao) return;
                    const local = locaisMapa[botao.dataset.locationId];
                    if (!local) return;
                    mapaVitalize.setView(local.coords, botao.dataset.locationId === 'maua' ? 13 : 16);
                    local.marker.openPopup();
                });
            } catch (erro) {
                mostrarErroMapa('Não foi possível inicializar o mapa. Os endereços e links do Google Maps continuam disponíveis ao lado.');
                console.error('Falha ao iniciar o mapa Vitalize:', erro);
            }
        })();
    </script>



    <section class="grupos">



        <h2 class="h2titulo">Grupos de apoio</h2>


        <div class="cardsgrupos" id="gruposDestaque" aria-live="polite">

            <p class="estado-vazio">Carregando grupos de apoio…</p>



        <div style="align-items: center;" class="botoescards">
            <a href="conteudo.php?pagina=sobregrupos" class="adecorado">Saiba Mais</a>
        </div>

    </section>

    <script src="api/cliente.js?v=20261124-1"></script>
    <script>
        (async () => {
            const alvo = document.getElementById('gruposDestaque');
            const seguro = valor => String(valor ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
            try {
                const resposta = await VitalizeAPI.requisicao('/api/groups');
                const todos = [...(resposta.grupos || []).map(g => ({...g, demo:false})), ...(resposta.grupos_demo || []).map(g => ({...g, demo:true}))].sort(() => Math.random() - 0.5).slice(0, 3);
                alvo.innerHTML = `${todos.some(g => g.demo) ? '<div class="aviso-demo" role="note"><strong>Modelos fictícios para apresentação.</strong> Registros aleatórios cadastrados no banco; atividades demonstrativas.</div>' : ''}${todos.map(g => `<article class="cardgrupos"><img src="${seguro(g.imagem || 'img/apoio1.webp')}" alt="Imagem ilustrativa de ${seguro(g.nome_grupo)}"><div class="conteudocards"><span class="taggrupo">${seguro(g.foco || 'Grupo de apoio')}</span><h3>${seguro(g.nome_grupo)}</h3><div class="infosgrupo"><span>${g.data_encontro ? new Date(`${g.data_encontro}T12:00:00`).toLocaleDateString('pt-BR') : 'Data a definir'}</span><span>${seguro((g.horario || '').slice(0,5) || 'Horário a definir')}</span></div><p class="focogrupo">${seguro(g.mais_info || '')}</p><ul class="dados-grupo-meta"><li><strong>Responsável:</strong> ${seguro(g.responsavel || 'Não informado')}</li><li><strong>Contato:</strong> ${seguro(g.telefone_grupo || 'Não informado')}</li></ul><a class="infogrupos link-ficha-demo" href="conteudo.php?pagina=grupos">Ver ficha completa</a></div></article>`).join('') || '<p class="estado-vazio">Nenhum grupo publicado no momento.</p>'}`;
            } catch (e) { alvo.innerHTML = '<p class="estado-vazio">Não foi possível carregar grupos de apoio.</p>'; }
        })();
    </script>

    <section class="noticias-home">

            <h2 class="h2titulo">Fique por dentro</h2>

            <div class="cards-noticias">

                <div class="card-noticia">
                    <img src="img/noticia1.webp">

                    <div class="conteudo-noticia">

                        <h3>B12: vitamina essencial com uma relação complexa com o câncer</h3>

                        <a href="https://www.metropoles.com/saude/b12-vitamina-essencial-com-uma-relacao-complexa-com-o-cancer"
                            target="_blank" rel="noopener noreferrer">
                            Ler mais
                        </a>

                    </div>
                </div>

                <div class="card-noticia">
                    <img src="img/noticia2.jpg" alt="Paciente em recuperação">

                    <div class="conteudo-noticia">

                        <h3>Biobancos: para que servem coleções de amostras de câncer</h3>

                        <a
                            href="https://www.oncoguia.org.br/conteudo/biobancos-para-que-servem-colecoes-de-amostras-de-cancer/18136/7/">
                            Ler mais
                        </a>

                    </div>
                </div>

                <div class="card-noticia">
                    <img src="img/noticia3.jpeg" alt="Projeto de apoio a pacientes">

                    <div class="conteudo-noticia">

                        <h3>Conheça o OncoTTGen: primeiro Instituto de pesquisa focado no tratamento avançado do câncer
                            no Piauí</h3>


                        <a href="https://ufpi.br/ultimas-noticias-ufpi/1107-destaques-ufpi/68024-conheca-o-oncottgen-primeiro-instituto-de-pesquisa-focado-no-tratamento-avancado-do-cancer-no-piaui"
                            target="_blank" rel="noopener noreferrer">
                            Ler mais
                        </a>

                    </div>
                </div>

            </div>

    </section>
    
    <?php include 'footer.php'; ?>

        <script>
            const toggle = document.querySelector(".menu-toggle");
            const menu = document.querySelector(".menu");

            toggle.addEventListener("click", () => {
                menu.classList.toggle("ativo");
            });
        </script>
</body>


</html>

