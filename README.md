# Vitalize
### Tecnologia para acolhimento, informação e conexão

**PHP · MySQL · HTML · CSS · JavaScript · Docker**

Plataforma desenvolvida em equipe como Trabalho de Conclusão de Curso para apoiar pessoas em tratamento contra o câncer. Reúne páginas de informação, grupos de apoio e relatos em uma aplicação web.

[**Abrir aplicação publicada →**](https://vitalize-production.up.railway.app/)

![Identidade visual do Vitalize](img/banner-home.png)

## Recursos presentes no projeto

- Cadastro, login e perfil de usuário.
- Recuperação de senha por código de seis dígitos enviado por e-mail.
- Páginas e cadastro de grupos de apoio.
- Relatos e conteúdo sobre saúde e doação de cabelo.
- Agenda com link opcional para reuniões virtuais e área administrativa.
- Endpoint de assistente de bem-estar com integração à API Groq.

## Executar localmente

Pré-requisitos: Git, MySQL/MariaDB e PHP 8.2 com `pdo_mysql`, `mysqli`, `mbstring` e `curl`. O Dockerfile usa PHP 8.2 com Apache.

```bash
git clone https://github.com/rillopedro/vitalize.git
cd vitalize
```

Crie um banco de desenvolvimento chamado `vitalize` e importe `vitalize.sql` nele usando seu cliente MySQL ou phpMyAdmin.

Configure as variáveis no terminal que iniciará o servidor. Exemplo para PowerShell:

```powershell
$env:DB_HOST = "127.0.0.1"
$env:DB_PORT = "3306"
$env:DB_NAME = "vitalize"
$env:DB_USER = "seu_usuario"
$env:DB_PASSWORD = "sua_senha"
php -S 127.0.0.1:8000
```

Abra [a aplicação local](http://127.0.0.1:8000/index.php). O servidor embutido é indicado para desenvolvimento.

`conexao.php` lê variáveis de ambiente; ele não carrega um arquivo `.env` automaticamente. Também aceita `DATABASE_URL`, `MYSQL_URL` e as variáveis MySQL usadas pelo Railway.

## E-mail de recuperação

Configure `RESEND_API_KEY` e `MAIL_FROM` no ambiente do servidor para enviar os códigos. `MAIL_FROM` precisa ser um remetente permitido pela conta Resend. Os códigos expiram em 10 minutos, têm até cinco tentativas e podem ser solicitados novamente após 60 segundos.

Para bancos existentes, faça backup e execute cada migração ainda não aplicada, uma única vez e em ordem: [`migrations/20261020_recuperacao_e_link_reuniao.sql`](migrations/20261020_recuperacao_e_link_reuniao.sql) e [`migrations/20261021_seguranca_moderacao.sql`](migrations/20261021_seguranca_moderacao.sql). Instalações novas já incluem as colunas e tabelas em `vitalize.sql`.

A migração de moderação oculta os conteúdos existentes até revisão e invalida sessões ativas para que todos entrem novamente. Depois de aplicar, revise e aprove cada item autorizado no painel `/administrador.php`. Cadastre o primeiro administrador promovendo uma conta confiável diretamente no banco, por exemplo: `UPDATE usuarios SET tipo='admin' WHERE email='admin@exemplo.com';`. Use um e-mail já cadastrado e mantenha o acesso ao banco restrito.

## Configuração do assistente

Para habilitar o endpoint `POST /api/assistant`, configure no servidor:

| Variável | Finalidade |
| --- | --- |
| `GROQ_API_KEY` | Chave da API Groq |
| `GROQ_MODEL` | Identificador de um modelo compatível com a chamada usada no código |

A chave deve ficar somente no servidor, como variável de ambiente. Sem essas configurações, o endpoint retorna 503. Se uma chave Groq antiga já foi incluída em qualquer commit, revogue-a no painel da Groq e gere outra; apagar o arquivo atual não remove a chave do histórico Git. A integração depende de acesso ao serviço externo e pode gerar custos conforme a conta utilizada.

O assistente oferece conteúdo de bem-estar; não substitui orientação médica ou nutricional.

## Estrutura

| Caminho | Responsabilidade |
| --- | --- |
| `index.php` e páginas PHP | Interface da aplicação, consumindo a API |
| `api/index.php` | Entrada HTTP da API REST |
| `api/Routes/rotas.php` | Roteamento de endpoints |
| `api/Controllers/VitalizeController.php` | Operações REST, validação e acesso ao banco |
| `api/bootstrap.php` | Sessão, PDO, respostas JSON e proteção CSRF |
| `api/cliente.js` | Cliente HTTP do frontend |
| `conexao.php` | Conexão PDO usada pela API |
| `processos/` | Compatibilidade temporária; operações antigas respondem HTTP 410 |
| `css/` e `img/` | Estilos e recursos visuais |
| `vitalize.sql` | Estrutura e dados do banco |
| `Dockerfile` | Ambiente PHP/Apache |
| `railway.json` | Configuração de build no Railway |

Consulte [`api/README.md`](api/README.md) para a lista de rotas, métodos, autenticação e formato das requisições.
## Docker e publicação

```bash
docker build -t vitalize .
```

Para executar a imagem, forneça as variáveis de banco e publique a porta 80 do contêiner. O banco é externo à imagem; o Dockerfile não cria nem importa o MySQL. Em Docker, `127.0.0.1` aponta para o próprio contêiner: use um host de banco alcançável por ele.

## Equipe

Katarina Silva, Letícia Vasconcelos, Mayara Sant'Anna, Pedro Rillo e Vitória Guedes. Orientação: Cíntia Pinho.

## Escopo e evolução

Projeto acadêmico em desenvolvimento. A disponibilidade da demonstração depende da hospedagem e do banco. Antes de usar com dados reais, revise os fluxos de acesso, a privacidade e o conteúdo do dump SQL.

## Contato

[Pedro Henrique Rillo — GitHub](https://github.com/rillopedro) · [LinkedIn](https://www.linkedin.com/in/pedro-henrique-rillo-de-carvalho-24a5b7258/)
