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
