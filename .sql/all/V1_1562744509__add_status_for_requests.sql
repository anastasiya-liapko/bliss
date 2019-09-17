ALTER TABLE requests
  MODIFY COLUMN status
    ENUM ('pending', 'declined', 'canceled', 'manual', 'approved', 'confirmed', 'waiting_for_limit')
    NOT NULL
    COMMENT 'статусы заявки (в процессе, отказано, клиент отменил, требуется решение менеджера, одобрена, подтверждена клиентом, ожидает одобрения лимита)';
