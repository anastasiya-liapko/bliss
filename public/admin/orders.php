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
			
   		$shop_id_values = json_encode(q("SELECT name as text, id as value FROM shops", []));
				$shop_id_values_text = "";
					foreach(json_decode($shop_id_values, true) as $opt)
					{
					  $shop_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
					}
$status_values = '[{"text":"Ожидает заполнения кредитной заявки","value":"waiting_for_registration"},{"text":"На рассмотрении в ФО","value":"pending_by_mfi"},{"text":"Отклонён ФО","value":"declined_by_mfi"},{"text":"Отклонён покупателем","value":"canceled_by_client"},{"text":"Не хватило времени","value":"mfi_did_not_answer"},{"text":"Ожидает подтверждения покупателя","value":"approved_by_mfi"},{"text":"Ожидает подтверждения магазина","value":"pending_by_shop"},{"text":"Ожидает доставки","value":"waiting_for_delivery"},{"text":"Ожидает оплаты","value":"waiting_for_payment"},{"text":"Оплачен","value":"paid"},{"text":"Отклонён магазином","value":"declined_by_shop"},{"text":"название","value":"Отменён покупателем при получении"}]';
		$status_values_text = "";
		foreach(json_decode($status_values, true) as $opt)
		{
			$status_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		
$delivery_service_id_values = json_encode(q("SELECT name as text, id as value FROM delivery_services", []));
			$delivery_service_id_values_text = "";
				foreach(json_decode($delivery_service_id_values, true) as $opt)
				{
				  $delivery_service_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
				}

		list($items, $pagination, $cnt) = get_data();

		$sort_order[$_REQUEST['sort_by']] = $_REQUEST['sort_order'];

$next_order['id']='asc';
$next_order['shop_id']='asc';
$next_order['order_id_in_shop']='asc';
$next_order['order_price']='asc';
$next_order['time_of_creation']='asc';
$next_order['status']='asc';
$next_order['delivery_service_id']='asc';
$next_order['tracking_code']='asc';

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
					$(\'.big-icon\').html(\'<i class="fas fa-shopping-bag"></i>\');
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
				<h2><a href="#" class="back-btn"><span class="fa fa-arrow-circle-left"></span></a> '."Заказы".' </h2>
				
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
				<label style="display:block;">ID заказа в магазине</label>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="order_id_in_shop_filter_from" value="'.$_REQUEST['order_id_in_shop_filter_from'].'" placeholder="От" style="display:inline; width:49%;"/>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="order_id_in_shop_filter_to" value="'.$_REQUEST['order_id_in_shop_filter_to'].'" placeholder="До" style="display:inline; width:49%; float:right;"/>
			</div>

				<div class="form-group">
    				<label>Логист</label>
					<select class="form-control filter-select delivery_service_id-extra-filter" name="delivery_service_id_filter" style="width:100%">
					<option value="">----------</option>
					'. $delivery_service_id_values_text .'
					</select>
  				</div>
				<script>
					document.addEventListener("DOMContentLoaded", (event) =>
					{
  						$(".delivery_service_id-extra-filter").val('.($_REQUEST['delivery_service_id_filter'] ?? "''").').trigger("change");
					});
				</script>
			

			<div class="form-group">
				<label>Код отслеживания посылки</label>
				<input type="text" class="form-control filter-text" name="tracking_code_filter" style="width:100%" value="'.$_REQUEST['tracking_code_filter'].'">
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
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=shop_id&sort_order='. ($next_order['shop_id']) .'\' class=\'sort\' column=\'shop_id\' sort_order=\''.$sort_order['shop_id'].'\'>Название магазина'. $sort_icon['shop_id'].'</a>
					
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
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=order_price&sort_order='. ($next_order['order_price']) .'\' class=\'sort\' column=\'order_price\' sort_order=\''.$sort_order['order_price'].'\'>Сумма'. $sort_icon['order_price'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="order_price_filter_from" placeholder="От"/>
							<span class="input-group-btn" style="width:0px;"></span>
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="order_price_filter_to" placeholder="До"/>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=time_of_creation&sort_order='. ($next_order['time_of_creation']) .'\' class=\'sort\' column=\'time_of_creation\' sort_order=\''.$sort_order['time_of_creation'].'\'>Дата'. $sort_icon['time_of_creation'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input autocomplete="off" type="text" class="form-control daterange filter-date-range" name="time_of_creation_filter">
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
							<select class="form-control filter-select" multiple name="status_filter">
							'.str_replace(chr(39), '&#39;', $status_values_text).'
							</select>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
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
					<span class='genesis-attached-column-name'>Название магазина:</span>
				</span>
				<span >".$item['shop_id_text']."</div>", $item, "Название магазина"):"<div class='genesis-item-property '>
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
					<span class='genesis-attached-column-name'>Название магазина:</span>
				</span>
				<span >".$item['shop_id_text']."</div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=order_price&sort_order='. ($next_order['order_price']) .'\' class=\'sort\' column=\'order_price\' sort_order=\''.$sort_order['order_price'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['order_price'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-from\" name=\"order_price_filter_from\" placeholder=\"От\"/>
							<span class=\"input-group-btn\" style=\"width:0px;\"></span>
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-to\" name=\"order_price_filter_to\" placeholder=\"До\"/>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Сумма:</span>
		</span>".htmlspecialchars($item['order_price'])."</div>", $item, "Сумма"):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=order_price&sort_order='. ($next_order['order_price']) .'\' class=\'sort\' column=\'order_price\' sort_order=\''.$sort_order['order_price'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['order_price'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-from\" name=\"order_price_filter_from\" placeholder=\"От\"/>
							<span class=\"input-group-btn\" style=\"width:0px;\"></span>
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-to\" name=\"order_price_filter_to\" placeholder=\"До\"/>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Сумма:</span>
		</span>".htmlspecialchars($item['order_price'])."</div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=time_of_creation&sort_order='. ($next_order['time_of_creation']) .'\' class=\'sort\' column=\'time_of_creation\' sort_order=\''.$sort_order['time_of_creation'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['time_of_creation'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input autocomplete=\"off\" type=\"text\" class=\"form-control daterange filter-date-range\" name=\"time_of_creation_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Дата:</span>
		</span>".htmlspecialchars($item['time_of_creation'])."</div>", $item, "Дата"):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=time_of_creation&sort_order='. ($next_order['time_of_creation']) .'\' class=\'sort\' column=\'time_of_creation\' sort_order=\''.$sort_order['time_of_creation'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['time_of_creation'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input autocomplete=\"off\" type=\"text\" class=\"form-control daterange filter-date-range\" name=\"time_of_creation_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Дата:</span>
		</span>".htmlspecialchars($item['time_of_creation'])."</div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=status&sort_order='. ($next_order['status']) .'\' class=\'sort\' column=\'status\' sort_order=\''.$sort_order['status'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['status'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<select class=\"form-control filter-select\" multiple name=\"status_filter\">
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
							<select class=\"form-control filter-select\" multiple name=\"status_filter\">
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
			$item = q("SELECT * FROM orders WHERE id=?",[$id]);
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
			$item = q("SELECT * FROM orders WHERE id=?",[$id]);
			$item = $item[0];
		}
		else
		{
			die("Ошибка. Редактирование несуществующей записи (вы не указали id)");
		}

		


		$html = '
			<h1 style="line-height: 30px"> Редактирование <br /><small>'."Заказы".' #'.$id.'</small></h1>
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
			qi("UPDATE `orders` SET `` = ? WHERE id = ?", [$i, $line[$i]]);
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


		$sql = "INSERT IGNORE INTO orders () VALUES ()";

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
		$sql = "INSERT INTO orders () VALUES ()";
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
				$sql = "UPDATE orders SET $set WHERE id=?";
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
			qi("DELETE FROM orders WHERE id=?", [$id]);
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

		

		if(isset2($_REQUEST['shop_id_filter']))
		{
			$filters[] = "`shop_id` = '{$_REQUEST['shop_id_filter']}'";
		}
				

		if(isset2($_REQUEST['order_id_in_shop_filter_from']) && isset2($_REQUEST['order_id_in_shop_filter_to']))
		{
			$filters[] = "order_id_in_shop >= {$_REQUEST['order_id_in_shop_filter_from']} AND order_id_in_shop <= {$_REQUEST['order_id_in_shop_filter_to']}";
		}

		

		if(isset2($_REQUEST['order_price_filter_from']) && isset2($_REQUEST['order_price_filter_to']))
		{
			$filters[] = "order_price >= {$_REQUEST['order_price_filter_from']} AND order_price <= {$_REQUEST['order_price_filter_to']}";
		}

		

		if(isset2($_REQUEST['time_of_creation_filter_from']) && isset2($_REQUEST['time_of_creation_filter_to']))
		{
			$filters[] = "time_of_creation >= '{$_REQUEST['time_of_creation_filter_from']}' AND time_of_creation <= '{$_REQUEST['time_of_creation_filter_to']}'";
		}

		

		if(isset2($_REQUEST['status_filter']))
		{
			$status_filter_array = array_map(function($i)
			{
				return "'$i'";
			},$_REQUEST['status_filter']);

			$status_filter_string = implode(', ', $status_filter_array);
			$filters[] = "`status` IN ({$status_filter_string})";
		}
				

		if(isset2($_REQUEST['delivery_service_id_filter']))
		{
			$filters[] = "`delivery_service_id` = '{$_REQUEST['delivery_service_id_filter']}'";
		}
				

		if(isset2($_REQUEST['tracking_code_filter']))
		{
			$filters[] = "`tracking_code` LIKE '%{$_REQUEST['tracking_code_filter']}%'";
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
		$shop_id_values = json_encode(q("SELECT name as text, id as value FROM shops", []));
				$shop_id_values_text = "";
					foreach(json_decode($shop_id_values, true) as $opt)
					{
					  $shop_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
					}
$status_values = '[{"text":"Ожидает заполнения кредитной заявки","value":"waiting_for_registration"},{"text":"На рассмотрении в ФО","value":"pending_by_mfi"},{"text":"Отклонён ФО","value":"declined_by_mfi"},{"text":"Отклонён покупателем","value":"canceled_by_client"},{"text":"Не хватило времени","value":"mfi_did_not_answer"},{"text":"Ожидает подтверждения покупателя","value":"approved_by_mfi"},{"text":"Ожидает подтверждения магазина","value":"pending_by_shop"},{"text":"Ожидает доставки","value":"waiting_for_delivery"},{"text":"Ожидает оплаты","value":"waiting_for_payment"},{"text":"Оплачен","value":"paid"},{"text":"Отклонён магазином","value":"declined_by_shop"},{"text":"название","value":"Отменён покупателем при получении"}]';
		$status_values_text = "";
		foreach(json_decode($status_values, true) as $opt)
		{
			$status_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		
$delivery_service_id_values = json_encode(q("SELECT name as text, id as value FROM delivery_services", []));
			$delivery_service_id_values_text = "";
				foreach(json_decode($delivery_service_id_values, true) as $opt)
				{
				  $delivery_service_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
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
					<span class='fa fa-times remove-tag'></span> Название магазина: <b>{$text_option}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['order_id_in_shop_filter_from']) && isset2($_REQUEST['order_id_in_shop_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='order_id_in_shop_filter_from' value='{$_REQUEST['order_id_in_shop_filter_from']}'>
					<input type='hidden' class='filter' name='order_id_in_shop_filter_to' value='{$_REQUEST['order_id_in_shop_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> ID заказа в магазине: <b>{$_REQUEST['order_id_in_shop_filter_from']}–{$_REQUEST['order_id_in_shop_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['order_price_filter_from']) && isset2($_REQUEST['order_price_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='order_price_filter_from' value='{$_REQUEST['order_price_filter_from']}'>
					<input type='hidden' class='filter' name='order_price_filter_to' value='{$_REQUEST['order_price_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Сумма: <b>{$_REQUEST['order_price_filter_from']}–{$_REQUEST['order_price_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['time_of_creation_filter_from']))
		{
			$from = date('d.m.Y', strtotime($_REQUEST['time_of_creation_filter_from']));
			$to = date('d.m.Y', strtotime($_REQUEST['time_of_creation_filter_to']));
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='time_of_creation_filter_from' value='{$_REQUEST['time_of_creation_filter_from']}'>
					<input type='hidden' class='filter' name='time_of_creation_filter_to' value='{$_REQUEST['time_of_creation_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Дата: <b>{$from}–{$to}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				




		if(isset2($_REQUEST['status_filter']))
		{

			$text_option = array_filter(json_decode($status_values, true), function($i)
			{
				return in_array($i['value'], $_REQUEST['status_filter']);
			});

			$text_option = array_map(function($item)
			{
				return $item['text'];
			}, $text_option);

			$text_option = implode(', ', $text_option);

			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='status_filter' value='{$_REQUEST['status_filter']}'>
					<span class='fa fa-times remove-tag'></span> Статус: <b>{$text_option}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		$text_option = array_filter(json_decode($delivery_service_id_values, true), function($i)
		{
			return $i['value']==$_REQUEST['delivery_service_id_filter'];
		});
		$text_option = array_values($text_option)[0]['text'];
		if(isset2($_REQUEST['delivery_service_id_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='delivery_service_id_filter' value='{$_REQUEST['delivery_service_id_filter']}'>
					<span class='fa fa-times remove-tag'></span> Логист: <b>{$text_option}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['tracking_code_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='tracking_code_filter' value='{$_REQUEST['tracking_code_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Код отслеживания посылки: <b>{$_REQUEST['tracking_code_filter']}</b>
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

		$sql = "SELECT 1 as stub  FROM (SELECT main_table.* , (select text FROM (SELECT name as text, id as value FROM shops) tmp_9d772070 WHERE value=main_table.shop_id) as shop_id_text FROM orders main_table) temp $srch $filter $where $order";

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
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT  main_table.* , (select text FROM (SELECT name as text, id as value FROM shops) tmp_9d772070 WHERE value=main_table.shop_id) as shop_id_text FROM orders main_table) temp $srch $filter $where $order LIMIT :start, :limit";
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
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT main_table.* , (select text FROM (SELECT name as text, id as value FROM shops) tmp_9d772070 WHERE value=main_table.shop_id) as shop_id_text FROM orders main_table) temp $srch $filter $where $order";
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
	echo masterRender("Заказы", $content, 13);
