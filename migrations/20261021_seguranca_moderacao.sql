-- Faça backup antes de executar. Execute uma vez em bancos existentes.
-- Conteúdos existentes e novos ficam ocultos até revisão administrativa.
ALTER TABLE grupos
  ADD COLUMN status_moderacao ENUM('pendente','aprovado','recusado') NOT NULL DEFAULT 'pendente';

ALTER TABLE relatos
  ADD COLUMN status_moderacao ENUM('pendente','aprovado','recusado') NOT NULL DEFAULT 'pendente';

CREATE INDEX grupos_status_moderacao ON grupos (status_moderacao, id_grupo);
CREATE INDEX relatos_status_data ON relatos (status_moderacao, data_publicacao);

ALTER TABLE usuarios
  ADD COLUMN versao_sessao INT UNSIGNED NOT NULL DEFAULT 0;

CREATE TABLE api_rate_limits (
  chave CHAR(64) NOT NULL,
  janela_inicio DATETIME NOT NULL,
  tentativas INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (chave),
  KEY api_rate_limits_janela_inicio (janela_inicio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
