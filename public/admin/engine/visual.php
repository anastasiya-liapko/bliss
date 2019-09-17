<?php

function get_query()
{
		$arr = [];
		foreach ($_GET as $key => $value)
		{
			if(is_string($value))
			{
				$arr[] = urlencode($key)."=".urlencode($value);
			}
		}
		return implode("&", $arr);
}

function renderRadioGroup($input_name, $values_json_str, $table, $id, $cur_val)
{
	$html = '<div class="list-inline segmented-control" table="'.$table.'" pk="'.$id.'" name="'.$input_name.'">';

	foreach (json_decode($values_json_str, true) as $val)
	{
		$checked = "";
		$active = "";
		if($cur_val == $val['value'])
		{
			$checked='checked';
			$active = 'active';
		}

		$html.='
			<a href="#" class="list-group-item '.$active.'">
					'.$val['text'].'
					<input type="radio" '.$checked.' name="'.$input_name."_".$id.'" value="'.$val['value'].'"/>
			</a>';
		}
	$html .='</div>';
	return $html;
}


function renderEditRadioGroup($input_name, $values_json_str, $cur_val)
{
	$html = '<div class="list-inline segmented-control segmented-control-edit"  name="'.$input_name.'">';


	$first_loop = 1;
	foreach (json_decode($values_json_str, true) as $val)
	{

		$checked = "";
		$active = "";
		if($cur_val == $val['value'])
		{
			$checked='checked';
			$active = 'active';
		}

		if(!isset($cur_val) && $first_loop == 1)
		{
			$checked='checked';
			$active = 'active';
		}

		$html.='
			<a href="#" class="list-group-item '.$active.'">
					'.$val['text'].'
					<input type="radio" '.$checked.' name="'.$input_name.'" value="'.$val['value'].'"/>
			</a>';

			$first_loop = 0;
		}
	$html .='</div>';
	return $html;
}



function renderAuth() // окошко авторизации
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

    $alert = '';
    if(isset($_REQUEST['authorize']) && $_REQUEST['authorize']==1) // Если мы сюда попали с предыдущей попытки авторизации,
    {
        // сообщим пользователю, что он неудачник
        $alert = "
				<script>
					alert('В доступе отказано!')
				</script>";
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
		$login_html = '<input class="form-control not_error" type="text" name="mail" id="username" placeholder="Логин" required="" minlength="3" value="'.$_REQUEST['mail'].'">
		<div class="error-box" style="padding-left: 5px;">Поле должно содержать не менее 3-х символов</div>';
	}




	if($project_recovery != 1)
	{
		$recovery_hidden = 'style="display:none"';
	}
	else
	{
		$recovery_hidden = "";
	}

	if($project_signup != 'none')
	{
		$signup_hidden = '';
	}
	else
	{
		$signup_hidden = 'style="display:none"';
	}



	$parts = explode('?', $_SERVER['REQUEST_URI'], 2);
	$cur_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http"). "://".$_SERVER['HTTP_HOST'].$parts[0];
	$cur_link = str_replace("index.php", "", $cur_link);

    $html = '<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, width=device-width">
    <meta name="description" content="">
    <meta name="theme-color" content="#000">
    <meta name="msapplication-navbutton-color" content="#000">
    <meta name="apple-mobile-web-app-status-bar-style" content="#000">
    <link rel="shortcut icon" href="style/favicon.png" type="image/x-icon">
    <title>Вход</title>
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

            '.($logo!=""?'<div class="logo-form"><img src="'.$logo.'" alt="logo"></div>':"").'

			<h3>Авторизация</h3>
            <form id="login" novalidate method="POST">
							<div class="input-group">
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

              				<input type="hidden" name="authorize" value="1" />
							<div class="input-group">
								<button class="btn btn-block disabled'.($_REQUEST['authorize']==1?" error":"").'" id="submit"><i class="fas fa-refresh fa-spin" aria-hidden="true"></i>Войти</button>
								<div class="error-box">В доступе отказано</div>
							</div>
							<div class="signup-and-recover">
								<a href="engine/recovery.php" '.$recovery_hidden.'>Восстановить пароль</a>
								<a href="engine/signup.php" '.$signup_hidden.'>Зарегистрироваться</a>
							</div>
							<div class="social-links-container">'
								.($auth_vk==1?'<a href="http://oauth.vk.com/authorize?client_id='.$vk_client_id.'&amp;redirect_uri='.$cur_link.'engine/vk_auth.php&amp;scope=email&amp;response_type=code" class="btn blue-inline vk"><i class="fab fa-vk"></i></a>':'')
								.($auth_fb==1?'<a href="https://www.facebook.com/dialog/oauth?client_id='.$fb_client_id.'&amp;redirect_uri='.$cur_link.'engine/fb_auth.php&amp;scope=email&amp;response_type=code" class="btn blue-inline fb"><i class="fab fa-facebook-f"></i></a>':'')
								.($auth_google==1?'<a href="https://accounts.google.com/o/oauth2/auth?redirect_uri='.$cur_link.'engine/google_auth.php&amp;response_type=code&amp;client_id='.$google_client_id.'&amp;scope=email profile" class="btn blue-inline google"><img style="vertical-align:middle;" src="style/glogo.png" width="20" height="20"></a>':'')
							.'</div>
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

function renderMenu($active)
{
	include __DIR__."/../menu.php";
	if(count($menu)<=1)
	{
		return "";
	}

		$number_of_items=0;
    $highlight = array();
    $highlight[$active] = " class='active'";

    $groups = [];
    $html="";
    $n=0;

    foreach($menu as $m)
    {
    	$publish = 0;

		if($GLOBALS['unauthorized_access']==1 && $m['unauthorized_access']==0)
		{
			$n++;
			continue;
		}

		if($m['visible']==0)
		{
			$n++;
			continue;
		}

    	if(trim($mysql_user_role) == "")
    	{
	    	$publish = 1;
    	}
    	else
    	{

    		$roles = [];
    		foreach(explode(",", $m['roles']) as $r)
    		{
    			$roles[] = trim($r);
    		}


    		if(in_array($_SESSION['user'][$mysql_user_role], $roles))
    		{
    			$publish = 1;
    		}
    	}

    	if($publish==1)
    	{
            $number_of_items++;
            $m['name'] = stripslashes($m['name']);

			$item = '<i class="fas fa-'.$m['icon'].'"></i> '.$m['name'];
			if(function_exists("processMenuItem"))
			{
				$item = processMenuItem($item, $m);
			}
			$item = '<li'.$highlight[$n].'><a href="'.$m['link'].'">'.$item.'</a></li>';

            if(isset($m['group']) && $m['group']!=''){
                if(!isset($groups[$m['group']])){
                    $groups[$m['group']]=array('content' => "",'status' => 'style="display:none;"','caret'=>'');
                    $html .= "{{SUBMENUGROUP:{$m['group']}}}";
                }
                $groups[$m['group']]['content'] .= $item;
                if($active==$n)
				{
                    $groups[$m['group']]['status']='';
                    $groups[$m['group']]['caret']='open-caret';
                }
            }else{
                $html .= $item;
            }
		}
		$n++;
	}
	if($number_of_items<=1)
	{
		return "";
	}

    foreach ($groups as $key => $item) {
        $sub_menu_group = '<li><a class="submenu-caption"><i class="fas fa-caret-down '.$item['caret'].'"></i> '.$key.'</a></li>
        <ul class="submenu" '.$item['status'].'>'.$item['content'].'</ul>';
        $html=str_replace("{{SUBMENUGROUP:".$key."}}", $sub_menu_group, $html);
    }

	if(function_exists("processMenu"))
	{
	  $html = processMenu($html);
	}

	$html = '
						<div class="col-xl-2">
	            <nav class="left-sidebar">
	              <button class="btn menu-toggler"><span class="top"></span><span class="mid"></span><span class="bot"></span></button>
	              <ul class="nav-list">
	                '.$html.'
	              </ul><a class="logo" href="http://l.alef.im/" target="_blank"><img src="style/logo-alef-dev.svg" alt="logo"></a>
	            </nav>
	          </div>';


    return $html;
}


function masterRender($title, $content, $activeMenuItem) // Вставляет контент в общую страницу — всякий заголовки, менюшки и т.д.
{
		include __DIR__."/../menu.php";
		$title = addslashes($title);
		eval("\$title = \"$title\";");



			$js_request = json_encode($_REQUEST, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        /* Массив вывода сообщений и ошибок пользователюя */

        $messages = "";

        if(isset($_SESSION['messages']) && is_array($_SESSION['messages'])){
            foreach($_SESSION['messages'] as $msg){
                $messages .= "<div class=\"alert alert-{$msg['class']} alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>{$msg['text']}</div>";
            }
            unset($_SESSION['messages']);
        }

				$wdth = 10;
				if(renderMenu($activeMenuItem)==="")
				{
					$wdth=12;
				}

        $menu = renderMenu($activeMenuItem);
				$logo_html =  $logo!=""?'<img class="logo" src="'.$logo.'" alt="logo">':"{$project_name}";
				$maincss="main.css";
				if($project_wireframe==1)
				{
					$maincss = "_main.css";
				}

		if($GLOBALS['unauthorized_access']!=1)
		{
			$auth_button = '<a href="engine/exit.php" class="btn logout">
					<span class="name">Выход</span>
					<i class="fas fa-power-off"></i>
				</a>';
		}
		else
		{
			$auth_button = '<a href="?genesis_show_auth=1" class="btn">
					<span class="name">Вход</span>
					<i class="fas fa-sign-in-alt"></i>
				</a>';
		}

        $html = <<<HTML
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, width=device-width">
    <meta name="description" content="">
    <meta name="theme-color" content="#000">
    <meta name="msapplication-navbutton-color" content="#000">
    <meta name="apple-mobile-web-app-status-bar-style" content="#000">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
    <title>{$title}</title>


		<link rel="stylesheet" href="style/{$maincss}">


			<script>
				var JS_REQUEST = $js_request;
			</script>
  </head>


	<body class="concept">
  	<div id="my_notifications">{$messages}</div>

		<!-- Modal -->
		<div class="modal fade" id="edit_modal" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn cancel" data-dismiss="modal" aria-label="Close">Закрыть</button>
						<button type="button" class="btn blue-inline" id="edit_save">Сохранить</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="create_modal" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn cancel" data-dismiss="modal" aria-label="Close">Закрыть</button>
						<button type="button" class="btn blue-inline" id="create_save">Создать</button>
					</div>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row">
				<header><a class="category text-truncate" href="index.php">$logo_html</a>
					<div class="logout-wrap">
						$auth_button
					</div>
				</header>
			</div>
		</div>

		<main>
      <div class="container-fluid">
        <div class="row">
					{$menu}
          <div class="col-xl-{$wdth} content-wrap">
		 	<div class="big-icon"></div>
            <section class="content">
              {$content}
            </section>
          </div>
        </div>
      </div>
    </main>



		<script src="js/libs.js"></script>
		<link href='//cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css' rel='stylesheet'>

		<script>Dropzone.autoDiscover = false;</script>
		<script src="//cdn.ckeditor.com/4.9.0/standard-all/ckeditor.js"></script>
		<script src="js/main.js"></script>



</body>
</html>
HTML;
        return $html;

}

?>
