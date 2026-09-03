https://mayarasantanna2.github.io/vitalize/

## Assistente de bem-estar com IA

A página `saude.php` usa a Groq Responses API para criar uma sugestão de café da manhã e uma mensagem motivacional conforme o humor e os sintomas selecionados.

No XAMPP, configure a chave como variável de ambiente do Apache. Uma opção local é adicionar ao arquivo `C:\xampp\apache\conf\extra\httpd-vhosts.conf`, dentro do `VirtualHost` do projeto:

```apache
SetEnv GROQ_API_KEY "SUA_CHAVE_PRIVADA"
SetEnv GROQ_MODEL "SEU_MODELO_PRIVADO"
```

Depois, reinicie o Apache. A chave é lida somente por `api/assistente_bem_estar.php` e nunca deve ser colocada no JavaScript ou enviada ao Git. O arquivo `.env.example` apenas documenta os nomes das variáveis; o PHP não carrega arquivos `.env` automaticamente.
