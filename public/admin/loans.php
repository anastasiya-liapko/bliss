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
function processTD($html, $item, $column)
{
 return $html;
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
			
   		$shop_id_values = json_encode(q("SELECT name AS text, id as value FROM shops", []));
				$shop_id_values_text = "";
					foreach(json_decode($shop_id_values, true) as $opt)
					{
					  $shop_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
					}
$mfi_id_values = json_encode(q("SELECT name AS text, id as value FROM mfi", []));
				$mfi_id_values_text = "";
					foreach(json_decode($mfi_id_values, true) as $opt)
					{
					  $mfi_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
					}
$status_values = '[
 {
 "text": "Ожидает подтверждения магазина",
 "value": "pending"
 },
 {
 "text": "Ожидает доставки",
 "value": "waiting_for_delivery"
 },
 {
 "text": "Выдан",
 "value": "issued"
 },
 {
 "text": "Отклонён магазином",
 "value": "declined_by_shop"
 },
 {
 "text": "Отменён клиентом",
 "value": "canceled_by_client"
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
$next_order['']='asc';
$next_order['shop_id']='asc';
$next_order['mfi_id']='asc';
$next_order['status']='asc';
$next_order['is_mfi_pai']='asc';
$next_order['customer_id']='asc';
$next_order['contract_id']='asc';
$next_order['loan_id']='asc';
$next_order['loan_body']='asc';
$next_order['loan_cost']='asc';
$next_order['loan_period']='asc';
$next_order['loan_daily_percent_rate']='asc';
$next_order['loan_terms_link']='asc';

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
					$(\'.big-icon\').html(\'<i class="fas fa-ruble-sign"></i>\');
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
				<h2><a href="#" class="back-btn"><span class="fa fa-arrow-circle-left"></span></a> '."Кредиты".' </h2>
				
				<p class="small res-cnt">Кол-во результатов: <span class="cnt-number-span">'.$cnt.'</span></p>
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
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=&sort_order='. ($next_order['']) .'\' class=\'sort\' column=\'\' sort_order=\''.$sort_order[''].'\'>Заявка'. $sort_icon[''].'</a>
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
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=mfi_id&sort_order='. ($next_order['mfi_id']) .'\' class=\'sort\' column=\'mfi_id\' sort_order=\''.$sort_order['mfi_id'].'\'>МФО'. $sort_icon['mfi_id'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<select class="form-control filter-select" name="mfi_id_filter">


							'.str_replace(chr(39), '&#39;', $mfi_id_values_text).'


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
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=is_mfi_pai&sort_order='. ($next_order['is_mfi_pai']) .'\' class=\'sort\' column=\'is_mfi_pai\' sort_order=\''.$sort_order['is_mfi_pai'].'\'>МФО перечислило деньги магазину?'. $sort_icon['is_mfi_pai'].'</a>
					
      <span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group text-center">
              <input type="checkbox" class="filter-checkbox" name="is_mfi_pai_filter">
              <span class="input-group-btn">
                <button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
              </span>
            </div>\'>
      </span>
				</nobr>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=customer_id&sort_order='. ($next_order['customer_id']) .'\' class=\'sort\' column=\'customer_id\' sort_order=\''.$sort_order['customer_id'].'\'>ID покупателя в МФО'. $sort_icon['customer_id'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="text" class="form-control filter-text" name="customer_id_filter">
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=contract_id&sort_order='. ($next_order['contract_id']) .'\' class=\'sort\' column=\'contract_id\' sort_order=\''.$sort_order['contract_id'].'\'>ID контракта в МФО'. $sort_icon['contract_id'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="text" class="form-control filter-text" name="contract_id_filter">
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_id&sort_order='. ($next_order['loan_id']) .'\' class=\'sort\' column=\'loan_id\' sort_order=\''.$sort_order['loan_id'].'\'>ID кредита в МФО'. $sort_icon['loan_id'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="text" class="form-control filter-text" name="loan_id_filter">
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_body&sort_order='. ($next_order['loan_body']) .'\' class=\'sort\' column=\'loan_body\' sort_order=\''.$sort_order['loan_body'].'\'>Тело кредита, руб.'. $sort_icon['loan_body'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="loan_body_filter_from" placeholder="От"/>
							<span class="input-group-btn" style="width:0px;"></span>
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="loan_body_filter_to" placeholder="До"/>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_cost&sort_order='. ($next_order['loan_cost']) .'\' class=\'sort\' column=\'loan_cost\' sort_order=\''.$sort_order['loan_cost'].'\'>Полная стоимость кредита, руб.'. $sort_icon['loan_cost'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="loan_cost_filter_from" placeholder="От"/>
							<span class="input-group-btn" style="width:0px;"></span>
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="loan_cost_filter_to" placeholder="До"/>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_period&sort_order='. ($next_order['loan_period']) .'\' class=\'sort\' column=\'loan_period\' sort_order=\''.$sort_order['loan_period'].'\'>Срок кредита, дн.'. $sort_icon['loan_period'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="loan_period_filter_from" placeholder="От"/>
							<span class="input-group-btn" style="width:0px;"></span>
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="loan_period_filter_to" placeholder="До"/>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_daily_percent_rate&sort_order='. ($next_order['loan_daily_percent_rate']) .'\' class=\'sort\' column=\'loan_daily_percent_rate\' sort_order=\''.$sort_order['loan_daily_percent_rate'].'\'>Ставка в день, %'. $sort_icon['loan_daily_percent_rate'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="loan_daily_percent_rate_filter_from" placeholder="От"/>
							<span class="input-group-btn" style="width:0px;"></span>
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="loan_daily_percent_rate_filter_to" placeholder="До"/>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_terms_link&sort_order='. ($next_order['loan_terms_link']) .'\' class=\'sort\' column=\'loan_terms_link\' sort_order=\''.$sort_order['loan_terms_link'].'\'>Ссылка на условия'. $sort_icon['loan_terms_link'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="text" class="form-control filter-text" name="loan_terms_link_filter">
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
".(function_exists("processTD")?processTD("
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'></span>
			<span class='genesis-attached-column-name'>Заявка:</span>
		</span>
		".((function($item)
{
	return '<div class="text-center genesis-button-container">
			 <a href="requests.php?id_filter_from=' . $item['request_id'] . '&id_filter_to=' . $item['request_id'] . '" class="btn btn-primary btn-genesis">
				 ID ' . $item['request_id'] . ' <i class="fas fa-arrow-circle-right"></i>
		 	</a>
 		</div>';
})($item))."
	</div>", $item, "Заявка"):"
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'></span>
			<span class='genesis-attached-column-name'>Заявка:</span>
		</span>
		".((function($item)
{
	return '<div class="text-center genesis-button-container">
			 <a href="requests.php?id_filter_from=' . $item['request_id'] . '&id_filter_to=' . $item['request_id'] . '" class="btn btn-primary btn-genesis">
				 ID ' . $item['request_id'] . ' <i class="fas fa-arrow-circle-right"></i>
		 	</a>
 		</div>';
})($item))."
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
					<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=mfi_id&sort_order='. ($next_order['mfi_id']) .'\' class=\'sort\' column=\'mfi_id\' sort_order=\''.$sort_order['mfi_id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['mfi_id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<select class=\"form-control filter-select\" name=\"mfi_id_filter\">


							".str_replace(chr(39), '&#39;', $mfi_id_values_text)."


							</select>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
					<span class='genesis-attached-column-name'>МФО:</span>
				</span>
				<span >".$item['mfi_id_text']."</div>", $item, "МФО"):"<div class='genesis-item-property '>
				<span class='genesis-attached-column-info'>
					<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=mfi_id&sort_order='. ($next_order['mfi_id']) .'\' class=\'sort\' column=\'mfi_id\' sort_order=\''.$sort_order['mfi_id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['mfi_id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<select class=\"form-control filter-select\" name=\"mfi_id_filter\">


							".str_replace(chr(39), '&#39;', $mfi_id_values_text)."


							</select>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
					<span class='genesis-attached-column-name'>МФО:</span>
				</span>
				<span >".$item['mfi_id_text']."</div>")."
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
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=is_mfi_pai&sort_order='. ($next_order['is_mfi_pai']) .'\' class=\'sort\' column=\'is_mfi_pai\' sort_order=\''.$sort_order['is_mfi_pai'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['is_mfi_pai'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
      <span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group text-center\">
              <input type=\"checkbox\" class=\"filter-checkbox\" name=\"is_mfi_pai_filter\">
              <span class=\"input-group-btn\">
                <button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
              </span>
            </div>'>
      </span></span>
			<span class='genesis-attached-column-name'>МФО перечислило деньги магазину?:</span>
		</span>
		<div class='checkbox-container'><input disabled data-url='engine/ajax.php?action=editable&table=loans' data-pk='{$item['id']}' data-name='is_mfi_pai' type='checkbox'".($item['is_mfi_pai']==1?" checked ":" ")." class='ajax-checkbox'></div></div>", $item, "МФО перечислило деньги магазину?"):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=is_mfi_pai&sort_order='. ($next_order['is_mfi_pai']) .'\' class=\'sort\' column=\'is_mfi_pai\' sort_order=\''.$sort_order['is_mfi_pai'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['is_mfi_pai'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
      <span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group text-center\">
              <input type=\"checkbox\" class=\"filter-checkbox\" name=\"is_mfi_pai_filter\">
              <span class=\"input-group-btn\">
                <button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
              </span>
            </div>'>
      </span></span>
			<span class='genesis-attached-column-name'>МФО перечислило деньги магазину?:</span>
		</span>
		<div class='checkbox-container'><input disabled data-url='engine/ajax.php?action=editable&table=loans' data-pk='{$item['id']}' data-name='is_mfi_pai' type='checkbox'".($item['is_mfi_pai']==1?" checked ":" ")." class='ajax-checkbox'></div></div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=customer_id&sort_order='. ($next_order['customer_id']) .'\' class=\'sort\' column=\'customer_id\' sort_order=\''.$sort_order['customer_id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['customer_id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"customer_id_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>ID покупателя в МФО:</span>
		</span>".htmlspecialchars($item['customer_id'])."</div>", $item, "ID покупателя в МФО"):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=customer_id&sort_order='. ($next_order['customer_id']) .'\' class=\'sort\' column=\'customer_id\' sort_order=\''.$sort_order['customer_id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['customer_id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"customer_id_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>ID покупателя в МФО:</span>
		</span>".htmlspecialchars($item['customer_id'])."</div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=contract_id&sort_order='. ($next_order['contract_id']) .'\' class=\'sort\' column=\'contract_id\' sort_order=\''.$sort_order['contract_id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['contract_id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"contract_id_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>ID контракта в МФО:</span>
		</span>".htmlspecialchars($item['contract_id'])."</div>", $item, "ID контракта в МФО"):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=contract_id&sort_order='. ($next_order['contract_id']) .'\' class=\'sort\' column=\'contract_id\' sort_order=\''.$sort_order['contract_id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['contract_id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"contract_id_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>ID контракта в МФО:</span>
		</span>".htmlspecialchars($item['contract_id'])."</div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_id&sort_order='. ($next_order['loan_id']) .'\' class=\'sort\' column=\'loan_id\' sort_order=\''.$sort_order['loan_id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['loan_id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"loan_id_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>ID кредита в МФО:</span>
		</span>".htmlspecialchars($item['loan_id'])."</div>", $item, "ID кредита в МФО"):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_id&sort_order='. ($next_order['loan_id']) .'\' class=\'sort\' column=\'loan_id\' sort_order=\''.$sort_order['loan_id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['loan_id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"loan_id_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>ID кредита в МФО:</span>
		</span>".htmlspecialchars($item['loan_id'])."</div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_body&sort_order='. ($next_order['loan_body']) .'\' class=\'sort\' column=\'loan_body\' sort_order=\''.$sort_order['loan_body'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['loan_body'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-from\" name=\"loan_body_filter_from\" placeholder=\"От\"/>
							<span class=\"input-group-btn\" style=\"width:0px;\"></span>
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-to\" name=\"loan_body_filter_to\" placeholder=\"До\"/>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Тело кредита, руб.:</span>
		</span>".htmlspecialchars($item['loan_body'])."</div>", $item, "Тело кредита, руб."):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_body&sort_order='. ($next_order['loan_body']) .'\' class=\'sort\' column=\'loan_body\' sort_order=\''.$sort_order['loan_body'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['loan_body'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-from\" name=\"loan_body_filter_from\" placeholder=\"От\"/>
							<span class=\"input-group-btn\" style=\"width:0px;\"></span>
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-to\" name=\"loan_body_filter_to\" placeholder=\"До\"/>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Тело кредита, руб.:</span>
		</span>".htmlspecialchars($item['loan_body'])."</div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_cost&sort_order='. ($next_order['loan_cost']) .'\' class=\'sort\' column=\'loan_cost\' sort_order=\''.$sort_order['loan_cost'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['loan_cost'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-from\" name=\"loan_cost_filter_from\" placeholder=\"От\"/>
							<span class=\"input-group-btn\" style=\"width:0px;\"></span>
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-to\" name=\"loan_cost_filter_to\" placeholder=\"До\"/>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Полная стоимость кредита, руб.:</span>
		</span>".htmlspecialchars($item['loan_cost'])."</div>", $item, "Полная стоимость кредита, руб."):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_cost&sort_order='. ($next_order['loan_cost']) .'\' class=\'sort\' column=\'loan_cost\' sort_order=\''.$sort_order['loan_cost'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['loan_cost'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-from\" name=\"loan_cost_filter_from\" placeholder=\"От\"/>
							<span class=\"input-group-btn\" style=\"width:0px;\"></span>
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-to\" name=\"loan_cost_filter_to\" placeholder=\"До\"/>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Полная стоимость кредита, руб.:</span>
		</span>".htmlspecialchars($item['loan_cost'])."</div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_period&sort_order='. ($next_order['loan_period']) .'\' class=\'sort\' column=\'loan_period\' sort_order=\''.$sort_order['loan_period'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['loan_period'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-from\" name=\"loan_period_filter_from\" placeholder=\"От\"/>
							<span class=\"input-group-btn\" style=\"width:0px;\"></span>
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-to\" name=\"loan_period_filter_to\" placeholder=\"До\"/>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Срок кредита, дн.:</span>
		</span>".htmlspecialchars($item['loan_period'])."</div>", $item, "Срок кредита, дн."):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_period&sort_order='. ($next_order['loan_period']) .'\' class=\'sort\' column=\'loan_period\' sort_order=\''.$sort_order['loan_period'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['loan_period'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-from\" name=\"loan_period_filter_from\" placeholder=\"От\"/>
							<span class=\"input-group-btn\" style=\"width:0px;\"></span>
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-to\" name=\"loan_period_filter_to\" placeholder=\"До\"/>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Срок кредита, дн.:</span>
		</span>".htmlspecialchars($item['loan_period'])."</div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_daily_percent_rate&sort_order='. ($next_order['loan_daily_percent_rate']) .'\' class=\'sort\' column=\'loan_daily_percent_rate\' sort_order=\''.$sort_order['loan_daily_percent_rate'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['loan_daily_percent_rate'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-from\" name=\"loan_daily_percent_rate_filter_from\" placeholder=\"От\"/>
							<span class=\"input-group-btn\" style=\"width:0px;\"></span>
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-to\" name=\"loan_daily_percent_rate_filter_to\" placeholder=\"До\"/>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Ставка в день, %:</span>
		</span>".htmlspecialchars($item['loan_daily_percent_rate'])."</div>", $item, "Ставка в день, %"):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_daily_percent_rate&sort_order='. ($next_order['loan_daily_percent_rate']) .'\' class=\'sort\' column=\'loan_daily_percent_rate\' sort_order=\''.$sort_order['loan_daily_percent_rate'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['loan_daily_percent_rate'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-from\" name=\"loan_daily_percent_rate_filter_from\" placeholder=\"От\"/>
							<span class=\"input-group-btn\" style=\"width:0px;\"></span>
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-to\" name=\"loan_daily_percent_rate_filter_to\" placeholder=\"До\"/>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Ставка в день, %:</span>
		</span>".htmlspecialchars($item['loan_daily_percent_rate'])."</div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_terms_link&sort_order='. ($next_order['loan_terms_link']) .'\' class=\'sort\' column=\'loan_terms_link\' sort_order=\''.$sort_order['loan_terms_link'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['loan_terms_link'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"loan_terms_link_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Ссылка на условия:</span>
		</span>".htmlspecialchars($item['loan_terms_link'])."</div>", $item, "Ссылка на условия"):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=loan_terms_link&sort_order='. ($next_order['loan_terms_link']) .'\' class=\'sort\' column=\'loan_terms_link\' sort_order=\''.$sort_order['loan_terms_link'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['loan_terms_link'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"loan_terms_link_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Ссылка на условия:</span>
		</span>".htmlspecialchars($item['loan_terms_link'])."</div>")."
					
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
			$item = q("SELECT * FROM loans WHERE id=?",[$id]);
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
			$item = q("SELECT * FROM loans WHERE id=?",[$id]);
			$item = $item[0];
		}
		else
		{
			die("Ошибка. Редактирование несуществующей записи (вы не указали id)");
		}

		


		$html = '
			<h1 style="line-height: 30px"> Редактирование <br /><small>'."Кредиты".' #'.$id.'</small></h1>
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
			qi("UPDATE `loans` SET `` = ? WHERE id = ?", [$i, $line[$i]]);
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


		$sql = "INSERT IGNORE INTO loans () VALUES ()";

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
		$sql = "INSERT INTO loans () VALUES ()";
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
				$sql = "UPDATE loans SET $set WHERE id=?";
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
			qi("DELETE FROM loans WHERE id=?", [$id]);
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
				

		if(isset2($_REQUEST['mfi_id_filter']))
		{
			$filters[] = "`mfi_id` = '{$_REQUEST['mfi_id_filter']}'";
		}
				

		if(isset2($_REQUEST['status_filter']))
		{
			$filters[] = "`status` = '{$_REQUEST['status_filter']}'";
		}
				

if(isset2($_REQUEST['is_mfi_pai_filter']))
{
  $filters[] = "`is_mfi_pai` = '{$_REQUEST['is_mfi_pai_filter']}'";
}
    

		if(isset2($_REQUEST['customer_id_filter']))
		{
			$filters[] = "`customer_id` LIKE '%{$_REQUEST['customer_id_filter']}%'";
		}
				

		if(isset2($_REQUEST['contract_id_filter']))
		{
			$filters[] = "`contract_id` LIKE '%{$_REQUEST['contract_id_filter']}%'";
		}
				

		if(isset2($_REQUEST['loan_id_filter']))
		{
			$filters[] = "`loan_id` LIKE '%{$_REQUEST['loan_id_filter']}%'";
		}
				

		if(isset2($_REQUEST['loan_body_filter_from']) && isset2($_REQUEST['loan_body_filter_to']))
		{
			$filters[] = "loan_body >= {$_REQUEST['loan_body_filter_from']} AND loan_body <= {$_REQUEST['loan_body_filter_to']}";
		}

		

		if(isset2($_REQUEST['loan_cost_filter_from']) && isset2($_REQUEST['loan_cost_filter_to']))
		{
			$filters[] = "loan_cost >= {$_REQUEST['loan_cost_filter_from']} AND loan_cost <= {$_REQUEST['loan_cost_filter_to']}";
		}

		

		if(isset2($_REQUEST['loan_period_filter_from']) && isset2($_REQUEST['loan_period_filter_to']))
		{
			$filters[] = "loan_period >= {$_REQUEST['loan_period_filter_from']} AND loan_period <= {$_REQUEST['loan_period_filter_to']}";
		}

		

		if(isset2($_REQUEST['loan_daily_percent_rate_filter_from']) && isset2($_REQUEST['loan_daily_percent_rate_filter_to']))
		{
			$filters[] = "loan_daily_percent_rate >= {$_REQUEST['loan_daily_percent_rate_filter_from']} AND loan_daily_percent_rate <= {$_REQUEST['loan_daily_percent_rate_filter_to']}";
		}

		

		if(isset2($_REQUEST['loan_terms_link_filter']))
		{
			$filters[] = "`loan_terms_link` LIKE '%{$_REQUEST['loan_terms_link_filter']}%'";
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
		$shop_id_values = json_encode(q("SELECT name AS text, id as value FROM shops", []));
				$shop_id_values_text = "";
					foreach(json_decode($shop_id_values, true) as $opt)
					{
					  $shop_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
					}
$mfi_id_values = json_encode(q("SELECT name AS text, id as value FROM mfi", []));
				$mfi_id_values_text = "";
					foreach(json_decode($mfi_id_values, true) as $opt)
					{
					  $mfi_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
					}
$status_values = '[
 {
 "text": "Ожидает подтверждения магазина",
 "value": "pending"
 },
 {
 "text": "Ожидает доставки",
 "value": "waiting_for_delivery"
 },
 {
 "text": "Выдан",
 "value": "issued"
 },
 {
 "text": "Отклонён магазином",
 "value": "declined_by_shop"
 },
 {
 "text": "Отменён клиентом",
 "value": "canceled_by_client"
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
				

		$text_option = array_filter(json_decode($mfi_id_values, true), function($i)
		{
			return $i['value']==$_REQUEST['mfi_id_filter'];
		});
		$text_option = array_values($text_option)[0]['text'];
		if(isset2($_REQUEST['mfi_id_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='mfi_id_filter' value='{$_REQUEST['mfi_id_filter']}'>
					<span class='fa fa-times remove-tag'></span> МФО: <b>{$text_option}</b>
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
				

if(isset2($_REQUEST['is_mfi_pai_filter']))
{
  $filter_divs .= "
  <div class='filter-tag'>
      <input type='hidden' class='filter' name='is_mfi_pai_filter' value='{$_REQUEST['is_mfi_pai_filter']}'>
       <span class='fa fa-times remove-tag'></span> МФО перечислило деньги магазину?: <b>".($_REQUEST['is_mfi_pai_filter']?"Вкл":"Выкл")."</b>
  </div>";

  $filter_caption = "Фильтры: ";
}



		if(isset2($_REQUEST['customer_id_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='customer_id_filter' value='{$_REQUEST['customer_id_filter']}'>
				   <span class='fa fa-times remove-tag'></span> ID покупателя в МФО: <b>{$_REQUEST['customer_id_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['contract_id_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='contract_id_filter' value='{$_REQUEST['contract_id_filter']}'>
				   <span class='fa fa-times remove-tag'></span> ID контракта в МФО: <b>{$_REQUEST['contract_id_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['loan_id_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='loan_id_filter' value='{$_REQUEST['loan_id_filter']}'>
				   <span class='fa fa-times remove-tag'></span> ID кредита в МФО: <b>{$_REQUEST['loan_id_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['loan_body_filter_from']) && isset2($_REQUEST['loan_body_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='loan_body_filter_from' value='{$_REQUEST['loan_body_filter_from']}'>
					<input type='hidden' class='filter' name='loan_body_filter_to' value='{$_REQUEST['loan_body_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Тело кредита, руб.: <b>{$_REQUEST['loan_body_filter_from']}–{$_REQUEST['loan_body_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['loan_cost_filter_from']) && isset2($_REQUEST['loan_cost_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='loan_cost_filter_from' value='{$_REQUEST['loan_cost_filter_from']}'>
					<input type='hidden' class='filter' name='loan_cost_filter_to' value='{$_REQUEST['loan_cost_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Полная стоимость кредита, руб.: <b>{$_REQUEST['loan_cost_filter_from']}–{$_REQUEST['loan_cost_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['loan_period_filter_from']) && isset2($_REQUEST['loan_period_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='loan_period_filter_from' value='{$_REQUEST['loan_period_filter_from']}'>
					<input type='hidden' class='filter' name='loan_period_filter_to' value='{$_REQUEST['loan_period_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Срок кредита, дн.: <b>{$_REQUEST['loan_period_filter_from']}–{$_REQUEST['loan_period_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['loan_daily_percent_rate_filter_from']) && isset2($_REQUEST['loan_daily_percent_rate_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='loan_daily_percent_rate_filter_from' value='{$_REQUEST['loan_daily_percent_rate_filter_from']}'>
					<input type='hidden' class='filter' name='loan_daily_percent_rate_filter_to' value='{$_REQUEST['loan_daily_percent_rate_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Ставка в день, %: <b>{$_REQUEST['loan_daily_percent_rate_filter_from']}–{$_REQUEST['loan_daily_percent_rate_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['loan_terms_link_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='loan_terms_link_filter' value='{$_REQUEST['loan_terms_link_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Ссылка на условия: <b>{$_REQUEST['loan_terms_link_filter']}</b>
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

		$sql = "SELECT 1 as stub  FROM (SELECT main_table.* , (select text FROM (SELECT name AS text, id as value FROM shops) tmp_a487e42f WHERE value=main_table.shop_id) as shop_id_text, (select text FROM (SELECT name AS text, id as value FROM mfi) tmp_1d542107 WHERE value=main_table.mfi_id) as mfi_id_text FROM loans main_table) temp $srch $filter $where $order";

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
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT  main_table.* , (select text FROM (SELECT name AS text, id as value FROM shops) tmp_a487e42f WHERE value=main_table.shop_id) as shop_id_text, (select text FROM (SELECT name AS text, id as value FROM mfi) tmp_1d542107 WHERE value=main_table.mfi_id) as mfi_id_text FROM loans main_table) temp $srch $filter $where $order LIMIT :start, :limit";
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
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT main_table.* , (select text FROM (SELECT name AS text, id as value FROM shops) tmp_a487e42f WHERE value=main_table.shop_id) as shop_id_text, (select text FROM (SELECT name AS text, id as value FROM mfi) tmp_1d542107 WHERE value=main_table.mfi_id) as mfi_id_text FROM loans main_table) temp $srch $filter $where $order";
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
	echo masterRender("Кредиты", $content, 16);
