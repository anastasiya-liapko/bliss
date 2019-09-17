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
