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

    <link rel="stylesheet" href="css/style.css?v=20261126-cards-consent">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
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

                <p class="mapa-hint">Os marcadores mostram os serviços em Ribeirão Pires e Santo André e referências municipais de parceiros. Os pontos aproximados não indicam endereços ou locais atuais de coleta.</p>
                <div id="mapaVitalize" role="region" aria-label="Mapa com os locais de atendimento e parceiros do Vitalize"></div>

            </div>

            <div class="lista-clinicas">

                <div class="card-clinica">
                    <span class="tag-local">Atendimento especializado · SUS</span>
                    <h3>CEM + CAISM</h3>
                    <p>Av. Humberto de Campos, 70 — Suíssa, Ribeirão Pires. Atendimento especializado e diagnóstico; acesso encaminhado pela UBS/USF.</p>
                    <div class="acoes-clinica">
                        <a class="acao-clinica" data-location-id="cem" href="https://www.google.com/maps/search/?api=1&amp;query=Centro+de+Especialidades+M%C3%A9dicas+Av.+Humberto+de+Campos+70+Ribeir%C3%A3o+Pires" target="_blank" rel="noopener noreferrer">Ver no mapa</a>
                        <a class="acao-clinica secundaria" href="https://www.ribeiraopires.sp.gov.br/portal/secretarias-paginas/16/enderecos-uteis/" target="_blank" rel="noopener noreferrer">Informações oficiais</a>
                    </div>
                </div>

                <div class="card-clinica">
                    <span class="tag-local">Oncologia · SUS</span>
                    <h3>Hospital Estadual Mário Covas</h3>
                    <p>Rua Dr. Henrique Calderazzo, 321 — Santo André. Atendimento oncológico regulado pelo SUS.</p>
                    <div class="acoes-clinica">
                        <a class="acao-clinica" data-location-id="hemc" href="https://www.google.com/maps/search/?api=1&amp;query=Hospital+Estadual+M%C3%A1rio+Covas+Santo+Andr%C3%A9" target="_blank" rel="noopener noreferrer">Ver no mapa</a>
                        <a class="acao-clinica secundaria" href="https://hemc.org.br/fale-conosco/" target="_blank" rel="noopener noreferrer">Contato oficial</a>
                    </div>
                </div>

                <div class="card-clinica parceiro-local">
                    <span class="tag-local">Parceiro Vitalize</span>
                    <h3>Salão de Beleza Absoluto</h3>
                    <p>Mauá – SP. O perfil informa a cidade, mas não publica o endereço completo.</p>
                    <div class="acoes-clinica">
                        <a class="acao-clinica" data-location-id="maua" href="https://www.google.com/maps/search/?api=1&amp;query=Sal%C3%A3o+de+Beleza+Absoluto+Mau%C3%A1+SP" target="_blank" rel="noopener noreferrer">Ver região no mapa</a>
                        <a class="acao-clinica secundaria" href="https://www.instagram.com/salaodebelezaabsoluto/" target="_blank" rel="noopener noreferrer">Consultar o parceiro</a>
                    </div>
                </div>

                <div class="card-clinica parceiro-local">
                    <span class="tag-local">Parceiro de apoio</span>
                    <h3>Instituto Romatt de Combate ao Câncer</h3>
                    <p>Parceiro de Mauá. O marcador indica apenas a referência do município; o endereço do Instituto não foi confirmado.</p>
                    <div class="acoes-clinica">
                        <a class="acao-clinica" data-location-id="maua" href="https://www.google.com/maps/search/?api=1&amp;query=Instituto+Romatt+Mau%C3%A1+SP" target="_blank" rel="noopener noreferrer">Ver região no mapa</a>
                        <a class="acao-clinica secundaria" href="https://www.instagram.com/ircc.br/" target="_blank" rel="noopener noreferrer">Instagram do parceiro</a>
                    </div>
                </div>

                <div class="card-clinica parceiro-local">
                    <span class="tag-local">Parceiro de apoio</span>
                    <h3>Doe Lenço, Doe Alegria</h3>
                    <p>Referência municipal em Ribeirão Pires. O projeto não tem endereço fixo confirmado; pontos de coleta podem variar, então consulte o perfil antes de se deslocar.</p>
                    <div class="acoes-clinica">
                        <a class="acao-clinica" data-location-id="doelenco" href="https://www.google.com/maps/search/?api=1&amp;query=Ribeir%C3%A3o+Pires+SP" target="_blank" rel="noopener noreferrer">Ver região no mapa</a>
                        <a class="acao-clinica secundaria" href="https://www.instagram.com/doelencodoealegria/" target="_blank" rel="noopener noreferrer">Instagram do parceiro</a>
                    </div>
                </div>

            </div>

        </div>

    </section>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        const mapaVitalize = L.map('mapaVitalize', { scrollWheelZoom: false });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(mapaVitalize);

        const locaisVitalize = {
            cem: {
                coords: [-23.71196, -46.41790],
                nome: 'CEM + CAISM',
                endereco: 'Av. Humberto de Campos, 70 — Suíssa, Ribeirão Pires',
                link: 'https://www.google.com/maps/search/?api=1&query=Centro+de+Especialidades+M%C3%A9dicas+Av.+Humberto+de+Campos+70+Ribeir%C3%A3o+Pires'
            },
            hemc: {
                coords: [-23.67093, -46.53299],
                nome: 'Hospital Estadual Mário Covas',
                endereco: 'Rua Dr. Henrique Calderazzo, 321 — Santo André',
                link: 'https://www.google.com/maps/search/?api=1&query=Hospital+Estadual+M%C3%A1rio+Covas+Santo+Andr%C3%A9'
            },
            maua: {
                coords: [-23.66667, -46.46111],
                nome: 'Mauá — parceiros do Vitalize',
                endereco: 'Referência no município. O endereço exato do Salão Absoluto e do Instituto Romatt não foi confirmado; este marcador é aproximado.',
                link: 'https://www.google.com/maps/search/?api=1&query=Mau%C3%A1+SP'
            },
            doelenco: {
                coords: [-23.71056, -46.41333],
                nome: 'Doe Lenço, Doe Alegria — referência municipal',
                endereco: 'Referência aproximada em Ribeirão Pires. O endereço do projeto e os pontos atuais de coleta não foram confirmados.',
                link: 'https://www.google.com/maps/search/?api=1&query=Ribeir%C3%A3o+Pires+SP'
            }
        };

        Object.entries(locaisVitalize).forEach(([id, local]) => {
            local.marker = L.marker(local.coords).addTo(mapaVitalize);
            local.marker.bindPopup(`<strong>${local.nome}</strong><br>${local.endereco}<br><a href="${local.link}" target="_blank" rel="noopener noreferrer">Abrir no Google Maps</a>`);
        });
        mapaVitalize.fitBounds(Object.values(locaisVitalize).map(local => local.coords), { padding: [28, 28] });

        document.querySelectorAll('.acao-clinica[data-location-id]').forEach(link => {
            link.addEventListener('click', () => {
                const local = locaisVitalize[link.dataset.locationId];
                if (!local) return;
                mapaVitalize.setView(local.coords, 15);
                local.marker.openPopup();
            });
        });
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
            const imagensAlternativasGrupo = [
                'img/grupos/grupo-01.webp', 'img/grupos/grupo-02.webp',
                'img/grupos/grupo-03.webp', 'img/grupos/grupo-04.webp',
                'img/grupos/grupo-05.webp', 'img/grupos/grupo-06.webp'
            ];
            const chaveImagem = caminho => new URL(caminho, document.baseURI).pathname.toLocaleLowerCase();
            const imagemAlternativaGrupo = (grupo, usadas) => {
                const inicio = (Math.max(1, Number(grupo.id_grupo) || 1) - 1) % imagensAlternativasGrupo.length;
                for (let passo = 0; passo < imagensAlternativasGrupo.length; passo++) {
                    const imagem = imagensAlternativasGrupo[(inicio + passo) % imagensAlternativasGrupo.length];
                    if (!usadas.has(chaveImagem(imagem))) return imagem;
                }
                return imagensAlternativasGrupo[inicio];
            };
            const urlImagemGrupo = (valor, alternativa) => {
                const caminho = String(valor ?? '').trim().replace(/\\/g, '/');
                if (!caminho || caminho === 'img/apoio1.webp') return alternativa;
                if (/^https?:\/\//i.test(caminho) || caminho.startsWith('/')) return caminho;
                if (caminho.startsWith('img/')) return caminho;
                if (caminho.startsWith('uploads/')) return `img/${caminho}`;
                if (/^[a-z0-9_.-]+\.(?:jpe?g|png|webp)$/i.test(caminho)) return `img/uploads/grupos/${encodeURIComponent(caminho)}`;
                return alternativa;
            };
            try {
                const resposta = await VitalizeAPI.requisicao('/api/groups');
                const todos = (resposta.grupos || []).slice(0, 3);
                const imagensUsadas = new Set();
                alvo.innerHTML = `${todos.map(g => {
                    const alternativa = imagemAlternativaGrupo(g, imagensUsadas);
                    imagensUsadas.add(chaveImagem(alternativa));
                    let foto = urlImagemGrupo(g.imagem, alternativa);
                    if (imagensUsadas.has(chaveImagem(foto))) foto = alternativa;
                    imagensUsadas.add(chaveImagem(foto));
                    return `<article class="cardgrupos"><img src="${seguro(foto)}" data-fallback="${alternativa}" onerror="this.onerror=null;this.src=this.dataset.fallback" alt="Imagem do grupo ${seguro(g.nome_grupo)}"><div class="conteudocards"><span class="taggrupo">Foco: ${seguro(g.foco || 'Foco não informado')}</span><h3>${seguro(g.nome_grupo)}</h3><div class="infosgrupo"><span>${g.data_encontro ? new Date(`${g.data_encontro}T12:00:00`).toLocaleDateString('pt-BR') : 'Data a definir'}</span><span>${seguro((g.horario || '').slice(0,5) || 'Horário a definir')}</span></div><p class="focogrupo">${seguro(g.mais_info || '')}</p><ul class="dados-grupo-meta"><li><strong>Responsável:</strong> ${seguro(g.responsavel || 'Não informado')}</li><li><strong>Contato:</strong> ${seguro(g.telefone_grupo || 'Não informado')}</li></ul><a class="infogrupos" href="conteudo.php?pagina=grupos">Ver grupos e detalhes</a></div></article>`;
                }).join('') || '<p class="estado-vazio">Nenhum grupo de apoio publicado no momento.</p>'}`;
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

