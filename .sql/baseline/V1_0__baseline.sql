-- V1_0__baseline

DROP TABLE IF EXISTS admins;
CREATE TABLE admins (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL COMMENT 'имя',
  email VARCHAR(255) UNIQUE NOT NULL COMMENT 'email и логин',
  password_hash VARCHAR(255) NOT NULL COMMENT 'хэш пароля',
  role ENUM('super_admin', 'admin', 'manager') NOT NULL COMMENT 'роль'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'администраторы системы';

DROP TABLE IF EXISTS organization_categories;
CREATE TABLE organization_categories (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL COMMENT 'название категории'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'категории деятельности организации';

DROP TABLE IF EXISTS organizations;
CREATE TABLE organizations (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  type ENUM('entrepreneur','llc') NOT NULL COMMENT 'тип формы собственности',
  vat DECIMAL(19, 4) UNSIGNED NOT NULL COMMENT 'НДС',
  legal_name VARCHAR(255) DEFAULT NULL COMMENT 'юридическое наименование',
  tin VARCHAR(12) UNIQUE NOT NULL COMMENT 'ИНН',
  cio CHAR(9) DEFAULT NULL COMMENT 'КПП',
  bin VARCHAR(15) UNIQUE NOT NULL COMMENT 'ОГРН',
  is_licensed_activity TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'подлежит ли деятельность лицензированию (1 - да, 0 - нет)',
  license_type VARCHAR(255) DEFAULT NULL COMMENT 'тип лицензии',
  license_number VARCHAR(255) DEFAULT NULL COMMENT 'номер лицензии',
  category_id INT(11) UNSIGNED NOT NULL COMMENT 'id категории',
  legal_address VARCHAR(255) DEFAULT NULL COMMENT 'юридический адрес',
  registration_address VARCHAR(255) DEFAULT NULL COMMENT 'адрес регистрации',
  fact_address VARCHAR(255) NOT NULL COMMENT 'фактический адрес',
  bik CHAR(9) UNIQUE NOT NULL COMMENT 'БИК',
  bank_name VARCHAR(255) NOT NULL COMMENT 'название банка',
  correspondent_account CHAR(20) UNIQUE NOT NULL COMMENT 'корреспондентский счёт',
  settlement_account CHAR(20) UNIQUE NOT NULL COMMENT 'расчётный счёт',
  boss_full_name VARCHAR(255) NOT NULL COMMENT 'ФИО руководителя',
  boss_position VARCHAR(255) DEFAULT NULL COMMENT 'должность руководителя',
  boss_basis_acts VARCHAR(255) DEFAULT NULL COMMENT 'руководитель действует на основании',
  boss_basis_acts_number VARCHAR(255) DEFAULT NULL COMMENT 'серия и номер документа, на основании которого действует руководитель',
  boss_basis_acts_issued_date DATETIME DEFAULT NULL COMMENT 'дата выдачи документа, на основании которого действует руководитель',
  boss_passport_number CHAR(12) NOT NULL COMMENT 'серия и номер паспорта руководителя',
  boss_passport_issued_date DATETIME NOT NULL COMMENT 'дата выдачи паспорта руководителя',
  boss_passport_division_code CHAR(7) NOT NULL COMMENT 'код подразделения, выдавшего паспорт руководителя',
  boss_passport_issued_by VARCHAR(255) NOT NULL COMMENT 'кем выдан паспорт руководителя',
  boss_birth_date DATETIME NOT NULL COMMENT 'дата рождения руководителя',
  boss_birth_place VARCHAR(255) NOT NULL COMMENT 'место рождения руководителя',
  email VARCHAR(255) UNIQUE NOT NULL COMMENT 'email магазина',
  document_snils VARCHAR(255) DEFAULT NULL COMMENT 'документ - СНИЛС',
  document_passport VARCHAR(255) DEFAULT NULL COMMENT 'документ - паспорт',
  document_statute_with_tax_mark VARCHAR(255) DEFAULT NULL COMMENT 'документ - устав с отметкой налогового органа на дату решения об избрании единоличного исполнительного органа',
  document_participants_decision VARCHAR(255) DEFAULT NULL COMMENT 'документ - протокол общего собрания участников/Решение единственного участника',
  document_ogrn VARCHAR(255) DEFAULT NULL COMMENT 'документ - ОГРН',
  document_questionnaire_fl_115 VARCHAR(255) DEFAULT NULL COMMENT 'документ - анкета-опросник по 115 ФЗ',
  document_order_on_appointment VARCHAR(255) DEFAULT NULL COMMENT 'документ - приказ о назначении единоличного исполнительного органа с подписью и печатью единоличного исполнительного органа',
  document_statute_of_current_edition VARCHAR(255) DEFAULT NULL COMMENT 'документ - устав действующей редакции'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'организации';

DROP TABLE IF EXISTS shops;
CREATE TABLE shops (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL COMMENT 'название магазина',
  email VARCHAR(255) UNIQUE NOT NULL COMMENT 'email магазина',
  is_activated TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'активный ли магазин (1 - да, 0 - нет)',
  secret_key VARCHAR(255) NOT NULL COMMENT 'секретный ключ',
  organization_id INT(11) UNSIGNED NOT NULL COMMENT 'id организации'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'магазины';

DROP TABLE IF EXISTS shops_admins;
CREATE TABLE shops_admins (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL COMMENT 'имя',
  email VARCHAR(255) UNIQUE NOT NULL COMMENT 'email и логин',
  password_hash VARCHAR(255) NOT NULL COMMENT 'хэш пароля',
  phone CHAR(12) DEFAULT NULL COMMENT 'телефон',
  role ENUM('admin', 'manager') NOT NULL COMMENT 'роль',
  shop_id INT(11) UNSIGNED NOT NULL COMMENT 'id магазина',
  is_activated TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'активный ли администратор (1 - да, 0 - нет)'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'администраторы магазинов';

DROP TABLE IF EXISTS clients;
CREATE TABLE clients (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  last_name VARCHAR(255) NOT NULL COMMENT 'фамилия',
  first_name VARCHAR(255) NOT NULL COMMENT 'имя',
  middle_name VARCHAR(255) NOT NULL COMMENT 'отчество',
  birth_date DATETIME NOT NULL COMMENT 'дата рождения',
  birth_place VARCHAR(255) NOT NULL COMMENT 'место рождения',
  sex ENUM('male', 'female') NOT NULL COMMENT 'пол',
  is_last_name_changed TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'менялась ли фамилия (1 - да, 0 - нет)',
  previous_last_name VARCHAR(255) COMMENT 'предыдущая фамилия',
  tin VARCHAR(12) UNIQUE NOT NULL COMMENT 'ИНН',
  snils CHAR(14) UNIQUE NOT NULL COMMENT 'СНИЛС',
  passport_number CHAR(12) UNIQUE NOT NULL COMMENT 'серия и номер паспорта',
  passport_division_code CHAR(7) NOT NULL COMMENT 'код подразделения, выдавшего паспорт',
  passport_issued_by VARCHAR(255) NOT NULL COMMENT 'кем выдан паспорт',
  passport_issued_date DATETIME NOT NULL COMMENT 'дата выдачи паспорта',
  workplace VARCHAR(255) NOT NULL COMMENT 'место работы',
  salary BIGINT UNSIGNED NOT NULL COMMENT 'ежемесячный доход',
  reg_zip_code CHAR(6) NOT NULL COMMENT 'индекс по прописке',
  reg_city VARCHAR(255) NOT NULL COMMENT 'город по прописке',
  reg_street VARCHAR(255) DEFAULT NULL COMMENT 'улица по прописке',
  reg_building VARCHAR(255) NOT NULL COMMENT 'дом по прописке',
  reg_apartment VARCHAR(255) DEFAULT NULL COMMENT 'квартира по прописке',
  is_address_matched TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'совпадают ли фактический и адрес прописки (1 - да, 0 - нет)',
  fact_zip_code CHAR(6) DEFAULT NULL COMMENT 'индекс по факту',
  fact_city VARCHAR(255) DEFAULT NULL COMMENT 'город по факту',
  fact_street VARCHAR(255) DEFAULT NULL COMMENT 'улица по факту',
  fact_building VARCHAR(255) DEFAULT NULL COMMENT 'дом по факту',
  fact_apartment VARCHAR(255) DEFAULT NULL COMMENT 'квартира по факту',
  phone CHAR(12) UNIQUE NOT NULL COMMENT 'телефон',
  additional_phone CHAR(12) DEFAULT NULL COMMENT 'дополнительный телефон',
  email VARCHAR(255) UNIQUE NOT NULL COMMENT 'email'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'покупатели';

DROP TABLE IF EXISTS mfi;
CREATE TABLE mfi (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL COMMENT 'название',
  slug VARCHAR(255) DEFAULT NULL COMMENT 'буквенный идентификатор',
  phone CHAR(12) DEFAULT NULL COMMENT 'телефон',
  email VARCHAR(255) DEFAULT NULL COMMENT 'email',
  min_loan_sum DECIMAL(19, 2) UNSIGNED NOT NULL COMMENT 'минимальная сумма кредита',
  max_loan_sum DECIMAL(19, 2) UNSIGNED NOT NULL COMMENT 'максимальная сумма кредита',
  can_loan_postponed TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'работает ли с отложенными кредитами (1 - да, 0 - нет)',
  time_limit INT(11) UNSIGNED DEFAULT 0 COMMENT 'время в секундах, отведённое на обработку заявки',
  priority INT(11) UNSIGNED DEFAULT 0 COMMENT 'приоритет при обработке заявок'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'МФО';

DROP TABLE IF EXISTS integration_plugins;
CREATE TABLE integration_plugins (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL COMMENT 'название системы, с которой возможна интеграция',
  img_url VARCHAR(255) NOT NULL COMMENT 'ссылка на логотип системы',
  url VARCHAR(255) NOT NULL COMMENT 'ссылка на плагин',
  orderby INT(11) UNSIGNED DEFAULT 0 COMMENT 'сортировка'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'плагины для интеграции';

DROP TABLE IF EXISTS partners;
CREATE TABLE partners (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL COMMENT 'название',
  img_url VARCHAR(255) NOT NULL COMMENT 'ссылка на логотип',
  url VARCHAR(255) NOT NULL COMMENT 'ссылка на сайт партнера',
  orderby INT(11) UNSIGNED DEFAULT 0 COMMENT 'сортировка'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'партнёры';

DROP TABLE IF EXISTS mfi_shop_cooperation;
CREATE TABLE mfi_shop_cooperation (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  mfi_id INT(11) UNSIGNED NOT NULL COMMENT 'id МФО',
  shop_id INT(11) UNSIGNED NOT NULL COMMENT 'id магазина',
  mfi_api_parameters VARCHAR(255) DEFAULT NULL COMMENT 'json-представление api параметров'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'МФО и магазины, с которыми они работают';

DROP TABLE IF EXISTS mfi_responses;
CREATE TABLE mfi_responses (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  mfi_id INT(11) NOT NULL COMMENT 'id МФО',
  request_id INT(11) NOT NULL COMMENT 'id заявки',
  status ENUM('approved', 'declined', 'did_not_have_time') NOT NULL COMMENT 'одобрено, отклонено, не успела ответить',
  time_response TIMESTAMP NOT NULL COMMENT 'время ответа'
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'ответы МФО на заявки';

DROP TABLE IF EXISTS requests;
CREATE TABLE requests (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  client_id INT(11) UNSIGNED NOT NULL COMMENT 'id клиента',
  shop_id INT(11) UNSIGNED NOT NULL COMMENT 'id магазина',
  order_id VARCHAR(255) NOT NULL COMMENT 'id заказа в магазине',
  order_price DECIMAL(19, 2) UNSIGNED NOT NULL COMMENT 'сумма заказа, руб.',
  goods TEXT NOT NULL COMMENT 'сериализованный массив товаров',
  is_test_mode_enabled TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'тестовая ли заявка (1 - да, 0 - нет)',
  is_loan_postponed TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'отложенный ли кредит (1 - да, 0 - нет)',
  status ENUM('pending', 'declined', 'canceled', 'manual', 'approved', 'confirmed') NOT NULL COMMENT 'статусы заявки (в процессе, отказано, клиент отменил, требуется решение менеджера, одобрена, подтверждена клиентом)',
  approved_mfi_id INT(11) UNSIGNED DEFAULT NULL COMMENT 'id одобрившей МФО',
  approved_mfi_response TEXT DEFAULT NULL COMMENT 'сериализованный массив с ответом',
  time_start TIMESTAMP NOT NULL COMMENT 'время подачи заявки',
  time_finish TIMESTAMP NULL DEFAULT NULL COMMENT 'время закрытия заявки'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'заявки на кредит';

DROP TABLE IF EXISTS loans;
CREATE TABLE loans (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  request_id INT(11) UNSIGNED NOT NULL COMMENT 'id заявки',
  shop_id INT(11) UNSIGNED NOT NULL COMMENT 'id магазина',
  mfi_id INT(11) UNSIGNED NOT NULL COMMENT 'id МФО',
  status ENUM('pending', 'declined', 'waiting_for_receipt', 'issued') NOT NULL COMMENT 'статусы кредита (в ожидании решения магазина, отклонён, выдать кредит при получении товаров, кредит выдан покупателю)',
  customer_id VARCHAR(255) DEFAULT NULL COMMENT 'id покупателя в МФО',
  contract_id VARCHAR(255) DEFAULT NULL COMMENT 'id контракта в МФО',
  loan_id VARCHAR(255) DEFAULT NULL COMMENT 'id кредита в МФО',
  loan_body DECIMAL(19, 2) UNSIGNED NOT NULL COMMENT 'тело кредита, руб.',
  loan_cost DECIMAL(19, 2) UNSIGNED DEFAULT NULL COMMENT 'полная стоимость кредита, руб.',
  loan_period INT(11) UNSIGNED DEFAULT NULL COMMENT 'срок кредита, дн.',
  loan_daily_percent_rate DECIMAL(19, 4) UNSIGNED DEFAULT NULL COMMENT 'процентная ставка в день, %',
  loan_terms_link VARCHAR(255) DEFAULT NULL COMMENT 'ссылка на договор',
  delivery_service_id INT(11) UNSIGNED DEFAULT NULL COMMENT 'id службы доставки',
  tracking_code VARCHAR(255) DEFAULT NULL COMMENT 'код отслеживания посылки, его выдаёт служба доставки'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'кредиты';

DROP TABLE IF EXISTS delivery_services;
CREATE TABLE delivery_services (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL COMMENT 'название',
  slug VARCHAR(255) NOT NULL COMMENT 'идентификатор'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'сервисы доставки';

DROP TABLE IF EXISTS remembered_clients;
CREATE TABLE remembered_clients (
  token_hash VARCHAR(64) NOT NULL PRIMARY KEY COMMENT 'хэш токена',
  client_id INT(11) UNSIGNED DEFAULT NULL COMMENT 'id клиента',
  phone CHAR(12) DEFAULT NULL COMMENT 'телефон',
  is_verified TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'подтверждён ли телефон (1 - да, 0 - нет)',
  shop_id INT(11) UNSIGNED NOT NULL COMMENT 'id магазина',
  order_id VARCHAR(255) NOT NULL COMMENT 'id заказа в магазине',
  order_price DECIMAL(19, 2) UNSIGNED NOT NULL COMMENT 'сумма заказа',
  goods TEXT NOT NULL COMMENT 'сериализованный массив товаров',
  callback_url VARCHAR(255) NOT NULL COMMENT 'ссылка для возврата',
  is_test_mode_enabled TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'тестовый ли режим(1 - да, 0 - нет)',
  signature VARCHAR(255) NOT NULL COMMENT 'подпись',
  is_loan_postponed TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'отложенный ли кредит (1 - да, 0 - нет)',
  request_id INT(11) UNSIGNED DEFAULT NULL COMMENT 'id заявки',
  token_expires_at DATETIME NOT NULL COMMENT 'срок годности токена',
  sms_code VARCHAR(255) DEFAULT NULL COMMENT 'sms-код',
  sms_code_wrong_inputs_number INT(11) UNSIGNED DEFAULT 0 COMMENT 'кол-во неверного ввода sms-кода',
  sms_code_total_wrong_inputs_number INT(11) UNSIGNED DEFAULT 0 COMMENT 'общее кол-во неверного ввода sms-кода',
  sms_code_sends_at DATETIME DEFAULT NULL COMMENT 'время отправки последнего sms-кода',
  sms_code_expires_at DATETIME DEFAULT NULL COMMENT 'срок годности sms-кода'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'данные, с которыми клиент попал на сайт';

DROP TABLE IF EXISTS locked_phones;
CREATE TABLE locked_phones (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  phone CHAR(12) NOT NULL COMMENT 'телефон',
  locked_until DATETIME NOT NULL COMMENT 'заблокирован до'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'заблокированные номера телефонов';

ALTER TABLE organizations
  ADD CONSTRAINT fk_organizations_organization_categories FOREIGN KEY (category_id) REFERENCES organization_categories (id) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE shops
  ADD CONSTRAINT fk_shop_organization FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE shops_admins
  ADD CONSTRAINT fk_shop_admin FOREIGN KEY (shop_id) REFERENCES shops (id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE mfi_shop_cooperation
  ADD CONSTRAINT fk_shop_cooperation FOREIGN KEY (shop_id) REFERENCES shops (id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE mfi_shop_cooperation
  ADD CONSTRAINT fk_mfi_cooperation FOREIGN KEY (mfi_id) REFERENCES mfi (id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE requests
  ADD CONSTRAINT fk_request_client FOREIGN KEY (client_id) REFERENCES clients (id) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE requests
  ADD CONSTRAINT fk_request_shop FOREIGN KEY (shop_id) REFERENCES shops (id) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE requests
  ADD CONSTRAINT fk_request_mfi FOREIGN KEY (approved_mfi_id) REFERENCES mfi (id) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE loans
  ADD CONSTRAINT fk_loan_request FOREIGN KEY (request_id) REFERENCES requests (id) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE loans
  ADD CONSTRAINT fk_loan_delivery_service FOREIGN KEY (delivery_service_id) REFERENCES delivery_services (id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE loans
  ADD CONSTRAINT fk_loans_shop FOREIGN KEY (shop_id) REFERENCES shops (id) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE remembered_clients
  ADD CONSTRAINT fk_remembered_client_client FOREIGN KEY (client_id) REFERENCES clients (id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE remembered_clients
  ADD CONSTRAINT fk_remembered_client_shop FOREIGN KEY (shop_id) REFERENCES shops (id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE remembered_clients
  ADD CONSTRAINT fk_remembered_client_request FOREIGN KEY (request_id) REFERENCES requests (id) ON DELETE CASCADE ON UPDATE CASCADE;

/* Machine God set us free */
