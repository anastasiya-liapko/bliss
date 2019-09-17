/* admins */
INSERT INTO admins (id, name, email, password_hash, role)
  VALUES (1, 'Супер администратор', 'admin@bliss24.ru', 'de65262646860a5aba74c271718c2f32', 'super_admin');

/* clients */
INSERT
INTO clients (id, last_name, first_name, middle_name, birth_date, birth_place, sex, is_last_name_changed,
              previous_last_name, tin, snils, passport_number, passport_division_code, passport_issued_by,
              passport_issued_date, workplace, salary, reg_zip_code, reg_city, reg_street, reg_building,
              reg_apartment, is_address_matched, fact_zip_code, fact_city, fact_street, fact_building,
              fact_apartment, phone, additional_phone, email)
VALUES (1, 'Петров', 'Пётр', 'Петрович', '1980-01-01 00:00:00', 'г. Москва', 'male', 0, '', '111111111111',
        '111-111-111 11', '11 11 111111', '770-001', 'УПРАВЛЕНИЕ ФЕДЕРАЛЬНОЙ МИГРАЦИОННОЙ СЛУЖБЫ РОССИИ ПО ГОР. МОСКВЕ',
        '2010-01-01 00:00:00', 'ООО "Ромашка"', 20000, '101000', 'Москва', 'Ленина', 10, 20, 1, '101000', 'Москва',
        'Ленина', 10, 20, '79097391754', '', 'petrov_pp@mail.ru');

/* organization_categories */
INSERT INTO organization_categories (id, name)
  VALUES (1, 'прочее'),
  (2, 'образование'),
  (3, 'ювелирные изделия'),
  (4, 'спорттовары и туризм'),
  (5, 'красота и здоровье'),
  (6, 'авто'),
  (7, 'товары для детей'),
  (8, 'ремонт'),
  (9, 'зоотовары'),
  (10, 'страхование'),
  (11, 'АЗС'),
  (12, 'развлечения'),
  (13, 'кафе и рестораны'),
  (14, 'путешествия'),
  (15, 'обувь'),
  (16, 'мебель'),
  (17, 'электроника'),
  (18, 'одежда');

/* organizations */
INSERT
INTO organizations (id, type, vat, legal_name, tin, cio, bin, is_licensed_activity, license_type, license_number,
                    category_id, legal_address, registration_address, fact_address, bik, bank_name,
                    correspondent_account, settlement_account, boss_full_name, boss_position, boss_basis_acts,
                    boss_basis_acts_number, boss_basis_acts_issued_date, boss_passport_number,
                    boss_passport_issued_date, boss_passport_division_code, boss_passport_issued_by,
                    boss_birth_date, boss_birth_place, email, document_snils, document_passport,
                    document_statute_with_tax_mark, document_participants_decision, document_ogrn,
                    document_questionnaire_fl_115, document_order_on_appointment,
                    document_statute_of_current_edition)
VALUES (1, 'entrepreneur', 10, null, '111111111111', null, '111111111111111', 0, null, null, 1, null,
        'г. Москва, ул. Ленина, д. 10, кв. 20', 'г. Москва, ул. Ленина, д. 10, кв. 20', '111111111', 'Сбербанк',
        '11111111111111111111', '11111111111111111111', 'Петров Пётр Петрович', null, null, null, null,
        '11 11 111111',
        '2010-01-01 00:00:00', '770-001', 'УПРАВЛЕНИЕ ФЕДЕРАЛЬНОЙ МИГРАЦИОННОЙ СЛУЖБЫ РОССИИ ПО ГОР. МОСКВЕ',
        '1980-01-01 00:00:00', 'Москва', 'petrov_pp@mail.ru',
        '/documents/organizations/tin-111111111111/document_snils.pdf',
        '/documents/organizations/tin-111111111111/document_passport.pdf', null, null,
        '/documents/organizations/tin-111111111111/document_ogrn.pdf',
        '/documents/organizations/tin-111111111111/document_questionnaire_fl_115.pdf', null, null),
       (2, 'entrepreneur', 0.0000, '', '343522286382', '', '3187746005348', 0, '', '', 18, '',
        'Россия, 108818, г.Москва, ул.Кедровая, д.12', 'Россия, 108818, г.Москва, ул.Кедровая, д.12', '044525225',
        'ПАО СБЕРБАНК', '30101810400000000225', '40802810738000110334', 'Чертина Виктория Николаевна', '', 'statute',
        '', '1970-01-01 03:00:00', '18 01 679672', '2001-11-20 00:00:00', '342-011',
        'УВД ГОРОДА ВОЛЖСКОГО ВОЛГОГРАДСКОЙ ОБЛАСТИ', '1976-04-22 00:00:00', 'г. Волжский', 'vikatel@mail.ru',
        '/documents/organizations/tin-343522286382/document_snils.pdf',
        '/documents/organizations/tin-343522286382/document_passport.pdf', NULL, NULL, 'test',
        '/documents/organizations/tin-343522286382/document_questionnaire_fl_115.pdf', NULL, NULL);

/* shops */
INSERT INTO shops (id, name, email, is_activated, secret_key, organization_id)
  VALUES (1, 'ИП "Петров Пётр Петрович"', 'petrov_pp@mail.ru', 1, 'FMNDesQ58G8y4O8bgGPvsEGFPwEe8Gdj', 1),
  (2, 'ИП "Чертина Виктория Николаевна"', 'vikatel@mail.ru', 1, 'WkShmtFBICHic64rLZ2dnpg6l2s7VW7I', 2);

/* shops_admins */
INSERT INTO shops_admins (id, name, email, password_hash, phone, role, shop_id, is_activated)
  VALUES (1, 'Петров Пётр Петрович', 'petrov_pp@mail.ru', 'da3f4dfe6cfc9eb2ddd2f2bf683a2956', '', 'admin', 1, 1),
  (2, 'Чертина Виктория Николаевна', 'vikatel@mail.ru', 'bb89652b06ca14b8abcfc11283213034', '', 'admin', 2, 1);

/* mfi */
INSERT INTO mfi (id, name, slug, phone, email, min_loan_sum, max_loan_sum, can_loan_postponed, time_limit)
  VALUES (1, 'WEBBANKIR', 'Webbankir', '', '', 3000, 15000, 1, 600);

/* mfi_shop_cooperation */
INSERT INTO mfi_shop_cooperation (mfi_id, shop_id, mfi_api_parameters)
  VALUES (1, 1, '{"merchantId":1,"shopId":1,"password":"qwerty"}'),
  (1, 2, '{"merchantId":306,"shopId":237,"password":"kas382dnet"}');

/* delivery_services */
INSERT INTO delivery_services (id, name, slug)
  VALUES (1, 'Другая (отслеживание и смена статуса производятся вручную)', 'default'),
  (2, 'Почта России', 'russian_post');
