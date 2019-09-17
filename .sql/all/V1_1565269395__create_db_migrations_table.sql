DROP TABLE IF EXISTS db_migrations;
CREATE TABLE db_migrations
(
    id           INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(255) UNIQUE NOT NULL COMMENT 'название',
    is_completed TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'выполнена ли миграция (1 - да, 0 - нет)'
) ENGINE = INNODB
  DEFAULT CHARSET = utf8 COMMENT 'миграции базы данных';
