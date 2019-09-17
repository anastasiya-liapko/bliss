<?php
	require_once(__DIR__.'/../menu.php');
	require_once(__DIR__.'/sql.php');

	if($fb_id_field=="")
	{
		die("Необходимо в настройках проекта указать значение для параметра \"Поле для FB id\"");
	}

	$parts = explode('?', $_SERVER['REQUEST_URI'], 2);
	$redirect_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http"). "://".$_SERVER['HTTP_HOST'].$parts[0]; // Адрес сайта
	$redirect_uri = str_replace("index.php", "", $redirect_uri);

		if (isset($_GET['code']))
		{
			$result = false;

			$params = urldecode(http_build_query(array
			(

				'client_id'     => $fb_client_id,
				'redirect_uri'  => $redirect_uri,
				'client_secret' => $fb_secret,
				'code'          => $_GET['code']
			)));

			$token = json_decode(file_get_contents("https://graph.facebook.com/oauth/access_token?".$params), true);

			if (isset($token["access_token"]))
			{

				$params = urldecode(http_build_query(array
				(

					'access_token' => $token['access_token'],
					'fields'       => 'id,name,email,picture.width(478).height(478)'
				)));

				$userInfo = json_decode(file_get_contents("https://graph.facebook.com/me?".$params), true);

				if (isset($userInfo["id"]))
				{
					$result = true;
				}
			}

			if ($result == true)
			{				

				$user = q1("SELECT * FROM `{$mysql_user_table}` WHERE `{$fb_id_field}`=?", [$userInfo['id']]);
				if((!isset($user['id']) || $user['id']=='') && $login_validation=='email' && isset($userInfo['email']) && $userInfo['email']!="")
				{
					$user = q1("SELECT * FROM `{$mysql_user_table}` WHERE `{$mysql_user_login}`=?", [$userInfo['email']]);
				}

				$user_id = $user['id'];

				if(!isset($user['id']) || $user['id']=='')
				{
					//новый юзер
					// создаем нового пользователя
					$fields = [];
					$fields["`{$mysql_user_login}`"] = $userInfo['id'];
					$fields["`{$mysql_user_pass}`"] = md5("addfvtcwf".microtime(true))."111";
					$fields["`{$fb_id_field}`"] = $userInfo['id'];

					if($soc_avatar_mysql_field!="")
					{
						$fields["`{$soc_avatar_mysql_field}`"] = $userInfo['picture']['data']['url'];
					}
					if($soc_avatar_mysql_field!="")
					{
						$fields["`{$soc_name_mysql_field}`"] = $userInfo['name'];
					}
					if($soc_email_mysql_field!="")
					{
						$fields["`{$soc_email_mysql_field}`"] = $userInfo['email'];
					}


					if($mysql_user_role!="")
					{
						$fields["`{$mysql_user_role}`"] = $role_after_social_auth;
					}

					qi("INSERT INTO `{$mysql_user_table}` (".implode(', ', array_keys($fields)).") VALUES (".implode(', ', array_map(function($i){return "?";}, $fields)).")", array_values($fields));
					$user_id = qInsertId();
				}
				else
				{
					$params = [];

					$user = q1("SELECT * FROM `{$mysql_user_table}` WHERE id=?", [$user_id]);

					$params[$fb_id_field] = $userInfo['id'];

					if($soc_avatar_mysql_field!="" && (!isset($user[$soc_avatar_mysql_field]) || $user[$soc_avatar_mysql_field]==""))
					{
						$params["{$soc_avatar_mysql_field}"] = $userInfo['picture']['data']['url'];
					}
					if($soc_avatar_mysql_field!="" && (!isset($user[$soc_name_mysql_field]) || $user[$soc_name_mysql_field]==""))
					{
						$params["{$soc_name_mysql_field}"] = $userInfo['name'];
					}
					if($soc_email_mysql_field!="" && (!isset($user[$soc_email_mysql_field]) || $user[$soc_email_mysql_field]==""))
					{
						$params["{$soc_email_mysql_field}"] = $userInfo['email'];
					}

					qi("UPDATE `{$mysql_user_table}` SET ".implode(", ", array_map(function($k){return "`{$k}`=?";}, array_keys($params)))." WHERE id = ?", array_values(array_merge($params, [$user_id])));

					//существующий юзер
					// апдейтим все поля
				}

				$user = q1("SELECT * FROM `{$mysql_user_table}` WHERE id=?", [$user_id]);



				session_start();
				$_SESSION['user'] = $user;
				$_SESSION['core_address'] = __DIR__;
				header("Location: ../");

			}



		}

	die();
?>
