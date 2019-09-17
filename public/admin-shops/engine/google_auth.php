<?php
	require_once(__DIR__.'/engine/sql.php');
	
	$client_id = '727680034792-llduqebtvdobi469a11i4g1gd3jr1ekj.apps.googleusercontent.com'; // Client ID
	$client_secret = 'jMRndPUyQ5boLMFzdvC_wyvr'; // Client secret
	$redirect_uri = 'https://refhub.io/google_auth.php'; // Redirect URI
	
	$url = 'https://accounts.google.com/o/oauth2/auth';

	$params = array(
		'redirect_uri'  => $redirect_uri,
		'response_type' => 'code',
		'client_id'     => $client_id,
		'scope'         => 'email profile'
	);

	if (isset($_GET['code'])) {
		$result = false;

		$params = array(
			'client_id'     => $client_id,
			'client_secret' => $client_secret,
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
		
		
		if ($result == true) {
				
			$user = q1("SELECT * FROM `users` WHERE `email`=?", [$userInfo['email']]);
				
			if ($user['id'] == '') {
					
				$user = q1("SELECT * FROM `users` WHERE `google_id`=?", [$userInfo['sub']]);
					
				if ($user['id'] == '') {
						
					$bytes = random_bytes(5);
					$newpass = bin2hex($bytes);
						
					qi('INSERT INTO `users` (`name`, `email`, `pass`, `role`, `avatar`, `google_id`) VALUES (?, ?, ?, ?, ?, ?)', [$userInfo['name'], $userInfo['email'], md5($newpass), 'referee', $userInfo['picture'], $userInfo['sub']]);

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
			$_SESSION['core_address'] = __DIR__ . DIRECTORY_SEPARATOR ."engine";
			header("Location: index.php");
		}
	}
	die();
?>