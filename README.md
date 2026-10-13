# Vitalize
### Tecnologia para acolhimento, informação e conexão

**PHP · MySQL · HTML · CSS · JavaScript · Docker**

Plataforma desenvolvida em equipe como Trabalho de Conclusão de Curso para apoiar pessoas em tratamento contra o câncer. Reúne páginas de informação, grupos de apoio e relatos em uma aplicação web.

[**Abrir aplicação publicada →**](https://vitalize-production.up.railway.app/)

![Identidade visual do Vitalize](img/banner-home.png)

## Recursos presentes no projeto

- Cadastro, login e perfil de usuário.
- Páginas e cadastro de grupos de apoio.
- Relatos e conteúdo sobre saúde e doação de cabelo.
- Processamento de agenda e área administrativa.
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

## Configuração do assistente

Para habilitar o endpoint `api/assistente_bem_estar.php`, configure no servidor:

| Variável | Finalidade |
| --- | --- |
| `GROQ_API_KEY` | Chave da API Groq |
| `GROQ_MODEL` | Identificador de um modelo compatível com a chamada usada no código |

A chave deve ficar no servidor. Sem essas configurações, o endpoint retorna 503. A integração depende de acesso ao serviço externo e pode gerar custos conforme a conta utilizada.

O assistente oferece conteúdo de bem-estar; não substitui orientação médica ou nutricional.

## Estrutura

| Caminho | Responsabilidade |
| --- | --- |
| `index.php` e páginas PHP | Interface da aplicação |
| `conexao.php` | Conexão PDO com MySQL |
| `processos/` | Operações de cadastro, sessão, perfil e relatos |
| `api/` | Integração do assistente |
| `css/` e `img/` | Estilos e recursos visuais |
| `vitalize.sql` | Estrutura e dados do banco |
| `Dockerfile` | Ambiente PHP/Apache |
| `railway.json` | Configuração de build no Railway |

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
