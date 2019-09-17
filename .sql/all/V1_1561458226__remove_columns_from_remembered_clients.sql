ALTER TABLE remembered_clients
  DROP FOREIGN KEY fk_remembered_client_client;

ALTER TABLE remembered_clients
  DROP FOREIGN KEY fk_remembered_client_request;

ALTER TABLE remembered_clients
  DROP COLUMN client_id,
  DROP COLUMN request_id;
