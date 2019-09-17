<?php
	require_once(__DIR__.'/../menu.php');
	require_once(__DIR__.'/sql.php');

	if($vk_id_field=="")
	{
		die("Необходимо в настройках проекта указать значение для параметра \"Поле для VK id\"");
	}

	$parts = explode('?', $_SERVER['REQUEST_URI'], 2);
	$redirect_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http"). "://".$_SERVER['HTTP_HOST'].$parts[0]; // Адрес сайта
	$redirect_uri = str_replace("index.php", "", $redirect_uri);

	if (isset($_GET['code']))
	{
		$result = false;
		$params = array
		(
			'client_id' => $vk_client_id,
			'client_secret' => $vk_secret,
			'code' => $_GET['code'],
			'redirect_uri' => $redirect_uri
		);


		$token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);
		// Array ( [access_token] =>  [expires_in] =>  [user_id] =>  [email] => )

		$email = $token['email'];

		if (isset($token['access_token']))
		{
			$params = array
			(
				'uids'         => $token['user_id'],
				'fields'       => ['photo_max_orig'],
				'access_token' => $token['access_token'],
				'v'            => '5.100'
			);

			$userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);

			if (isset($userInfo['response'][0]['id']))
			{
				$userInfo = $userInfo['response'][0];
				$result = true;
			}
		}

		if ($result == true)
		{

			$userInfo['email'] = $email;

			$user = q1("SELECT * FROM `{$mysql_user_table}` WHERE `{$vk_id_field}`=?", [$token['user_id']]);
			if((!isset($user['id']) || $user['id']=='') && $login_validation=='email')
			{
				$user = q1("SELECT * FROM `{$mysql_user_table}` WHERE `{$mysql_user_login}`=?", [$userInfo['email']]);
			}

			$user_id = $user['id'];

			if(!isset($user['id']) || $user['id']=='')
			{
				//новый юзер
				// создаем нового пользователя
				$fields = [];
				$fields["`{$mysql_user_login}`"] = $token['user_id'];
				$fields["`{$mysql_user_pass}`"] = md5("addfvtcwf".microtime(true))."111";
				$fields["`{$vk_id_field}`"] = $token['user_id'];

				if($soc_avatar_mysql_field!="")
				{
					$fields["`{$soc_avatar_mysql_field}`"] = $userInfo['photo_max_orig'];
				}
				if($soc_avatar_mysql_field!="")
				{
					$fields["`{$soc_name_mysql_field}`"] = $userInfo['first_name']." ".$userInfo['last_name'];
				}
				if($soc_email_mysql_field!="")
				{
					$fields["`{$soc_email_mysql_field}`"] = $email;
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

				$params[$vk_id_field] = $token['user_id'];

				if($soc_avatar_mysql_field!="" && (!isset($user[$soc_avatar_mysql_field]) || $user[$soc_avatar_mysql_field]==""))
				{
					$params["{$soc_avatar_mysql_field}"] = $userInfo['photo_max_orig'];
				}
				if($soc_avatar_mysql_field!="" && (!isset($user[$soc_name_mysql_field]) || $user[$soc_name_mysql_field]==""))
				{
					$params["{$soc_name_mysql_field}"] = $userInfo['first_name']." ".$userInfo['last_name'];
				}
				if($soc_email_mysql_field!="" && (!isset($user[$soc_email_mysql_field]) || $user[$soc_email_mysql_field]==""))
				{
					$params["{$soc_email_mysql_field}"] = $email;
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
