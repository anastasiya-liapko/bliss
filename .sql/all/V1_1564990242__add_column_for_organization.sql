ALTER TABLE organizations
  ADD COLUMN is_documents_checked TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'проверены ли документы (1 - да, 0 - нет)';
