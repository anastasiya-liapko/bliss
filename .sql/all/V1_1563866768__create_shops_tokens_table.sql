DROP TABLE IF EXISTS shops_tokens;
CREATE TABLE shops_tokens (
  token_hash VARCHAR(64) NOT NULL PRIMARY KEY COMMENT 'хэш токена',
  token_expires_at TIMESTAMP NULL DEFAULT NULL COMMENT 'срок годности токена',
  shop_id INT(11) UNSIGNED DEFAULT NULL COMMENT 'id магазина'
) ENGINE = INNODB
  DEFAULT CHARSET = utf8 COMMENT 'токены магазинов';

ALTER TABLE shops_tokens
  ADD CONSTRAINT fk_shop_token_shop FOREIGN KEY (shop_id) REFERENCES shops (id) ON DELETE RESTRICT ON UPDATE CASCADE;
