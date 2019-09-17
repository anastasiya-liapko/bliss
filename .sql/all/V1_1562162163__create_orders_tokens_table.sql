DROP TABLE IF EXISTS orders_tokens;
CREATE TABLE orders_tokens (
  token_hash VARCHAR(64) NOT NULL PRIMARY KEY COMMENT 'хэш токена',
  order_id INT(11) UNSIGNED NOT NULL COMMENT 'id заказа в системе Блисс',
  client_phone CHAR(12) DEFAULT NULL COMMENT 'телефон клиента',
  process_order_link VARCHAR(255) DEFAULT NULL COMMENT 'ссылка на обработку заказа'
) ENGINE = INNODB
  DEFAULT CHARSET = utf8 COMMENT 'токены заказов';

ALTER TABLE orders_tokens
  ADD CONSTRAINT fk_order_token_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE RESTRICT ON UPDATE CASCADE;
