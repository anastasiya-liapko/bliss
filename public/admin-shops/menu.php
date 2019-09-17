<?php
	$menu = array (
  0 => 
  array (
    'name' => 'Входящие заказы',
    'icon' => 'clock',
    'link' => 'index.php',
    'roles' => 'super_admin, admin',
    'visible' => 1,
    'group' => '',
    'menu_sort_order' => '10',
    'unauthorized_access' => '0',
  ),
  1 => 
  array (
    'name' => 'Ожидают доставки',
    'icon' => 'truck',
    'link' => 'delivering-orders.php',
    'roles' => 'super_admin, admin',
    'visible' => 1,
    'group' => '',
    'menu_sort_order' => '10',
    'unauthorized_access' => '0',
  ),
  2 => 
  array (
    'name' => 'Оплаченные заказы',
    'icon' => 'ruble-sign',
    'link' => 'issued-orders.php',
    'roles' => 'super_admin, admin',
    'visible' => 1,
    'group' => '',
    'menu_sort_order' => '10',
    'unauthorized_access' => '0',
  ),
  3 => 
  array (
    'name' => 'Отмененные заказы',
    'icon' => 'ban',
    'link' => 'failed-orders.php',
    'roles' => 'super_admin, admin',
    'visible' => 1,
    'group' => '',
    'menu_sort_order' => '10',
    'unauthorized_access' => '0',
  ),
);
	$project_name = 'Bliss-shop-admin'; 

	$project_wireframe = '0'; 

	$project_recovery = '0'; 

	$project_signup = 'none'; 

	$mysql_user_table = 'shops_admins'; 

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

	$generation_date = '2019-08-07'; 
