<?php
	$menu = array (
  0 => 
  array (
    'name' => 'Организации',
    'icon' => 'building',
    'link' => 'organizations.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
    'group' => 'Партнёры',
    'menu_sort_order' => '0',
    'unauthorized_access' => '0',
  ),
  1 => 
  array (
    'name' => 'Все магазины',
    'icon' => 'store',
    'link' => 'index.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
    'group' => 'Партнёры',
    'menu_sort_order' => '10',
    'unauthorized_access' => '0',
  ),
  2 => 
  array (
    'name' => 'Сотрудничество МФО и магазинов',
    'icon' => 'hands-helping',
    'link' => 'mfi_shop_cooperation.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
    'group' => 'Партнёры',
    'menu_sort_order' => '20',
    'unauthorized_access' => '0',
  ),
  3 => 
  array (
    'name' => 'Администраторы магазинов',
    'icon' => 'user-tie',
    'link' => 'shops_admins.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
    'group' => 'Партнёры',
    'menu_sort_order' => '30',
    'unauthorized_access' => '0',
  ),
  4 => 
  array (
    'name' => 'МФО',
    'icon' => 'university',
    'link' => 'mfi.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
    'group' => 'Настройки',
    'menu_sort_order' => '40',
    'unauthorized_access' => '0',
  ),
  5 => 
  array (
    'name' => 'Службы доставки',
    'icon' => 'truck-loading',
    'link' => 'delivery_services.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
    'group' => 'Настройки',
    'menu_sort_order' => '50',
    'unauthorized_access' => '0',
  ),
  6 => 
  array (
    'name' => 'Партнёры',
    'icon' => 'handshake',
    'link' => 'partners.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
    'group' => 'Настройки',
    'menu_sort_order' => '70',
    'unauthorized_access' => '0',
  ),
  7 => 
  array (
    'name' => 'Плагины',
    'icon' => 'plug',
    'link' => 'integration_plugins.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
    'group' => 'Настройки',
    'menu_sort_order' => '80',
    'unauthorized_access' => '0',
  ),
  8 => 
  array (
    'name' => 'Администраторы Bliss',
    'icon' => 'user-ninja',
    'link' => 'admins.php',
    'roles' => 'super_admin',
    'visible' => 1,
    'group' => 'Настройки',
    'menu_sort_order' => '90',
    'unauthorized_access' => '0',
  ),
  9 => 
  array (
    'name' => 'Конфигурация PHP',
    'icon' => 'cog',
    'link' => 'phpinfo.php',
    'roles' => 'super_admin',
    'visible' => 1,
    'group' => 'Настройки',
    'menu_sort_order' => '100',
    'unauthorized_access' => '0',
  ),
  10 => 
  array (
    'name' => 'Бланки документов',
    'icon' => 'file-alt',
    'link' => 'document_templates.php',
    'roles' => 'super_admin',
    'visible' => 1,
    'group' => 'Настройки',
    'menu_sort_order' => '110',
    'unauthorized_access' => '0',
  ),
  11 => 
  array (
    'name' => 'Скачать логи',
    'icon' => 'file-download',
    'link' => 'logs.php',
    'roles' => 'super_admin',
    'visible' => 1,
    'group' => 'Настройки',
    'menu_sort_order' => '120',
    'unauthorized_access' => '0',
  ),
  12 => 
  array (
    'name' => 'Работа с базой данных',
    'icon' => 'database',
    'link' => 'database.php',
    'roles' => 'super_admin',
    'visible' => 1,
    'group' => 'Настройки',
    'menu_sort_order' => '125',
    'unauthorized_access' => '0',
  ),
  13 => 
  array (
    'name' => 'Заказы',
    'icon' => 'shopping-bag',
    'link' => 'orders.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
    'group' => 'Клиенты и кредиты',
    'menu_sort_order' => '130',
    'unauthorized_access' => '0',
  ),
  14 => 
  array (
    'name' => 'Заявки на кредит',
    'icon' => 'clipboard-list',
    'link' => 'requests.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
    'group' => 'Клиенты и кредиты',
    'menu_sort_order' => '140',
    'unauthorized_access' => '0',
  ),
  15 => 
  array (
    'name' => 'Ответы на заявки',
    'icon' => 'receipt',
    'link' => 'mfi_responses.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
    'group' => 'Клиенты и кредиты',
    'menu_sort_order' => '150',
    'unauthorized_access' => '0',
  ),
  16 => 
  array (
    'name' => 'Кредиты',
    'icon' => 'ruble-sign',
    'link' => 'loans.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
    'group' => 'Клиенты и кредиты',
    'menu_sort_order' => '160',
    'unauthorized_access' => '0',
  ),
  17 => 
  array (
    'name' => 'Клиенты',
    'icon' => 'users',
    'link' => 'clients.php',
    'roles' => 'super_admin, admin, manager',
    'visible' => 1,
    'group' => 'Клиенты и кредиты',
    'menu_sort_order' => '170',
    'unauthorized_access' => '0',
  ),
);
	$project_name = 'Bliss-admin'; 

	$project_wireframe = '0'; 

	$project_recovery = '0'; 

	$project_signup = 'none'; 

	$mysql_user_table = 'admins'; 

	$mysql_user_login = 'email'; 

	$mysql_user_pass = 'password_hash'; 

	$mysql_user_role = 'role'; 

	$pass_encryption = ''; 

	$mailman_key = ''; 

	$smsru_key = ''; 

	$auth_bg = ''; 

	$auth_bg_block = '/assets/front/img/admin-page-background.png'; 

	$logo = '/assets/front/img/bliss-logotype.png'; 

	$login_validation = 'none'; 

	$auth_page_caption = 'Контрольная панель'; 

	$auth_fb = 0; 

	$auth_vk = 0; 

	$auth_google = 0; 


	$vk_client_id = '7072037'; 

	$vk_secret = '5ja1q3YvVHwP37Ws2VrC'; 

	$vk_id_field = '';

	$fb_client_id = '450332879154467'; 

	$fb_secret = '68e95d5d43840d88e25fdae5280d45fe'; 

	$fb_id_field = ''; 

	$google_client_id = '658267997441-44rqgdrefnps6gt233v4oj8vnktc336e.apps.googleusercontent.com'; 

	$google_secret = 'yrjEZ4fWqXVE-4uHLD2DqlHF'; 

	$google_id_field = ''; 


	$soc_avatar_mysql_field= ''; 

	$soc_email_mysql_field= ''; 

	$soc_name_mysql_field= ''; 

	$role_after_social_auth= ''; 


	$project_tint = '#c6273a'; 

	$tinypng_key = ''; 

	$generation_date = '2019-08-09'; 
