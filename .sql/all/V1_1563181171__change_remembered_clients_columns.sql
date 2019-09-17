ALTER TABLE remembered_clients
  MODIFY token_expires_at TIMESTAMP NOT NULL COMMENT 'срок годности токена';
ALTER TABLE remembered_clients
  MODIFY sms_code_sends_at TIMESTAMP NULL DEFAULT NULL COMMENT 'время отправки последнего sms-кода';
ALTER TABLE remembered_clients
  MODIFY sms_code_expires_at TIMESTAMP NULL DEFAULT NULL COMMENT 'срок годности sms-кода';
