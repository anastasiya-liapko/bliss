ALTER TABLE organizations
  ADD COLUMN phone CHAR(16) NOT NULL COMMENT 'телефон' AFTER email;
