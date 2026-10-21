# API do Vitalize

API REST em PHP/PDO, baseada no modelo enviado (`index.php`, rotas e controllers), adaptada ao banco do Vitalize. Respostas em JSON; sessão autenticada por cookie `HttpOnly` no mesmo domínio. O frontend usa `api/cliente.js` para enviar requisições e token CSRF.

Em Apache, `api/.htaccess` direciona URLs amigáveis ao `index.php`. Com o servidor embutido do PHP, use `/api/index.php/...` ou configure o document root/roteador para encaminhar `/api/*` a este arquivo.

## Rotas

| Método | Rota | Acesso | Descrição |
| --- | --- | --- | --- |
| GET | `/api/health` | Público | Estado da API |
| GET | `/api/csrf` | Público | Cria/devolve token CSRF da sessão |
| POST | `/api/auth/register` | Público | Cadastra usuário e inicia sessão |
| POST | `/api/auth/login` | Público | Autentica e inicia sessão |
| POST | `/api/auth/logout` | Autenticado | Encerra sessão |
| GET | `/api/me` | Autenticado | Consulta perfil atual |
| POST/PATCH | `/api/me` | Autenticado | Atualiza perfil; use POST com `multipart/form-data` para foto |
| DELETE | `/api/me` | Autenticado | Exclui conta e dados associados |
| GET | `/api/appointments` | Autenticado | Lista consultas/exames do usuário |
| POST | `/api/appointments` | Autenticado | Cria consulta/exame |
| PUT/DELETE | `/api/appointments/{id}` | Autenticado | Atualiza/remove item da agenda |
| GET | `/api/groups` | Público | Lista grupos de apoio |
| POST | `/api/groups` | Autenticado | Cadastra grupo; aceita imagem multipart |
| POST | `/api/groups/{id}/share` | Autenticado | Envia ao e-mail da conta o link do grupo |
| GET | `/api/stories` | Público | Lista relatos aprovados, ocultando autor/foto dos anônimos |
| POST | `/api/stories` | Autenticado | Envia relato para moderação (exige consentimento) |
| GET | `/api/admin/moderation` | Administrador | Lista relatos e grupos pendentes |
| PATCH | `/api/admin/moderation/{stories|groups}/{id}` | Administrador | Aprova ou recusa conteúdo pendente |
| POST | `/api/password/forgot` | Público | Solicita código por e-mail (Resend) |
| POST | `/api/password/reset` | Público | Valida código e redefine senha |
| POST | `/api/assistant` | Público | Assistente Groq de bem-estar |

Para mutações, envie `X-CSRF-Token` obtido em `GET /api/csrf`. O cliente JavaScript incluído faz isso automaticamente. Envie dados JSON, exceto uploads, que usam `FormData`.

Senhas são armazenadas com `password_hash`; respostas nunca incluem o hash. O cadastro devolve HTTP 409 para e-mail já usado. Trocar a senha no perfil exige a senha atual; recuperar ou trocar a senha invalida sessões anteriores. Há limites por IP/conta para login, cadastro, recuperação, grupos, relatos e assistente.

Configure `RESEND_API_KEY`, `MAIL_FROM`, `GROQ_API_KEY` e `GROQ_MODEL` somente no ambiente do servidor. A tabela de limites e as colunas de moderação/sessão precisam ser criadas pela migração `migrations/20261021_seguranca_moderacao.sql` em bancos já existentes. O `.env` do ZIP de referência não foi copiado.
