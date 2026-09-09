# Vitalize

Aplicação PHP com MySQL para apoio a pessoas em tratamento contra o câncer.

## Hospedagem

O `Dockerfile` instala PHP 8.2, Apache, PDO MySQL, cURL, mbstring e fileinfo.
No Railway, conecte um banco MySQL e mantenha disponíveis as variáveis fornecidas pelo serviço (`MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER` e `MYSQLPASSWORD`). A aplicação também aceita os nomes equivalentes `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` e `DB_PASSWORD`.

1. Importe `vitalize.sql` no banco.
2. Faça o deploy usando o `Dockerfile` da raiz.
3. Para habilitar o assistente de bem-estar, configure `GROQ_API_KEY` e `GROQ_MODEL` como variáveis secretas.

O diretório `img/uploads` é criado automaticamente quando uma imagem é enviada. Em ambientes com armazenamento efêmero, configure um volume persistente ou use armazenamento externo para preservar fotos após novos deploys.

