DROP TABLE IF EXISTS orders_callbacks;
CREATE TABLE orders_callbacks (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  order_id INT(11) UNSIGNED UNIQUE NOT NULL COMMENT 'id заказа',
  callback_url VARCHAR(255) NOT NULL COMMENT 'коллбэк-ссылка',
  is_callback_sent TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'отправлен ли коллбэк'
) ENGINE = INNODB
  DEFAULT CHARSET = utf8 COMMENT 'коллбэк-ссылки заказов';

ALTER TABLE orders_callbacks
  ADD CONSTRAINT fk_order_callback_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE RESTRICT ON UPDATE CASCADE;
