<?php

include __DIR__."/functions.php";
include __DIR__."/sql.php";
include __DIR__."/auth.php";
include __DIR__."/../master-include.php";


function renderSignupOne($signup_error="")
{


	include __DIR__."/../menu.php";


	if(trim($auth_bg)=="")
	{
		$auth_bg = "//genesis.alef.im/default_bg_for_auth.png";
	}

	if(trim($auth_bg_block)=="")
	{
		$auth_bg_block = "//genesis.alef.im/default_bg_for_auth.png";
	}

	if(trim($auth_page_caption)=="" || !isset($auth_page_caption))
	{
		$auth_page_caption = "КОНТРОЛЬНАЯ ПАНЕЛЬ";
	}


	if($GLOBALS['unauthorized_access']==1)
	{
		$close_btn = '
		<div class="close" style="position: absolute;right: 20px;top: 20px;">
		  <a class="fa fa-times" href="?"></a>
		</div>';
	}
	else
	{
		$close_btn = "";
	}

	if($login_validation=='phone')
	{
		$login_html = '<input class="form-control not_error" type="tel" name="mail" id="username" placeholder="Телефон" pattern="^(\\s*)?(\\+)?([- _():=+]?\\d[- _():=+]?){10,14}(\\s*)?$" value="'.$_REQUEST['mail'].'">
		<div class="error-box" style="padding-left: 5px;">Введите корректный номер телефона</div>';
	}
	else if($login_validation=='email')
	{
		$login_html = '<input class="form-control not_error" type="text" name="mail" id="username" placeholder="Эл. почта" required="" minlength="3" value="'.$_REQUEST['mail'].'">
		<div class="error-box" style="padding-left: 5px;">Поле должно содержать не менее 3-х символов</div>';
	}
	else
	{
		if($project_signup == 'twostep')
		{
			die("При регистрации по логину, возможна только мговенная регистрация, а сейчас выбран вариант 'с подтверждением'.");
		}
		else
		{
			$login_html = '<input class="form-control not_error" type="text" name="mail" id="username" placeholder="Логин" required="" minlength="3" value="'.$_REQUEST['mail'].'">
			<div class="error-box" style="padding-left: 5px;">Поле должно содержать не менее 3-х символов</div>';
		}
	}




	$html = '<!DOCTYPE html>
	<html lang="ru">
	<head>
	<base href="..">
	<meta charset="utf-8">
	<meta name="viewport" content="initial-scale=1.0, width=device-width">
	<meta name="description" content="">
	<meta name="theme-color" content="#000">
	<meta name="msapplication-navbutton-color" content="#000">
	<meta name="apple-mobile-web-app-status-bar-style" content="#000">
	<link rel="shortcut icon" href="style/favicon.png" type="image/x-icon">
	<title>Регистрация</title>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
	<link rel="stylesheet" href="style/main.css">
	</head>
	<link rel="stylesheet" href="style/login.css">
	<body class="login" style="background-image:url('.$auth_bg.')">
	<div class="container login-wrap">
	  <div class="row">
		<div class="col-md-5 left-col" style="background-image:url('.$auth_bg_block.')">
		  <h1>'.$auth_page_caption.'</h1><a class="logo" href="http://l.alef.im/" target="_blank"><img src="http://l.alef.im/img/logo-white.svg" alt="logo"></a>
		</div>
		<div class="col-md-7 right-col">
		  <div class="form">
			<div class="logo-form">
			'.($logo!=""?'<img src="'.$logo.'" alt="logo">':"").'
			</div>
			<h3>Регистрация</h3>
			'.($signup_error!=""?'<p style="color: #EA2424; font-size: 12px; text-align: left; position: relative; top: -15px;">'.$signup_error.'</p>':'').'
			<form id="login" novalidate method="POST">
							<div class="input-group '.($signup_error!=""?"error2":"not_error2").'">
								<span class="input-group-addon">
									<i class="fas fa-user login-icon" aria-hidden="true"></i>
								</span>'.
								$login_html
								.
							'</div>

							<div class="input-group">
								<span class="input-group-addon">
									<i class="fas fa-lock login-icon" aria-hidden="true"></i>
								</span>
								<input class="not_error form-control" type="password" name="pass" id="password" placeholder="Пароль" required="" minlength="3" value="'.$_REQUEST['pass'].'">
								<div class="error-box" style="padding-left: 5px;">Поле должно содержать не менее 3-х символов</div>
							</div>


							<div class="input-group">
								<span class="input-group-addon">
									<i class="fas fa-lock login-icon" aria-hidden="true"></i>
								</span>
								<input class="form-control not_error" type="password" name="pass2" id="password2" placeholder="Подтверджение" required="" minlength="3" value="'.$_REQUEST['pass2'].'">
								<div class="error-box" style="padding-left: 5px;">Поле должно содержать не менее 3-х символов</div>
							</div>

							<div class="input-group">
								<button class="btn btn-block" id="submit"><i class="fas fa-refresh fa-spin" aria-hidden="true"></i>Зарегистрироваться</button>
							</div>
							<div class="signup-and-recover">
								<a href="index.php">Авторизация</a>
							</div>
			</form>
		  </div>'.
		  $close_btn.'
		</div>
	  </div>
	</div>
	</body>
	<script src="js/libs.js"></script>
	<script src="js/main.js"></script>

	</html>';
	return $html;
}


function renderSignupTwo()
{


	include __DIR__."/../menu.php";

	if($_REQUEST['pass'] != $_REQUEST['pass2'])
	{
		die(renderSignupOne("Пароль не совпадает с подтверждением"));
	}

	$users = q("SELECT * FROM `$mysql_user_table` WHERE `$mysql_user_login` = ?", [$_REQUEST['mail']]);
	if(count($users)>0)
	{
		die(renderSignupOne("Такой логин уже занят, выберите другой"));
	}

	if($project_signup=="onestep")
	{
		// МОМЕНТАЛЬНАЯ РЕГИСТРАЦИЯ
		qi("INSERT INTO `$mysql_user_table` (`$mysql_user_login`, `$mysql_user_pass`) VALUES (?, MD5(?))", [$_REQUEST['mail'], $_REQUEST['pass']]);


		$mail = $_REQUEST['mail'];
	    $pass = $_REQUEST['pass'];

	    $user = engineLoginAttempt($mail, $pass);
	    if($user !== FALSE)
	    {
			session_start();
	        $_SESSION['user'] = $user;
			$_SESSION['core_address'] = __DIR__;
	        header("Location: ../index.php");

			die();
	    }
		else
		{
			die("Произошла ошибка при регистрации нового пользователя");
		}

	}

	//РЕГИСТРАЦИЯ С ПОДТВЕРЖДЕНИЕМ
	qi("INSERT INTO `$mysql_user_table` (`$mysql_user_login`, `$mysql_user_pass`) VALUES (?, CONCAT(MD5(?), '_not_activated'))", [$_REQUEST['mail'], $_REQUEST['pass']]);

	$users = q("SELECT * FROM `$mysql_user_table` WHERE `$mysql_user_login` = ?", [$_REQUEST['mail']]);
	if(count($users)>0)
	{

		$cur_url = $url = "http".(!empty($_SERVER['HTTPS'])?"s":"")."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."?code=".md5($users[0][$mysql_user_login]."ajbgFyEaob#gAFp4fK1d".$users[0][$mysql_user_pass]);

		$signup_mail_text = "Кликните для завершения регистрации: ".$cur_url;
		$signup_mail_header = $project_name.": подтверждение электронной почты";
		$signup_sms_text = $cur_url;


		if(function_exists("lettersAndSMS"))
		{
			$lettersAndSMS = lettersAndSMS($user, $cur_url);

			$signup_mail_text = $lettersAndSMS['signup_mail_text'];
			$signup_mail_header = $lettersAndSMS['signup_mail_header'];
			$signup_sms_text = $lettersAndSMS['signup_sms_text'];
		}


		if($login_validation == "phone")
		{
			$time = sendSMSLimited($users[0][$mysql_user_login], $signup_sms_text);
		}
		else if($login_validation == "email")
		{
			$time = sendMailLimited($users[0][$mysql_user_login], $signup_mail_text, $signup_mail_header);
		}
		else
		{
			die("Двухступенчатая регистрация невозможна для проектов, где для авторизации используется логин. В настройках проекта смените тип авторизации на email или phone");
		}
	}

	if($time===true)
	{
		$time = 60;
	}


	if(trim($auth_bg)=="")
	{
		$auth_bg = "//genesis.alef.im/default_bg_for_auth.png";
	}

	if(trim($auth_bg_block)=="")
	{
		$auth_bg_block = "//genesis.alef.im/default_bg_for_auth.png";
	}

	if(trim($auth_page_caption)=="" || !isset($auth_page_caption))
	{
		$auth_page_caption = "КОНТРОЛЬНАЯ ПАНЕЛЬ";
	}


	if($GLOBALS['unauthorized_access']==1)
	{
		$close_btn = '
		<div class="close" style="position: absolute;right: 20px;top: 20px;">
		  <a class="fa fa-times" href="?"></a>
		</div>';
	}
	else
	{
		$close_btn = "";
	}

	if($login_validation=='phone')
	{
		$login_html = '<input class="form-control not_error" type="tel" name="mail" id="username" placeholder="Телефон" pattern="^(\\s*)?(\\+)?([- _():=+]?\\d[- _():=+]?){10,14}(\\s*)?$" value="'.$_REQUEST['mail'].'">
		<div class="error-box" style="padding-left: 5px;">Введите корректный номер телефона</div>';
	}
	else if($login_validation=='email')
	{
		$login_html = '<input class="form-control not_error" type="text" name="mail" id="username" placeholder="Эл. почта" required="" minlength="3" value="'.$_REQUEST['mail'].'">
		<div class="error-box" style="padding-left: 5px;">Поле должно содержать не менее 3-х символов</div>';
	}


	$cur_url = "http".(!empty($_SERVER['HTTPS'])?"s":"")."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];


	$html = '<!DOCTYPE html>
	<html lang="ru">
	<head>
	<base href="..">
	<meta charset="utf-8">
	<meta name="viewport" content="initial-scale=1.0, width=device-width">
	<meta name="description" content="">
	<meta name="theme-color" content="#000">
	<meta name="msapplication-navbutton-color" content="#000">
	<meta name="apple-mobile-web-app-status-bar-style" content="#000">
	<link rel="shortcut icon" href="style/favicon.png" type="image/x-icon">
	<title>Регистрация</title>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
	<link rel="stylesheet" href="style/main.css">
	</head>
	<link rel="stylesheet" href="style/login.css">
	<body class="login" style="background-image:url('.$auth_bg.')">
	<div class="container login-wrap">
	  <div class="row">
		<div class="col-md-5 left-col" style="background-image:url('.$auth_bg_block.')">
		  <h1>'.$auth_page_caption.'</h1><a class="logo" href="http://l.alef.im/" target="_blank"><img src="http://l.alef.im/img/logo-white.svg" alt="logo"></a>
		</div>
		<div class="col-md-7 right-col">
		  <div class="form">
			<div class="logo-form">
			'.($logo!=""?'<img src="'.$logo.'" alt="logo">':"").'
			</div>
			<h3 style="text-align:center;">Регистрация</h3>
			<form id="login" novalidate method="POST">
							<p stle="text-align:left;">Вам было отправлено сообщение со ссылкой. Перейдите по ней, чтобы активировать учетную запись.</p>
							<div class="signup-and-recover" style="display:block; text-align:center; margin-top:30px;">
								<a href="index.php">Авторизация</a>
								<a href="#" class="resend" data-countdown='.$time.' style="cursor:not-allowed;">Повторная отправка ('.$time.' сек)</a>
							</div>
			</form>
		  </div>'.
		  $close_btn.'
		</div>
	  </div>
	</div>
	</body>
	<script src="js/libs.js"></script>
	<script src="js/main.js"></script>
	<script>
		document.countdown_start = Math.floor(Date.now() / 1000);
		$(document).ready(function()
		{
			$(document).on("click", ".resend", function(e)
			{
				e.preventDefault();
				if($(".resend").css("cursor") == "not-allowed")
				{
					return;
				}
				else
				{
					$(".resend").ploading({action:"show"});
					$.ajax("'.$cur_url.'",
					{
						data:
						{
							resend: "'.$_REQUEST['mail'].'",
						},
						success: function(str)
						{
							$(".resend").ploading({action:"hide"});
							var json = JSON.parse(str);
							if(json.status!=0)
							{
								alert("При отправке сообщения произошла ошибка, повторите попытку позже");
							}
							else
							{
								$(".resend").attr("data-countdown", 60);
								document.countdown_start = Math.floor(Date.now() / 1000);
								document.cd_interval = setInterval(coundownTicker, 1000);
								coundownTicker();
								alert("Cообщение отправлено");
							}
						},
						error: function()
						{
							$(".resend").ploading({action:"hide"});
							alert("Нет связи с сервером, повторите попытку позже");
						}
					});
				}
			});

			document.cd_interval = setInterval(coundownTicker, 1000);
		});

		function coundownTicker()
		{
			var total = $(".resend").attr("data-countdown");

			var left = total - (Math.floor(Date.now() / 1000) - document.countdown_start);

			if(left>0)
			{
				$(".resend").text("Повторная отправка ("+left+" сек)").css("cursor", "not-allowed");
			}
			else
			{
				clearInterval(document.cd_interval);
				$(".resend").text("Повторная отправка").css("cursor", "auto");
			}
		}
	</script>

	</html>';
	return $html;
}


function signupCodeCheck($code)
{
	include __DIR__."/../menu.php";

	$users = q("SELECT * FROM `$mysql_user_table` WHERE MD5(CONCAT({$mysql_user_login}, 'ajbgFyEaob#gAFp4fK1d', {$mysql_user_pass})) = ?", [$_REQUEST['code']]);
	if(count($users)==0)
	{
		die("Неверная ссылка");
	}

	qi("UPDATE `$mysql_user_table` SET `{$mysql_user_pass}` = REPLACE({$mysql_user_pass}, '_not_activated', '') WHERE id=?", [$users[0]['id']]);

	$user = $users[0];

	session_start();
	$_SESSION['user'] = $user;
	$_SESSION['core_address'] = __DIR__;
	header("Location: ../index.php");
	die();



}

function resend($mail)
{
	include __DIR__."/../menu.php";
	$users = q("SELECT * FROM `$mysql_user_table` WHERE `$mysql_user_login` = ?", [$mail]);
	if(count($users)>0)
	{

		$cur_url = "http".(!empty($_SERVER['HTTPS'])?"s":"")."://".$_SERVER['SERVER_NAME'].explode('?', $_SERVER['REQUEST_URI'], 2)[0]."?code=".md5($users[0][$mysql_user_login]."ajbgFyEaob#gAFp4fK1d".$users[0][$mysql_user_pass]);
		

		$signup_mail_text = "Кликните для завершения регистрации: ".$cur_url;
		$signup_mail_header = $project_name.": подтверждение электронной почты";
		$signup_sms_text = $cur_url;

		if(function_exists("lettersAndSMS"))
		{
			$lettersAndSMS = lettersAndSMS($user, $cur_url);

			$signup_mail_text = $lettersAndSMS['signup_mail_text'];
			$signup_mail_header = $lettersAndSMS['signup_mail_header'];
			$signup_sms_text = $lettersAndSMS['signup_sms_text'];
		}


		if($login_validation == "phone")
		{
			$time = sendSMSLimited($users[0][$mysql_user_login], $signup_sms_text);
		}
		else if($login_validation == "email")
		{
			$time = sendMailLimited($users[0][$mysql_user_login], $signup_mail_text, $signup_mail_header);
		}
		else
		{
			return '{"status":1}';
		}
	}
	return '{"status":0, "address":"'.$users[0][$mysql_user_login].'"}';
}

if(isset($_REQUEST['resend']))
{
	echo resend($_REQUEST['resend']);
}
else if(isset($_REQUEST['code']))
{
	signupCodeCheck($_REQUEST['code']);
}
else if(isset($_REQUEST['mail']))
{
	echo renderSignupTwo();	//письмо отправлено
}
else
{
	echo renderSignupOne(); // укажите почту
}
