<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/conexao.php';

$pdo->exec("CREATE TABLE IF NOT EXISTS relatos_demo (
    id_demo INT NOT NULL AUTO_INCREMENT,
    codigo_demo VARCHAR(40) NOT NULL,
    nome_autor VARCHAR(100) NOT NULL,
    perfil VARCHAR(120) NOT NULL,
    cidade VARCHAR(120) NOT NULL,
    data_publicacao DATE NOT NULL,
    tema VARCHAR(100) NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    conteudo TEXT NOT NULL,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (id_demo),
    UNIQUE KEY relatos_demo_codigo (codigo_demo),
    KEY relatos_demo_ativo (ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$colunas = $pdo->query("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'grupos'")->fetchAll(PDO::FETCH_COLUMN);
if (!in_array('is_demo', $colunas, true)) {
    $pdo->exec("ALTER TABLE grupos ADD COLUMN is_demo TINYINT(1) NOT NULL DEFAULT 0 AFTER status_moderacao");
}
if (!in_array('codigo_demo', $colunas, true)) {
    $pdo->exec("ALTER TABLE grupos ADD COLUMN codigo_demo VARCHAR(40) NULL AFTER is_demo");
}
$indices = $pdo->query("SELECT INDEX_NAME FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'grupos'")->fetchAll(PDO::FETCH_COLUMN);
if (!in_array('grupos_codigo_demo', $indices, true)) {
    $pdo->exec("ALTER TABLE grupos ADD UNIQUE KEY grupos_codigo_demo (codigo_demo)");
}

$grupos = [
    ['DEMO-GRP-01','Modelo fictício: Roda de Acolhimento','Ana Paula Nunes · personagem fictícia','Encontro com apresentação breve, conversa opcional e indicação de canais de apoio.','Demonstração · Pacientes e familiares','2026-11-28','19:00:00','roda@exemplo.invalid','img/apoio1.webp'],
    ['DEMO-GRP-02','Modelo fictício: Rede Entre Nós','Carlos Martins · personagem fictício','Roda ilustrativa sobre rotina, comunicação em família e divisão das tarefas de cuidado.','Demonstração · Familiares e cuidadores','2026-11-30','10:00:00','rede@exemplo.invalid','img/apoio2.jpg'],
    ['DEMO-GRP-03','Modelo fictício: Juventude em Movimento','Luiza Ferreira · personagem fictícia','Encontro ilustrativo sobre estudos, amizades, autoestima e pedidos de ajuda.','Demonstração · Jovens adultos','2026-12-02','18:30:00','juventude@exemplo.invalid','img/apoio3.jpg'],
    ['DEMO-GRP-04','Modelo fictício: Laços em Família','Bruno Almeida · personagem fictício','Exemplo de roda para familiares sobre escuta, acordos cotidianos e rede de apoio.','Demonstração · Família','2026-12-03','18:00:00','lacos@exemplo.invalid','img/apoio4.jpg'],
    ['DEMO-GRP-05','Modelo fictício: Entre Conversas','Sofia Carvalho · personagem fictícia','Ficha ilustrativa para pacientes adultos compartilharem experiências e estratégias de rotina.','Demonstração · Pacientes adultos','2026-12-05','14:00:00','conversas@exemplo.invalid','img/apoio5.jpg'],
    ['DEMO-GRP-06','Modelo fictício: Pausa para Cuidar','Diego Ferreira · personagem fictício','Encontro de demonstração com conversa sobre limites, descanso e apoio entre cuidadores.','Demonstração · Pessoas cuidadoras','2026-12-07','17:30:00','pausa@exemplo.invalid','img/apoio6.jpg'],
];
$salvarGrupo = $pdo->prepare("INSERT INTO grupos (id_criador,nome_grupo,responsavel,mais_info,foco,data_encontro,horario,link,contato,imagem,status_moderacao,is_demo,codigo_demo) VALUES (NULL,?,?,?,?,?,?,NULL,?,?, 'aprovado',1,?) ON DUPLICATE KEY UPDATE nome_grupo=VALUES(nome_grupo), responsavel=VALUES(responsavel), mais_info=VALUES(mais_info), foco=VALUES(foco), data_encontro=VALUES(data_encontro), horario=VALUES(horario), contato=VALUES(contato), imagem=VALUES(imagem), status_moderacao='aprovado', is_demo=1");
foreach ($grupos as $g) {
    $salvarGrupo->execute([$g[1],$g[2],$g[3],$g[4],$g[5],$g[6],$g[7],$g[8],$g[0]]);
}

$relatos = [
    ['DEMO-STORY-01','Marina Lopes','Familiar e cuidadora','Ribeirão Pires, SP','2026-11-18','Apoio familiar','Organizar a semana da consulta','Neste relato fictício, Marina anota as dúvidas para a consulta e combina com a irmã quem vai acompanhá-la. Dividir a organização deixou a semana menos confusa e abriu espaço para conversarem com calma.'],
    ['DEMO-STORY-02','Rafael Mendes','Paciente adulto','Mauá, SP','2026-11-12','Rotina e acolhimento','Aprender a pedir ajuda também conta','Rafael, personagem fictício, tentava cuidar sozinho de todos os compromissos. Aos poucos, aceitou ajuda para organizar a rotina e percebeu que conversar sobre o que precisava o fazia se sentir acompanhado.'],
    ['DEMO-STORY-03','Camila Duarte','Paciente adulta','Santo André, SP','2026-11-05','Planos pessoais','Retomar as coisas no meu ritmo','Neste relato demonstrativo, Camila reserva um tempo para uma atividade de que gosta. Ela escolhe um passo pequeno e ajusta os planos conforme se sente; a narrativa não representa uma pessoa real nem recomenda condutas de saúde.'],
    ['DEMO-STORY-04','João Ribeiro','Familiar','São Bernardo do Campo, SP','2026-10-29','Rede de apoio','A conversa que aproximou a família','Personagem fictício, João descreve como a família passou a dividir tarefas da semana em uma conversa curta aos domingos. O combinado é revisto quando a rotina muda.'],
    ['DEMO-STORY-05','Beatriz Santos','Paciente jovem adulta','Diadema, SP','2026-10-21','Estudos e rotina','Um passo de cada vez nos estudos','Neste exemplo fictício, Beatriz conversa com colegas e professores para reorganizar prazos durante uma fase difícil. Ela destaca que encontrou apoio ao explicar o que precisava, sem que isso resolva todas as dificuldades.'],
    ['DEMO-STORY-06','André Costa','Pessoa cuidadora','São Caetano do Sul, SP','2026-10-14','Cuidado de quem cuida','Pedir uma pausa também é cuidado','André é uma personagem fictícia que relata ter combinado com parentes pequenos períodos de descanso. A história ilustra uma experiência inventada e não substitui orientação profissional.'],
];
$salvarRelato = $pdo->prepare("INSERT INTO relatos_demo (codigo_demo,nome_autor,perfil,cidade,data_publicacao,tema,titulo,conteudo,ativo) VALUES (?,?,?,?,?,?,?,?,1) ON DUPLICATE KEY UPDATE nome_autor=VALUES(nome_autor), perfil=VALUES(perfil), cidade=VALUES(cidade), data_publicacao=VALUES(data_publicacao), tema=VALUES(tema), titulo=VALUES(titulo), conteudo=VALUES(conteudo), ativo=1");
foreach ($relatos as $r) {
    $salvarRelato->execute($r);
}

echo "Conteúdo demonstrativo preparado com sucesso.\n";

