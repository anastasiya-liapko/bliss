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

	// через этот метод проходит каждый td нашей таблицы. В переменной $item хранится информация об объекте, чью строчку мы сейчас отрисовывам. В $column информация о таблице, которую мы отрисовываем. Если возвращать '', то из таблицы можно скрывать какой-то столбец
function processTD( $html, $item, $column ) 
{
 return $html;
}

// через этот метод проходит HTML каждого tr. Можно например добавить свой столбец. 
function processTR($html, $item)
{
	return $html;
}

// HTML всей таблицы целиком. Можно после таблицы добавить каких-то графиков и тд
function processTable($html)
{
 if( isset( $_REQUEST['back'] ) ) {
 $html = '<p><a href="#" onclick="history.back();" class="back-btn" style="display: inline;"><span class="fa fa-arrow-circle-left"></span> ' . $_REQUEST['back'] . '</a></p>' . $html;
 }

	return $html;
}

// HTML всей таблицы. При помощи str_replace можешь поменять все что угодно на всей странице
function processPage($html)
{
	return $html;
}

// изменение. Если вернуть false то изменение не произойдет, но никакой ошибки не будет показано. Если хочешь показать ошибку — покажи ее сам при помощи buildMsg();
function allowUpdate()
{
	return true;
}

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
			
   		$client_id_values = json_encode(q("SELECT CONCAT(last_name, ' ', first_name, ' ', middle_name, ' (ID', id, ')') AS text, id as value FROM clients", []));
				$client_id_values_text = "";
					foreach(json_decode($client_id_values, true) as $opt)
					{
					  $client_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
					}
$shop_id_values = json_encode(q("SELECT CONCAT(name, ' (ID', id, ')') AS text, id as value FROM shops", []));
				$shop_id_values_text = "";
					foreach(json_decode($shop_id_values, true) as $opt)
					{
					  $shop_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
					}
$approved_mfi_id_values = json_encode(q("SELECT CONCAT(name, ' (ID', id, ')') AS text, id as value FROM mfi", []));
				$approved_mfi_id_values_text = "";
					foreach(json_decode($approved_mfi_id_values, true) as $opt)
					{
					  $approved_mfi_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
					}
$status_values = '[
 {
 "text": "В рассмотрении",
 "value": "pending"
 },
 {
 "text": "Отклонена",
 "value": "declined"
 },
 {
 "text": "Отменена клиентом",
 "value": "canceled"
 },
 {
 "text": "Не хватило времени",
 "value": "manual"
 },
 {
 "text": "Одобрена",
 "value": "approved"
 },
 {
 "text": "Подтверждена клиентом",
 "value": "confirmed"
 },
{
 "text": "Ожидает одобрения лимита",
 "value": "waiting_for_limit"
 }
]';
		$status_values_text = "";
		foreach(json_decode($status_values, true) as $opt)
		{
			$status_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		

		list($items, $pagination, $cnt) = get_data();

		$sort_order[$_REQUEST['sort_by']] = $_REQUEST['sort_order'];

$next_order['id']='asc';
$next_order['client_id']='asc';
$next_order['shop_id']='asc';
$next_order['approved_mfi_id']='asc';
$next_order['order_id']='asc';
$next_order['order_id']='asc';
$next_order['is_test_mode_enabled']='asc';
$next_order['is_loan_postponed']='asc';
$next_order['status']='asc';
$next_order['time_start']='asc';
$next_order['time_finish']='asc';
$next_order['']='asc';

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
					$(\'.big-icon\').html(\'<i class="fas fa-clipboard-list"></i>\');
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
							<small>Вставьте сюда новые записи. Каждая запись на новой строчке: <b class="csv-create-format"></b></small>
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
				<h2><a href="#" class="back-btn"><span class="fa fa-arrow-circle-left"></span></a> '."Заявки на кредит".' </h2>
				
				<p class="small res-cnt">Кол-во результатов: <span class="cnt-number-span">'.$cnt.'</span></p>
			</div>
			
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
    				<label>МФО</label>
					<select class="form-control filter-select approved_mfi_id-extra-filter" name="approved_mfi_id_filter" style="width:100%">
					<option value="">----------</option>
					'. $approved_mfi_id_values_text .'
					</select>
  				</div>
				<script>
					document.addEventListener("DOMContentLoaded", (event) =>
					{
  						$(".approved_mfi_id-extra-filter").val('.($_REQUEST['approved_mfi_id_filter'] ?? "''").').trigger("change");
					});
				</script>
			

			<div class="form-group">
				<label style="display:block;">ID заказа</label>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="order_id_filter_from" value="'.$_REQUEST['order_id_filter_from'].'" placeholder="От" style="display:inline; width:49%;"/>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="order_id_filter_to" value="'.$_REQUEST['order_id_filter_to'].'" placeholder="До" style="display:inline; width:49%; float:right;"/>
			</div>

			<div class="form-group">
				<label style="display:block;">ID заказа в системе Блисс</label>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="order_id_filter_from" value="'.$_REQUEST['order_id_filter_from'].'" placeholder="От" style="display:inline; width:49%;"/>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="order_id_filter_to" value="'.$_REQUEST['order_id_filter_to'].'" placeholder="До" style="display:inline; width:49%; float:right;"/>
			</div>

				<div class="form-group">
					<label>Тестовый режим</label>
					<select class="form-control filter-select  is_test_mode_enabled-extra-filter" name="is_test_mode_enabled_filter" style="width:100%">
						<option value="">----------</option>
						<option value="0">Нет</option>
						<option value="1">Да</option>
					</select>
				</div>
				<script>
					document.addEventListener("DOMContentLoaded", (event) =>
					{
  						$(".is_test_mode_enabled-extra-filter").val('.($_REQUEST['is_test_mode_enabled_filter'] ?? "''").').trigger("change");
					});
				</script>
			

				<div class="form-group">
					<label>Кредит отложенный?</label>
					<select class="form-control filter-select  is_loan_postponed-extra-filter" name="is_loan_postponed_filter" style="width:100%">
						<option value="">----------</option>
						<option value="0">Нет</option>
						<option value="1">Да</option>
					</select>
				</div>
				<script>
					document.addEventListener("DOMContentLoaded", (event) =>
					{
  						$(".is_loan_postponed-extra-filter").val('.($_REQUEST['is_loan_postponed_filter'] ?? "''").').trigger("change");
					});
				</script>
			

			<div class="form-group">
				<label>Закрытие</label>
				<input autocomplete="off" type="text" class="form-control daterange filter-date-range time_finish-extra-filter" name="time_finish_filter" style="width:100%;">
			</div>
			<script>
				document.addEventListener("DOMContentLoaded", (event) =>
				{
					$("#js-extra-filters-modal").on("shown.bs.modal", function()
					{
						if("'.($_REQUEST['time_finish_filter_from'] ?? 0).'"!="0")
						{
							$(".time_finish-extra-filter").data("daterangepicker").setStartDate("'.date("d-m-Y", strtotime($_REQUEST['time_finish_filter_from'])).'");
						  	$(".time_finish-extra-filter").data("daterangepicker").setEndDate("'.date("d-m-Y", strtotime($_REQUEST['time_finish_filter_to'])).'");
							$(".created_ts-extra-filter").trigger(\'apply\');
						}

					});
				});
			</script>
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
			<div class="data-container genesis-presentation-table  " id="tableMain">
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
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=client_id&sort_order='. ($next_order['client_id']) .'\' class=\'sort\' column=\'client_id\' sort_order=\''.$sort_order['client_id'].'\'>Покупатель'. $sort_icon['client_id'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<select class="form-control filter-select" name="client_id_filter">


							'.str_replace(chr(39), '&#39;', $client_id_values_text).'


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
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=status&sort_order='. ($next_order['status']) .'\' class=\'sort\' column=\'status\' sort_order=\''.$sort_order['status'].'\'>Статус'. $sort_icon['status'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<select class="form-control filter-select" name="status_filter">


							'.str_replace(chr(39), '&#39;', $status_values_text).'


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
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=time_start&sort_order='. ($next_order['time_start']) .'\' class=\'sort\' column=\'time_start\' sort_order=\''.$sort_order['time_start'].'\'>Подача'. $sort_icon['time_start'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input autocomplete="off" type="text" class="form-control daterange filter-date-range" name="time_start_filter">
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>


			<div class="genesis-header-property">
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=&sort_order='. ($next_order['']) .'\' class=\'sort\' column=\'\' sort_order=\''.$sort_order[''].'\'>Ответы'. $sort_icon[''].'</a>
			</div>
					
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
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
				<span class='genesis-attached-column-info'>
					<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=client_id&sort_order='. ($next_order['client_id']) .'\' class=\'sort\' column=\'client_id\' sort_order=\''.$sort_order['client_id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['client_id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<select class=\"form-control filter-select\" name=\"client_id_filter\">


							".str_replace(chr(39), '&#39;', $client_id_values_text)."


							</select>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
					<span class='genesis-attached-column-name'>Покупатель:</span>
				</span>
				<span >".$item['client_id_text']."</div>", $item, "Покупатель"):"<div class='genesis-item-property '>
				<span class='genesis-attached-column-info'>
					<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=client_id&sort_order='. ($next_order['client_id']) .'\' class=\'sort\' column=\'client_id\' sort_order=\''.$sort_order['client_id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['client_id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<select class=\"form-control filter-select\" name=\"client_id_filter\">


							".str_replace(chr(39), '&#39;', $client_id_values_text)."


							</select>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
					<span class='genesis-attached-column-name'>Покупатель:</span>
				</span>
				<span >".$item['client_id_text']."</div>")."
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
				</span>
				<span >".$item['shop_id_text']."</div>", $item, "Магазин"):"<div class='genesis-item-property '>
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
				</span>
				<span >".$item['shop_id_text']."</div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=status&sort_order='. ($next_order['status']) .'\' class=\'sort\' column=\'status\' sort_order=\''.$sort_order['status'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['status'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<select class=\"form-control filter-select\" name=\"status_filter\">


							".str_replace(chr(39), '&#39;', $status_values_text)."


							</select>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Статус:</span>
		</span>
		<span>".select_mapping($status_values, $item['status'])."</span></div>", $item, "Статус"):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=status&sort_order='. ($next_order['status']) .'\' class=\'sort\' column=\'status\' sort_order=\''.$sort_order['status'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['status'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<select class=\"form-control filter-select\" name=\"status_filter\">


							".str_replace(chr(39), '&#39;', $status_values_text)."


							</select>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Статус:</span>
		</span>
		<span>".select_mapping($status_values, $item['status'])."</span></div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=time_start&sort_order='. ($next_order['time_start']) .'\' class=\'sort\' column=\'time_start\' sort_order=\''.$sort_order['time_start'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['time_start'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input autocomplete=\"off\" type=\"text\" class=\"form-control daterange filter-date-range\" name=\"time_start_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Подача:</span>
		</span><span>".(new DateTime(($item['time_start']?$item['time_start']:"1970-01-01 00:00:00") ))->format('Y-m-d H:i')."</span></div>", $item, "Подача"):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=time_start&sort_order='. ($next_order['time_start']) .'\' class=\'sort\' column=\'time_start\' sort_order=\''.$sort_order['time_start'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['time_start'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input autocomplete=\"off\" type=\"text\" class=\"form-control daterange filter-date-range\" name=\"time_start_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Подача:</span>
		</span><span>".(new DateTime(($item['time_start']?$item['time_start']:"1970-01-01 00:00:00") ))->format('Y-m-d H:i')."</span></div>")."
".(function_exists("processTD")?processTD("
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'></span>
			<span class='genesis-attached-column-name'>Ответы:</span>
		</span>
		".((function($item)
{
 $result = q1("SELECT COUNT(id) AS requests_total FROM mfi_responses WHERE request_id = :request_id", [':request_id' => $item['id']]);
 
	return '<div class="text-center genesis-button-container">
			 <a href="mfi_responses.php?request_id_filter_from=' . $item['id'] . '&request_id_filter_to=' . $item['id'] . '" class="btn btn-primary btn-genesis">
				 ' . $result['requests_total'] . ' <i class="fas fa-arrow-circle-right"></i>
		 	</a>
 		</div>';
})($item))."
	</div>", $item, "Ответы"):"
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'></span>
			<span class='genesis-attached-column-name'>Ответы:</span>
		</span>
		".((function($item)
{
 $result = q1("SELECT COUNT(id) AS requests_total FROM mfi_responses WHERE request_id = :request_id", [':request_id' => $item['id']]);
 
	return '<div class="text-center genesis-button-container">
			 <a href="mfi_responses.php?request_id_filter_from=' . $item['id'] . '&request_id_filter_to=' . $item['id'] . '" class="btn btn-primary btn-genesis">
				 ' . $result['requests_total'] . ' <i class="fas fa-arrow-circle-right"></i>
		 	</a>
 		</div>';
})($item))."
	</div>")."
					
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
			$item = q("SELECT * FROM requests WHERE id=?",[$id]);
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
			$item = q("SELECT * FROM requests WHERE id=?",[$id]);
			$item = $item[0];
		}
		else
		{
			die("Ошибка. Редактирование несуществующей записи (вы не указали id)");
		}

		


		$html = '
			<h1 style="line-height: 30px"> Редактирование <br /><small>'."Заявки на кредит".' #'.$id.'</small></h1>
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
			qi("UPDATE `requests` SET `` = ? WHERE id = ?", [$i, $line[$i]]);
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


		$sql = "INSERT IGNORE INTO requests () VALUES ()";

		$lines = preg_split("/\r\n|\n|\r/", $_REQUEST['csv']);
		$success_count = 0;
		$errors_count = 0;
		foreach($lines as $line)
		{
			$line = str_getcsv($line);
			qi($sql, []);
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
		

		$params = [];
		$sql = "INSERT INTO requests () VALUES ()";
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

			

			if(count($set)>0)
			{
				$set = implode(", ", $set);
				$sql = "UPDATE requests SET $set WHERE id=?";
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
			qi("DELETE FROM requests WHERE id=?", [$id]);
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

		

		if(isset2($_REQUEST['client_id_filter']))
		{
			$filters[] = "`client_id` = '{$_REQUEST['client_id_filter']}'";
		}
				

		if(isset2($_REQUEST['shop_id_filter']))
		{
			$filters[] = "`shop_id` = '{$_REQUEST['shop_id_filter']}'";
		}
				

		if(isset2($_REQUEST['approved_mfi_id_filter']))
		{
			$filters[] = "`approved_mfi_id` = '{$_REQUEST['approved_mfi_id_filter']}'";
		}
				

		if(isset2($_REQUEST['order_id_filter_from']) && isset2($_REQUEST['order_id_filter_to']))
		{
			$filters[] = "order_id >= {$_REQUEST['order_id_filter_from']} AND order_id <= {$_REQUEST['order_id_filter_to']}";
		}

		

		if(isset2($_REQUEST['order_id_filter_from']) && isset2($_REQUEST['order_id_filter_to']))
		{
			$filters[] = "order_id >= {$_REQUEST['order_id_filter_from']} AND order_id <= {$_REQUEST['order_id_filter_to']}";
		}

		

if(isset2($_REQUEST['is_test_mode_enabled_filter']))
{
  $filters[] = "`is_test_mode_enabled` = '{$_REQUEST['is_test_mode_enabled_filter']}'";
}
    

if(isset2($_REQUEST['is_loan_postponed_filter']))
{
  $filters[] = "`is_loan_postponed` = '{$_REQUEST['is_loan_postponed_filter']}'";
}
    

		if(isset2($_REQUEST['status_filter']))
		{
			$filters[] = "`status` = '{$_REQUEST['status_filter']}'";
		}
				

		if(isset2($_REQUEST['time_start_filter_from']) && isset2($_REQUEST['time_start_filter_to']))
		{
			$filters[] = "time_start >= '{$_REQUEST['time_start_filter_from']}' AND time_start <= '{$_REQUEST['time_start_filter_to']}'";
		}

		

		if(isset2($_REQUEST['time_finish_filter_from']) && isset2($_REQUEST['time_finish_filter_to']))
		{
			$filters[] = "time_finish >= '{$_REQUEST['time_finish_filter_from']}' AND time_finish <= '{$_REQUEST['time_finish_filter_to']}'";
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
		$client_id_values = json_encode(q("SELECT CONCAT(last_name, ' ', first_name, ' ', middle_name, ' (ID', id, ')') AS text, id as value FROM clients", []));
				$client_id_values_text = "";
					foreach(json_decode($client_id_values, true) as $opt)
					{
					  $client_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
					}
$shop_id_values = json_encode(q("SELECT CONCAT(name, ' (ID', id, ')') AS text, id as value FROM shops", []));
				$shop_id_values_text = "";
					foreach(json_decode($shop_id_values, true) as $opt)
					{
					  $shop_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
					}
$approved_mfi_id_values = json_encode(q("SELECT CONCAT(name, ' (ID', id, ')') AS text, id as value FROM mfi", []));
				$approved_mfi_id_values_text = "";
					foreach(json_decode($approved_mfi_id_values, true) as $opt)
					{
					  $approved_mfi_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
					}
$status_values = '[
 {
 "text": "В рассмотрении",
 "value": "pending"
 },
 {
 "text": "Отклонена",
 "value": "declined"
 },
 {
 "text": "Отменена клиентом",
 "value": "canceled"
 },
 {
 "text": "Не хватило времени",
 "value": "manual"
 },
 {
 "text": "Одобрена",
 "value": "approved"
 },
 {
 "text": "Подтверждена клиентом",
 "value": "confirmed"
 },
{
 "text": "Ожидает одобрения лимита",
 "value": "waiting_for_limit"
 }
]';
		$status_values_text = "";
		foreach(json_decode($status_values, true) as $opt)
		{
			$status_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
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
				

		$text_option = array_filter(json_decode($client_id_values, true), function($i)
		{
			return $i['value']==$_REQUEST['client_id_filter'];
		});
		$text_option = array_values($text_option)[0]['text'];
		if(isset2($_REQUEST['client_id_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='client_id_filter' value='{$_REQUEST['client_id_filter']}'>
					<span class='fa fa-times remove-tag'></span> Покупатель: <b>{$text_option}</b>
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
				

		$text_option = array_filter(json_decode($approved_mfi_id_values, true), function($i)
		{
			return $i['value']==$_REQUEST['approved_mfi_id_filter'];
		});
		$text_option = array_values($text_option)[0]['text'];
		if(isset2($_REQUEST['approved_mfi_id_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='approved_mfi_id_filter' value='{$_REQUEST['approved_mfi_id_filter']}'>
					<span class='fa fa-times remove-tag'></span> МФО: <b>{$text_option}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['order_id_filter_from']) && isset2($_REQUEST['order_id_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='order_id_filter_from' value='{$_REQUEST['order_id_filter_from']}'>
					<input type='hidden' class='filter' name='order_id_filter_to' value='{$_REQUEST['order_id_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> ID заказа: <b>{$_REQUEST['order_id_filter_from']}–{$_REQUEST['order_id_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['order_id_filter_from']) && isset2($_REQUEST['order_id_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='order_id_filter_from' value='{$_REQUEST['order_id_filter_from']}'>
					<input type='hidden' class='filter' name='order_id_filter_to' value='{$_REQUEST['order_id_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> ID заказа в системе Блисс: <b>{$_REQUEST['order_id_filter_from']}–{$_REQUEST['order_id_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

if(isset2($_REQUEST['is_test_mode_enabled_filter']))
{
  $filter_divs .= "
  <div class='filter-tag'>
      <input type='hidden' class='filter' name='is_test_mode_enabled_filter' value='{$_REQUEST['is_test_mode_enabled_filter']}'>
       <span class='fa fa-times remove-tag'></span> Тестовый режим: <b>".($_REQUEST['is_test_mode_enabled_filter']?"Вкл":"Выкл")."</b>
  </div>";

  $filter_caption = "Фильтры: ";
}



if(isset2($_REQUEST['is_loan_postponed_filter']))
{
  $filter_divs .= "
  <div class='filter-tag'>
      <input type='hidden' class='filter' name='is_loan_postponed_filter' value='{$_REQUEST['is_loan_postponed_filter']}'>
       <span class='fa fa-times remove-tag'></span> Кредит отложенный?: <b>".($_REQUEST['is_loan_postponed_filter']?"Вкл":"Выкл")."</b>
  </div>";

  $filter_caption = "Фильтры: ";
}



		$text_option = array_filter(json_decode($status_values, true), function($i)
		{
			return $i['value']==$_REQUEST['status_filter'];
		});
		$text_option = array_values($text_option)[0]['text'];
		if(isset2($_REQUEST['status_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='status_filter' value='{$_REQUEST['status_filter']}'>
					<span class='fa fa-times remove-tag'></span> Статус: <b>{$text_option}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['time_start_filter_from']))
		{
			$from = date('d.m.Y', strtotime($_REQUEST['time_start_filter_from']));
			$to = date('d.m.Y', strtotime($_REQUEST['time_start_filter_to']));
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='time_start_filter_from' value='{$_REQUEST['time_start_filter_from']}'>
					<input type='hidden' class='filter' name='time_start_filter_to' value='{$_REQUEST['time_start_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Подача: <b>{$from}–{$to}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['time_finish_filter_from']))
		{
			$from = date('d.m.Y', strtotime($_REQUEST['time_finish_filter_from']));
			$to = date('d.m.Y', strtotime($_REQUEST['time_finish_filter_to']));
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='time_finish_filter_from' value='{$_REQUEST['time_finish_filter_from']}'>
					<input type='hidden' class='filter' name='time_finish_filter_to' value='{$_REQUEST['time_finish_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Закрытие: <b>{$from}–{$to}</b>
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

		$sql = "SELECT 1 as stub  FROM (SELECT main_table.* , (select text FROM (SELECT CONCAT(last_name, ' ', first_name, ' ', middle_name, ' (ID', id, ')') AS text, id as value FROM clients) tmp_8f002b14 WHERE value=main_table.client_id) as client_id_text, (select text FROM (SELECT CONCAT(name, ' (ID', id, ')') AS text, id as value FROM shops) tmp_ef1f4f70 WHERE value=main_table.shop_id) as shop_id_text, (select text FROM (SELECT CONCAT(name, ' (ID', id, ')') AS text, id as value FROM mfi) tmp_f179a12c WHERE value=main_table.approved_mfi_id) as approved_mfi_id_text FROM requests main_table) temp $srch $filter $where $order";

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
				$default_sort_order = 'desc';
			

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
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT  main_table.* , (select text FROM (SELECT CONCAT(last_name, ' ', first_name, ' ', middle_name, ' (ID', id, ')') AS text, id as value FROM clients) tmp_8f002b14 WHERE value=main_table.client_id) as client_id_text, (select text FROM (SELECT CONCAT(name, ' (ID', id, ')') AS text, id as value FROM shops) tmp_ef1f4f70 WHERE value=main_table.shop_id) as shop_id_text, (select text FROM (SELECT CONCAT(name, ' (ID', id, ')') AS text, id as value FROM mfi) tmp_f179a12c WHERE value=main_table.approved_mfi_id) as approved_mfi_id_text FROM requests main_table) temp $srch $filter $where $order LIMIT :start, :limit";
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
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT main_table.* , (select text FROM (SELECT CONCAT(last_name, ' ', first_name, ' ', middle_name, ' (ID', id, ')') AS text, id as value FROM clients) tmp_8f002b14 WHERE value=main_table.client_id) as client_id_text, (select text FROM (SELECT CONCAT(name, ' (ID', id, ')') AS text, id as value FROM shops) tmp_ef1f4f70 WHERE value=main_table.shop_id) as shop_id_text, (select text FROM (SELECT CONCAT(name, ' (ID', id, ')') AS text, id as value FROM mfi) tmp_f179a12c WHERE value=main_table.approved_mfi_id) as approved_mfi_id_text FROM requests main_table) temp $srch $filter $where $order";
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
	echo masterRender("Заявки на кредит", $content, 14);
