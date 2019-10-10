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

// обработка sql-запроса вставки данных
function processInsertQuery($sql_query_text)
{
	return $sql_query_text;
}

// HTML всей таблицы целиком. Можно после таблицы добавить каких-то графиков и тд
function processTable($html)
{
 
 if( isset( $_REQUEST['back'] ) ) {
 $html = '<p><a href="#" onclick="history.back();" class="back-btn" style="display: inline;"><span class="fa fa-arrow-circle-left"></span> ' . $_REQUEST['back'] . '</a></p>' . $html;
 }

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
			
   		$sex_values = '[
 {
 "text": "мужской",
 "value": "male"
 },
 {
 "text": "женский",
 "value": "female"
 }
]';
		$sex_values_text = "";
		foreach(json_decode($sex_values, true) as $opt)
		{
			$sex_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		

		list($items, $pagination, $cnt) = get_data();

		$sort_order[$_REQUEST['sort_by']] = $_REQUEST['sort_order'];

$next_order['id']='asc';
$next_order['last_name']='asc';
$next_order['first_name']='asc';
$next_order['middle_name']='asc';
$next_order['birth_date']='asc';
$next_order['birth_place']='asc';
$next_order['sex']='asc';
$next_order['is_last_name_changed']='asc';
$next_order['previous_last_name']='asc';
$next_order['tin']='asc';
$next_order['snils']='asc';
$next_order['passport_number']='asc';
$next_order['passport_division_code']='asc';
$next_order['passport_issued_by']='asc';
$next_order['passport_issued_date']='asc';
$next_order['workplace']='asc';
$next_order['salary']='asc';
$next_order['reg_zip_code']='asc';
$next_order['reg_city']='asc';
$next_order['reg_street']='asc';
$next_order['reg_building']='asc';
$next_order['reg_apartment']='asc';
$next_order['is_address_matched']='asc';
$next_order['fact_zip_code']='asc';
$next_order['fact_city']='asc';
$next_order['fact_street']='asc';
$next_order['fact_building']='asc';
$next_order['fact_apartment']='asc';
$next_order['phone']='asc';
$next_order['additional_phone']='asc';
$next_order['email']='asc';

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
					$(\'.big-icon\').html(\'<i class="fas fa-users"></i>\');
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
							<small>Вставьте сюда новые записи. Каждая запись на новой строчке: <b class="csv-create-format">Фамилия, Имя, Отчество, Дата рождения, Место рождения, Пол, Менялась ли фамилия, Предыдущая фамилия, ИНН, СНИЛС, Серия и номер паспорта, Код подразделения, Кем выдан паспорт, Дата выдачи паспорта, Место работы, Ежемесячный доход, Индекс по прописке, Город по прописке, Улица по прописке, Дом по прописке, Квартира по прописке, Совпадают ли фактический и адрес прописки?, Индекс по факту, Город по факту, Улица по факту, Дом по факту, Квартира по факту, Телефон, Дополнительный телефон, Email</b></small>
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
				<h2><a href="#" class="back-btn"><span class="fa fa-arrow-circle-left"></span></a> '."Клиенты".' </h2>
				<button class="btn blue-inline add_button" data-toggle="modal" data-target="#modal-main">ДОБАВИТЬ</button>
				<p class="small res-cnt">Кол-во результатов: <span class="cnt-number-span">'.$cnt.'</span></p>
			</div>
			
			<form class="navbar-form search-form" role="search">
				<div class="input-group">
					<input type="text" class="form-control" placeholder="Поиск" name="srch-term" id="srch-term" value="'.$_REQUEST['srch-term'].'">
					<button class="input-group-addon"><i class="fa fa-search"></i></button>
				</div>
			</form>
			<a href="#" class="js-extra-filters extra-filters btn blue-inline extra-filters-btn" data-toggle="modal" data-target="#js-extra-filters-modal"><i class="fa fa-filter"></i></a>
			<div class="modal fade" id="js-extra-filters-modal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
						<h4 class="modal-title">Дополнительные фильтры</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<div class="modal-body">
						
			<div class="form-group">
				<label>Дата рождения</label>
				<input autocomplete="off" type="text" class="form-control daterange filter-date-range birth_date-extra-filter" name="birth_date_filter" style="width:100%;">
			</div>
			<script>
				document.addEventListener("DOMContentLoaded", (event) =>
				{
					$("#js-extra-filters-modal").on("shown.bs.modal", function()
					{
						if("'.($_REQUEST['birth_date_filter_from'] ?? 0).'"!="0")
						{
							$(".birth_date-extra-filter").data("daterangepicker").setStartDate("'.date("d-m-Y", strtotime($_REQUEST['birth_date_filter_from'])).'");
						  	$(".birth_date-extra-filter").data("daterangepicker").setEndDate("'.date("d-m-Y", strtotime($_REQUEST['birth_date_filter_to'])).'");
							$(".created_ts-extra-filter").trigger(\'apply\');
						}

					});
				});
			</script>

			<div class="form-group">
				<label>Место рождения</label>
				<input type="text" class="form-control filter-text" name="birth_place_filter" style="width:100%" value="'.$_REQUEST['birth_place_filter'].'">
			</div>

				<div class="form-group">
    				<label>Пол</label>
					<select class="form-control filter-select sex-extra-filter" name="sex_filter" style="width:100%">
					<option value="">----------</option>
					'. $sex_values_text .'
					</select>
  				</div>
				<script>
					document.addEventListener("DOMContentLoaded", (event) =>
					{
  						$(".sex-extra-filter").val('.($_REQUEST['sex_filter'] ?? "''").').trigger("change");
					});
				</script>
			

				<div class="form-group">
					<label>Менялась ли фамилия</label>
					<select class="form-control filter-select  is_last_name_changed-extra-filter" name="is_last_name_changed_filter" style="width:100%">
						<option value="">----------</option>
						<option value="0">Нет</option>
						<option value="1">Да</option>
					</select>
				</div>
				<script>
					document.addEventListener("DOMContentLoaded", (event) =>
					{
  						$(".is_last_name_changed-extra-filter").val('.($_REQUEST['is_last_name_changed_filter'] ?? "''").').trigger("change");
					});
				</script>
			

			<div class="form-group">
				<label>Предыдущая фамилия</label>
				<input type="text" class="form-control filter-text" name="previous_last_name_filter" style="width:100%" value="'.$_REQUEST['previous_last_name_filter'].'">
			</div>

			<div class="form-group">
				<label style="display:block;">ИНН</label>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="tin_filter_from" value="'.$_REQUEST['tin_filter_from'].'" placeholder="От" style="display:inline; width:49%;"/>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="tin_filter_to" value="'.$_REQUEST['tin_filter_to'].'" placeholder="До" style="display:inline; width:49%; float:right;"/>
			</div>

			<div class="form-group">
				<label>СНИЛС</label>
				<input type="text" class="form-control filter-text" name="snils_filter" style="width:100%" value="'.$_REQUEST['snils_filter'].'">
			</div>

			<div class="form-group">
				<label>Код подразделения</label>
				<input type="text" class="form-control filter-text" name="passport_division_code_filter" style="width:100%" value="'.$_REQUEST['passport_division_code_filter'].'">
			</div>

			<div class="form-group">
				<label>Кем выдан паспорт</label>
				<input type="text" class="form-control filter-text" name="passport_issued_by_filter" style="width:100%" value="'.$_REQUEST['passport_issued_by_filter'].'">
			</div>

			<div class="form-group">
				<label>Дата выдачи паспорта</label>
				<input autocomplete="off" type="text" class="form-control daterange filter-date-range passport_issued_date-extra-filter" name="passport_issued_date_filter" style="width:100%;">
			</div>
			<script>
				document.addEventListener("DOMContentLoaded", (event) =>
				{
					$("#js-extra-filters-modal").on("shown.bs.modal", function()
					{
						if("'.($_REQUEST['passport_issued_date_filter_from'] ?? 0).'"!="0")
						{
							$(".passport_issued_date-extra-filter").data("daterangepicker").setStartDate("'.date("d-m-Y", strtotime($_REQUEST['passport_issued_date_filter_from'])).'");
						  	$(".passport_issued_date-extra-filter").data("daterangepicker").setEndDate("'.date("d-m-Y", strtotime($_REQUEST['passport_issued_date_filter_to'])).'");
							$(".created_ts-extra-filter").trigger(\'apply\');
						}

					});
				});
			</script>

			<div class="form-group">
				<label>Место работы</label>
				<input type="text" class="form-control filter-text" name="workplace_filter" style="width:100%" value="'.$_REQUEST['workplace_filter'].'">
			</div>

			<div class="form-group">
				<label style="display:block;">Ежемесячный доход</label>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="salary_filter_from" value="'.$_REQUEST['salary_filter_from'].'" placeholder="От" style="display:inline; width:49%;"/>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="salary_filter_to" value="'.$_REQUEST['salary_filter_to'].'" placeholder="До" style="display:inline; width:49%; float:right;"/>
			</div>

			<div class="form-group">
				<label>Индекс по прописке</label>
				<input type="text" class="form-control filter-text" name="reg_zip_code_filter" style="width:100%" value="'.$_REQUEST['reg_zip_code_filter'].'">
			</div>

			<div class="form-group">
				<label>Город по прописке</label>
				<input type="text" class="form-control filter-text" name="reg_city_filter" style="width:100%" value="'.$_REQUEST['reg_city_filter'].'">
			</div>

			<div class="form-group">
				<label>Улица по прописке</label>
				<input type="text" class="form-control filter-text" name="reg_street_filter" style="width:100%" value="'.$_REQUEST['reg_street_filter'].'">
			</div>

			<div class="form-group">
				<label>Дом по прописке</label>
				<input type="text" class="form-control filter-text" name="reg_building_filter" style="width:100%" value="'.$_REQUEST['reg_building_filter'].'">
			</div>

			<div class="form-group">
				<label>Квартира по прописке</label>
				<input type="text" class="form-control filter-text" name="reg_apartment_filter" style="width:100%" value="'.$_REQUEST['reg_apartment_filter'].'">
			</div>

				<div class="form-group">
					<label>Совпадают ли фактический и адрес прописки?</label>
					<select class="form-control filter-select  is_address_matched-extra-filter" name="is_address_matched_filter" style="width:100%">
						<option value="">----------</option>
						<option value="0">Нет</option>
						<option value="1">Да</option>
					</select>
				</div>
				<script>
					document.addEventListener("DOMContentLoaded", (event) =>
					{
  						$(".is_address_matched-extra-filter").val('.($_REQUEST['is_address_matched_filter'] ?? "''").').trigger("change");
					});
				</script>
			

			<div class="form-group">
				<label>Индекс по факту</label>
				<input type="text" class="form-control filter-text" name="fact_zip_code_filter" style="width:100%" value="'.$_REQUEST['fact_zip_code_filter'].'">
			</div>

			<div class="form-group">
				<label>Город по факту</label>
				<input type="text" class="form-control filter-text" name="fact_city_filter" style="width:100%" value="'.$_REQUEST['fact_city_filter'].'">
			</div>

			<div class="form-group">
				<label>Улица по факту</label>
				<input type="text" class="form-control filter-text" name="fact_street_filter" style="width:100%" value="'.$_REQUEST['fact_street_filter'].'">
			</div>

			<div class="form-group">
				<label>Дом по факту</label>
				<input type="text" class="form-control filter-text" name="fact_building_filter" style="width:100%" value="'.$_REQUEST['fact_building_filter'].'">
			</div>

			<div class="form-group">
				<label>Квартира по факту</label>
				<input type="text" class="form-control filter-text" name="fact_apartment_filter" style="width:100%" value="'.$_REQUEST['fact_apartment_filter'].'">
			</div>

			<div class="form-group">
				<label>Дополнительный телефон</label>
				<input type="text" class="form-control filter-text" name="additional_phone_filter" style="width:100%" value="'.$_REQUEST['additional_phone_filter'].'">
			</div>

			<div class="form-group">
				<label>Email</label>
				<input type="text" class="form-control filter-text" name="email_filter" style="width:100%" value="'.$_REQUEST['email_filter'].'">
			</div>
						</div>

						<div class="modal-footer">
						<button type="button" class="btn cancel" data-dismiss="modal">Отмена</button>
						<button type="button" class="btn btn blue-inline add-filter" data-dismiss="modal">Применить</button>
						</div>
					</div>
				</div>
			</div>
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
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=last_name&sort_order='. ($next_order['last_name']) .'\' class=\'sort\' column=\'last_name\' sort_order=\''.$sort_order['last_name'].'\'>Фамилия'. $sort_icon['last_name'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="text" class="form-control filter-text" name="last_name_filter">
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=first_name&sort_order='. ($next_order['first_name']) .'\' class=\'sort\' column=\'first_name\' sort_order=\''.$sort_order['first_name'].'\'>Имя'. $sort_icon['first_name'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="text" class="form-control filter-text" name="first_name_filter">
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=middle_name&sort_order='. ($next_order['middle_name']) .'\' class=\'sort\' column=\'middle_name\' sort_order=\''.$sort_order['middle_name'].'\'>Отчество'. $sort_icon['middle_name'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="text" class="form-control filter-text" name="middle_name_filter">
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>








			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=passport_number&sort_order='. ($next_order['passport_number']) .'\' class=\'sort\' column=\'passport_number\' sort_order=\''.$sort_order['passport_number'].'\'>Паспорт'. $sort_icon['passport_number'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="text" class="form-control filter-text" name="passport_number_filter">
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
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
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=last_name&sort_order='. ($next_order['last_name']) .'\' class=\'sort\' column=\'last_name\' sort_order=\''.$sort_order['last_name'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['last_name'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"last_name_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Фамилия:</span>
		</span>
		<span class='editable' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='last_name'>".htmlspecialchars($item['last_name'])."</span>
	</div>", $item, "Фамилия"):"
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=last_name&sort_order='. ($next_order['last_name']) .'\' class=\'sort\' column=\'last_name\' sort_order=\''.$sort_order['last_name'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['last_name'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"last_name_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Фамилия:</span>
		</span>
		<span class='editable' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='last_name'>".htmlspecialchars($item['last_name'])."</span>
	</div>")."
".(function_exists("processTD")?processTD("
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=first_name&sort_order='. ($next_order['first_name']) .'\' class=\'sort\' column=\'first_name\' sort_order=\''.$sort_order['first_name'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['first_name'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"first_name_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Имя:</span>
		</span>
		<span class='editable' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='first_name'>".htmlspecialchars($item['first_name'])."</span>
	</div>", $item, "Имя"):"
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=first_name&sort_order='. ($next_order['first_name']) .'\' class=\'sort\' column=\'first_name\' sort_order=\''.$sort_order['first_name'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['first_name'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"first_name_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Имя:</span>
		</span>
		<span class='editable' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='first_name'>".htmlspecialchars($item['first_name'])."</span>
	</div>")."
".(function_exists("processTD")?processTD("
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=middle_name&sort_order='. ($next_order['middle_name']) .'\' class=\'sort\' column=\'middle_name\' sort_order=\''.$sort_order['middle_name'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['middle_name'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"middle_name_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Отчество:</span>
		</span>
		<span class='editable' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='middle_name'>".htmlspecialchars($item['middle_name'])."</span>
	</div>", $item, "Отчество"):"
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=middle_name&sort_order='. ($next_order['middle_name']) .'\' class=\'sort\' column=\'middle_name\' sort_order=\''.$sort_order['middle_name'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['middle_name'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"middle_name_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Отчество:</span>
		</span>
		<span class='editable' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='middle_name'>".htmlspecialchars($item['middle_name'])."</span>
	</div>")."
".(function_exists("processTD")?processTD("
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=passport_number&sort_order='. ($next_order['passport_number']) .'\' class=\'sort\' column=\'passport_number\' sort_order=\''.$sort_order['passport_number'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['passport_number'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"passport_number_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Паспорт:</span>
		</span>
		<span class='editable' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='passport_number'>".htmlspecialchars($item['passport_number'])."</span>
	</div>", $item, "Паспорт"):"
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=passport_number&sort_order='. ($next_order['passport_number']) .'\' class=\'sort\' column=\'passport_number\' sort_order=\''.$sort_order['passport_number'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['passport_number'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"passport_number_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Паспорт:</span>
		</span>
		<span class='editable' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='passport_number'>".htmlspecialchars($item['passport_number'])."</span>
	</div>")."
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
			$item = q("SELECT * FROM clients WHERE id=?",[$id]);
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
									<label class="control-label" for="textinput">Фамилия</label>
									<div>
										<input id="last_name" name="last_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["last_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Имя</label>
									<div>
										<input id="first_name" name="first_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["first_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Отчество</label>
									<div>
										<input id="middle_name" name="middle_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["middle_name"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата рождения</label>
						<div>
							<input autocomplete="off" id="birth_date" placeholder="" name="birth_date" type="text" class="form-control datepicker "  data-timepicker="0"  data-format="Y-m-d" value="'.(isset($item["birth_date"])?((new DateTime($item["birth_date"]))->format("Y-m-d")):date("Y-m-d")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Место рождения</label>
									<div>
										<input id="birth_place" name="birth_place" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["birth_place"]).'">
									</div>
								</div>

							



				<div class="form-group">
					<label class="control-label" for="textinput">Пол</label>
					<div>
						<select id="sex" name="sex" class="form-control input-md ">
							<option value="male" '.($item["sex"]=="male"?"selected":"").'>мужской</option> 
<option value="female" '.($item["sex"]=="female"?"selected":"").'>женский</option> 

						</select>
					</div>
				</div>

			


						<div class="form-group">
							<label class="control-label" for="textinput">Менялась ли фамилия</label>
							<div>
								<input id="is_last_name_changed" name="is_last_name_changed" class=""  type="checkbox"  value="1" '.($item["is_last_name_changed"]==1?"checked":"").'>
							</div>
						</div>

					


								<div class="form-group">
									<label class="control-label" for="textinput">Предыдущая фамилия</label>
									<div>
										<input id="previous_last_name" name="previous_last_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["previous_last_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">ИНН</label>
									<div>
										<input id="tin" name="tin" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["tin"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">СНИЛС</label>
									<div>
										<input id="snils" name="snils" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["snils"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Паспорт</label>
									<div>
										<input id="passport_number" name="passport_number" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["passport_number"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Код подразделения</label>
									<div>
										<input id="passport_division_code" name="passport_division_code" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["passport_division_code"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Кем выдан паспорт</label>
									<div>
										<input id="passport_issued_by" name="passport_issued_by" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["passport_issued_by"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата выдачи паспорта</label>
						<div>
							<input autocomplete="off" id="passport_issued_date" placeholder="" name="passport_issued_date" type="text" class="form-control datepicker "  data-timepicker="0"  data-format="Y-m-d" value="'.(isset($item["passport_issued_date"])?((new DateTime($item["passport_issued_date"]))->format("Y-m-d")):date("Y-m-d")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Место работы</label>
									<div>
										<input id="workplace" name="workplace" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["workplace"]).'">
									</div>
								</div>

							


	               <div class="form-group">
	                 <label class="control-label" for="textinput">Ежемесячный доход</label>
	                 <div>
	                   <input id="salary" name="salary" type="number" step="0.01" class="form-control input-md " placeholder=""  value="'.htmlspecialchars($item["salary"]).'">
	                 </div>
	               </div>

	             


								<div class="form-group">
									<label class="control-label" for="textinput">Индекс по прописке</label>
									<div>
										<input id="reg_zip_code" name="reg_zip_code" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_zip_code"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Город по прописке</label>
									<div>
										<input id="reg_city" name="reg_city" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_city"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Улица по прописке</label>
									<div>
										<input id="reg_street" name="reg_street" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_street"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Дом по прописке</label>
									<div>
										<input id="reg_building" name="reg_building" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_building"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Квартира по прописке</label>
									<div>
										<input id="reg_apartment" name="reg_apartment" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_apartment"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Совпадают ли фактический и адрес прописки?</label>
							<div>
								<input id="is_address_matched" name="is_address_matched" class=""  type="checkbox"  value="1" '.($item["is_address_matched"]==1?"checked":"").'>
							</div>
						</div>

					


								<div class="form-group">
									<label class="control-label" for="textinput">Индекс по факту</label>
									<div>
										<input id="fact_zip_code" name="fact_zip_code" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_zip_code"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Город по факту</label>
									<div>
										<input id="fact_city" name="fact_city" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_city"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Улица по факту</label>
									<div>
										<input id="fact_street" name="fact_street" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_street"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Дом по факту</label>
									<div>
										<input id="fact_building" name="fact_building" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_building"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Квартира по факту</label>
									<div>
										<input id="fact_apartment" name="fact_apartment" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_apartment"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Телефон</label>
							<div>
								<input id="phone" name="phone" type="text" data-inp="phone" class="form-control input-md  " placeholder=""  value="'.htmlspecialchars($item["phone"]).'">
							</div>
						</div>

					


						<div class="form-group">
							<label class="control-label" for="textinput">Дополнительный телефон</label>
							<div>
								<input id="additional_phone" name="additional_phone" type="text" data-inp="phone" class="form-control input-md  " placeholder=""  value="'.htmlspecialchars($item["additional_phone"]).'">
							</div>
						</div>

					


								<div class="form-group">
									<label class="control-label" for="textinput">Email</label>
									<div>
										<input id="email" name="email" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["email"]).'">
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
									<label class="control-label" for="textinput">Фамилия</label>
									<div>
										<input id="last_name" name="last_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["last_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Имя</label>
									<div>
										<input id="first_name" name="first_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["first_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Отчество</label>
									<div>
										<input id="middle_name" name="middle_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["middle_name"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата рождения</label>
						<div>
							<input autocomplete="off" id="birth_date" placeholder="" name="birth_date" type="text" class="form-control datepicker "  data-timepicker="0"  data-format="Y-m-d" value="'.(isset($item["birth_date"])?((new DateTime($item["birth_date"]))->format("Y-m-d")):date("Y-m-d")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Место рождения</label>
									<div>
										<input id="birth_place" name="birth_place" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["birth_place"]).'">
									</div>
								</div>

							



				<div class="form-group">
					<label class="control-label" for="textinput">Пол</label>
					<div>
						<select id="sex" name="sex" class="form-control input-md ">
							<option value="male" '.($item["sex"]=="male"?"selected":"").'>мужской</option> 
<option value="female" '.($item["sex"]=="female"?"selected":"").'>женский</option> 

						</select>
					</div>
				</div>

			


						<div class="form-group">
							<label class="control-label" for="textinput">Менялась ли фамилия</label>
							<div>
								<input id="is_last_name_changed" name="is_last_name_changed" class=""  type="checkbox"  value="1" '.($item["is_last_name_changed"]==1?"checked":"").'>
							</div>
						</div>

					


								<div class="form-group">
									<label class="control-label" for="textinput">Предыдущая фамилия</label>
									<div>
										<input id="previous_last_name" name="previous_last_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["previous_last_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">ИНН</label>
									<div>
										<input id="tin" name="tin" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["tin"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">СНИЛС</label>
									<div>
										<input id="snils" name="snils" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["snils"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Серия и номер паспорта</label>
									<div>
										<input id="passport_number" name="passport_number" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["passport_number"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Код подразделения</label>
									<div>
										<input id="passport_division_code" name="passport_division_code" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["passport_division_code"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Кем выдан паспорт</label>
									<div>
										<input id="passport_issued_by" name="passport_issued_by" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["passport_issued_by"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата выдачи паспорта</label>
						<div>
							<input autocomplete="off" id="passport_issued_date" placeholder="" name="passport_issued_date" type="text" class="form-control datepicker "  data-timepicker="0"  data-format="Y-m-d" value="'.(isset($item["passport_issued_date"])?((new DateTime($item["passport_issued_date"]))->format("Y-m-d")):date("Y-m-d")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Место работы</label>
									<div>
										<input id="workplace" name="workplace" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["workplace"]).'">
									</div>
								</div>

							


	               <div class="form-group">
	                 <label class="control-label" for="textinput">Ежемесячный доход</label>
	                 <div>
	                   <input id="salary" name="salary" type="number" step="0.01" class="form-control input-md " placeholder=""  value="'.htmlspecialchars($item["salary"]).'">
	                 </div>
	               </div>

	             


								<div class="form-group">
									<label class="control-label" for="textinput">Индекс по прописке</label>
									<div>
										<input id="reg_zip_code" name="reg_zip_code" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_zip_code"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Город по прописке</label>
									<div>
										<input id="reg_city" name="reg_city" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_city"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Улица по прописке</label>
									<div>
										<input id="reg_street" name="reg_street" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_street"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Дом по прописке</label>
									<div>
										<input id="reg_building" name="reg_building" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_building"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Квартира по прописке</label>
									<div>
										<input id="reg_apartment" name="reg_apartment" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_apartment"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Совпадают ли фактический и адрес прописки?</label>
							<div>
								<input id="is_address_matched" name="is_address_matched" class=""  type="checkbox"  value="1" '.($item["is_address_matched"]==1?"checked":"").'>
							</div>
						</div>

					


								<div class="form-group">
									<label class="control-label" for="textinput">Индекс по факту</label>
									<div>
										<input id="fact_zip_code" name="fact_zip_code" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_zip_code"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Город по факту</label>
									<div>
										<input id="fact_city" name="fact_city" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_city"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Улица по факту</label>
									<div>
										<input id="fact_street" name="fact_street" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_street"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Дом по факту</label>
									<div>
										<input id="fact_building" name="fact_building" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_building"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Квартира по факту</label>
									<div>
										<input id="fact_apartment" name="fact_apartment" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_apartment"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Телефон</label>
							<div>
								<input id="phone" name="phone" type="text" data-inp="phone" class="form-control input-md  " placeholder=""  value="'.htmlspecialchars($item["phone"]).'">
							</div>
						</div>

					


						<div class="form-group">
							<label class="control-label" for="textinput">Дополнительный телефон</label>
							<div>
								<input id="additional_phone" name="additional_phone" type="text" data-inp="phone" class="form-control input-md  " placeholder=""  value="'.htmlspecialchars($item["additional_phone"]).'">
							</div>
						</div>

					


								<div class="form-group">
									<label class="control-label" for="textinput">Email</label>
									<div>
										<input id="email" name="email" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["email"]).'">
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
			$item = q("SELECT * FROM clients WHERE id=?",[$id]);
			$item = $item[0];
		}
		else
		{
			die("Ошибка. Редактирование несуществующей записи (вы не указали id)");
		}

		


		$html = '
			<h1 style="line-height: 30px"> Редактирование <br /><small>'."Клиенты".' #'.$id.'</small></h1>
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
									<label class="control-label" for="textinput">Фамилия</label>
									<div>
										<input id="last_name" name="last_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["last_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Имя</label>
									<div>
										<input id="first_name" name="first_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["first_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Отчество</label>
									<div>
										<input id="middle_name" name="middle_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["middle_name"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата рождения</label>
						<div>
							<input autocomplete="off" id="birth_date" placeholder="" name="birth_date" type="text" class="form-control datepicker "  data-timepicker="0"  data-format="Y-m-d" value="'.(isset($item["birth_date"])?((new DateTime($item["birth_date"]))->format("Y-m-d")):date("Y-m-d")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Место рождения</label>
									<div>
										<input id="birth_place" name="birth_place" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["birth_place"]).'">
									</div>
								</div>

							



				<div class="form-group">
					<label class="control-label" for="textinput">Пол</label>
					<div>
						<select id="sex" name="sex" class="form-control input-md ">
							<option value="male" '.($item["sex"]=="male"?"selected":"").'>мужской</option> 
<option value="female" '.($item["sex"]=="female"?"selected":"").'>женский</option> 

						</select>
					</div>
				</div>

			


						<div class="form-group">
							<label class="control-label" for="textinput">Менялась ли фамилия</label>
							<div>
								<input id="is_last_name_changed" name="is_last_name_changed" class=""  type="checkbox"  value="1" '.($item["is_last_name_changed"]==1?"checked":"").'>
							</div>
						</div>

					


								<div class="form-group">
									<label class="control-label" for="textinput">Предыдущая фамилия</label>
									<div>
										<input id="previous_last_name" name="previous_last_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["previous_last_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">ИНН</label>
									<div>
										<input id="tin" name="tin" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["tin"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">СНИЛС</label>
									<div>
										<input id="snils" name="snils" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["snils"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Паспорт</label>
									<div>
										<input id="passport_number" name="passport_number" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["passport_number"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Код подразделения</label>
									<div>
										<input id="passport_division_code" name="passport_division_code" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["passport_division_code"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Кем выдан паспорт</label>
									<div>
										<input id="passport_issued_by" name="passport_issued_by" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["passport_issued_by"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата выдачи паспорта</label>
						<div>
							<input autocomplete="off" id="passport_issued_date" placeholder="" name="passport_issued_date" type="text" class="form-control datepicker "  data-timepicker="0"  data-format="Y-m-d" value="'.(isset($item["passport_issued_date"])?((new DateTime($item["passport_issued_date"]))->format("Y-m-d")):date("Y-m-d")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Место работы</label>
									<div>
										<input id="workplace" name="workplace" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["workplace"]).'">
									</div>
								</div>

							


	               <div class="form-group">
	                 <label class="control-label" for="textinput">Ежемесячный доход</label>
	                 <div>
	                   <input id="salary" name="salary" type="number" step="0.01" class="form-control input-md " placeholder=""  value="'.htmlspecialchars($item["salary"]).'">
	                 </div>
	               </div>

	             


								<div class="form-group">
									<label class="control-label" for="textinput">Индекс по прописке</label>
									<div>
										<input id="reg_zip_code" name="reg_zip_code" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_zip_code"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Город по прописке</label>
									<div>
										<input id="reg_city" name="reg_city" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_city"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Улица по прописке</label>
									<div>
										<input id="reg_street" name="reg_street" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_street"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Дом по прописке</label>
									<div>
										<input id="reg_building" name="reg_building" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_building"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Квартира по прописке</label>
									<div>
										<input id="reg_apartment" name="reg_apartment" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_apartment"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Совпадают ли фактический и адрес прописки?</label>
							<div>
								<input id="is_address_matched" name="is_address_matched" class=""  type="checkbox"  value="1" '.($item["is_address_matched"]==1?"checked":"").'>
							</div>
						</div>

					


								<div class="form-group">
									<label class="control-label" for="textinput">Индекс по факту</label>
									<div>
										<input id="fact_zip_code" name="fact_zip_code" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_zip_code"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Город по факту</label>
									<div>
										<input id="fact_city" name="fact_city" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_city"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Улица по факту</label>
									<div>
										<input id="fact_street" name="fact_street" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_street"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Дом по факту</label>
									<div>
										<input id="fact_building" name="fact_building" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_building"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Квартира по факту</label>
									<div>
										<input id="fact_apartment" name="fact_apartment" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_apartment"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Телефон</label>
							<div>
								<input id="phone" name="phone" type="text" data-inp="phone" class="form-control input-md  " placeholder=""  value="'.htmlspecialchars($item["phone"]).'">
							</div>
						</div>

					


						<div class="form-group">
							<label class="control-label" for="textinput">Дополнительный телефон</label>
							<div>
								<input id="additional_phone" name="additional_phone" type="text" data-inp="phone" class="form-control input-md  " placeholder=""  value="'.htmlspecialchars($item["additional_phone"]).'">
							</div>
						</div>

					


								<div class="form-group">
									<label class="control-label" for="textinput">Email</label>
									<div>
										<input id="email" name="email" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["email"]).'">
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
			qi("UPDATE `clients` SET `` = ? WHERE id = ?", [$i, $line[$i]]);
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


		$sql = "INSERT IGNORE INTO clients (`last_name`, `first_name`, `middle_name`, `birth_date`, `birth_place`, `sex`, `is_last_name_changed`, `previous_last_name`, `tin`, `snils`, `passport_number`, `passport_division_code`, `passport_issued_by`, `passport_issued_date`, `workplace`, `salary`, `reg_zip_code`, `reg_city`, `reg_street`, `reg_building`, `reg_apartment`, `is_address_matched`, `fact_zip_code`, `fact_city`, `fact_street`, `fact_building`, `fact_apartment`, `phone`, `additional_phone`, `email`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$lines = preg_split("/\r\n|\n|\r/", $_REQUEST['csv']);
		$success_count = 0;
		$errors_count = 0;
		foreach($lines as $line)
		{
			$line = str_getcsv($line);
			qi($sql, [trim($line[0]), trim($line[1]), trim($line[2]), trim($line[3]), trim($line[4]), trim($line[5]), trim($line[6]), trim($line[7]), trim($line[8]), trim($line[9]), trim($line[10]), trim($line[11]), trim($line[12]), trim($line[13]), trim($line[14]), trim($line[15]), trim($line[16]), trim($line[17]), trim($line[18]), trim($line[19]), trim($line[20]), trim($line[21]), trim($line[22]), trim($line[23]), trim($line[24]), trim($line[25]), trim($line[26]), trim($line[27]), trim($line[28]), trim($line[29])]);
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
		$last_name = $_REQUEST['last_name'];
$first_name = $_REQUEST['first_name'];
$middle_name = $_REQUEST['middle_name'];
$birth_date = $_REQUEST['birth_date'];
$birth_place = $_REQUEST['birth_place'];
$sex = $_REQUEST['sex'];
$is_last_name_changed = $_REQUEST['is_last_name_changed'];
$previous_last_name = $_REQUEST['previous_last_name'];
$tin = $_REQUEST['tin'];
$snils = $_REQUEST['snils'];
$passport_number = $_REQUEST['passport_number'];
$passport_division_code = $_REQUEST['passport_division_code'];
$passport_issued_by = $_REQUEST['passport_issued_by'];
$passport_issued_date = $_REQUEST['passport_issued_date'];
$workplace = $_REQUEST['workplace'];
$salary = $_REQUEST['salary'];
$reg_zip_code = $_REQUEST['reg_zip_code'];
$reg_city = $_REQUEST['reg_city'];
$reg_street = $_REQUEST['reg_street'];
$reg_building = $_REQUEST['reg_building'];
$reg_apartment = $_REQUEST['reg_apartment'];
$is_address_matched = $_REQUEST['is_address_matched'];
$fact_zip_code = $_REQUEST['fact_zip_code'];
$fact_city = $_REQUEST['fact_city'];
$fact_street = $_REQUEST['fact_street'];
$fact_building = $_REQUEST['fact_building'];
$fact_apartment = $_REQUEST['fact_apartment'];
$phone = $_REQUEST['phone'];
$additional_phone = $_REQUEST['additional_phone'];
$email = $_REQUEST['email'];

		$params = [$last_name, $first_name, $middle_name, $birth_date, $birth_place, $sex, $is_last_name_changed, $previous_last_name, $tin, $snils, $passport_number, $passport_division_code, $passport_issued_by, $passport_issued_date, $workplace, $salary, $reg_zip_code, $reg_city, $reg_street, $reg_building, $reg_apartment, $is_address_matched, $fact_zip_code, $fact_city, $fact_street, $fact_building, $fact_apartment, $phone, $additional_phone, $email];
		$sql = "INSERT INTO clients (`last_name`, `first_name`, `middle_name`, `birth_date`, `birth_place`, `sex`, `is_last_name_changed`, `previous_last_name`, `tin`, `snils`, `passport_number`, `passport_division_code`, `passport_issued_by`, `passport_issued_date`, `workplace`, `salary`, `reg_zip_code`, `reg_city`, `reg_street`, `reg_building`, `reg_apartment`, `is_address_matched`, `fact_zip_code`, `fact_city`, `fact_street`, `fact_building`, `fact_apartment`, `phone`, `additional_phone`, `email`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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

			$set[] = is_null($_REQUEST['last_name'])?"`last_name`=NULL":"`last_name`='".addslashes($_REQUEST['last_name'])."'";
$set[] = is_null($_REQUEST['first_name'])?"`first_name`=NULL":"`first_name`='".addslashes($_REQUEST['first_name'])."'";
$set[] = is_null($_REQUEST['middle_name'])?"`middle_name`=NULL":"`middle_name`='".addslashes($_REQUEST['middle_name'])."'";
$set[] = is_null($_REQUEST['birth_date'])?"`birth_date`=NULL":"`birth_date`='".addslashes($_REQUEST['birth_date'])."'";
$set[] = is_null($_REQUEST['birth_place'])?"`birth_place`=NULL":"`birth_place`='".addslashes($_REQUEST['birth_place'])."'";
$set[] = is_null($_REQUEST['sex'])?"`sex`=NULL":"`sex`='".addslashes($_REQUEST['sex'])."'";
$set[] = is_null($_REQUEST['is_last_name_changed'])?"`is_last_name_changed`=NULL":"`is_last_name_changed`='".addslashes($_REQUEST['is_last_name_changed'])."'";
$set[] = is_null($_REQUEST['previous_last_name'])?"`previous_last_name`=NULL":"`previous_last_name`='".addslashes($_REQUEST['previous_last_name'])."'";
$set[] = is_null($_REQUEST['tin'])?"`tin`=NULL":"`tin`='".addslashes($_REQUEST['tin'])."'";
$set[] = is_null($_REQUEST['snils'])?"`snils`=NULL":"`snils`='".addslashes($_REQUEST['snils'])."'";
$set[] = is_null($_REQUEST['passport_number'])?"`passport_number`=NULL":"`passport_number`='".addslashes($_REQUEST['passport_number'])."'";
$set[] = is_null($_REQUEST['passport_division_code'])?"`passport_division_code`=NULL":"`passport_division_code`='".addslashes($_REQUEST['passport_division_code'])."'";
$set[] = is_null($_REQUEST['passport_issued_by'])?"`passport_issued_by`=NULL":"`passport_issued_by`='".addslashes($_REQUEST['passport_issued_by'])."'";
$set[] = is_null($_REQUEST['passport_issued_date'])?"`passport_issued_date`=NULL":"`passport_issued_date`='".addslashes($_REQUEST['passport_issued_date'])."'";
$set[] = is_null($_REQUEST['workplace'])?"`workplace`=NULL":"`workplace`='".addslashes($_REQUEST['workplace'])."'";
$set[] = is_null($_REQUEST['salary'])?"`salary`=NULL":"`salary`='".addslashes($_REQUEST['salary'])."'";
$set[] = is_null($_REQUEST['reg_zip_code'])?"`reg_zip_code`=NULL":"`reg_zip_code`='".addslashes($_REQUEST['reg_zip_code'])."'";
$set[] = is_null($_REQUEST['reg_city'])?"`reg_city`=NULL":"`reg_city`='".addslashes($_REQUEST['reg_city'])."'";
$set[] = is_null($_REQUEST['reg_street'])?"`reg_street`=NULL":"`reg_street`='".addslashes($_REQUEST['reg_street'])."'";
$set[] = is_null($_REQUEST['reg_building'])?"`reg_building`=NULL":"`reg_building`='".addslashes($_REQUEST['reg_building'])."'";
$set[] = is_null($_REQUEST['reg_apartment'])?"`reg_apartment`=NULL":"`reg_apartment`='".addslashes($_REQUEST['reg_apartment'])."'";
$set[] = is_null($_REQUEST['is_address_matched'])?"`is_address_matched`=NULL":"`is_address_matched`='".addslashes($_REQUEST['is_address_matched'])."'";
$set[] = is_null($_REQUEST['fact_zip_code'])?"`fact_zip_code`=NULL":"`fact_zip_code`='".addslashes($_REQUEST['fact_zip_code'])."'";
$set[] = is_null($_REQUEST['fact_city'])?"`fact_city`=NULL":"`fact_city`='".addslashes($_REQUEST['fact_city'])."'";
$set[] = is_null($_REQUEST['fact_street'])?"`fact_street`=NULL":"`fact_street`='".addslashes($_REQUEST['fact_street'])."'";
$set[] = is_null($_REQUEST['fact_building'])?"`fact_building`=NULL":"`fact_building`='".addslashes($_REQUEST['fact_building'])."'";
$set[] = is_null($_REQUEST['fact_apartment'])?"`fact_apartment`=NULL":"`fact_apartment`='".addslashes($_REQUEST['fact_apartment'])."'";
$set[] = is_null($_REQUEST['phone'])?"`phone`=NULL":"`phone`='".addslashes($_REQUEST['phone'])."'";
$set[] = is_null($_REQUEST['additional_phone'])?"`additional_phone`=NULL":"`additional_phone`='".addslashes($_REQUEST['additional_phone'])."'";
$set[] = is_null($_REQUEST['email'])?"`email`=NULL":"`email`='".addslashes($_REQUEST['email'])."'";

			if(count($set)>0)
			{
				$set = implode(", ", $set);
				$sql = "UPDATE clients SET $set WHERE id=?";
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
			qi("DELETE FROM clients WHERE id=?", [$id]);
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

		

		if(isset2($_REQUEST['last_name_filter']))
		{
			$filters[] = "`last_name` LIKE '%{$_REQUEST['last_name_filter']}%'";
		}
				

		if(isset2($_REQUEST['first_name_filter']))
		{
			$filters[] = "`first_name` LIKE '%{$_REQUEST['first_name_filter']}%'";
		}
				

		if(isset2($_REQUEST['middle_name_filter']))
		{
			$filters[] = "`middle_name` LIKE '%{$_REQUEST['middle_name_filter']}%'";
		}
				

		if(isset2($_REQUEST['birth_date_filter_from']) && isset2($_REQUEST['birth_date_filter_to']))
		{
			$filters[] = "birth_date >= '{$_REQUEST['birth_date_filter_from']}' AND birth_date <= '{$_REQUEST['birth_date_filter_to']}'";
		}

		

		if(isset2($_REQUEST['birth_place_filter']))
		{
			$filters[] = "`birth_place` LIKE '%{$_REQUEST['birth_place_filter']}%'";
		}
				

		if(isset2($_REQUEST['sex_filter']))
		{
			$filters[] = "`sex` = '{$_REQUEST['sex_filter']}'";
		}
				

if(isset2($_REQUEST['is_last_name_changed_filter']))
{
  $filters[] = "`is_last_name_changed` = '{$_REQUEST['is_last_name_changed_filter']}'";
}
    

		if(isset2($_REQUEST['previous_last_name_filter']))
		{
			$filters[] = "`previous_last_name` LIKE '%{$_REQUEST['previous_last_name_filter']}%'";
		}
				

		if(isset2($_REQUEST['tin_filter_from']) && isset2($_REQUEST['tin_filter_to']))
		{
			$filters[] = "tin >= {$_REQUEST['tin_filter_from']} AND tin <= {$_REQUEST['tin_filter_to']}";
		}

		

		if(isset2($_REQUEST['snils_filter']))
		{
			$filters[] = "`snils` LIKE '%{$_REQUEST['snils_filter']}%'";
		}
				

		if(isset2($_REQUEST['passport_number_filter']))
		{
			$filters[] = "`passport_number` LIKE '%{$_REQUEST['passport_number_filter']}%'";
		}
				

		if(isset2($_REQUEST['passport_division_code_filter']))
		{
			$filters[] = "`passport_division_code` LIKE '%{$_REQUEST['passport_division_code_filter']}%'";
		}
				

		if(isset2($_REQUEST['passport_issued_by_filter']))
		{
			$filters[] = "`passport_issued_by` LIKE '%{$_REQUEST['passport_issued_by_filter']}%'";
		}
				

		if(isset2($_REQUEST['passport_issued_date_filter_from']) && isset2($_REQUEST['passport_issued_date_filter_to']))
		{
			$filters[] = "passport_issued_date >= '{$_REQUEST['passport_issued_date_filter_from']}' AND passport_issued_date <= '{$_REQUEST['passport_issued_date_filter_to']}'";
		}

		

		if(isset2($_REQUEST['workplace_filter']))
		{
			$filters[] = "`workplace` LIKE '%{$_REQUEST['workplace_filter']}%'";
		}
				

		if(isset2($_REQUEST['salary_filter_from']) && isset2($_REQUEST['salary_filter_to']))
		{
			$filters[] = "salary >= {$_REQUEST['salary_filter_from']} AND salary <= {$_REQUEST['salary_filter_to']}";
		}

		

		if(isset2($_REQUEST['reg_zip_code_filter']))
		{
			$filters[] = "`reg_zip_code` LIKE '%{$_REQUEST['reg_zip_code_filter']}%'";
		}
				

		if(isset2($_REQUEST['reg_city_filter']))
		{
			$filters[] = "`reg_city` LIKE '%{$_REQUEST['reg_city_filter']}%'";
		}
				

		if(isset2($_REQUEST['reg_street_filter']))
		{
			$filters[] = "`reg_street` LIKE '%{$_REQUEST['reg_street_filter']}%'";
		}
				

		if(isset2($_REQUEST['reg_building_filter']))
		{
			$filters[] = "`reg_building` LIKE '%{$_REQUEST['reg_building_filter']}%'";
		}
				

		if(isset2($_REQUEST['reg_apartment_filter']))
		{
			$filters[] = "`reg_apartment` LIKE '%{$_REQUEST['reg_apartment_filter']}%'";
		}
				

if(isset2($_REQUEST['is_address_matched_filter']))
{
  $filters[] = "`is_address_matched` = '{$_REQUEST['is_address_matched_filter']}'";
}
    

		if(isset2($_REQUEST['fact_zip_code_filter']))
		{
			$filters[] = "`fact_zip_code` LIKE '%{$_REQUEST['fact_zip_code_filter']}%'";
		}
				

		if(isset2($_REQUEST['fact_city_filter']))
		{
			$filters[] = "`fact_city` LIKE '%{$_REQUEST['fact_city_filter']}%'";
		}
				

		if(isset2($_REQUEST['fact_street_filter']))
		{
			$filters[] = "`fact_street` LIKE '%{$_REQUEST['fact_street_filter']}%'";
		}
				

		if(isset2($_REQUEST['fact_building_filter']))
		{
			$filters[] = "`fact_building` LIKE '%{$_REQUEST['fact_building_filter']}%'";
		}
				

		if(isset2($_REQUEST['fact_apartment_filter']))
		{
			$filters[] = "`fact_apartment` LIKE '%{$_REQUEST['fact_apartment_filter']}%'";
		}
				

		if(isset2($_REQUEST['phone_filter']))
		{
			$filters[] = "`phone` LIKE '%{$_REQUEST['phone_filter']}%'";
		}
				

		if(isset2($_REQUEST['additional_phone_filter']))
		{
			$filters[] = "`additional_phone` LIKE '%{$_REQUEST['additional_phone_filter']}%'";
		}
				

		if(isset2($_REQUEST['email_filter']))
		{
			$filters[] = "`email` LIKE '%{$_REQUEST['email_filter']}%'";
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
		$sex_values = '[
 {
 "text": "мужской",
 "value": "male"
 },
 {
 "text": "женский",
 "value": "female"
 }
]';
		$sex_values_text = "";
		foreach(json_decode($sex_values, true) as $opt)
		{
			$sex_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
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
				

		if(isset2($_REQUEST['last_name_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='last_name_filter' value='{$_REQUEST['last_name_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Фамилия: <b>{$_REQUEST['last_name_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['first_name_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='first_name_filter' value='{$_REQUEST['first_name_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Имя: <b>{$_REQUEST['first_name_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['middle_name_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='middle_name_filter' value='{$_REQUEST['middle_name_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Отчество: <b>{$_REQUEST['middle_name_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['birth_date_filter_from']))
		{
			$from = date('d.m.Y', strtotime($_REQUEST['birth_date_filter_from']));
			$to = date('d.m.Y', strtotime($_REQUEST['birth_date_filter_to']));
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='birth_date_filter_from' value='{$_REQUEST['birth_date_filter_from']}'>
					<input type='hidden' class='filter' name='birth_date_filter_to' value='{$_REQUEST['birth_date_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Дата рождения: <b>{$from}–{$to}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['birth_place_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='birth_place_filter' value='{$_REQUEST['birth_place_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Место рождения: <b>{$_REQUEST['birth_place_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		$text_option = array_filter(json_decode($sex_values, true), function($i)
		{
			return $i['value']==$_REQUEST['sex_filter'];
		});
		$text_option = array_values($text_option)[0]['text'];
		if(isset2($_REQUEST['sex_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='sex_filter' value='{$_REQUEST['sex_filter']}'>
					<span class='fa fa-times remove-tag'></span> Пол: <b>{$text_option}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

if(isset2($_REQUEST['is_last_name_changed_filter']))
{
  $filter_divs .= "
  <div class='filter-tag'>
      <input type='hidden' class='filter' name='is_last_name_changed_filter' value='{$_REQUEST['is_last_name_changed_filter']}'>
       <span class='fa fa-times remove-tag'></span> Менялась ли фамилия: <b>".($_REQUEST['is_last_name_changed_filter']?"Вкл":"Выкл")."</b>
  </div>";

  $filter_caption = "Фильтры: ";
}



		if(isset2($_REQUEST['previous_last_name_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='previous_last_name_filter' value='{$_REQUEST['previous_last_name_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Предыдущая фамилия: <b>{$_REQUEST['previous_last_name_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['tin_filter_from']) && isset2($_REQUEST['tin_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='tin_filter_from' value='{$_REQUEST['tin_filter_from']}'>
					<input type='hidden' class='filter' name='tin_filter_to' value='{$_REQUEST['tin_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> ИНН: <b>{$_REQUEST['tin_filter_from']}–{$_REQUEST['tin_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['snils_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='snils_filter' value='{$_REQUEST['snils_filter']}'>
				   <span class='fa fa-times remove-tag'></span> СНИЛС: <b>{$_REQUEST['snils_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['passport_number_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='passport_number_filter' value='{$_REQUEST['passport_number_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Паспорт: <b>{$_REQUEST['passport_number_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['passport_division_code_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='passport_division_code_filter' value='{$_REQUEST['passport_division_code_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Код подразделения: <b>{$_REQUEST['passport_division_code_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['passport_issued_by_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='passport_issued_by_filter' value='{$_REQUEST['passport_issued_by_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Кем выдан паспорт: <b>{$_REQUEST['passport_issued_by_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['passport_issued_date_filter_from']))
		{
			$from = date('d.m.Y', strtotime($_REQUEST['passport_issued_date_filter_from']));
			$to = date('d.m.Y', strtotime($_REQUEST['passport_issued_date_filter_to']));
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='passport_issued_date_filter_from' value='{$_REQUEST['passport_issued_date_filter_from']}'>
					<input type='hidden' class='filter' name='passport_issued_date_filter_to' value='{$_REQUEST['passport_issued_date_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Дата выдачи паспорта: <b>{$from}–{$to}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['workplace_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='workplace_filter' value='{$_REQUEST['workplace_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Место работы: <b>{$_REQUEST['workplace_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['salary_filter_from']) && isset2($_REQUEST['salary_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='salary_filter_from' value='{$_REQUEST['salary_filter_from']}'>
					<input type='hidden' class='filter' name='salary_filter_to' value='{$_REQUEST['salary_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Ежемесячный доход: <b>{$_REQUEST['salary_filter_from']}–{$_REQUEST['salary_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['reg_zip_code_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='reg_zip_code_filter' value='{$_REQUEST['reg_zip_code_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Индекс по прописке: <b>{$_REQUEST['reg_zip_code_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['reg_city_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='reg_city_filter' value='{$_REQUEST['reg_city_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Город по прописке: <b>{$_REQUEST['reg_city_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['reg_street_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='reg_street_filter' value='{$_REQUEST['reg_street_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Улица по прописке: <b>{$_REQUEST['reg_street_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['reg_building_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='reg_building_filter' value='{$_REQUEST['reg_building_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Дом по прописке: <b>{$_REQUEST['reg_building_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['reg_apartment_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='reg_apartment_filter' value='{$_REQUEST['reg_apartment_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Квартира по прописке: <b>{$_REQUEST['reg_apartment_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

if(isset2($_REQUEST['is_address_matched_filter']))
{
  $filter_divs .= "
  <div class='filter-tag'>
      <input type='hidden' class='filter' name='is_address_matched_filter' value='{$_REQUEST['is_address_matched_filter']}'>
       <span class='fa fa-times remove-tag'></span> Совпадают ли фактический и адрес прописки?: <b>".($_REQUEST['is_address_matched_filter']?"Вкл":"Выкл")."</b>
  </div>";

  $filter_caption = "Фильтры: ";
}



		if(isset2($_REQUEST['fact_zip_code_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='fact_zip_code_filter' value='{$_REQUEST['fact_zip_code_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Индекс по факту: <b>{$_REQUEST['fact_zip_code_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['fact_city_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='fact_city_filter' value='{$_REQUEST['fact_city_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Город по факту: <b>{$_REQUEST['fact_city_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['fact_street_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='fact_street_filter' value='{$_REQUEST['fact_street_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Улица по факту: <b>{$_REQUEST['fact_street_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['fact_building_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='fact_building_filter' value='{$_REQUEST['fact_building_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Дом по факту: <b>{$_REQUEST['fact_building_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['fact_apartment_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='fact_apartment_filter' value='{$_REQUEST['fact_apartment_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Квартира по факту: <b>{$_REQUEST['fact_apartment_filter']}</b>
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

		

		if(isset2($_REQUEST['additional_phone_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='additional_phone_filter' value='{$_REQUEST['additional_phone_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Дополнительный телефон: <b>{$_REQUEST['additional_phone_filter']}</b>
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

		
		$show = $filter_caption.$filter_divs;

		return $show;
	}

	function get_agregate()
	{

		$items = [];

		$srch = "";
		
			if($_REQUEST['srch-term'])
			{
				$srch = "WHERE ((`last_name` LIKE '%{$_REQUEST['srch-term']}%') or (`first_name` LIKE '%{$_REQUEST['srch-term']}%') or (`middle_name` LIKE '%{$_REQUEST['srch-term']}%'))";
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

		$sql = "SELECT 1 as stub  FROM (SELECT main_table.*  FROM clients main_table) temp $srch $filter $where $order";

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
				$srch = "WHERE ((`last_name` LIKE '%{$_REQUEST['srch-term']}%') or (`first_name` LIKE '%{$_REQUEST['srch-term']}%') or (`middle_name` LIKE '%{$_REQUEST['srch-term']}%'))";
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
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT  main_table.*  FROM clients main_table) temp $srch $filter $where $order LIMIT :start, :limit";
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
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT main_table.*  FROM clients main_table) temp $srch $filter $where $order";
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
	echo masterRender("Клиенты", $content, 17);
