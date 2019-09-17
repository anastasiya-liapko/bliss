<?php
require_once(__DIR__.'/../menu.php');
require_once(__DIR__.'/sql.php');

if($google_id_field=="")
{
	die("Необходимо в настройках проекта указать значение для параметра \"Поле для Google id\"");
}

$parts = explode('?', $_SERVER['REQUEST_URI'], 2);
$redirect_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http"). "://".$_SERVER['HTTP_HOST'].$parts[0]; // Адрес сайта
$redirect_uri = str_replace("index.php", "", $redirect_uri);


	if (isset($_GET['code'])) {
		$result = false;

		$params = array(
			'client_id'     => $google_client_id,
			'client_secret' => $google_secret,
			'redirect_uri'  => $redirect_uri,
			'grant_type'    => 'authorization_code',
			'code'          => $_GET['code']
		);

		$url = 'https://accounts.google.com/o/oauth2/token';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($curl);
		curl_close($curl);
		$tokenInfo = json_decode($result, true);

		if (isset($tokenInfo['access_token'])) {
			$params['access_token'] = $tokenInfo['access_token'];

			$userInfo = json_decode(file_get_contents('https://www.googleapis.com/oauth2/v3/userinfo' . '?' . urldecode(http_build_query($params))), true);

			if (isset($userInfo['sub'])) {
				$result = true;
			}
		}


		if ($result == true)
		{


			$user = q1("SELECT * FROM `{$mysql_user_table}` WHERE `{$google_id_field}`=?", [$userInfo['sub']]);
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
				$fields["`{$mysql_user_login}`"] = $userInfo['sub'];
				$fields["`{$mysql_user_pass}`"] = md5("addfvtcwf".microtime(true))."111";
				$fields["`{$google_id_field}`"] = $userInfo['sub'];

				if($soc_avatar_mysql_field!="")
				{
					$fields["`{$soc_avatar_mysql_field}`"] = $userInfo['picture'];
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

				$params[$google_id_field] = $userInfo['sub'];

				if($soc_avatar_mysql_field!="" && (!isset($user[$soc_avatar_mysql_field]) || $user[$soc_avatar_mysql_field]==""))
				{
					$params["{$soc_avatar_mysql_field}"] = $userInfo['picture'];
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

			// $user = q1("SELECT * FROM `users` WHERE `email`=?", [$userInfo['email']]);
			//
			// if ($user['id'] == '') {
			//
			// 	$user = q1("SELECT * FROM `users` WHERE `google_id`=?", [$userInfo['sub']]);
			//
			// 	if ($user['id'] == '') {
			//
			// 		$bytes = random_bytes(5);
			// 		$newpass = bin2hex($bytes);
			//
			// 		qi('INSERT INTO `users` (`name`, `email`, `pass`, `role`, `avatar`, `google_id`) VALUES (?, ?, ?, ?, ?, ?)', [$userInfo['name'], $userInfo['email'], md5($newpass), 'referee', $userInfo['picture'], $userInfo['sub']]);
			//
			// 		$msg_admin = "<p>"."Добрый день! "."</p><p>На проекте зарегистрирован новый рекомендатель</p><p>".$userInfo['email']."</p></br>"."С наилучшими пожеланиями, "."</br>".$_SERVER['SERVER_NAME'];
			// 		//отправка письма админу при регистрации
			// 		sendEmail("a@refhub.io", "Новый пользователь", $msg);
			//
			// 		$user = q1("SELECT * FROM `users` WHERE `id`=?", [qInsertId()]);
			// 	} else {
			// 		if ((isset($userInfo['email']))&&($userInfo['email'] != '')&&(filter_var($userInfo['email'], FILTER_VALIDATE_EMAIL))) {
			// 			qi('UPDATE `users` SET `email`=? WHERE `id`=?', [$userInfo['email'], $user['id']]);
			// 		}
			// 	}
			// }
			//
			// session_start();
			// $_SESSION['user'] = $user;
			// $_SESSION['core_address'] = __DIR__ . DIRECTORY_SEPARATOR ."engine";
			// header("Location: index.php");
		}
	}
	die();
?>
