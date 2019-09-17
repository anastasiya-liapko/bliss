DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  shop_id INT(11) UNSIGNED NOT NULL COMMENT 'id магазина',
  order_id_in_shop VARCHAR(255) NOT NULL COMMENT 'id заказа в магазине',
  order_price DECIMAL(19, 2) UNSIGNED NOT NULL COMMENT 'сумма заказа, руб.',
  goods TEXT NOT NULL COMMENT 'сериализованный массив товаров',
  status ENUM('waiting_for_registration', 'pending_by_mfi', 'declined_by_mfi', 'canceled_by_client', 'mfi_did_not_answer', 'approved_by_mfi', 'pending_by_shop', 'waiting_for_delivery', 'waiting_for_payment', 'paid', 'declined_by_shop', 'canceled_by_client_upon_receipt') NOT NULL COMMENT 'статус',
  time_of_creation TIMESTAMP NOT NULL COMMENT 'время создания заказа',
  delivery_service_id INT(11) UNSIGNED DEFAULT NULL COMMENT 'id службы доставки',
  tracking_code VARCHAR(255) DEFAULT NULL COMMENT 'код отслеживания посылки, его выдаёт служба доставки'
) ENGINE = INNODB
  DEFAULT CHARSET = utf8 COMMENT 'заказы';

ALTER TABLE orders
  ADD CONSTRAINT fk_orders_shop FOREIGN KEY (shop_id) REFERENCES shops (id) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE orders
  ADD CONSTRAINT fk_orders_delivery_service FOREIGN KEY (delivery_service_id) REFERENCES delivery_services (id) ON DELETE SET NULL ON UPDATE CASCADE;

INSERT INTO orders (shop_id, order_id_in_shop, order_price, goods, status, time_of_creation, delivery_service_id, tracking_code)
SELECT
       rq.shop_id,
       rq.order_id,
       rq.order_price,
       rq.goods,
       CASE
         WHEN rq.status = 'pending'
           THEN 'pending_by_mfi'
         WHEN rq.status = 'declined'
           THEN 'declined_by_mfi'
         WHEN rq.status = 'canceled'
           THEN 'canceled_by_client'
         WHEN rq.status = 'manual'
           THEN 'mfi_did_not_answer'
         WHEN rq.status = 'approved'
           THEN 'approved_by_mfi'
         WHEN rq.status = 'confirmed' AND l.status = 'pending'
           THEN 'pending_by_shop'
         WHEN rq.status = 'confirmed' AND l.status = 'waiting_for_delivery'
           THEN 'waiting_for_delivery'
         WHEN rq.status = 'confirmed' AND l.status = 'issued' AND l.is_mfi_paid = 0
           THEN 'waiting_for_payment'
         WHEN rq.status = 'confirmed' AND l.status = 'issued' AND l.is_mfi_paid = 1
           THEN 'paid'
         WHEN rq.status = 'confirmed' AND l.status = 'declined_by_shop'
           THEN 'declined_by_shop'
         WHEN rq.status = 'confirmed' AND l.status = 'canceled_by_client'
           THEN 'canceled_by_client_upon_receipt'
       END AS status,
         rq.time_start,
         l.delivery_service_id,
         l.tracking_code
FROM requests AS rq
       LEFT JOIN loans AS l
                  ON rq.id = l.request_id;

ALTER TABLE requests CHANGE order_id order_id VARCHAR(255) NOT NULL COMMENT 'id заказа в системе Блисс';

UPDATE requests
SET order_id =
      (SELECT orders.id
       FROM orders
       WHERE requests.shop_id = orders.shop_id
         AND requests.order_id = orders.order_id_in_shop);

ALTER TABLE requests
  DROP COLUMN order_price,
  DROP COLUMN goods;

ALTER TABLE loans
  DROP FOREIGN KEY fk_loan_delivery_service;

ALTER TABLE loans
  DROP COLUMN delivery_service_id,
  DROP COLUMN tracking_code;
