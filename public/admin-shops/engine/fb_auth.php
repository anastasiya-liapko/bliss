<?php
	require_once(__DIR__.'/engine/sql.php');
	
	$client_id = '342197136477263'; // ID приложения
	$client_secret = '54a8785b7159592bef2d2d655c0229d6'; // Защищённый ключ
	$redirect_uri = 'https://refhub.io/fb_auth.php'; // Адрес сайта
	
		if (isset($_GET['code'])) {
			$result = false;
			
			$params = urldecode(http_build_query(array(

				'client_id'     => $client_id,
				'redirect_uri'  => $redirect_uri,
				'client_secret' => $client_secret,
				'code'          => $_GET['code']
			)));

			$token = json_decode(file_get_contents("https://graph.facebook.com/oauth/access_token?".$params), true);

			if (isset($token["access_token"])) {

				$params = urldecode(http_build_query(array(

					'access_token' => $token['access_token'],
					'fields'       => 'id,name,email,picture.width(478).height(478)'
				)));
				
				$userInfo = json_decode(file_get_contents("https://graph.facebook.com/me?".$params), true);

				if (isset($userInfo["id"])) {

					$result = true;

				}
			}
			
			if ($result == true) {
				
				$user = q1("SELECT * FROM `users` WHERE `email`=?", [$userInfo['email']]);
				
				if ($user['id'] == '') {
					
					$user = q1("SELECT * FROM `users` WHERE `fb_id`=?", [$userInfo['id']]);
					
					if ($user['id'] == '') {
						
						$bytes = random_bytes(5);
						$newpass = bin2hex($bytes);
						
						qi('INSERT INTO `users` (`name`, `email`, `pass`, `role`, `avatar`, `fb_id`) VALUES (?, ?, ?, ?, ?, ?)', [$userInfo['name'], $userInfo['email'], md5($newpass), 'referee', $userInfo['picture']['data']['url'], $userInfo['id']]);
						
						$msg_admin = "<p>"."Добрый день! "."</p><p>На проекте зарегистрирован новый рекомендатель</p><p>".$userInfo['email']."</p></br>"."С наилучшими пожеланиями, "."</br>".$_SERVER['SERVER_NAME'];
						//отправка письма админу при регистрации
						sendEmail("a@refhub.io", "Новый пользователь", $msg);
						
						$user = q1("SELECT * FROM `users` WHERE `id`=?", [qInsertId()]);
					} else {
						if ((isset($userInfo['email']))&&($userInfo['email'] != '')&&(filter_var($userInfo['email'], FILTER_VALIDATE_EMAIL))) {
							qi('UPDATE `users` SET `email`=? WHERE `id`=?', [$userInfo['email'], $user['id']]);
						}
					}
				}
				
				session_start();
				$_SESSION['user'] = $user;
				$_SESSION['core_address'] = __DIR__."/engine";
				header("Location: index.php#");
			}
			

			
		}
	
	die();
?>