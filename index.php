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
            Atendimento <span>perto de você</span>
        </h2>

        <div class="conteudo-clinicas">

            <div class="mapa">

                <iframe src="https://maps.google.com/maps?q=ribeirao%20pires&t=&z=13&ie=UTF8&iwloc=&output=embed">
                </iframe>

            </div>

            <div class="lista-clinicas">

                <div class="card-clinica">
                    <h3>Instituto Vida Plena</h3>
                    <button>Ver rota</button>
                </div>

                <div class="card-clinica">
                    <h3>Clínica Renascer</h3>
                    <button>Ver rota</button>
                </div>

                <div class="card-clinica">
                    <h3>OncoCare</h3>
                    <button>Ver rota</button>
                </div>
                <div class="card-clinica">
                    <h3>Clínica Esperança</h3>
                    <button>Ver rota</button>
                </div>

            </div>

        </div>

    </section>



    <section class="grupos">



        <h2 class="h2titulo">Grupos de apoio</h2>


        <div class="cardsgrupos">



            <div class="cardgrupos">

                <img src="img/apoio1.webp" alt="">

                <div class="conteudocards">

                    <span class="taggrupo">Mulheres em tratamento</span>

                    <h3>Florescer Juntos</h3>

                    <div class="infosgrupo">
                        <span>25/06/2026</span>
                        <span>19h00</span>
                    </div>

                    <p class="focogrupo">
                        Mulheres com câncer de mama.
                    </p>


                </div>

            </div>



            <div class="cardgrupos">

                <img src="img/apoio2.jpg" alt="">

                <div class="conteudocards">

                    <span class="taggrupo">Pacientes em tratamento</span>

                    <h3>Asas da Esperança</h3>

                    <div class="infosgrupo">
                        <span>02/07/2026</span>
                        <span>20h00</span>
                    </div>

                    <p class="focogrupo">
                        Pacientes oncológicos em geral.
                    </p>


                </div>

            </div>



            <div class="cardgrupos">

                <img src="img/apoio3.jpg" alt="">

                <div class="conteudocards">

                    <span class="taggrupo">18 a 25 anos</span>

                    <h3>Jovens em Movimento</h3>

                    <div class="infosgrupo">
                        <span>10/07/2026</span>
                        <span>18h00</span>
                    </div>

                    <p class="focogrupo">
                        Jovens em tratamento oncológico.
                    </p>



                </div>

            </div>

        </div>
        <div style="align-items: center;" class="botoescards">
            <a href="sobregrupos.php" class="adecorado">Saiba Mais</a>
        </div>

    </section>

    <section class="noticias-home">

            <h2 class="h2titulo">Fique por dentro</h2>

            <div class="cards-noticias">

                <div class="card-noticia">
                    <img src="img/noticia1.webp">

                    <div class="conteudo-noticia">

                        <h3>B12: vitamina essencial com uma relação complexa com o câncer</h3>

                        <a href="https://www.metropoles.com/saude/b12-vitamina-essencial-com-uma-relacao-complexa-com-o-cancer"
                            target="_blank">
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
                            target="_blank">
                            Ler mais
                        </a>

                    </div>
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

        <script>
            const toggle = document.querySelector(".menu-toggle");
            const menu = document.querySelector(".menu");

            toggle.addEventListener("click", () => {
                menu.classList.toggle("ativo");
            });
        </script>
</body>


</html>