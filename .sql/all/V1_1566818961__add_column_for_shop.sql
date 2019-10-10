ALTER TABLE shops
    ADD COLUMN is_old_integration TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'старая ли интеграция';
