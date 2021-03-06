# Bliss

Серверная часть приложения написана на языке программирования PHP.

В приложении применяется архитектурный паттерн MVC.

Пользовательские запросы обрабатываются файлом public/index.php, где осуществляется маршрутизация.

Для упрощения работы на клиентской части подключена библиотека jQuery, а также библиотека Bootstrap. CSS компилируются из SASS-файлов. Сборка front-end осуществляется с помощью Gulp.

Административные панели сгенерированы с помощью внутреннего инструмента — Genesis v2.

В административной панели магазинов подключена библиотека React.js. Сборка компонентов осуществляется с помощью Webpack.

Ниже приведены подключенные к проекту PHP-библиотеки. Не подключайте дополнительно библиотеки, которые реализуют тот же функционал.

PHP-библиотеки:

- Composer — менеджер зависимостей для PHP;
- Guzzle — HTTP клиент;
- Asynchronous SOAP client — SOAP клиент;
- Monolog — логгер;
- PHPWord — библиотека для работы с текстовыми файлами разных форматов;
- Twig — шаблонизатор;
- The HttpFoundation Component — определяет объектно-ориентированный слой для спецификации HTTP;
- Rakit Validation — валидация полей на стороне сервера;
- PHPUnit — юнит-тестирование.

JS-библиотеки и плагины:

- Bootstrap 4 — CSS и JavaScript-библиотека;
- date-fns — работа с датами;
- Inputmask — маски для полей;
- JQuery — JavaScript-библиотека;
- The Final Countdown — обратный отсчёт времени;
- jQuery Validation Plugin — валидация полей на стороне клиента;
- JavaScript Cookie — работа с куками;
- prop-types — проверка типов для React.js;
- React.js - JavaScript-фреймоворк;
- React Bootstrap - Bootstrap-компоненты для React.js;
- React Date Picker - компонент выбора даты для React.js;
- ReactDOM - пакет для работы с DOM для React.js;
- React Paginate - компонент-пагинация страниц для React.js;
- Swiper — слайдер;

## Структура проекта

### .circleci/

Здесь находится конфигурационный файл config.yml для работы с сервисом CircleCI.

### .sql/

Место хранения sql-файлов.

- **all/** — в папке находятся файлы sql для записи новой информации в базу данных;
- **baseline/** — здесь находится файл V1_0__baseline.sql, который описывают структуру базы данных;
- **test/** — здесь расположены файлы sql с информацией, необходимой для запуска юнит-тестов.

### App/

Основной каталог приложения. Внутри находятся директории и файлы:

- **Controllers/** — контроллеры приложения:
- **AdminPanel/** — контроллеры административной панели;
- **AdminPanel.php** — содержит абстрактный класс \App\Controllers\AdminPanel\AdminPanel, который наследуется от абстрактного класса \Core\Controller.
- **DocumentTemplates.php** — содержит класс \App\Controllers\AdminPanel\DocumentTemplates, который наследуется от абстрактного класса \App\Controllers\AdminPanel\AdminPanel. Отвечает за шаблоны документов.
- **Logs.php** — содержит класс \App\Controllers\AdminPanel\Logs, который наследуется от абстрактного класса \App\Controllers\AdminPanel\AdminPanel. Отвечает за логи сайта.
- **Api/** — API для интеграции с торговыми организациями партнёрами;

  - **V1/** — первая версия;
  - **Api.php** — содержит абстрактный класс \App\Controllers\Api\V1\Api.
  - **Orders.php** — содержит класс \App\Controllers\Api\V1\Orders, который наследуется от абстрактного класса \App\Controllers\Api\V1\Api. Отвечает за создание и изменение заказов.
  - **Shops.php** — содержит класс \App\Controllers\Api\V1\Shops, который наследуется от абстрактного класса \App\Controllers\Api\V1\Api. Отвечает за получение токена для магазина.

- **ShopAdminPanel/** — контроллеры административной панели магазинов;

-
  - **DeliveringOrders.php** — содержит класс \App\Controllers\ShopAdminPanel\DeliveringOrders, который наследуется от абстрактного класса \App\Controllers\ShopAdminPanel\ShopAdminPanel. Отвечает за заказы, которые ожидают доставки.
  - **FailedOrders.php** — содержит класс \App\Controllers\ShopAdminPanel\FailedOrders, который наследуется от абстрактного класса \App\Controllers\ShopAdminPanel\ShopAdminPanel. Отвечает за отменённые заказы.
  - **IncomingOrders.php** — содержит класс \App\Controllers\ShopAdminPanel\IncomingOrders, который наследуется от абстрактного класса \App\Controllers\ShopAdminPanel\ShopAdminPanel. Отвечает за входящие заказы.
  - **IssuedOrders.php** — содержит класс \App\Controllers\ShopAdminPanel\IssuedOrders, который наследуется от абстрактного класса \App\Controllers\ShopAdminPanel\ShopAdminPanel. Отвечает за выданные заказы.
  - **ShopAdminPanel.php** — содержит абстрактный класс \App\Controllers\ShopAdminPanel\ShopAdminPanel, который наследуется от абстрактного класса \Core\Controller.

- **CodeSms.php** — содержит класс \App\Controllers\CodeSms, который наследуется от абстрактного класса \Core\Controller. Отвечает за отображение и взаимодействие пользователя со страницей /code-sms. На этой странице пользователь вводит код из СМС для подтверждения номера телефона;
- **Declined.php** — содержит класс \App\Controllers\Declined, который наследуется от абстрактного класса \Core\Controller. Отвечает за отображение и взаимодействие пользователя со страницей /declined. На эту страницу пользователь попадает, если все ФО отказали в кредите;
- **Error.php** — содержит класс \App\Controllers\Error, который наследуется от абстрактного класса \Core\Controller. Отвечает за отображение и взаимодействие пользователя со страницей /error. На эту страницу пользователь попадает, если он пришёл на сайт для оформления заявки с неверными параметрами;
- **Home.php** — содержит класс \App\Controllers\Home, который наследуется от абстрактного класса \Core\Controller. Отвечает за отображение и взаимодействие пользователя с главной страницей сайта;
- **PhoneNumber.php** — содержит класс \App\Controllers\PhoneNumber, который наследуется от абстрактного класса \Core\Controller. Отвечает за отображение и взаимодействие пользователя со страницей /phone-number. На эту страницу пользователь попадает при нажатии кнопки "Купить в кредит" на сайте интернет-магазина;
- **ProfileClient.php** — содержит класс \App\Controllers\ProfileClient, который наследуется от абстрактного класса \Core\Controller. Отвечает за отображение и взаимодействие пользователя со страницей /profile-client. На этой странице находится анкета пользователя;
- **ProfileShop.php** — содержит класс \App\Controllers\ProfileShop, который наследуется от абстрактного класса \Core\Controller. Отвечает за отображение и взаимодействие пользователя со страницей /profile-client. На этой странице находится анкета магазина;
- **Success.php** — содержит класс \App\Controllers\Success, который наследуется от абстрактного класса \Core\Controller. Отвечает за отображение и взаимодействие пользователя со страницей /success. На эту страницу пользователь попадает при успешном оформлении кредита;
- **Test.php** — содержит класс \App\Controllers\Test, который наследуется от абстрактного класса \Core\Controller. Отвечает за отображение и взаимодействие пользователя со страницей /test. Эта страница предназначена для тестирования системы;
- **Logistics/** — интеграции со службами доставки:
- **RussianPost.php** — содержит класс \App\Logistics\RussianPost, который предназначен для интеграции с Почтой России. С помощью этого класса можно отслеживать посылки по трек-коду;
- **MFI/** — интеграции с ФО:
- **MFI.php** — абстрактный коннектор для интеграции с ФО;
- **Webbankir** — содержит класс \App\MFI\Webbankir, который наследуется от абстрактного класса \App\MFI\MFI. Предназначен для интеграции с API системы Webbankir.
- **Models/** — модели приложения:
- **Admin.php** — содержит класс \App\Models\Admin, который наследуется от абстрактного класса \Core\Model. Отвечает за логику, связанную с администратором Bliss;
- **Client.php** — содержит класс \App\Models\Client, который наследуется от абстрактного класса \Core\Model. Отвечает за логику, связанную с клиентами.;
- **DeliveryService.php** — содержит класс \App\Models\DeliveryService, который наследуется от абстрактного класса \Core\Model. Отвечает за логику, связанную со службами доставки.;
- **IntegrationPlugin.php** — содержит класс \App\Models\IntegrationPlugin, который наследуется от абстрактного класса \Core\Model. Отвечает за логику, связанную с интеграционными плагинами для интернет-магазинов.;
- **Loan.php** — содержит класс \App\Models\Loan, который наследуется от абстрактного класса \Core\Model. Отвечает за логику, связанную с кредитами.;
- **LockedPhone.php** — содержит класс \App\Models\LockedPhone, который наследуется от абстрактного класса \Core\Model. Отвечает за логику блокировки номеров телефонов.;
- **MFI.php** — содержит класс \App\Models\MFI, который наследуется от абстрактного класса \Core\Model. Отвечает за логику, связанную с ФО.;
- **Order.php** — содержит класс \App\Models\Order, который наследуется от абстрактного класса \Core\Model. Отвечает за логику, связанную с заказами.;
- **Organization.php** — содержит класс \App\Models\Organization, который наследуется от абстрактного класса \Core\Model. Отвечает за логику, связанную с организациями.;
- **Partner.php** — содержит класс \App\Models\Partner, который наследуется от абстрактного класса \Core\Model. Отвечает за логику, связанную с партнёрами, которые отображаются в слайдере на главной странице сайта.;
- **RememberedClient.php** — содержит класс \App\Models\RememberedClient, который наследуется от абстрактного класса \Core\Model. Отвечает за логику, связанную с получением и обработкой информации от пользователя, когда он перешёл на сайт с интернет-магазина.;
- **Request.php** — содержит класс \App\Models\Request, который наследуется от абстрактного класса \Core\Model. Отвечает за логику, связанную с заявками на кредит.;
- **Shop.php** — содержит класс \App\Models\Shop, который наследуется от абстрактного класса \Core\Model. Отвечает за логику, связанную с магазинами.;
- **ShopAdmin.php** — содержит класс \App\Models\ShopAdmin, который наследуется от абстрактного класса \Core\Model. Отвечает за логику, связанную с администраторами магазинов.;
- **Views/** — представления приложения:
- **CodeSms/index.twig** — отвечает за отображение страницы /code-sms. На этой странице пользователь вводит код из СМС для подтверждения номера телефона;
- **Declined/index.twig** — отвечает за отображение страницы /declined. На эту страницу пользователь попадает, если все ФО отказали в кредите;
- **EmailTemplates/shop_admin_auth_data.twig** — html-шаблон письма. Отправляется новому администратору магазина при регистрации;
- **EmailTemplates/shop_admin_auth_data.txt** — txt-шаблон письма. Отправляется новому администратору магазина при регистрации;
- **EmailTemplates/client_canceled_request.twig** — html-шаблон письма. Отправляется администратору Bliss, когда пользователь отказывается от заявки;
- **EmailTemplates/client_canceled_request.txt** — txt-шаблон письма. Отправляется администратору Bliss, когда пользователь отказывается от заявки;
- **EmailTemplates/client_confirmed_loan.twig** — html-шаблон письма. Отправляется магазину, когда покупателю одобрили кредит;
- **EmailTemplates/client_confirmed_loan.txt** — txt-шаблон письма. Отправляется магазину, когда покупателю одобрили кредит;
- **EmailTemplates/order_link.twig** — html-шаблон письма. Содержит ссылку на заказ;
- **EmailTemplates/order_link.txt** — txt-шаблон письма. Отправляется клиенту, содержит ссылку на заказ;
- **EmailTemplates/new_organization.twig** — html-шаблон письма. Отправляется администратору Bliss, когда создана новая организация;
- **EmailTemplates/new_organization.txt** — txt-шаблон письма. Отправляется администратору Bliss, когда создана новая организация;
- **EmailTemplates/organization_uploaded_signed_documents.twig** — html-шаблон письма. Отправляется администратору Bliss, когда новая организация загрузила документы;
- **EmailTemplates/organization_uploaded_signed_documents.txt** — txt-шаблон письма. Отправляется администратору Bliss, когда новая организация загрузила документы;
- **Error/index.twig** — отвечает за отображение страницы /error. На эту страницу пользователь попадает, если он пришёл на сайт для оформления заявки с неверными параметрами;
- **Home/index.twig** — отвечает за отображение главной страницы сайта;
- **PhoneNumber/index.twig** — отвечает за отображение страницы /phone-number. На эту страницу пользователь попадает при нажатии кнопки "Купить в кредит" на сайте интернет-магазина;
- **ProfileClient/index.twig** — отвечает за отображение страницы /profile-client;
- **ProfileShop/index.twig** — отвечает за отображение страницы /profile-shop. На этой странице находится анкета магазина;
- **ProfileShop/second_step.twig** — отвечает за отображение второго шага страницы /profile-shop. На этой странице пользователь скачивает бланки документов и загружает подписанные документы;
- **Success/index.twig** — отвечает за отображение страницы /success. На эту страницу пользователь попадает при успешном оформлении кредита;
- **Test/index.twig** — отвечает за отображение страницы /test. Эта страница предназначена для тестирования системы;
- **404.twig** — отвечает за отображение страницы с кодом 404;
- **500.twig** — отвечает за отображение страницы с кодом 500;
- **base.twig** — базовый шаблон, от которого наследуются другие шаблоны.
- **Config.php** — конфигурация приложения;
- **Crediting.php** — обработка заявок на кредиты;
- **Dadata.php** — интеграция с сервисом dadata.ru;
- **DateRule.php** — правила валидации даты для библиотеки Rakit Validation.
- **Email.php** — отправка email.
- **FileUploader.php** — загрузка файлов с помощью HttpFoundation;
- **Helper.php** — класс со вспомогательными методами;
- **Logging.php** — логирование ошибок и запросов к сторонним сервисам;
- **MailMan.php** — интеграция с сервисом MailMan;
- **Morpher.php** — интеграция с сервисом ws3.morpher.ru;
- **PlainRule.php** — правила валидации текста для библиотеки Rakit Validation;
- **SiteInfo.php** — информация о приложении;
- **SMSRu.php** — интеграция с сервисом sms.ru;
- **SMS.php** — отправка СМС;
- **TelegramClientBot.php** — отправка сообщений через мессенджер Telegram;
- **TelegramMFIBot.php** — отправка сообщений через мессенджер Telegram;
- **TelegramOrganizationBot.php** — отправка сообщений через мессенджер Telegram;
- **Telegram.php** — интеграция с мессенджером Telegram;
- **Token.php** — работа с токенами;
- **UniqueRule.php** — правила валидации на уникальность для библиотеки Rakit Validation.

### Core/

Ядро приложения. Внутри находятся директории и файлы:

- **Controller.php** — абстрактный контроллер;
- **Error.php** — обработка ошибок и исключений;
- **Model.php** — абстрактная модель;
- **Router.php** — маршрутизатор;
- **View.php** — обработчик представлений.

### cron/

Cron-задачи:

- **check-delivery.php** — отслеживание посылок по отложенным кредитам.
- **clean.php** — очистка базы данных и логов.

### document/templates/

Шаблоны документов.

### exec/

Exec-задачи:

- **start-crediting.php** — запуск процесса кредитования.

### logs/

Здесь хранятся все логи приложения. Скачать архив логов можно через админку системы нажав пункт меню "Скачать логи".

### public/

Публичная часть приложения:

- **admin/** — административная панель Bliss;
- **admin-shops/** — административная панель для магазинов;
- **assets/** — место хранения файлов js, css, а также изображений и т. д.;
- **distributions/** — место хранения дистрибутивов;
- **documents/** — место хранения файлов pdf, docx и т. п.;
- **uploads/** — место хранения медиа-файлов, добавленных через административную панель;
- **.htaccess** — файл дополнительной конфигурации веб-сервера Apache;
- **index.php** — в этом файле подключается автозагрузчик, объявляются обработчики ошибок и исключений, открывается сессия и определяются настройки маршрутизатора. Все запросы от пользователей, за исключением запросов в административные панели, проходят через этот файл.

### src/

Front-end исходники.

### test/

Тесты приложения.

### tmp/

Папка для временного хранения.

### vendor/

Зависимости проекта.

### webpack/

Конфигурационные файлы для Webpack.

### .editorconfig

Конфигурационный файл, который создаёт единый формат по оформлению кода проекта.

### .eslintrc

Конфигурационный файл для ESLint.

### .gitattributes

Конфигурационный файл для Git. Определение атрибутов.

### .gitignore

Конфигурационный файл для Git. Игнорирование файлов.

### .htaccess

Файл дополнительной конфигурации веб-сервера Apache.

### .htpasswd

Файл, содержащий пароли для доступа к ресурсу у веб-сервера Apache.

### alef_run.sh

Файл, для запуска Unix-команд.

### composer.json

Файла-манифест проекта. Хранит список composer-пакетов, необходимых для проекта с нужными версиями.

### composer.lock

Этот файл блокирует composer-зависимости проекта до известного состояния.

### gulpfile.js

Файл содержит задачи для сборщика проектов Gulp.

### package.json

Файла-манифест проекта. Хранит список npm-пакетов, необходимых для проекта с нужными версиями.

### package-lock.json

Этот файл блокирует npm-зависимости проекта до известного состояния.

### phpunit.xml.dist

Конфигурационный файл для юнит-тестирования с помощью PHPUnit библиотеки.

## Структура базы данных

### admins

Администраторы системы Bliss.

Поля:

- **id** — идентификатор;
- **name** — имя;
- **email** — email;
- **password_hash** — хэш пароля;
- **role** — роль (super_admin, admin, manager).

### clients

Клиенты.

Поля:

- **id** — идентификатор;
- **last_name** — фамилия;
- **first_name** — имя;
- **middle_name** — отчество;
- **birth_date** — дата рождения;
- **birth_place** — место рождения;
- **sex** — пол (male, female);
- **is_last_name_changed** — менялась ли фамилия (1 — да, 0 — нет);
- **previous_last_name** — предыдущая фамилия;
- **tin** — ИНН;
- **snils** — СНИЛС;
- **passport_number** — серия и номер паспорта;
- **passport_division_code** — код подразделения, выдавшего паспорт;
- **passport_issued_by** — кем выдан паспорт;
- **passport_issued_date** — дата выдачи паспорта;
- **workplace** — место работы;
- **salary** — ежемесячный доход;
- **reg_zip_code** — индекс по прописке;
- **reg_city** — город по прописке;
- **reg_street** — улица по прописке;
- **reg_building** — дом по прописке;
- **reg_apartment** — квартира по прописке;
- **is_address_matched** — совпадают ли фактический и адрес прописки (1 — да, 0 — нет);
- **fact_zip_code** — индекс по факту;
- **fact_city** — город по факту;
- **fact_street** — улица по факту;
- **fact_building** — дом по факту;
- **fact_apartment** — квартира по факту;
- **phone** — телефон;
- **additional_phone** — дополнительный телефон;
- **email** — email.

### delivery_services

Сервисы доставки.

Поля:

- **id** — идентификатор;
- **name** — название;
- **slug** — буквенный идентификатор.

### integration_plugins

Плагины для интеграции.

Поля:

- **id** — идентификатор;
- **name** — название системы, с которой возможна интеграция;
- **img_url** — ссылка на логотип системы;
- **url** — ссылка на плагин;
- **orderby** — сортировка.

### loans

Кредиты.

Поля:

- **id** — идентификатор;
- **request_id** — id заявки;
- **shop_id** — id магазина;
- **mfi_id** — перечислило ли ФО деньги магазину (1 - да, 0 - нет);
- **status** — статус кредита;
- **is_mfi_paid** — статусы кредита;
- **customer_id** — id покупателя в ФО;
- **contract_id** — id контракта в ФО;
- **loan_id** — id кредита в ФО;
- **loan_body** — тело кредита, руб;
- **loan_cost** — полная стоимость кредита, руб.;
- **loan_period** — срок кредита, дн.;
- **loan_daily_percent_rate** — процентная ставка в день, %;
- **loan_terms_link** — ссылка на договор.

### locked_phones

Заблокированные номера телефонов.

Поля:

- **id** — идентификатор;
- **phone** —телефон;
- **locked_until** — заблокирован до.

### mfi

ФО.

Поля:

- **id** — идентификатор;
- **name** — название;
- **slug** — буквенный идентификатор;
- **phone** — телефон;
- **email** — email;
- **min_loan_sum** — минимальная сумма кредита;
- **max_loan_sum** — максимальная сумма кредита;
- **can_loan_postponed** — работает ли с отложенными кредитами (1 — да, 0 — нет);
- **time_limit** — время в секундах, отведённое на обработку заявки;
- **priority** — приоритет при обработке заявок.

### mfi_responses

Ответы ФО на заявки.

Поля:

- **id** — идентификатор;
- **mfi_id** — id ФО;
- **request_id** — id заявки;
- **status** — статус;
- **time_response** — время ответа.

### mfi_shop_cooperation

Связь между магазинами и ФО.

Поля:

- **id** — идентификатор;
- **mfi_id** — id ФО;
- **shop_id** — id магазина;
- **mfi_api_parameters** — json-представление api параметров.

### orders

Заказы.

Поля:

- **id** — идентификатор;
- **shop_id** — id магазина;
- **order_id_in_shop** — id заказа в магазине;
- **order_price** — стоимость заказа;
- **goods** — сериализованный массив товаров;
- **status** — статус заказа;
- **time_of_creation** — время создания заказа;
- **delivery_service_id** — id службы доставки;
- **tracking_code** — код отслеживания посылки.

### orders_callbacks

Коллбэки заказов.

Поля:

- **id** — идентификатор;
- **order_id** — id заказа;
- **is_callback_sent** —  отправлен ли коллбэк.

### orders_tokens

Токены заказов.

Поля:

- **token_hash** — хэш токена;
- **order_id** — id заказа;
- **client_phone** —  номер телефона клиента;
- **process_order_link** — url на страницу обработки заказа.

### organization_categories

Поля:

- **id** — идентификатор;
- **name** — имя.

### organizations

Поля:

- **id** — идентификатор;
- **type** — тип формы собственности;
- **vat** — НДС;
- **legal_name** — юридическое наименование;
- **tin** — ИНН;
- **cio** — КПП;
- **bin** — ОГРН;
- **is_licensed_activity** — подлежит ли деятельность лицензированию (1 — да, 0 — нет);
- **license_type** — тип лицензии;
- **license_number** — номер лицензии;
- **category_id** — id категории;
- **legal_addres** s — юридический адрес;
- **registration_address** — адрес регистрации;
- **fact_address** — фактический адрес;
- **bik** — БИК;
- **bank_name** — название банка;
- **correspondent_account** — корреспондентский счёт;
- **settlement_account** — расчётный счёт;
- **boss_full_name** — ФИО руководителя;
- **boss_position** — должность руководителя;
- **boss_basis_acts** — руководитель действует на основании;
- **boss_basis_acts_number** — серия и номер документа, на основании которого действует руководитель;
- **boss_basis_acts_issued_date** — дата выдачи документа, на основании которого действует руководитель;
- **boss_passport_number** — серия и номер паспорта руководителя;
- **boss_passport_issued_date** — дата выдачи паспорта руководителя;
- **boss_passport_division_code** — код подразделения, выдавшего паспорт руководителя;
- **boss_passport_issued_by** — кем выдан паспорт руководителя;
- **boss_birth_date** — дата рождения руководителя;
- **boss_birth_place** — место рождения руководителя;
- **email** — email;
- **phone** — телефон.

### partners

Партнёры. Выводятся в слайдере на главной странице сайта.

Поля:

- **id** — идентификатор;
- **name** — имя;
- **img_url** — ссылка на логотип;
- **url** — ссылка на сайт партнера;
- **orderby** — сортировка.

### remembered_clients

Данные, с которыми клиент попал на сайт.

Поля:

- **token_hash** — хэш токена;
- **phone** — телефон;
- **is_verified** — подтверждён ли телефон (1 — да, 0 — нет);
- **shop_id** — id магазина;
- **order_id** — id заказа в магазине;
- **order_price** — сумма заказа, руб.;
- **goods** — сериализованный массив товаров;
- **callback_url** — ссылка для возврата;
- **is_test_mode_enabled** — тестовый режим (1 — да, 0 — нет);
- **signature** — подпись;
- **is_loan_postponed** — отложенный ли кредит (1 — да, 0 — нет);
- **token_expires_at** — срок годности токена;
- **sms_code** — sms-код;
- **sms_code_wrong_inputs_number** — кол-во неверного ввода sms-кода;
- **sms_code_total_wrong_inputs_number** — общее кол-во неверного ввода sms-кода;
- **sms_code_sends_at** — время отправки последнего sms-кода;
- **sms_code_expires_at** — срок годности sms-кода.

### requests

Заявки на кредит.

Поля:

- **id** — идентификатор;
- **client_id** — id клиента;
- **shop_id** — id магазина;
- **order_id** — id заказа в системе Блисс;
- **is_test_mode_enabled** — тестовая ли заявка (1 — да, 0 — нет);
- **is_loan_postponed** — отложенный ли кредит (1 — да, 0 — нет);
- **status** — статус заявки;
- **approved_mfi_id** — id одобрившей ФО;
- **approved_mfi_response** — сериализованный массив с ответом;
- **time_start** — время подачи заявки;
- **time_finish** — время закрытия заявки.

### shops

Магазины, зарегистрированные в системе Bliss.

Поля:

- **id** — идентификатор;
- **type** — тип формы собственности (entrepreneur, llc);
- **company_name** — название магазина;
- **last_name** — фамилия директора;
- **first_name** — имя директора;
- **middle_name** — отчество директора;
- **tin** — ИНН;
- **dsc** — описание;
- **is_activated** — активный ли магазин (1 — да, 0 — нет);
- **secret_key** — секретный ключ.

### shops_admins

Администраторы магазинов.

Поля:

- **id** — идентификатор;
- **name** — имя;
- **email** — email и логин;
- **password_hash** — хэш пароля;
- **phone** — телефон;
- **role** — роль (admin, manager);
- **shop_id** — id магазина;
- **is_activated** — активный ли админ (1 — да, 0 — нет).

### shops_tokens

Токены магазинов.

Поля:

- **token_hash** — хэш токена;
- **token_expires_at** — время окончания жизни токена;
- **shop_id** — id магазина.

## Запуск проекта на локальном сервере

1. Добавьте в корень проекта файл db.cfg.php, если у вас его нет.
2. Для подключения к базе данных пропишите в файле константы:DB_HOST, DB_USER, DB_PASS, DB_NAME.
3. Определите в файле константу DIESEL_SERVER со значением local.
4. Если вы работаете на Windows определите в файле константу WINDOWS_PHP_EXE со значением абсолютного пути к исполняемому файлу PHP.
5. Установите в базу данных поочерёдно скрипты .sql/baseline/V1_0__baseline.sql и .sql/tests/V1_1550159650__add_test.sql.

## Запуск юнит-тестов PHPUnit

1. Откройте командную строку и перейдите в корень проекта.
2. Запустите команду composer test.
3. После завершения тестов будет создана папка coverage-report/, где будет находится отчёт о тестировании.

## Интеграция кнопки на сайт магазина

Необходимо установить форму на страницу завершения заказа. Форма должна направлять покупателя по адресу https://bliss24.ru/phone-number и передавать массив POST-параметров, как в примере ниже (тестовый режим включен только на  тестовом сервере по адресу: https://bliss.alef.im/phone-number).

Пример запроса:

```$json
POST /phone-number

{
  "shop_id": 1,
  "order_id": 1,
  "order_price": 69999,
  "callback_url": "https://example.com",
  "is_loan_postponed": 1,
  "goods": "[{\"name\":\"Apple iPhone X\",\"price\":6999,\"is_returnable\":1,\"quantity\":1}]",
  "is_test_mode_enabled": 0,
  "signature": "4f62c40b9b1cc1057f993096453d4e7b44e2de089fedfa0663e85e14429a3f7f"
}
```

- **shop_id** — идентификатор магазина в системе Блисс;
- **order_id** — идентификатор заказа на сайте;
- **order_price** — сумма заказа в рублях, на которую запрашивается кредит;
- **callback_url** — адрес страницы, на которую должен быть переадресован пользователь после успешного оформления заказа;
- **is_loan_postponed** — является ли кредит отложенным. Если да — 1, нет — 0;
- **goods** — JSON-представление массива товаров. Каждый товар — это массив со следующими параметрами:
- **name** — название;
- **price** — цена в рублях;
- **quantity** — количество;
- **is_returnable** — допустима ли операция возврата товара клиентом. В общем случае должно передаваться значение 1. Значение 0 обычно требуется для товара "доставка" или, например, при продаже лекарственных средств, которые согласно закону Российской Федерации недопустимы к возврату.
- **is_test_mode_enabled** — оформлять ли заявку в тестовом режиме. Если да — 1, нет — 0. То есть при значении 1 настоящие кредиты оформляться не будут. Используйте это значение для тестирования и отладки;
- **signature** — поле, введёное для безопасности. Содержит хэш, созданный с помощью алгоритма sha256 из строки, которая представляет собой конкатенацию параметров **shop_id** , **order_id** , **order_price** , **callback_url** , **is_loan_postponed** , **goods** , **is_test_mode_enabled** , **secret_key** ,  где:
- **secret_key** — секретный ключ магазина, выданный системой Блисс.

Параметры необходимо конкатенировать в указанном выше порядке.
Когда заявка будет обработана пользователь будет переадресован на выбранную магазином страницу. В качестве параметров Блисс передаст следующие данные:

```$json
{
  "order_id": 1,
  "request_id": 1,
  "status": "issued",
  "is_test_mode_enabled": 0,
  "signature": "12180f586b8202c94a334ccf2c4234c301579e3e12d990adce9155c2f6243435"
}
```

- **order_id** — идентификатор заказа в магазине;
- **request_id** — идентификатор заявки на кредит;
- **status** — статус кредита (issued — выдан; issued_postponed — выдан отложенный; declined — отклонён; canceled — отменён клиентом; manual — возникла проблема, ожидается решение);
- **is_test_mode_enabled** — оформлена ли заявка в тестовом режиме. Если да — 1, нет — 0;
- **signature** — поле, введёное для безопасности.

После получения данных необходимо произвести проверку. Для этого нужно создать с помощью алгоритма sha256 хэш из строки, которая представляет собой конкатенацию параметров **order_id** , **request_id** , **status** , **is_test_mode_enabled** , **secret_key** , где:

- **secret_key** — секретный ключ магазина, выданный системой Bliss.

**Если вас перекидывает на страницу /error при запросе, проверьте следующее:**

- значения в массиве товаров имеют кодировку utf-8;
- в стоимости заказа нет нулевой дробной части. Дело в том что при конкатенации значений нули могут потеряться, поэтому может не совпадать подпись, например, значение 2000.00 лучше привести к виду — 2000.

## Интеграция c новой ФО

1. В папке App/MFI/ создать новый файл с названием, соответствующим названию ФО.
2. Внутри файла создать класс, который наследуется от абстрактного класса App\MFI\MFI.
3. Внутри нового класса создать конструктор и три публичных метода:

- **start** — для запуска обработки заявки;
- **sendConfirmLoanCode** — для запроса отправки СМС с кодом для подтверждения кредита;
- **confirmLoanByClient** — для подтверждения кредита клиентом кодом из СМС.

1. Если ФО работает с отложенными кредитами, необходимо создать ещё один публичный метод — confirmLoanByShop. С помощью этого метода отправляется запрос ФО для активации кредита.
2. Все остальные методы в классе сделать приватными.
3. Для обращения к API ФО использовать библиотеку Guzzle. Она уже подключена к проекту. Для работы с SOAP использовать библиотеку Asynchronous SOAP client, которая также подключена к проекту и является абстракцией поверх библиотеки Guzzle. Пример работы по HTTP можно посмотреть в классе \App\MFI\Webbankir, по SOAP — в классе \App\MFI\Migcredit.
4. Обязательно предусмотреть в классе обращение к тестовому серверу ФО. Обычно у ФО есть такие сервера. Работа в тестовом режиме включается, если в конструктор класса передаётся массив $crediting_data, где параметр is_test_mode_enabled равен 1.
5. В папке tests/App/MFI/ создать файл для тестирования нового класса. Использовать библиотеку PHPUnit. Хорошая документация по библиотеке — https://phpunit.readthedocs.io/ru/latest/installation.html. Для тестов не использовать реальные запросы к API ФО, вместо этого необходимо создать имитацию веб-сервера. Пример можно посмотреть в файле test/App/MFI/WebbankirTest.php. Про имитацию веб-серверов смотреть здесь — https://phpunit.readthedocs.io/ru/latest/test-doubles.html#test-doubles-stubbing-and-mocking-web-services
6. В базе данных в таблице mfi создать запись для новой ФО.
7. В базе данных в таблице mfi_shop_cooperation создать связи с существующими магазинами.

## Интеграция с новой службой доставки

1. В папке App/Logistics/ создать новый файл с названием, соответствующим названию службы доставки.
2. Внутри файла создать класс, который реализует запросы к API службы доставки.
3. Для обращения к API логиста использовать библиотеку Guzzle. Она уже подключена к проекту. Для работы с SOAP использовать библиотеку Asynchronous SOAP client, которая также подключена к проекту и является абстракцией поверх библиотеки Guzzle. Пример работы по SOAP можно посмотреть в классе \App\Logistics\RussianPost.
4. В папке tests/App/Logistics/ создать файл для тестирования нового класса. Использовать библиотеку PHPUnit. Хорошая документация по библиотеке — https://phpunit.readthedocs.io/ru/latest/installation.html. Для тестов не использовать реальные запросы к API логиста, вместо этого необходимо создать имитацию веб-сервера. Пример можно посмотреть в файле test/App/Logistics/RussianPostTest.php. Про имитацию веб-серверов смотреть здесь — https://phpunit.readthedocs.io/ru/latest/test-doubles.html#test-doubles-stubbing-and-mocking-web-services
5. В базе данных в таблице delivery_services создать запись о новой службе доставки.
6. В файле cron/check_delivery.php создать блок кода для новой службы доставки по примеру предыдущих блоков.
