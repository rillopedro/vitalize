-- Execute uma vez em bancos Vitalize já existentes, antes de publicar o código.
ALTER TABLE consultas
  ADD COLUMN link_reuniao VARCHAR(2048) NULL;

CREATE TABLE codigos_recuperacao (
  id_usuario INT NOT NULL,
  codigo_hash VARCHAR(255) NOT NULL,
  expira_em DATETIME NOT NULL,
  tentativas TINYINT UNSIGNED NOT NULL DEFAULT 0,
  solicitado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_usuario),
  CONSTRAINT codigos_recuperacao_ibfk_1
    FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
