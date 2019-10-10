/* V1_1557150619__change_organizations-unique-fields */
ALTER TABLE organizations
    DROP INDEX bik;
ALTER TABLE organizations
    DROP INDEX correspondent_account;

/* V1_1558093140__remove_columns_from_organizations */
ALTER TABLE organizations
    DROP COLUMN document_snils,
    DROP COLUMN document_passport,
    DROP COLUMN document_statute_with_tax_mark,
    DROP COLUMN document_participants_decision,
    DROP COLUMN document_ogrn,
    DROP COLUMN document_questionnaire_fl_115,
    DROP COLUMN document_order_on_appointment,
    DROP COLUMN document_statute_of_current_edition;

/* V1_1558602577__add_column_for_loans */
ALTER TABLE loans
    ADD COLUMN is_mfi_paid TINYINT(1) UNSIGNED DEFAULT 0
        COMMENT 'МФО перечислило деньги магазину? (1 - да, 0 - нет)'
        AFTER status;

/* V1_1559034603__add_column_for_organizations */
ALTER TABLE organizations
    ADD COLUMN phone CHAR(16) NOT NULL COMMENT 'телефон' AFTER email;

/* V1_1559729711__change_loan_statuses */
ALTER TABLE loans
    MODIFY COLUMN status
        ENUM ('pending', 'declined', 'waiting_for_receipt', 'issued', 'waiting_for_delivery', 'declined_by_shop',
            'canceled_by_client')
        NOT NULL;

UPDATE loans
SET status = 'declined_by_shop'
WHERE status = 'declined';

UPDATE loans
SET status = 'waiting_for_delivery'
WHERE status = 'waiting_for_receipt';

ALTER TABLE loans
    MODIFY COLUMN status
        ENUM ('pending', 'waiting_for_delivery', 'issued', 'declined_by_shop', 'canceled_by_client')
        NOT NULL
        COMMENT 'статусы кредита (ожидает решения магазина, ожидает доставки товаров,
        выдан покупателю, отклонён магазином, отменён клиентом)';

/* V1_1561458226__remove_columns_from_remembered_clients */
ALTER TABLE remembered_clients
    DROP FOREIGN KEY fk_remembered_client_client;

ALTER TABLE remembered_clients
    DROP FOREIGN KEY fk_remembered_client_request;

ALTER TABLE remembered_clients
    DROP COLUMN client_id,
    DROP COLUMN request_id;

/* V1_1561465488__create_orders_table */

DROP TABLE IF EXISTS orders;
CREATE TABLE orders
(
    id                  INT(11) UNSIGNED        NOT NULL AUTO_INCREMENT PRIMARY KEY,
    shop_id             INT(11) UNSIGNED        NOT NULL COMMENT 'id магазина',
    order_id_in_shop    VARCHAR(255)            NOT NULL COMMENT 'id заказа в магазине',
    order_price         DECIMAL(19, 2) UNSIGNED NOT NULL COMMENT 'сумма заказа, руб.',
    goods               TEXT                    NOT NULL COMMENT 'сериализованный массив товаров',
    status              ENUM ('waiting_for_registration', 'pending_by_mfi', 'declined_by_mfi',
        'canceled_by_client', 'mfi_did_not_answer', 'approved_by_mfi', 'pending_by_shop',
        'waiting_for_delivery', 'waiting_for_payment', 'paid', 'declined_by_shop',
        'canceled_by_client_upon_receipt')      NOT NULL COMMENT 'статус',
    time_of_creation    TIMESTAMP               NOT NULL COMMENT 'время создания заказа',
    delivery_service_id INT(11) UNSIGNED DEFAULT NULL COMMENT 'id службы доставки',
    tracking_code       VARCHAR(255)     DEFAULT NULL COMMENT 'код отслеживания посылки, его выдаёт служба доставки'
) ENGINE = INNODB
  DEFAULT CHARSET = utf8 COMMENT 'заказы';

ALTER TABLE orders
    ADD CONSTRAINT fk_orders_shop FOREIGN KEY (shop_id) REFERENCES shops (id) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE orders
    ADD CONSTRAINT fk_orders_delivery_service FOREIGN KEY (delivery_service_id) REFERENCES delivery_services (id) ON DELETE SET NULL ON UPDATE CASCADE;

INSERT INTO orders (shop_id, order_id_in_shop, order_price, goods, status, time_of_creation, delivery_service_id,
                    tracking_code)
SELECT rq.shop_id,
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

ALTER TABLE requests
    CHANGE order_id order_id VARCHAR(255) NOT NULL COMMENT 'id заказа в системе Блисс';

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

/* V1_1562162163__create_orders_tokens_table */

DROP TABLE IF EXISTS orders_tokens;
CREATE TABLE orders_tokens
(
    token_hash         VARCHAR(64)      NOT NULL PRIMARY KEY COMMENT 'хэш токена',
    order_id           INT(11) UNSIGNED NOT NULL COMMENT 'id заказа в системе Блисс',
    client_phone       CHAR(12)     DEFAULT NULL COMMENT 'телефон клиента',
    process_order_link VARCHAR(255) DEFAULT NULL COMMENT 'ссылка на обработку заказа'
) ENGINE = INNODB
  DEFAULT CHARSET = utf8 COMMENT 'токены заказов';

ALTER TABLE orders_tokens
    ADD CONSTRAINT fk_order_token_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE RESTRICT ON UPDATE CASCADE;

/* V1_1562744509__add_status_for_requests */

ALTER TABLE requests
    MODIFY COLUMN status
        ENUM ('pending', 'declined', 'canceled', 'manual', 'approved', 'confirmed', 'waiting_for_limit')
        NOT NULL
        COMMENT 'статусы заявки (в процессе, отказано, клиент отменил, требуется решение менеджера, одобрена, подтверждена клиентом, ожидает одобрения лимита)';

/* V1_1562931548__change_clients_columns */

ALTER TABLE clients
    MODIFY birth_date DATE NOT NULL COMMENT 'дата рождения';
ALTER TABLE clients
    MODIFY passport_issued_date DATE NOT NULL COMMENT 'дата выдачи паспорта';

/* V1_1563176112__change_organizations_columns */

ALTER TABLE organizations
    MODIFY boss_basis_acts_issued_date DATE DEFAULT NULL COMMENT 'дата выдачи документа, на основании которого действует руководитель';
ALTER TABLE organizations
    MODIFY boss_passport_issued_date DATE NOT NULL COMMENT 'дата выдачи паспорта руководителя';
ALTER TABLE organizations
    MODIFY boss_birth_date DATE NOT NULL COMMENT 'дата рождения руководителя';

/* V1_1563181171__change_remembered_clients_columns */

ALTER TABLE remembered_clients
    MODIFY token_expires_at TIMESTAMP NOT NULL COMMENT 'срок годности токена';
ALTER TABLE remembered_clients
    MODIFY sms_code_sends_at TIMESTAMP NULL DEFAULT NULL COMMENT 'время отправки последнего sms-кода';
ALTER TABLE remembered_clients
    MODIFY sms_code_expires_at TIMESTAMP NULL DEFAULT NULL COMMENT 'срок годности sms-кода';

/* V1_1563184523__change_locked_phones_columns */

ALTER TABLE locked_phones
    MODIFY locked_until TIMESTAMP NOT NULL COMMENT 'заблокирован до';

/* V1_1563272933__fix_for_timestamp_columns */

ALTER TABLE remembered_clients
    MODIFY token_expires_at TIMESTAMP NULL DEFAULT NULL COMMENT 'срок годности токена';

ALTER TABLE locked_phones
    MODIFY locked_until TIMESTAMP NULL DEFAULT NULL COMMENT 'заблокирован до';

ALTER TABLE mfi_responses
    MODIFY time_response TIMESTAMP NULL DEFAULT NULL COMMENT 'время ответа';

ALTER TABLE requests
    MODIFY time_start TIMESTAMP NULL DEFAULT NULL COMMENT 'время подачи заявки';

ALTER TABLE orders
    MODIFY time_of_creation TIMESTAMP NULL DEFAULT NULL COMMENT 'время создания заказа';

/* V1_1563866768__create_shops_tokens_table */

DROP TABLE IF EXISTS shops_tokens;
CREATE TABLE shops_tokens
(
    token_hash       VARCHAR(64) NOT NULL PRIMARY KEY COMMENT 'хэш токена',
    token_expires_at TIMESTAMP   NULL DEFAULT NULL COMMENT 'срок годности токена',
    shop_id          INT(11) UNSIGNED DEFAULT NULL COMMENT 'id магазина'
) ENGINE = INNODB
  DEFAULT CHARSET = utf8 COMMENT 'токены магазинов';

ALTER TABLE shops_tokens
    ADD CONSTRAINT fk_shop_token_shop FOREIGN KEY (shop_id) REFERENCES shops (id) ON DELETE RESTRICT ON UPDATE CASCADE;

/* V1_1563981923__create_orders_callbacks_table */

DROP TABLE IF EXISTS orders_callbacks;
CREATE TABLE orders_callbacks
(
    id               INT(11) UNSIGNED        NOT NULL AUTO_INCREMENT PRIMARY KEY,
    order_id         INT(11) UNSIGNED UNIQUE NOT NULL COMMENT 'id заказа',
    callback_url     VARCHAR(255)            NOT NULL COMMENT 'коллбэк-ссылка',
    is_callback_sent TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'отправлен ли коллбэк'
) ENGINE = INNODB
  DEFAULT CHARSET = utf8 COMMENT 'коллбэк-ссылки заказов';

ALTER TABLE orders_callbacks
    ADD CONSTRAINT fk_order_callback_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE RESTRICT ON UPDATE CASCADE;

/* V1_1564383856__add_foreign_keys */

ALTER TABLE requests
    MODIFY order_id INT(11) UNSIGNED NOT NULL COMMENT 'id заказа';

ALTER TABLE requests
    ADD CONSTRAINT fk_request_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE loans
    ADD CONSTRAINT fk_loan_mfi FOREIGN KEY (mfi_id) REFERENCES mfi (id) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE mfi_responses
    MODIFY mfi_id INT(11) UNSIGNED NOT NULL COMMENT 'id МФО';
ALTER TABLE mfi_responses
    MODIFY request_id INT(11) UNSIGNED NOT NULL COMMENT 'id заявки';

ALTER TABLE mfi_responses
    ADD CONSTRAINT fk_mfi_response_mfi FOREIGN KEY (mfi_id) REFERENCES mfi (id) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE mfi_responses
    ADD CONSTRAINT fk_mfi_response_request FOREIGN KEY (request_id) REFERENCES requests (id) ON DELETE RESTRICT ON UPDATE CASCADE;

/* V1_1564990242__add_column_for_organization */

ALTER TABLE organizations
    ADD COLUMN is_documents_checked TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'проверены ли документы (1 - да, 0 - нет)';

/* V1_1565269395__create_db_migrations_table */

DROP TABLE IF EXISTS db_migrations;
CREATE TABLE db_migrations
(
    id           INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(255) UNIQUE NOT NULL COMMENT 'название',
    is_completed TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'выполнена ли миграция (1 - да, 0 - нет)'
) ENGINE = INNODB
  DEFAULT CHARSET = utf8 COMMENT 'миграции базы данных';

/* V1_1566818961__add_column_for_shop */

ALTER TABLE shops
    ADD COLUMN is_old_integration TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'старая ли интеграция';

/* admins */
INSERT INTO admins (id, name, email, password_hash, role)
VALUES (1, 'Супер администратор', 'admin@bliss24.ru', 'de65262646860a5aba74c271718c2f32', 'super_admin');

/* clients */
INSERT
INTO clients (id, last_name, first_name, middle_name, birth_date, birth_place, sex, is_last_name_changed,
              previous_last_name, tin, snils, passport_number, passport_division_code, passport_issued_by,
              passport_issued_date, workplace, salary, reg_zip_code, reg_city, reg_street, reg_building,
              reg_apartment, is_address_matched, fact_zip_code, fact_city, fact_street, fact_building,
              fact_apartment, phone, additional_phone, email)
VALUES (1, 'Петров', 'Пётр', 'Петрович', '1980-01-01 00:00:00', 'г. Москва', 'male', 0, '', '111111111111',
        '111-111-111 11', '11 11 111111', '770-001',
        'УПРАВЛЕНИЕ ФЕДЕРАЛЬНОЙ МИГРАЦИОННОЙ СЛУЖБЫ РОССИИ ПО ГОР. МОСКВЕ',
        '2010-01-01 00:00:00', 'ООО "Ромашка"', 20000, '101000', 'Москва', 'Ленина', 10, 20, 1, '101000', 'Москва',
        'Ленина', 10, 20, '79097391754', '', 'petrov_pp@mail.ru');

/* organization_categories */
INSERT INTO organization_categories (id, name)
VALUES (1, 'прочее');

/* organization */
INSERT
INTO organizations (id, type, vat, legal_name, tin, cio, bin, is_licensed_activity, license_type, license_number,
                    category_id, legal_address, registration_address, fact_address, bik, bank_name,
                    correspondent_account, settlement_account, boss_full_name, boss_position, boss_basis_acts,
                    boss_basis_acts_number, boss_basis_acts_issued_date, boss_passport_number,
                    boss_passport_issued_date, boss_passport_division_code, boss_passport_issued_by,
                    boss_birth_date, boss_birth_place, email, phone)
VALUES (1, 'entrepreneur', 10, null, '111111111111', null, '111111111111111', 0, null, null, 1, null,
        'г. Москва, ул. Ленина, д. 10, кв. 20', 'г. Москва, ул. Ленина, д. 10, кв. 20', '111111111', 'Сбербанк',
        '11111111111111111111', '11111111111111111111', 'Петров Пётр Петрович', null, null, null, null,
        '11 11 111111', '2010-01-01 00:00:00', '770-001',
        'УПРАВЛЕНИЕ ФЕДЕРАЛЬНОЙ МИГРАЦИОННОЙ СЛУЖБЫ РОССИИ ПО ГОР. МОСКВЕ', '1980-01-01 00:00:00', 'Москва',
        'petrov_pp@mail.ru', '+7(111)111-11-11');

/* shops */
INSERT INTO shops (id, name, email, is_activated, secret_key, organization_id)
VALUES (1, 'ИП «Петров Пётр Петрович»', 'petrov_pp@mail.ru', 1, 'FMNDesQ58G8y4O8bgGPvsEGFPwEe8Gdj', 1),
       (2, 'ИП «Семёнов Семён Семёнович»', 'semenov_ss@mail.ru', 0, 'FnhCEcnhPllrfNapV1pKiflcQO531dvY', 1);

/* shops_admins */
INSERT INTO shops_admins (id, name, email, password_hash, phone, role, shop_id, is_activated)
VALUES (1, 'Петров Пётр Петрович', 'petrov_pp@mail.ru', 'da3f4dfe6cfc9eb2ddd2f2bf683a2956', '', 'admin', 1, 1),
       (2, 'Семёнов Семён Семёнович', 'semenov_ss@mail.ru', '937c2718614a37f4f9027b015ff09c15', '', 'admin', 2, 0);

/* mfi */
INSERT INTO mfi (id, name, slug, phone, email, min_loan_sum, max_loan_sum, can_loan_postponed, time_limit)
VALUES (1, 'WEBBANKIR', 'Webbankir', '', '', 3000, 15000, 1, 600);

/* mfi_shop_cooperation */
INSERT INTO mfi_shop_cooperation (mfi_id, shop_id, mfi_api_parameters)
VALUES (1, 1, '{"merchantId":1,"shopId":1,"password":"qwerty"}');

/* delivery_services */
INSERT INTO delivery_services (id, name, slug)
VALUES (1, 'Другая (отслеживание и смена статуса производятся вручную)', 'default'),
       (2, 'Почта России', 'russian_post');

/* orders */
INSERT
INTO orders (id, shop_id, order_id_in_shop, order_price, goods, status, time_of_creation, delivery_service_id,
             tracking_code)
VALUES (1, 1, 1, 95190,
        '[{"name":"\\u0421\\u043c\\u0430\\u0440\\u0442\\u0444\\u043e\\u043d Apple iPhone XS 256GB Gold","price":95190,"quantity":1,"is_returnable":1}]',
        'declined_by_shop', '2019-01-21 21:47:25', null, null),
       (2, 1, 2, 71490,
        '[{"name":"\\u0421\\u043c\\u0430\\u0440\\u0442\\u0444\\u043e\\u043d Samsung Galaxy Note 9 128Gb \\u0427\\u0435\\u0440\\u043d\\u044b\\u0439","price":71490,"quantity":1,"is_returnable":1}]',
        'canceled_by_client', '2019-01-21 21:49:29', null, null),
       (3, 1, 3, 39990,
        '[{"name":"\\u0421\\u043c\\u0430\\u0440\\u0442\\u0444\\u043e\\u043d Huawei P20 Pro Twilight","price":39990,"quantity":1,"is_returnable":1}]',
        'mfi_did_not_answer', '2019-01-21 21:51:34', null, null),
       (4, 1, 4, 24990,
        '[{"name":"\\u0421\\u043c\\u0430\\u0440\\u0442\\u0444\\u043e\\u043d Honor 10 64Gb Blue","price":24990,"quantity":1,"is_returnable":1}]',
        'canceled_by_client', '2019-01-21 21:53:36', null, null),
       (5, 1, 5, 24990,
        '[{"name":"\\u0421\\u043c\\u0430\\u0440\\u0442\\u0444\\u043e\\u043d Honor 10 64Gb Blue","price":24990,"quantity":1,"is_returnable":1}]',
        'waiting_for_delivery', '2019-01-21 21:55:36', 2, 'RA644000001RU'),
       (6, 1, 6, 63990,
        '[{"name":"\\u0421\\u043c\\u0430\\u0440\\u0442\\u0444\\u043e\\u043d Apple iPhone XR 64GB RED","price":63990,"quantity":1,"is_returnable":1}]',
        'paid', '2019-01-21 21:57:28', 2, 'RA644000002RU'),
       (7, 1, 7, 122190,
        '[{"name":"\\u0421\\u043c\\u0430\\u0440\\u0442\\u0444\\u043e\\u043d Apple iPhone XS Max 512GB Silver","price":122190,"quantity":1,"is_returnable":1}]',
        'pending_by_shop', '2019-01-21 21:58:28', null, null),
       (8, 1, 8, 3000,
        '[{"name":"\\u041d\\u0430\\u0443\\u0448\\u043d\\u0438\\u043a\\u0438 \\u0432\\u043d\\u0443\\u0442\\u0440\\u0438\\u043a\\u0430\\u043d\\u0430\\u043b\\u044c\\u043d\\u044b\\u0435 Sony MDR-EX15LP Black","price":3000,"quantity":1,"is_returnable":1}]',
        'pending_by_mfi', '2019-06-06 21:58:28', null, null),
       (9, 1, 9, 3000,
        '[{"name":"\\u041d\\u0430\\u0443\\u0448\\u043d\\u0438\\u043a\\u0438 \\u0432\\u043d\\u0443\\u0442\\u0440\\u0438\\u043a\\u0430\\u043d\\u0430\\u043b\\u044c\\u043d\\u044b\\u0435 Sony MDR-EX15LP Black","price":3000,"quantity":1,"is_returnable":1}]',
        'waiting_for_registration', '2019-06-06 21:58:48', null, null);

/* orders_tokens */
INSERT
INTO orders_tokens (token_hash, order_id, client_phone, process_order_link)
VALUES ('6d4cde3960ef794c4010e7719b71608fbecfc709825acf215585d61796e57568', 9, '79097391754',
        '//bliss.local/process-order?token=43d987204b339d8637c72341185e9429');

/* requests */
INSERT
INTO requests (id, client_id, shop_id, order_id, is_test_mode_enabled, is_loan_postponed, status, approved_mfi_id,
               approved_mfi_response, time_start, time_finish)
VALUES (1, 1, 1, 1, 1, 0, 'declined', null, '', '2019-01-21 21:47:25', '2019-01-21 21:48:25'),
       (2, 1, 1, 2, 1, 0, 'canceled', null, '', '2019-01-21 21:49:29', '2019-01-21 21:50:29'),
       (3, 1, 1, 3, 1, 0, 'manual', null, '', '2019-01-21 21:51:34', '2019-01-21 21:52:34'),
       (4, 1, 1, 4, 1, 1, 'canceled', 1,
        '{"status":"approved","customer_id":9778691,"contract_id":null,"loan_id":1,"loan_body":24990,"loan_cost":6747.3,"loan_period":180,"loan_daily_percent_rate":0.0015,"loan_terms_link":null}',
        '2019-01-21 21:53:36', '2019-01-21 21:54:36'),
       (5, 1, 1, 5, 1, 0, 'confirmed', 1,
        '{"status":"approved","customer_id":9778691,"contract_id":null,"loan_id":2,"loan_body":24990,"loan_cost":6747.3,"loan_period":180,"loan_daily_percent_rate":0.0015,"loan_terms_link":null}',
        '2019-01-21 21:55:36', '2019-01-21 21:56:36'),
       (6, 1, 1, 6, 1, 1, 'confirmed', 1,
        '{"status":"approved","customer_id":9778691,"contract_id":null,"loan_id":3,"loan_body":63990,"loan_cost":17277.3,"loan_period":180,"loan_daily_percent_rate":0.0015,"loan_terms_link":null}',
        '2019-01-21 21:57:28', '2019-01-21 21:58:28'),
       (7, 1, 1, 7, 1, 0, 'confirmed', null, '', '2019-01-21 21:58:28', '2019-01-21 21:59:28'),
       (8, 1, 1, 8, 1, 0, 'pending', null, '', '2019-06-06 21:58:28', null);

/* loans */
INSERT
INTO loans(id, request_id, shop_id, mfi_id, status, is_mfi_paid, customer_id, contract_id, loan_id, loan_body,
           loan_cost, loan_period, loan_daily_percent_rate)
VALUES (1, 5, 1, 1, 'waiting_for_delivery', 0, 9778691, 1, 1, 24990, 6747.3, 180, 0.0015),
       (2, 6, 1, 1, 'issued', 1, 9778691, 2, 2, 63990, 17277.3, 180, 0.0015),
       (3, 7, 1, 1, 'pending', 0, 9778691, 4, 4, 122190, 32991.3, 180, 0.0015);

/* mfi_responses */
INSERT INTO mfi_responses (id, mfi_id, request_id, status, time_response)
VALUES (1, 1, 1, 'declined', '2019-01-21 21:48:25'),
       (2, 1, 2, 'declined', '2019-01-21 21:50:29'),
       (3, 1, 3, 'did_not_have_time', '2019-01-21 21:52:34'),
       (4, 1, 4, 'approved', '2019-01-21 21:54:36'),
       (5, 1, 5, 'approved', '2019-01-21 21:56:36'),
       (6, 1, 6, 'approved', '2019-01-21 21:58:28');

/* integration_plugins */
INSERT INTO integration_plugins (name, url, img_url)
VALUES ('OpenCart', '#', '/uploads/1547725662_8b37a1efc1991f7abb550fb47c5bf24d.png'),
       ('WooCommerce', '#', '/uploads/1547725698_5c278af26289375771943c6e54e268b0.png'),
       ('Shopify', '#', '/uploads/1547725731_2da3f439436169159ae51ebe0b2e7b0a.png'),
       ('PrestaShop', '#', '/uploads/1547725770_59a007620dd0c030af70468049763ff3.png');
