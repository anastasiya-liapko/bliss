<?php
	include "master-include.php";
	include "engine/core.php";
	if(!in_array($_SESSION['user']['role'], array (
  0 => 'super_admin',
  1 => 'admin',
  2 => 'manager',
)) || $GLOBALS['unauthorized_access']==1){
			include "menu.php";
			foreach($menu as $m)
			{
				$rls = [];
				foreach(explode(",", $m["roles"]) as $r)
				{
					$rls[] = trim($r);
				}
				if(in_array($_SESSION["user"]["role"], $rls) || $m["unauthorized_access"]==1)
				{
					header("Location: {$m['link']}");
					die("");
				}
			}

			die("У вас нет доступа");
		}


	class GLOBAL_STORAGE
	{
	   static $parent_object;
	}
	

	$action = $_REQUEST['action'];
	$actions = [];

	// обработка массива данных, которые будут отбражаться на этой странице
function processData($data)
{
	return $data;
}

function processCreateModalHTML($html)
{
	return $html;
}

// изменение. Если вернуть false то изменение не произойдет, но никакой ошибки не будет показано. Если хочешь показать ошибку — покажи ее сам при помощи buildMsg();
function allowUpdate()
{
 $_REQUEST['phone'] = preg_replace( '/[-)+(\s]/', '', $_REQUEST['phone'] );
 
	return true;
}

function allowInsert()
{
 $_REQUEST['phone'] = preg_replace( '/[-)+(\s]/', '', $_REQUEST['phone'] );
 
	return true;
}

//этот код будет вставлен в конец файла. можешь объявлять в нем свои функции и тд

	define("RPP", 50); //кол-во строк на странице

	function array2csv($array)
	{
	   if (count($array) == 0)
	   {
	     return null;
	   }
	   ob_start();
	   $df = fopen("php://output", 'w');
	   fprintf($df, chr(0xEF).chr(0xBB).chr(0xBF));
	   fputcsv($df, array_keys($array[0]));
	   foreach ($array as $row)
	   {
	      fputcsv($df, array_values($row));
	   }
	   fclose($df);
	   return ob_get_clean();
	}

	function download_send_headers($filename)
	{
	    // disable caching
	    $now = gmdate("D, d M Y H:i:s");
	    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
	    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
	    header("Last-Modified: {$now} GMT");

	    // force download
	    header("Content-Type: application/force-download");
	    header("Content-Type: application/octet-stream");
	    header("Content-Type: application/download");

	    // disposition / encoding on response body
	    header("Content-Disposition: attachment;filename={$filename}");
	    header("Content-Transfer-Encoding: binary");
	}

	$actions['csv'] = function()
	{
		if(function_exists("allowCSV"))
		{
			if(!allowCSV())
			{
				die("У вас нет прав на экспорт CSV");
			}
		}
		download_send_headers("data_export_" . date("Y-m-d") . ".csv");
		$data = get_data(true)[0];

		if(function_exists("processCSV"))
		{
			$data = processCSV($data);
		}

		echo array2csv($data);
		die();
	};

	$actions[''] = function()
	{
			
   		$role_values = '[
	{
		"text": "Администратор",
		"value": "admin"
	},
	{
		"text": "Менеджер",
		"value": "manager"
	}
]';
		$role_values_text = "";
		foreach(json_decode($role_values, true) as $opt)
		{
			$role_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		
$shop_id_values = json_encode(q("SELECT name as text, id as value FROM shops", []));
			$shop_id_values_text = "";
				foreach(json_decode($shop_id_values, true) as $opt)
				{
				  $shop_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
				}

		list($items, $pagination, $cnt) = get_data();

		$sort_order[$_REQUEST['sort_by']] = $_REQUEST['sort_order'];

$next_order['id']='asc';
$next_order['name']='asc';
$next_order['email']='asc';
$next_order['password_hash']='asc';
$next_order['phone']='asc';
$next_order['role']='asc';
$next_order['shop_id']='asc';
$next_order['is_activated']='asc';

		if($_REQUEST['sort_order']=='asc')
		{
			$sort_icon[$_REQUEST['sort_by']] = '<span class="fa fa-sort-alpha-up" style="margin-left:5px;"></span>';
			$next_order[$_REQUEST['sort_by']] = 'desc';
		}
		else if($_REQUEST['sort_order']=='desc')
		{
			$sort_icon[$_REQUEST['sort_by']] = '<span class="fa fa-sort-alpha-down" style="margin-left:5px;"></span>';
			$next_order[$_REQUEST['sort_by']] = '';
		}
		else if($_REQUEST['sort_order']=='')
		{
			$next_order[$_REQUEST['sort_by']] = 'asc';
		}
		$filter_caption = "";
		$show = '
		<script>
				window.onload = function ()
				{
					$(\'.big-icon\').html(\'<i class="fas fa-user-tie"></i>\');
				};


		</script>
		
		<style>
			html body.concept, html body.concept header, body.concept .table
			{
				background-color:;
				color:;
			}

			.genesis-text-color
			{
				color:;
			}

			#tableMain div.genesis-item:nth-child(even), #tableMain div.genesis-item:nth-child(even) div.genesis-item-property
			{
  				background-color:  !important;
			}

			body.concept .page-link,
			body.concept .page-link:hover{
				color: ;
			}

			html body.concept, html body.concept header, body.concept .table
			{
				color: ;
			}

		</style>
		<!-- Modal -->
		<div class="modal fade" id="csv_create_modal" role="dialog" aria-labelledby="csvCreateModal" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<form method="POST">
					<input type="hidden" name="action" value="csv_create_execute">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Массовое добавление записей</h5>
						</div>
						<div class="modal-body">
							<small>Вставьте сюда новые записи. Каждая запись на новой строчке: <b class="csv-create-format">Имя, Email, Пароль, Телефон, Роль , ID магазина, Активирован</b></small>
							<textarea name="csv"></textarea>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn cancel" data-dismiss="modal" aria-label="Close">Закрыть</button>
							<button type="submit" class="btn blue-inline" id="csv_create_execute">Сохранить</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="content-header">
			<div class="btn-wrap">
				<h2><a href="#" class="back-btn"><span class="fa fa-arrow-circle-left"></span></a> '."Администраторы магазинов".' </h2>
				<button class="btn blue-inline add_button" data-toggle="modal" data-target="#modal-main">ДОБАВИТЬ</button>
				<p class="small res-cnt">Кол-во результатов: <span class="cnt-number-span">'.$cnt.'</span></p>
			</div>
			
			<form class="navbar-form search-form" role="search">
				<div class="input-group">
					<input type="text" class="form-control" placeholder="Поиск" name="srch-term" id="srch-term" value="'.$_REQUEST['srch-term'].'">
					<button class="input-group-addon"><i class="fa fa-search"></i></button>
				</div>
			</form>
			
		</div>
		<div>'.
		""
		.'</div>';

		$show .= filter_divs();

		$show.='
		
		<div class="table-wrap" data-fl-scrolls>';
		$table='
			<div class="data-container genesis-presentation-table  table-clickable" id="tableMain">
			<div class="genesis-header">
				<div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=id&sort_order='. ($next_order['id']) .'\' class=\'sort\' column=\'id\' sort_order=\''.$sort_order['id'].'\'>ID'. $sort_icon['id'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="id_filter_from" placeholder="От"/>
							<span class="input-group-btn" style="width:0px;"></span>
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="id_filter_to" placeholder="До"/>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=name&sort_order='. ($next_order['name']) .'\' class=\'sort\' column=\'name\' sort_order=\''.$sort_order['name'].'\'>Имя'. $sort_icon['name'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="text" class="form-control filter-text" name="name_filter">
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=email&sort_order='. ($next_order['email']) .'\' class=\'sort\' column=\'email\' sort_order=\''.$sort_order['email'].'\'>Email'. $sort_icon['email'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="text" class="form-control filter-text" name="email_filter">
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>

			<div class="genesis-header-property">
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=password_hash&sort_order='. ($next_order['password_hash']) .'\' class=\'sort\' column=\'password_hash\' sort_order=\''.$sort_order['password_hash'].'\'>Пароль'. $sort_icon['password_hash'].'</a>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=phone&sort_order='. ($next_order['phone']) .'\' class=\'sort\' column=\'phone\' sort_order=\''.$sort_order['phone'].'\'>Телефон'. $sort_icon['phone'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="text" class="form-control filter-text" name="phone_filter">
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=role&sort_order='. ($next_order['role']) .'\' class=\'sort\' column=\'role\' sort_order=\''.$sort_order['role'].'\'>Роль '. $sort_icon['role'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<select class="form-control filter-select" name="role_filter">


							'.str_replace(chr(39), '&#39;', $role_values_text).'


							</select>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=shop_id&sort_order='. ($next_order['shop_id']) .'\' class=\'sort\' column=\'shop_id\' sort_order=\''.$sort_order['shop_id'].'\'>Магазин'. $sort_icon['shop_id'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<select class="form-control filter-select" name="shop_id_filter">


							'.str_replace(chr(39), '&#39;', $shop_id_values_text).'


							</select>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=is_activated&sort_order='. ($next_order['is_activated']) .'\' class=\'sort\' column=\'is_activated\' sort_order=\''.$sort_order['is_activated'].'\'>Активен'. $sort_icon['is_activated'].'</a>
					
      <span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group text-center">
              <input type="checkbox" class="filter-checkbox" name="is_activated_filter">
              <span class="input-group-btn">
                <button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
              </span>
            </div>\'>
      </span>
				</nobr>
			</div>
					<div class="genesis-header-property"></div>
				</div>
		</div>
		<div class="genesis-tbody">';


		if(count($items) > 0)
		{
			$agregate = get_agregate();
			foreach($items as $item)
			{
				$master = ($item['master'] == 1) ? 'Да' : 'Нет';
				
				$tr = "

				<div class='genesis-item' pk='{$item['id']}'>
					
					".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=id&sort_order='. ($next_order['id']) .'\' class=\'sort\' column=\'id\' sort_order=\''.$sort_order['id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-from\" name=\"id_filter_from\" placeholder=\"От\"/>
							<span class=\"input-group-btn\" style=\"width:0px;\"></span>
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-to\" name=\"id_filter_to\" placeholder=\"До\"/>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>ID:</span>
		</span>".htmlspecialchars($item['id'])."</div>", $item, "ID"):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=id&sort_order='. ($next_order['id']) .'\' class=\'sort\' column=\'id\' sort_order=\''.$sort_order['id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-from\" name=\"id_filter_from\" placeholder=\"От\"/>
							<span class=\"input-group-btn\" style=\"width:0px;\"></span>
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-to\" name=\"id_filter_to\" placeholder=\"До\"/>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>ID:</span>
		</span>".htmlspecialchars($item['id'])."</div>")."
".(function_exists("processTD")?processTD("
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=name&sort_order='. ($next_order['name']) .'\' class=\'sort\' column=\'name\' sort_order=\''.$sort_order['name'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['name'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"name_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Имя:</span>
		</span>
		<span class='editable' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='name'>".htmlspecialchars($item['name'])."</span>
	</div>", $item, "Имя"):"
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=name&sort_order='. ($next_order['name']) .'\' class=\'sort\' column=\'name\' sort_order=\''.$sort_order['name'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['name'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"name_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Имя:</span>
		</span>
		<span class='editable' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='name'>".htmlspecialchars($item['name'])."</span>
	</div>")."
".(function_exists("processTD")?processTD("
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=email&sort_order='. ($next_order['email']) .'\' class=\'sort\' column=\'email\' sort_order=\''.$sort_order['email'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['email'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"email_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Email:</span>
		</span>
		<span class='editable' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='email'>".htmlspecialchars($item['email'])."</span>
	</div>", $item, "Email"):"
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=email&sort_order='. ($next_order['email']) .'\' class=\'sort\' column=\'email\' sort_order=\''.$sort_order['email'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['email'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"email_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Email:</span>
		</span>
		<span class='editable' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='email'>".htmlspecialchars($item['email'])."</span>
	</div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=password_hash&sort_order='. ($next_order['password_hash']) .'\' class=\'sort\' column=\'password_hash\' sort_order=\''.$sort_order['password_hash'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['password_hash'] ?? '<span class="fa fa-sort"></span>')).'</a>'."</span>
			<span class='genesis-attached-column-name'>Пароль:</span>
		</span>
		<span class='editable' data-type='password' data-display=false data-inp='pass'  data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='password_hash'></span></div>", $item, "Пароль"):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=password_hash&sort_order='. ($next_order['password_hash']) .'\' class=\'sort\' column=\'password_hash\' sort_order=\''.$sort_order['password_hash'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['password_hash'] ?? '<span class="fa fa-sort"></span>')).'</a>'."</span>
			<span class='genesis-attached-column-name'>Пароль:</span>
		</span>
		<span class='editable' data-type='password' data-display=false data-inp='pass'  data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='password_hash'></span></div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=phone&sort_order='. ($next_order['phone']) .'\' class=\'sort\' column=\'phone\' sort_order=\''.$sort_order['phone'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['phone'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"phone_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Телефон:</span>
		</span>".htmlspecialchars($item['phone'])."</div>", $item, "Телефон"):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=phone&sort_order='. ($next_order['phone']) .'\' class=\'sort\' column=\'phone\' sort_order=\''.$sort_order['phone'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['phone'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"phone_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Телефон:</span>
		</span>".htmlspecialchars($item['phone'])."</div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=role&sort_order='. ($next_order['role']) .'\' class=\'sort\' column=\'role\' sort_order=\''.$sort_order['role'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['role'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<select class=\"form-control filter-select\" name=\"role_filter\">


							".str_replace(chr(39), '&#39;', $role_values_text)."


							</select>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Роль :</span>
		</span>
		<span class='editable' data-inp='select' data-type='select' data-source='".htmlspecialchars($role_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='role'>".select_mapping($role_values, $item['role'])."</span>
		</div>", $item, "Роль "):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=role&sort_order='. ($next_order['role']) .'\' class=\'sort\' column=\'role\' sort_order=\''.$sort_order['role'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['role'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<select class=\"form-control filter-select\" name=\"role_filter\">


							".str_replace(chr(39), '&#39;', $role_values_text)."


							</select>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Роль :</span>
		</span>
		<span class='editable' data-inp='select' data-type='select' data-source='".htmlspecialchars($role_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='role'>".select_mapping($role_values, $item['role'])."</span>
		</div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
				<span class='genesis-attached-column-info'>
					<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=shop_id&sort_order='. ($next_order['shop_id']) .'\' class=\'sort\' column=\'shop_id\' sort_order=\''.$sort_order['shop_id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['shop_id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<select class=\"form-control filter-select\" name=\"shop_id_filter\">


							".str_replace(chr(39), '&#39;', $shop_id_values_text)."


							</select>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
					<span class='genesis-attached-column-name'>Магазин:</span>
				</span><span class='editable' data-inp='select' data-type='select' data-source='".htmlspecialchars($shop_id_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='shop_id'>".select_mapping($shop_id_values, $item['shop_id'])."</span></div>", $item, "Магазин"):"<div class='genesis-item-property '>
				<span class='genesis-attached-column-info'>
					<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=shop_id&sort_order='. ($next_order['shop_id']) .'\' class=\'sort\' column=\'shop_id\' sort_order=\''.$sort_order['shop_id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['shop_id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<select class=\"form-control filter-select\" name=\"shop_id_filter\">


							".str_replace(chr(39), '&#39;', $shop_id_values_text)."


							</select>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
					<span class='genesis-attached-column-name'>Магазин:</span>
				</span><span class='editable' data-inp='select' data-type='select' data-source='".htmlspecialchars($shop_id_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='shop_id'>".select_mapping($shop_id_values, $item['shop_id'])."</span></div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=is_activated&sort_order='. ($next_order['is_activated']) .'\' class=\'sort\' column=\'is_activated\' sort_order=\''.$sort_order['is_activated'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['is_activated'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
      <span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group text-center\">
              <input type=\"checkbox\" class=\"filter-checkbox\" name=\"is_activated_filter\">
              <span class=\"input-group-btn\">
                <button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
              </span>
            </div>'>
      </span></span>
			<span class='genesis-attached-column-name'>Активен:</span>
		</span>
		<div class='checkbox-container'><input  data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='is_activated' type='checkbox'".($item['is_activated']==1?" checked ":" ")." class='ajax-checkbox'></div></div>", $item, "Активен"):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=is_activated&sort_order='. ($next_order['is_activated']) .'\' class=\'sort\' column=\'is_activated\' sort_order=\''.$sort_order['is_activated'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['is_activated'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
      <span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group text-center\">
              <input type=\"checkbox\" class=\"filter-checkbox\" name=\"is_activated_filter\">
              <span class=\"input-group-btn\">
                <button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
              </span>
            </div>'>
      </span></span>
			<span class='genesis-attached-column-name'>Активен:</span>
		</span>
		<div class='checkbox-container'><input  data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='is_activated' type='checkbox'".($item['is_activated']==1?" checked ":" ")." class='ajax-checkbox'></div></div>")."
					<div class='genesis-control-cell'><a href='#' class='edit_btn'><i class='fa fa-edit' style='color:grey;'></i></a> <a href='#' class='delete_btn'><i class='fa fa-trash' style='color:red;'></i></a></div>
				</div>";

				if(function_exists("processTR"))
				{
					$tr = processTR($tr, $item);
				}

				$table.=$tr;
			}



			$table .= "</div></div></div>".$pagination;

		}
		else
		{
			$table.=' </div></div><div class="empty_table">Нет информации</div>';
		}

		if(function_exists("processTable"))
		{
			$table = processTable($table);
		}

		$show.=$table."<div></div>".'<button class="btn blue-inline csv-button float-right">СКАЧАТЬ CSV</button> ';

		if(function_exists("processPage"))
		{
			$show = processPage($show);
		}

		$show.="
		<style></style>
		<script></script>";


		return $show;

	};



	$actions['edit'] = function()
	{
		$id = $_REQUEST['genesis_edit_id'];
		if(isset($id))
		{
			$item = q("SELECT * FROM shops_admins WHERE id=?",[$id]);
			$item = $item[0];
		}

		

		$html = '
			<form class="form" enctype="multipart/form-data" method="POST">
				<fieldset>'.
					(isset($id)?
					'<input type="hidden" name="id" value="'.$id.'">
					<input type="hidden" name="action" value="edit_execute">'
					:
					'<input type="hidden" name="action" value="create_execute">'
					)
					.'

					

								<div class="form-group">
									<label class="control-label" for="textinput">Имя</label>
									<div>
										<input id="name" name="name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Email</label>
									<div>
										<input id="email" name="email" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["email"]).'">
									</div>
								</div>

							

	            '. (!isset($id)?'
							<div class="form-group">
								<label class="control-label" for="textinput">Пароль</label>
								<div>
									<input id="password_hash" name="password_hash" type="password"  class="form-control input-md " value="">
								</div>
							</div>':
	            ''
	            ).'
						


						<div class="form-group">
							<label class="control-label" for="textinput">Телефон</label>
							<div>
								<input id="phone" name="phone" type="text" data-inp="phone" class="form-control input-md  " placeholder=""  value="'.htmlspecialchars($item["phone"]).'">
							</div>
						</div>

					



				<div class="form-group">
					<label class="control-label" for="textinput">Роль </label>
					<div>
						<select id="role" name="role" class="form-control input-md ">
							<option value="admin" '.($item["role"]=="admin"?"selected":"").'>Администратор</option> 
<option value="manager" '.($item["role"]=="manager"?"selected":"").'>Менеджер</option> 

						</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">ID магазина</label>
									<div>
										<input id="shop_id" name="shop_id" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["shop_id"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Активирован</label>
							<div>
								<input id="is_activated" name="is_activated" class=""  type="checkbox"  value="1" '.($item["is_activated"]==1?"checked":"").'>
							</div>
						</div>

					
					<div class="text-center not-editable">
						
					</div>

				</fieldset>
			</form>

		';

		if(function_exists("processEditModalHTML"))
		{
			$html = processEditModalHTML($html);
		}
		die($html);
	};

	$actions['create'] = function()
	{

		

		$html = '
			<form class="form" enctype="multipart/form-data" method="POST">
				<fieldset>
					<input type="hidden" name="action" value="create_execute">
					

								<div class="form-group">
									<label class="control-label" for="textinput">Имя</label>
									<div>
										<input id="name" name="name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Email</label>
									<div>
										<input id="email" name="email" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["email"]).'">
									</div>
								</div>

							

	            '. (!isset($id)?'
							<div class="form-group">
								<label class="control-label" for="textinput">Пароль</label>
								<div>
									<input id="password_hash" name="password_hash" type="password"  class="form-control input-md " value="">
								</div>
							</div>':
	            ''
	            ).'
						


						<div class="form-group">
							<label class="control-label" for="textinput">Телефон</label>
							<div>
								<input id="phone" name="phone" type="text" data-inp="phone" class="form-control input-md  " placeholder=""  value="'.htmlspecialchars($item["phone"]).'">
							</div>
						</div>

					



				<div class="form-group">
					<label class="control-label" for="textinput">Роль </label>
					<div>
						<select id="role" name="role" class="form-control input-md ">
							<option value="admin" '.($item["role"]=="admin"?"selected":"").'>Администратор</option> 
<option value="manager" '.($item["role"]=="manager"?"selected":"").'>Менеджер</option> 

						</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">ID магазина</label>
									<div>
										<input id="shop_id" name="shop_id" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["shop_id"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Активирован</label>
							<div>
								<input id="is_activated" name="is_activated" class=""  type="checkbox"  value="1" '.($item["is_activated"]==1?"checked":"").'>
							</div>
						</div>

					
					<div class="text-center not-editable">
						
					</div>
				</fieldset>
			</form>

		';

		if(function_exists("processCreateModalHTML"))
		{
			$html = processCreateModalHTML($html);
		}
		die($html);
	};


	$actions['edit_page'] = function()
	{
		$id = $_REQUEST['genesis_edit_id'];
		if(isset($id))
		{
			$item = q("SELECT * FROM shops_admins WHERE id=?",[$id]);
			$item = $item[0];
		}
		else
		{
			die("Ошибка. Редактирование несуществующей записи (вы не указали id)");
		}

		


		$html = '
			<h1 style="line-height: 30px"> Редактирование <br /><small>'."Администраторы магазинов".' #'.$id.'</small></h1>
			<form class="form" enctype="multipart/form-data" method="POST">
				<input type="hidden" name="back" value="'.$_SERVER['HTTP_REFERER'].'">
				<fieldset>'.
					(isset($id)?
					'<input type="hidden" name="id" value="'.$id.'">
					<input type="hidden" name="action" value="edit_execute">'
					:
					'<input type="hidden" name="action" value="create_execute">'
					)
					.'

					

								<div class="form-group">
									<label class="control-label" for="textinput">Имя</label>
									<div>
										<input id="name" name="name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Email</label>
									<div>
										<input id="email" name="email" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["email"]).'">
									</div>
								</div>

							

	            '. (!isset($id)?'
							<div class="form-group">
								<label class="control-label" for="textinput">Пароль</label>
								<div>
									<input id="password_hash" name="password_hash" type="password"  class="form-control input-md " value="">
								</div>
							</div>':
	            ''
	            ).'
						


						<div class="form-group">
							<label class="control-label" for="textinput">Телефон</label>
							<div>
								<input id="phone" name="phone" type="text" data-inp="phone" class="form-control input-md  " placeholder=""  value="'.htmlspecialchars($item["phone"]).'">
							</div>
						</div>

					



				<div class="form-group">
					<label class="control-label" for="textinput">Роль </label>
					<div>
						<select id="role" name="role" class="form-control input-md ">
							<option value="admin" '.($item["role"]=="admin"?"selected":"").'>Администратор</option> 
<option value="manager" '.($item["role"]=="manager"?"selected":"").'>Менеджер</option> 

						</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">ID магазина</label>
									<div>
										<input id="shop_id" name="shop_id" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["shop_id"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Активирован</label>
							<div>
								<input id="is_activated" name="is_activated" class=""  type="checkbox"  value="1" '.($item["is_activated"]==1?"checked":"").'>
							</div>
						</div>

					

				</fieldset>
				<div>
					<a href="?'.(http_build_query(array_filter($_REQUEST, function($k){return !in_array($k, ['action', 'genesis_edit_id']);}, ARRAY_FILTER_USE_KEY))).'" class="btn cancel" >Закрыть</a>
					<button type="button" class="btn blue-inline" id="edit_page_save">Сохранить</a>
				</div>
			</form>

		';

		if(function_exists("processEditPageHTML"))
		{
			$html = processEditPageHTML($html);
		}
		return $html;
	};

	$actions['reorder'] = function()
	{
		$line = json_decode($_REQUEST['genesis_ids_in_order'], true);
		for ($i=0; $i < count($line); $i++)
		{
			qi("UPDATE `shops_admins` SET `` = ? WHERE id = ?", [$i, $line[$i]]);
		}


		die(json_encode(['status'=>0]));

	};


	$actions['csv_create_execute'] = function()
	{
		if(function_exists("allowInsert"))
		{
			if(!allowInsert())
			{
				header("Location: ".$_SERVER['HTTP_REFERER']);
				die("");
			}
		}


		$sql = "INSERT IGNORE INTO shops_admins (`name`, `email`, `password_hash`, `phone`, `role`, `shop_id`, `is_activated`) VALUES (?, ?, ?, ?, ?, ?, ?)";

		$lines = preg_split("/\r\n|\n|\r/", $_REQUEST['csv']);
		$success_count = 0;
		$errors_count = 0;
		foreach($lines as $line)
		{
			$line = str_getcsv($line);
			qi($sql, [trim($line[0]), trim($line[1]), trim($line[2]), trim($line[3]), trim($line[4]), trim($line[5]), trim($line[6])]);
			$last_id = qInsertId();
			if($last_id && $last_id>0)
			{
				$success_count++;
			}
			else
			{
				$errors_count++;
			}

			if(function_exists("afterInsert"))
			{
				afterInsert($last_id);
			}
		}

		buildMsg(
			($success_count>0?"Успешно добавлено: {$success_count}<br>":"").
			($errors_count>0?"Ошибок: {$errors_count}":""),

			$errors_count==0?"success":"danger"
		);





		header("Location: ".$_SERVER['HTTP_REFERER']);
		die("");

	};

	$actions['create_execute'] = function()
	{
		if(function_exists("allowInsert"))
		{
			if(!allowInsert())
			{
				header("Location: ".$_SERVER['HTTP_REFERER']);
				die("");
			}
		}
		$name = $_REQUEST['name'];
$email = $_REQUEST['email'];
$password_hash = md5($_REQUEST['password_hash']);
$phone = $_REQUEST['phone'];
$role = $_REQUEST['role'];
$shop_id = $_REQUEST['shop_id'];
$is_activated = $_REQUEST['is_activated'];

		$params = [$name, $email, $password_hash, $phone, $role, $shop_id, $is_activated];
		$sql = "INSERT INTO shops_admins (`name`, `email`, `password_hash`, `phone`, `role`, `shop_id`, `is_activated`) VALUES (?, ?, ?, ?, ?, ?, ?)";
		if(function_exists("processInsertQuery"))
		{
			list($sql, $params) = processInsertQuery($sql, $params);
		}

		qi($sql, array_values($params));
		$last_id = qInsertId();

		if(function_exists("afterInsert"))
		{
			afterInsert($last_id);
		}

		

		header("Location: ".$_SERVER['HTTP_REFERER']);
		die("");

	};

	$actions['edit_execute'] = function()
	{
		$skip = false;
		if(function_exists("allowUpdate"))
		{
			if(!allowUpdate())
			{
				$skip = true;
			}
		}
		if(!$skip)
		{
			$id = $_REQUEST['id'];
			$set = [];

			$set[] = is_null($_REQUEST['name'])?"`name`=NULL":"`name`='".addslashes($_REQUEST['name'])."'";
$set[] = is_null($_REQUEST['email'])?"`email`=NULL":"`email`='".addslashes($_REQUEST['email'])."'";
$set[] = is_null($_REQUEST['phone'])?"`phone`=NULL":"`phone`='".addslashes($_REQUEST['phone'])."'";
$set[] = is_null($_REQUEST['role'])?"`role`=NULL":"`role`='".addslashes($_REQUEST['role'])."'";
$set[] = is_null($_REQUEST['shop_id'])?"`shop_id`=NULL":"`shop_id`='".addslashes($_REQUEST['shop_id'])."'";
$set[] = is_null($_REQUEST['is_activated'])?"`is_activated`=NULL":"`is_activated`='".addslashes($_REQUEST['is_activated'])."'";

			if(count($set)>0)
			{
				$set = implode(", ", $set);
				$sql = "UPDATE shops_admins SET $set WHERE id=?";
				if(function_exists("processUpdateQuery"))
				{
					$sql = processUpdateQuery($sql);
				}

				qi($sql, [$id]);
				if(function_exists("afterUpdate"))
				{
					afterUpdate($id);
				}
			}
		}

		if(isset($_REQUEST['back']))
		{
			header("Location: {$_REQUEST['back']}");
		}
		else
		{
			header("Location: ".$_SERVER['HTTP_REFERER']);
		}
		die("");
	};



	$actions['delete'] = function()
	{
		if(function_exists("allowDelete"))
		{
			if(!allowDelete())
			{
				die("0");
			}
		}

		$id = $_REQUEST['id'];
		try
		{
			qi("DELETE FROM shops_admins WHERE id=?", [$id]);
			if(function_exists("afterDelete"))
			{
				afterDelete();
			}
			echo "1";
		}
		catch (Exception $e)
		{
			echo "0";
		}

		die("");
	};

	function filter_query($srch)
	{
		$filters = [];
		
		if(isset2($_REQUEST['id_filter_from']) && isset2($_REQUEST['id_filter_to']))
		{
			$filters[] = "id >= {$_REQUEST['id_filter_from']} AND id <= {$_REQUEST['id_filter_to']}";
		}

		

		if(isset2($_REQUEST['name_filter']))
		{
			$filters[] = "`name` LIKE '%{$_REQUEST['name_filter']}%'";
		}
				

		if(isset2($_REQUEST['email_filter']))
		{
			$filters[] = "`email` LIKE '%{$_REQUEST['email_filter']}%'";
		}
				

		if(isset2($_REQUEST['phone_filter']))
		{
			$filters[] = "`phone` LIKE '%{$_REQUEST['phone_filter']}%'";
		}
				

		if(isset2($_REQUEST['role_filter']))
		{
			$filters[] = "`role` = '{$_REQUEST['role_filter']}'";
		}
				

		if(isset2($_REQUEST['shop_id_filter']))
		{
			$filters[] = "`shop_id` = '{$_REQUEST['shop_id_filter']}'";
		}
				

if(isset2($_REQUEST['is_activated_filter']))
{
  $filters[] = "`is_activated` = '{$_REQUEST['is_activated_filter']}'";
}
    

		$filter="";
		if(count($filters)>0)
		{
			$filter = implode(" AND ", $filters);
			if($srch=="")
			{
				$filter = " WHERE $filter";
			}
			else
			{
				$filter = " AND ($filter)";
			}
		}
		return $filter;
	}

	function filter_divs()
	{
		$role_values = '[
	{
		"text": "Администратор",
		"value": "admin"
	},
	{
		"text": "Менеджер",
		"value": "manager"
	}
]';
		$role_values_text = "";
		foreach(json_decode($role_values, true) as $opt)
		{
			$role_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		
$shop_id_values = json_encode(q("SELECT name as text, id as value FROM shops", []));
			$shop_id_values_text = "";
				foreach(json_decode($shop_id_values, true) as $opt)
				{
				  $shop_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
				}
		
		if(isset2($_REQUEST['id_filter_from']) && isset2($_REQUEST['id_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='id_filter_from' value='{$_REQUEST['id_filter_from']}'>
					<input type='hidden' class='filter' name='id_filter_to' value='{$_REQUEST['id_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> ID: <b>{$_REQUEST['id_filter_from']}–{$_REQUEST['id_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['name_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='name_filter' value='{$_REQUEST['name_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Имя: <b>{$_REQUEST['name_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['email_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='email_filter' value='{$_REQUEST['email_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Email: <b>{$_REQUEST['email_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['phone_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='phone_filter' value='{$_REQUEST['phone_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Телефон: <b>{$_REQUEST['phone_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		$text_option = array_filter(json_decode($role_values, true), function($i)
		{
			return $i['value']==$_REQUEST['role_filter'];
		});
		$text_option = array_values($text_option)[0]['text'];
		if(isset2($_REQUEST['role_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='role_filter' value='{$_REQUEST['role_filter']}'>
					<span class='fa fa-times remove-tag'></span> Роль : <b>{$text_option}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		$text_option = array_filter(json_decode($shop_id_values, true), function($i)
		{
			return $i['value']==$_REQUEST['shop_id_filter'];
		});
		$text_option = array_values($text_option)[0]['text'];
		if(isset2($_REQUEST['shop_id_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='shop_id_filter' value='{$_REQUEST['shop_id_filter']}'>
					<span class='fa fa-times remove-tag'></span> Магазин: <b>{$text_option}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

if(isset2($_REQUEST['is_activated_filter']))
{
  $filter_divs .= "
  <div class='filter-tag'>
      <input type='hidden' class='filter' name='is_activated_filter' value='{$_REQUEST['is_activated_filter']}'>
       <span class='fa fa-times remove-tag'></span> Активен: <b>".($_REQUEST['is_activated_filter']?"Вкл":"Выкл")."</b>
  </div>";

  $filter_caption = "Фильтры: ";
}


		$show = $filter_caption.$filter_divs;

		return $show;
	}

	function get_agregate()
	{

		$items = [];

		$srch = "";
		
			if($_REQUEST['srch-term'])
			{
				$srch = "WHERE ((`name` LIKE '%{$_REQUEST['srch-term']}%'))";
			}

		$filter = filter_query($srch);
		$where = "";
		if($where != "")
		{
			if($filter!='' || $srch !='')
			{
				$where = " AND ($where)";
			}
			else
			{
				$where = " WHERE ($where)";
			}
		}

		$sql = "SELECT 1 as stub  FROM (SELECT main_table.*  FROM shops_admins main_table) temp $srch $filter $where $order";

		$debug = (isset($_REQUEST['alef_debug']) && $_REQUEST['alef_debug']==1);
		if(in_array($_SERVER['SERVER_NAME'], ["test-genesis.alef.im", "devtest-genesis.alef.im", "localhost"]) || $debug)
		{
			echo "<!--SQL AGREGATE {$sql} -->\n";
		}

		$result = q($sql, []);
		return $result[0];
	}

	function get_data($force_kill_pagination=false)
	{
		if(function_exists("allowSelect"))
		{
			if(!allowSelect())
			{
				die("У вас нет доступа к данной странице");
			}
		}

		$pagination = 1;
		if($force_kill_pagination==true)
		{
			$pagination = 0;
		}
		$items = [];

		$srch = "";
		
			if($_REQUEST['srch-term'])
			{
				$srch = "WHERE ((`name` LIKE '%{$_REQUEST['srch-term']}%'))";
			}

		$filter = filter_query($srch);
		$where = "";
		if($where != "")
		{
			if($filter!='' || $srch !='')
			{
				$where = " AND ($where)";
			}
			else
			{
				$where = " WHERE ($where)";
			}
		}


		
				$default_sort_by = '`id`';
				$default_sort_order = '';
			

		if(isset($default_sort_by) && $default_sort_by)
		{
			$order = "ORDER BY $default_sort_by $default_sort_order";
		}

		if(isset($_REQUEST['sort_by']) && $_REQUEST['sort_by']!="")
		{
			$order = "ORDER BY {$_REQUEST['sort_by']} {$_REQUEST['sort_order']}";
		}

		$debug = (isset($_REQUEST['alef_debug']) && $_REQUEST['alef_debug']==1);
		if($pagination == 1)
		{
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT  main_table.*  FROM shops_admins main_table) temp $srch $filter $where $order LIMIT :start, :limit";
			if(function_exists("processSelectQuery"))
			{
				$sql = processSelectQuery($sql);
			}


			if(in_array($_SERVER['SERVER_NAME'], ["test-genesis.alef.im", "devtest-genesis.alef.im", "localhost"]) || $debug)
			{
				echo "<!--SQL DATA {$sql} -->\n";
			}

			$items = q($sql,
				[
					'start' => MAX(($_GET['page']-1), 0)*RPP,
					'limit' => RPP
				]);
			$cnt = qRows();
			$pagination = pagination($cnt);
		}
		else
		{
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT main_table.*  FROM shops_admins main_table) temp $srch $filter $where $order";
			if(in_array($_SERVER['SERVER_NAME'], ["test-genesis.alef.im", "devtest-genesis.alef.im", "localhost"]) || $debug)
			{
				echo "<!--SQL DATA {$sql} -->";
			}
			if(function_exists("processSelectQuery"))
			{
				$sql = processSelectQuery($sql);
			}
			$items = q($sql, []);
			$cnt = qRows();
			$pagination = "";
		}

		if(function_exists("processData"))
		{
			$items = processData($items);
		}

		return [$items, $pagination, $cnt];
	}

	

	$content = $actions[$action]();
	echo masterRender("Администраторы магазинов", $content, 3);
