ALTER TABLE organizations
  MODIFY boss_basis_acts_issued_date DATE DEFAULT NULL COMMENT 'дата выдачи документа, на основании которого действует руководитель';
ALTER TABLE organizations
  MODIFY boss_passport_issued_date DATE NOT NULL COMMENT 'дата выдачи паспорта руководителя';
ALTER TABLE organizations
  MODIFY boss_birth_date DATE NOT NULL COMMENT 'дата рождения руководителя';
