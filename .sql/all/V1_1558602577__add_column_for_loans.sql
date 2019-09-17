ALTER TABLE loans
  ADD COLUMN is_mfi_paid TINYINT(1) UNSIGNED DEFAULT 0
    COMMENT 'МФО перечислило деньги магазину? (1 - да, 0 - нет)'
    AFTER status;
