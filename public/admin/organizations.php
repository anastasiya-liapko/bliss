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

// HTML всей таблицы целиком. Можно после таблицы добавить каких-то графиков и тд
function processTable($html)
{
 if( isset( $_REQUEST['back'] ) ) {
 $html = '<p><a href="#" onclick="history.back();" class="back-btn" style="display: inline;"><span class="fa fa-arrow-circle-left"></span> ' . $_REQUEST['back'] . '</a></p>' . $html;
 }
 
	return $html;
}

//вызывается после успешного апдейта
function afterUpdate($id)
{
 $result = q1("SELECT is_documents_checked FROM organizations WHERE id = :id", [':id' => $id]);
 
 if($result['is_documents_checked'] == 1) {
 /**
 * Composer.
 */
 require_once(__DIR__ . '/../../vendor/autoload.php');
 
 \App\Email::sendAboutAdminConfirmedOrganizationDocuments('sf@bliss24.ru', $id);
 }
}

// получение данных. Если вернуть false, то случится die('У вас нет доступа к данной странице')
function allowSelect()
{
 if (isset($_GET['download'], $_GET['tin']) && $_GET['download'] === 'all' && ! empty($_GET['tin'])) {
 require_once(__DIR__ . '/../../vendor/autoload.php');
 
 $documents_dir = __DIR__ . '/../../documents/organizations/tin-' . $_GET['tin'];
 
 if (is_dir($documents_dir)) {
 $documents_tmp_dir = \App\SiteInfo::getDocumentRoot() . '/tmp/documents/';
 
 if (! is_dir($documents_tmp_dir)) {
 mkdir($documents_tmp_dir, 0755, true);
 }
 
 $files = new RecursiveIteratorIterator(
 new RecursiveDirectoryIterator($documents_dir, RecursiveDirectoryIterator::SKIP_DOTS),
 RecursiveIteratorIterator::CATCH_GET_CHILD
 );
 
 foreach ($files as $name => $file) {
 if ($file->isDir()) {
 $zip = new ZipArchive();
 $name = basename($name);
 $file_path = $file->getRealPath();
 $path_zip = $documents_tmp_dir . "{$name}.zip";
 \App\Helper::compressDir($file_path, $path_zip, $zip);
 }
 }
 
 $zip = new ZipArchive();
 $documents_dir_zip = \App\SiteInfo::getDocumentRoot() . '/tmp/documents.zip';
 \App\Helper::compressDir($documents_tmp_dir, $documents_dir_zip, $zip);
 
 $files = new RecursiveIteratorIterator(
 new RecursiveDirectoryIterator($documents_tmp_dir),
 RecursiveIteratorIterator::LEAVES_ONLY
 );
 
 foreach ($files as $name => $file) {
 if (! $file->isDir()) {
 $file_path = $file->getRealPath();
 unlink($file_path);
 }
 }
 
 rmdir($documents_tmp_dir);
 
 \App\Helper::fileForceDownload($documents_dir_zip);
 } else {
 buildMsg('Файлы не загружены!', 'danger');
 }
 }

 return true;
}

// изменение. Если вернуть false то изменение не произойдет, но никакой ошибки не будет показано. Если хочешь показать ошибку — покажи ее сам при помощи buildMsg();
function allowUpdate()
{
 $_REQUEST['phone'] = str_replace( ' ', '', $_REQUEST['phone'] );
 
	return true;
}

function allowInsert()
{
 $_REQUEST['phone'] = str_replace( ' ', '', $_REQUEST['phone'] );
 
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
			
   		$type_values = '[{"text":"ИП", "value":"entrepreneur"},{"text":"ООО", "value":"llc"}]';
		$type_values_text = "";
		foreach(json_decode($type_values, true) as $opt)
		{
			$type_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		
$vat_values = '[
 {
 "text": "0",
 "value": "0"
 },
 {
 "text": "10%",
 "value": "10"
 },
 {
 "text": "18%",
 "value": "18"
 },
 {
 "text": "20%",
 "value": "20"
 },
 {
 "text": "Без НДС",
 "value": ""
 }
]';
		$vat_values_text = "";
		foreach(json_decode($vat_values, true) as $opt)
		{
			$vat_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		
$id_values = json_encode(q("SELECT IF (type = 'llc', legal_name, CONCAT('ИП «', boss_full_name, '»')) AS text, id AS value FROM organizations", []));
				$id_values_text = "";
					foreach(json_decode($id_values, true) as $opt)
					{
					  $id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
					}
$category_id_values = json_encode(q("SELECT name as text, id as value FROM organization_categories", []));
			$category_id_values_text = "";
				foreach(json_decode($category_id_values, true) as $opt)
				{
				  $category_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
				}
$boss_basis_acts_values = '[
 {
 "text": "Устава",
 "value": "statute"
 },
 {
 "text": "Доверенности",
 "value": "proxy"
 },
 {
 "text": "Договора доверительного управления",
 "value": "trust_management_agreement"
 },
 {
 "text": "Свидетельства",
 "value": "certificate"
 }
]';
		$boss_basis_acts_values_text = "";
		foreach(json_decode($boss_basis_acts_values, true) as $opt)
		{
			$boss_basis_acts_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		

		list($items, $pagination, $cnt) = get_data();

		$sort_order[$_REQUEST['sort_by']] = $_REQUEST['sort_order'];

$next_order['id']='asc';
$next_order['type']='asc';
$next_order['vat']='asc';
$next_order['id']='asc';
$next_order['legal_name']='asc';
$next_order['tin']='asc';
$next_order['cio']='asc';
$next_order['bin']='asc';
$next_order['is_licensed_activity']='asc';
$next_order['license_type']='asc';
$next_order['license_number']='asc';
$next_order['category_id']='asc';
$next_order['legal_address']='asc';
$next_order['registration_address']='asc';
$next_order['fact_address']='asc';
$next_order['bik']='asc';
$next_order['bank_name']='asc';
$next_order['correspondent_account']='asc';
$next_order['settlement_account']='asc';
$next_order['boss_full_name']='asc';
$next_order['boss_position']='asc';
$next_order['boss_basis_acts']='asc';
$next_order['boss_basis_acts_number']='asc';
$next_order['boss_basis_acts_issued_date']='asc';
$next_order['boss_passport_number']='asc';
$next_order['boss_passport_issued_date']='asc';
$next_order['boss_passport_division_code']='asc';
$next_order['boss_passport_issued_by']='asc';
$next_order['boss_birth_date']='asc';
$next_order['boss_birth_place']='asc';
$next_order['email']='asc';
$next_order['phone']='asc';
$next_order['']='asc';
$next_order['']='asc';
$next_order['is_documents_checked']='asc';

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
					$(\'.big-icon\').html(\'<i class="fas fa-building"></i>\');
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
				<h2><a href="#" class="back-btn"><span class="fa fa-arrow-circle-left"></span></a> '."Организации".' </h2>
				<button class="btn blue-inline add_button" data-toggle="modal" data-target="#modal-main">ДОБАВИТЬ</button>
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
    				<label>НДС</label>
					<select class="form-control filter-select vat-extra-filter" name="vat_filter" style="width:100%">
					<option value="">----------</option>
					'. $vat_values_text .'
					</select>
  				</div>
				<script>
					document.addEventListener("DOMContentLoaded", (event) =>
					{
  						$(".vat-extra-filter").val('.($_REQUEST['vat_filter'] ?? "''").').trigger("change");
					});
				</script>
			

			<div class="form-group">
				<label>Юридической наименование</label>
				<input type="text" class="form-control filter-text" name="legal_name_filter" style="width:100%" value="'.$_REQUEST['legal_name_filter'].'">
			</div>

			<div class="form-group">
				<label style="display:block;">КПП</label>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="cio_filter_from" value="'.$_REQUEST['cio_filter_from'].'" placeholder="От" style="display:inline; width:49%;"/>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="cio_filter_to" value="'.$_REQUEST['cio_filter_to'].'" placeholder="До" style="display:inline; width:49%; float:right;"/>
			</div>

			<div class="form-group">
				<label style="display:block;">ОГРН</label>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="bin_filter_from" value="'.$_REQUEST['bin_filter_from'].'" placeholder="От" style="display:inline; width:49%;"/>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="bin_filter_to" value="'.$_REQUEST['bin_filter_to'].'" placeholder="До" style="display:inline; width:49%; float:right;"/>
			</div>

				<div class="form-group">
					<label>Подлежит ли деятельность лицензированию</label>
					<select class="form-control filter-select  is_licensed_activity-extra-filter" name="is_licensed_activity_filter" style="width:100%">
						<option value="">----------</option>
						<option value="0">Нет</option>
						<option value="1">Да</option>
					</select>
				</div>
				<script>
					document.addEventListener("DOMContentLoaded", (event) =>
					{
  						$(".is_licensed_activity-extra-filter").val('.($_REQUEST['is_licensed_activity_filter'] ?? "''").').trigger("change");
					});
				</script>
			

			<div class="form-group">
				<label>Тип лицензии</label>
				<input type="text" class="form-control filter-text" name="license_type_filter" style="width:100%" value="'.$_REQUEST['license_type_filter'].'">
			</div>

			<div class="form-group">
				<label>Номер лицензии</label>
				<input type="text" class="form-control filter-text" name="license_number_filter" style="width:100%" value="'.$_REQUEST['license_number_filter'].'">
			</div>

			<div class="form-group">
				<label>Юридический адрес</label>
				<input type="text" class="form-control filter-text" name="legal_address_filter" style="width:100%" value="'.$_REQUEST['legal_address_filter'].'">
			</div>

			<div class="form-group">
				<label>Адрес регистрации</label>
				<input type="text" class="form-control filter-text" name="registration_address_filter" style="width:100%" value="'.$_REQUEST['registration_address_filter'].'">
			</div>

			<div class="form-group">
				<label>Фактический адрес</label>
				<input type="text" class="form-control filter-text" name="fact_address_filter" style="width:100%" value="'.$_REQUEST['fact_address_filter'].'">
			</div>

			<div class="form-group">
				<label style="display:block;">БИК</label>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="bik_filter_from" value="'.$_REQUEST['bik_filter_from'].'" placeholder="От" style="display:inline; width:49%;"/>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="bik_filter_to" value="'.$_REQUEST['bik_filter_to'].'" placeholder="До" style="display:inline; width:49%; float:right;"/>
			</div>

			<div class="form-group">
				<label>Название банка</label>
				<input type="text" class="form-control filter-text" name="bank_name_filter" style="width:100%" value="'.$_REQUEST['bank_name_filter'].'">
			</div>

			<div class="form-group">
				<label style="display:block;">Корреспондентский счёт</label>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="correspondent_account_filter_from" value="'.$_REQUEST['correspondent_account_filter_from'].'" placeholder="От" style="display:inline; width:49%;"/>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="correspondent_account_filter_to" value="'.$_REQUEST['correspondent_account_filter_to'].'" placeholder="До" style="display:inline; width:49%; float:right;"/>
			</div>

			<div class="form-group">
				<label style="display:block;">Расчётный счёт</label>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="settlement_account_filter_from" value="'.$_REQUEST['settlement_account_filter_from'].'" placeholder="От" style="display:inline; width:49%;"/>
				<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="settlement_account_filter_to" value="'.$_REQUEST['settlement_account_filter_to'].'" placeholder="До" style="display:inline; width:49%; float:right;"/>
			</div>

			<div class="form-group">
				<label>Должность руководителя</label>
				<input type="text" class="form-control filter-text" name="boss_position_filter" style="width:100%" value="'.$_REQUEST['boss_position_filter'].'">
			</div>

				<div class="form-group">
    				<label>Руководитель действует на основании документа</label>
					<select class="form-control filter-select boss_basis_acts-extra-filter" name="boss_basis_acts_filter" style="width:100%">
					<option value="">----------</option>
					'. $boss_basis_acts_values_text .'
					</select>
  				</div>
				<script>
					document.addEventListener("DOMContentLoaded", (event) =>
					{
  						$(".boss_basis_acts-extra-filter").val('.($_REQUEST['boss_basis_acts_filter'] ?? "''").').trigger("change");
					});
				</script>
			

			<div class="form-group">
				<label>Номер документа</label>
				<input type="text" class="form-control filter-text" name="boss_basis_acts_number_filter" style="width:100%" value="'.$_REQUEST['boss_basis_acts_number_filter'].'">
			</div>

			<div class="form-group">
				<label>Дата выдачи документа</label>
				<input autocomplete="off" type="text" class="form-control daterange filter-date-range boss_basis_acts_issued_date-extra-filter" name="boss_basis_acts_issued_date_filter" style="width:100%;">
			</div>
			<script>
				document.addEventListener("DOMContentLoaded", (event) =>
				{
					$("#js-extra-filters-modal").on("shown.bs.modal", function()
					{
						if("'.($_REQUEST['boss_basis_acts_issued_date_filter_from'] ?? 0).'"!="0")
						{
							$(".boss_basis_acts_issued_date-extra-filter").data("daterangepicker").setStartDate("'.date("d-m-Y", strtotime($_REQUEST['boss_basis_acts_issued_date_filter_from'])).'");
						  	$(".boss_basis_acts_issued_date-extra-filter").data("daterangepicker").setEndDate("'.date("d-m-Y", strtotime($_REQUEST['boss_basis_acts_issued_date_filter_to'])).'");
							$(".created_ts-extra-filter").trigger(\'apply\');
						}

					});
				});
			</script>

			<div class="form-group">
				<label>Серия и номер паспорта руководителя</label>
				<input type="text" class="form-control filter-text" name="boss_passport_number_filter" style="width:100%" value="'.$_REQUEST['boss_passport_number_filter'].'">
			</div>

			<div class="form-group">
				<label>Дата выдачи паспорта руководителя</label>
				<input autocomplete="off" type="text" class="form-control daterange filter-date-range boss_passport_issued_date-extra-filter" name="boss_passport_issued_date_filter" style="width:100%;">
			</div>
			<script>
				document.addEventListener("DOMContentLoaded", (event) =>
				{
					$("#js-extra-filters-modal").on("shown.bs.modal", function()
					{
						if("'.($_REQUEST['boss_passport_issued_date_filter_from'] ?? 0).'"!="0")
						{
							$(".boss_passport_issued_date-extra-filter").data("daterangepicker").setStartDate("'.date("d-m-Y", strtotime($_REQUEST['boss_passport_issued_date_filter_from'])).'");
						  	$(".boss_passport_issued_date-extra-filter").data("daterangepicker").setEndDate("'.date("d-m-Y", strtotime($_REQUEST['boss_passport_issued_date_filter_to'])).'");
							$(".created_ts-extra-filter").trigger(\'apply\');
						}

					});
				});
			</script>

			<div class="form-group">
				<label>Код подразделения, выдавшего паспорт</label>
				<input type="text" class="form-control filter-text" name="boss_passport_division_code_filter" style="width:100%" value="'.$_REQUEST['boss_passport_division_code_filter'].'">
			</div>

			<div class="form-group">
				<label>Кем выдан паспорт руководителя</label>
				<input type="text" class="form-control filter-text" name="boss_passport_issued_by_filter" style="width:100%" value="'.$_REQUEST['boss_passport_issued_by_filter'].'">
			</div>

			<div class="form-group">
				<label>Дата рождения руководителя</label>
				<input autocomplete="off" type="text" class="form-control daterange filter-date-range boss_birth_date-extra-filter" name="boss_birth_date_filter" style="width:100%;">
			</div>
			<script>
				document.addEventListener("DOMContentLoaded", (event) =>
				{
					$("#js-extra-filters-modal").on("shown.bs.modal", function()
					{
						if("'.($_REQUEST['boss_birth_date_filter_from'] ?? 0).'"!="0")
						{
							$(".boss_birth_date-extra-filter").data("daterangepicker").setStartDate("'.date("d-m-Y", strtotime($_REQUEST['boss_birth_date_filter_from'])).'");
						  	$(".boss_birth_date-extra-filter").data("daterangepicker").setEndDate("'.date("d-m-Y", strtotime($_REQUEST['boss_birth_date_filter_to'])).'");
							$(".created_ts-extra-filter").trigger(\'apply\');
						}

					});
				});
			</script>

			<div class="form-group">
				<label>Место рождения руководителя</label>
				<input type="text" class="form-control filter-text" name="boss_birth_place_filter" style="width:100%" value="'.$_REQUEST['boss_birth_place_filter'].'">
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
			<div class="data-container genesis-presentation-cards  table-clickable" id="tableMain">
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
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=type&sort_order='. ($next_order['type']) .'\' class=\'sort\' column=\'type\' sort_order=\''.$sort_order['type'].'\'>Тип'. $sort_icon['type'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<select class="form-control filter-select" name="type_filter">


							'.str_replace(chr(39), '&#39;', $type_values_text).'


							</select>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>


			<div class="genesis-header-property">
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=id&sort_order='. ($next_order['id']) .'\' class=\'sort\' column=\'id\' sort_order=\''.$sort_order['id'].'\'>Название'. $sort_icon['id'].'</a>
			</div>


			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=tin&sort_order='. ($next_order['tin']) .'\' class=\'sort\' column=\'tin\' sort_order=\''.$sort_order['tin'].'\'>ИНН'. $sort_icon['tin'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="tin_filter_from" placeholder="От"/>
							<span class="input-group-btn" style="width:0px;"></span>
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="tin_filter_to" placeholder="До"/>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</div>






			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=category_id&sort_order='. ($next_order['category_id']) .'\' class=\'sort\' column=\'category_id\' sort_order=\''.$sort_order['category_id'].'\'>Категория'. $sort_icon['category_id'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<select class="form-control filter-select" name="category_id_filter">


							'.str_replace(chr(39), '&#39;', $category_id_values_text).'


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
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=boss_full_name&sort_order='. ($next_order['boss_full_name']) .'\' class=\'sort\' column=\'boss_full_name\' sort_order=\''.$sort_order['boss_full_name'].'\'>ФИО руководителя'. $sort_icon['boss_full_name'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="text" class="form-control filter-text" name="boss_full_name_filter">
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
				   Скачать документы
			</div>

			<div class="genesis-header-property">
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=&sort_order='. ($next_order['']) .'\' class=\'sort\' column=\'\' sort_order=\''.$sort_order[''].'\'>Магазины'. $sort_icon[''].'</a>
			</div>

			<div class="genesis-header-property">
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=is_documents_checked&sort_order='. ($next_order['is_documents_checked']) .'\' class=\'sort\' column=\'is_documents_checked\' sort_order=\''.$sort_order['is_documents_checked'].'\'>Документы проверены'. $sort_icon['is_documents_checked'].'</a>
					
      <span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group text-center">
              <input type="checkbox" class="filter-checkbox" name="is_documents_checked_filter">
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
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=type&sort_order='. ($next_order['type']) .'\' class=\'sort\' column=\'type\' sort_order=\''.$sort_order['type'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['type'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<select class=\"form-control filter-select\" name=\"type_filter\">


							".str_replace(chr(39), '&#39;', $type_values_text)."


							</select>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Тип:</span>
		</span>
		<span class='editable' data-inp='select' data-type='select' data-source='".htmlspecialchars($type_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=organizations' data-pk='{$item['id']}' data-name='type'>".select_mapping($type_values, $item['type'])."</span>
		</div>", $item, "Тип"):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=type&sort_order='. ($next_order['type']) .'\' class=\'sort\' column=\'type\' sort_order=\''.$sort_order['type'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['type'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<select class=\"form-control filter-select\" name=\"type_filter\">


							".str_replace(chr(39), '&#39;', $type_values_text)."


							</select>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>Тип:</span>
		</span>
		<span class='editable' data-inp='select' data-type='select' data-source='".htmlspecialchars($type_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=organizations' data-pk='{$item['id']}' data-name='type'>".select_mapping($type_values, $item['type'])."</span>
		</div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
				<span class='genesis-attached-column-info'>
					<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=id&sort_order='. ($next_order['id']) .'\' class=\'sort\' column=\'id\' sort_order=\''.$sort_order['id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."</span>
					<span class='genesis-attached-column-name'>Название:</span>
				</span>
				<span >".$item['id_text']."</div>", $item, "Название"):"<div class='genesis-item-property '>
				<span class='genesis-attached-column-info'>
					<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=id&sort_order='. ($next_order['id']) .'\' class=\'sort\' column=\'id\' sort_order=\''.$sort_order['id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."</span>
					<span class='genesis-attached-column-name'>Название:</span>
				</span>
				<span >".$item['id_text']."</div>")."
".(function_exists("processTD")?processTD("
		<div class='genesis-item-property '>
			<span class='genesis-attached-column-info'>
				<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=tin&sort_order='. ($next_order['tin']) .'\' class=\'sort\' column=\'tin\' sort_order=\''.$sort_order['tin'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['tin'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-from\" name=\"tin_filter_from\" placeholder=\"От\"/>
							<span class=\"input-group-btn\" style=\"width:0px;\"></span>
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-to\" name=\"tin_filter_to\" placeholder=\"До\"/>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
				<span class='genesis-attached-column-name'>ИНН:</span>
			</span>
			<span class='editable' data-placeholder='' data-inp='number' data-url='engine/ajax.php?action=editable&table=organizations' data-pk='{$item['id']}' data-name='tin'>".htmlspecialchars($item['tin'])."</span>
		</div>", $item, "ИНН"):"
		<div class='genesis-item-property '>
			<span class='genesis-attached-column-info'>
				<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=tin&sort_order='. ($next_order['tin']) .'\' class=\'sort\' column=\'tin\' sort_order=\''.$sort_order['tin'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['tin'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-from\" name=\"tin_filter_from\" placeholder=\"От\"/>
							<span class=\"input-group-btn\" style=\"width:0px;\"></span>
							<input type=\"number\" min=\"-2147483648\" max=\"2147483648\" step=\"0.01\" class=\"form-control filter-number-to\" name=\"tin_filter_to\" placeholder=\"До\"/>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
				<span class='genesis-attached-column-name'>ИНН:</span>
			</span>
			<span class='editable' data-placeholder='' data-inp='number' data-url='engine/ajax.php?action=editable&table=organizations' data-pk='{$item['id']}' data-name='tin'>".htmlspecialchars($item['tin'])."</span>
		</div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
				<span class='genesis-attached-column-info'>
					<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=category_id&sort_order='. ($next_order['category_id']) .'\' class=\'sort\' column=\'category_id\' sort_order=\''.$sort_order['category_id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['category_id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<select class=\"form-control filter-select\" name=\"category_id_filter\">


							".str_replace(chr(39), '&#39;', $category_id_values_text)."


							</select>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
					<span class='genesis-attached-column-name'>Категория:</span>
				</span><span class='editable' data-inp='select' data-type='select' data-source='".htmlspecialchars($category_id_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=organizations' data-pk='{$item['id']}' data-name='category_id'>".select_mapping($category_id_values, $item['category_id'])."</span></div>", $item, "Категория"):"<div class='genesis-item-property '>
				<span class='genesis-attached-column-info'>
					<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=category_id&sort_order='. ($next_order['category_id']) .'\' class=\'sort\' column=\'category_id\' sort_order=\''.$sort_order['category_id'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['category_id'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<select class=\"form-control filter-select\" name=\"category_id_filter\">


							".str_replace(chr(39), '&#39;', $category_id_values_text)."


							</select>
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
					<span class='genesis-attached-column-name'>Категория:</span>
				</span><span class='editable' data-inp='select' data-type='select' data-source='".htmlspecialchars($category_id_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=organizations' data-pk='{$item['id']}' data-name='category_id'>".select_mapping($category_id_values, $item['category_id'])."</span></div>")."
".(function_exists("processTD")?processTD("
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=boss_full_name&sort_order='. ($next_order['boss_full_name']) .'\' class=\'sort\' column=\'boss_full_name\' sort_order=\''.$sort_order['boss_full_name'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['boss_full_name'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"boss_full_name_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>ФИО руководителя:</span>
		</span>
		<span class='editable' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=organizations' data-pk='{$item['id']}' data-name='boss_full_name'>".htmlspecialchars($item['boss_full_name'])."</span>
	</div>", $item, "ФИО руководителя"):"
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=boss_full_name&sort_order='. ($next_order['boss_full_name']) .'\' class=\'sort\' column=\'boss_full_name\' sort_order=\''.$sort_order['boss_full_name'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['boss_full_name'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
			<span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group\">
							<input type=\"text\" class=\"form-control filter-text\" name=\"boss_full_name_filter\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
							</span>
						</div>'>
			</span></span>
			<span class='genesis-attached-column-name'>ФИО руководителя:</span>
		</span>
		<span class='editable' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=organizations' data-pk='{$item['id']}' data-name='boss_full_name'>".htmlspecialchars($item['boss_full_name'])."</span>
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
		<span class='editable' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=organizations' data-pk='{$item['id']}' data-name='email'>".htmlspecialchars($item['email'])."</span>
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
		<span class='editable' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=organizations' data-pk='{$item['id']}' data-name='email'>".htmlspecialchars($item['email'])."</span>
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
".(function_exists("processTD")?processTD("
		<div class='genesis-item-property '>
			<span class='genesis-attached-column-info'>
				<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=&sort_order='. ($next_order['']) .'\' class=\'sort\' column=\'\' sort_order=\''.$sort_order[''].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon[''] ?? '<span class="fa fa-sort"></span>')).'</a>'."</span>
				<span class='genesis-attached-column-name'>Скачать документы:</span>
			</span>
			<div class='text-center genesis-button-container'>
				<a href='?download=all&tin={$item["tin"]}' class='btn btn-primary btn-genesis '>
					<span class='fa fa-download'></span> 
				</a>
			</div>
		</div>

		", $item, "Скачать документы"):"
		<div class='genesis-item-property '>
			<span class='genesis-attached-column-info'>
				<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=&sort_order='. ($next_order['']) .'\' class=\'sort\' column=\'\' sort_order=\''.$sort_order[''].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon[''] ?? '<span class="fa fa-sort"></span>')).'</a>'."</span>
				<span class='genesis-attached-column-name'>Скачать документы:</span>
			</span>
			<div class='text-center genesis-button-container'>
				<a href='?download=all&tin={$item["tin"]}' class='btn btn-primary btn-genesis '>
					<span class='fa fa-download'></span> 
				</a>
			</div>
		</div>

		")."
".(function_exists("processTD")?processTD("
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'></span>
			<span class='genesis-attached-column-name'>Магазины:</span>
		</span>
		".((function($item)
{
 $result = q1("SELECT COUNT(id) AS shops_total FROM shops WHERE organization_id = :organization_id", [':organization_id' => $item['id']]);
 
	return '<div class="text-center genesis-button-container">
			 <a href="index.php?organization_id_filter=' . $item['id'] . '" class="btn btn-primary btn-genesis">
				 ' . $result['shops_total'] . ' <i class="fas fa-arrow-circle-right"></i>
		 	</a>
 		</div>';
})($item))."
	</div>", $item, "Магазины"):"
	<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'></span>
			<span class='genesis-attached-column-name'>Магазины:</span>
		</span>
		".((function($item)
{
 $result = q1("SELECT COUNT(id) AS shops_total FROM shops WHERE organization_id = :organization_id", [':organization_id' => $item['id']]);
 
	return '<div class="text-center genesis-button-container">
			 <a href="index.php?organization_id_filter=' . $item['id'] . '" class="btn btn-primary btn-genesis">
				 ' . $result['shops_total'] . ' <i class="fas fa-arrow-circle-right"></i>
		 	</a>
 		</div>';
})($item))."
	</div>")."
".(function_exists("processTD")?processTD("<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=is_documents_checked&sort_order='. ($next_order['is_documents_checked']) .'\' class=\'sort\' column=\'is_documents_checked\' sort_order=\''.$sort_order['is_documents_checked'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['is_documents_checked'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
      <span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group text-center\">
              <input type=\"checkbox\" class=\"filter-checkbox\" name=\"is_documents_checked_filter\">
              <span class=\"input-group-btn\">
                <button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
              </span>
            </div>'>
      </span></span>
			<span class='genesis-attached-column-name'>Документы проверены:</span>
		</span>
		<div class='checkbox-container'><input  data-url='engine/ajax.php?action=editable&table=organizations' data-pk='{$item['id']}' data-name='is_documents_checked' type='checkbox'".($item['is_documents_checked']==1?" checked ":" ")." class='ajax-checkbox'></div></div>", $item, "Документы проверены"):"<div class='genesis-item-property '>
		<span class='genesis-attached-column-info'>
			<span class='buttons-panel'>".'<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=is_documents_checked&sort_order='. ($next_order['is_documents_checked']) .'\' class=\'sort\' column=\'is_documents_checked\' sort_order=\''.$sort_order['is_documents_checked'].'\'>'. (str_replace('style="margin-left:5px;"','',$sort_icon['is_documents_checked'] ?? '<span class="fa fa-sort"></span>')).'</a>'."
      <span class='fa fa-filter filter ' data-placement='bottom' data-content='<div class=\"input-group text-center\">
              <input type=\"checkbox\" class=\"filter-checkbox\" name=\"is_documents_checked_filter\">
              <span class=\"input-group-btn\">
                <button class=\"btn btn-primary add-filter\" type=\"button\"><span class=\"fa fa-filter\"></a></button>
              </span>
            </div>'>
      </span></span>
			<span class='genesis-attached-column-name'>Документы проверены:</span>
		</span>
		<div class='checkbox-container'><input  data-url='engine/ajax.php?action=editable&table=organizations' data-pk='{$item['id']}' data-name='is_documents_checked' type='checkbox'".($item['is_documents_checked']==1?" checked ":" ")." class='ajax-checkbox'></div></div>")."
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
			$item = q("SELECT * FROM organizations WHERE id=?",[$id]);
			$item = $item[0];
		}

		
				$category_id_options = q("SELECT name as text, id as value FROM organization_categories",[]);
				$category_id_options_html = "";
				foreach($category_id_options as $o)
				{
					$category_id_options_html .= "<option value=\"{$o['value']}\" ".($o["value"]==$item["category_id"]?"selected":"").">{$o['text']}</option>";
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
					<label class="control-label" for="textinput">Тип формы собственности</label>
					<div>
						<select id="type" name="type" class="form-control input-md ">
							<option value="entrepreneur" '.($item["type"]=="entrepreneur"?"selected":"").'>ИП</option> 
<option value="llc" '.($item["type"]=="llc"?"selected":"").'>ООО</option> 

						</select>
					</div>
				</div>

			



				<div class="form-group">
					<label class="control-label" for="textinput">НДС</label>
					<div>
						<select id="vat" name="vat" class="form-control input-md ">
							<option value="0" '.($item["vat"]=="0"?"selected":"").'>0</option> 
<option value="10" '.($item["vat"]=="10"?"selected":"").'>10%</option> 
<option value="18" '.($item["vat"]=="18"?"selected":"").'>18%</option> 
<option value="20" '.($item["vat"]=="20"?"selected":"").'>20%</option> 
<option value="" '.($item["vat"]==""?"selected":"").'>Без НДС</option> 

						</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">Юридической наименование</label>
									<div>
										<input id="legal_name" name="legal_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["legal_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">ИНН</label>
									<div>
										<input id="tin" name="tin" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["tin"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">КПП</label>
									<div>
										<input id="cio" name="cio" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["cio"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">ОГРН</label>
									<div>
										<input id="bin" name="bin" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["bin"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Подлежит ли деятельность лицензированию</label>
							<div>
								<input id="is_licensed_activity" name="is_licensed_activity" class=""  type="checkbox"  value="1" '.($item["is_licensed_activity"]==1?"checked":"").'>
							</div>
						</div>

					


								<div class="form-group">
									<label class="control-label" for="textinput">Тип лицензии</label>
									<div>
										<input id="license_type" name="license_type" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["license_type"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Номер лицензии</label>
									<div>
										<input id="license_number" name="license_number" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["license_number"]).'">
									</div>
								</div>

							

				<div class="form-group">
					<label class="control-label" for="textinput">Категория</label>
					<div>
						<select id="category_id" name="category_id" class="form-control input-md " >
							'.$category_id_options_html.'
							</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">Юридический адрес</label>
									<div>
										<input id="legal_address" name="legal_address" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["legal_address"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Адрес регистрации</label>
									<div>
										<input id="registration_address" name="registration_address" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["registration_address"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Фактический адрес</label>
									<div>
										<input id="fact_address" name="fact_address" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_address"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">БИК</label>
									<div>
										<input id="bik" name="bik" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["bik"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Название банка</label>
									<div>
										<input id="bank_name" name="bank_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["bank_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Корреспондентский счёт</label>
									<div>
										<input id="correspondent_account" name="correspondent_account" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["correspondent_account"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Расчётный счёт</label>
									<div>
										<input id="settlement_account" name="settlement_account" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["settlement_account"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">ФИО руководителя</label>
									<div>
										<input id="boss_full_name" name="boss_full_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["boss_full_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Должность руководителя</label>
									<div>
										<input id="boss_position" name="boss_position" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["boss_position"]).'">
									</div>
								</div>

							



				<div class="form-group">
					<label class="control-label" for="textinput">Руководитель действует на основании документа</label>
					<div>
						<select id="boss_basis_acts" name="boss_basis_acts" class="form-control input-md ">
							<option value="" '.($item["boss_basis_acts"]==""?"selected":"").'>-- выбрать --</option> 
<option value="statute" '.($item["boss_basis_acts"]=="statute"?"selected":"").'>Устава</option> 
<option value="proxy" '.($item["boss_basis_acts"]=="proxy"?"selected":"").'>Доверенности</option> 
<option value="trust_management_agreement" '.($item["boss_basis_acts"]=="trust_management_agreement"?"selected":"").'>Договора доверительного управления</option> 
<option value="certificate" '.($item["boss_basis_acts"]=="certificate"?"selected":"").'>Свидетельства</option> 

						</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">Номер документа</label>
									<div>
										<input id="boss_basis_acts_number" name="boss_basis_acts_number" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["boss_basis_acts_number"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата выдачи документа</label>
						<div>
							<input autocomplete="off" id="boss_basis_acts_issued_date" placeholder="" name="boss_basis_acts_issued_date" type="text" class="form-control datepicker "  data-timepicker="0"  data-format="Y-m-d" value="'.(isset($item["boss_basis_acts_issued_date"])?((new DateTime($item["boss_basis_acts_issued_date"]))->format("Y-m-d")):date("Y-m-d")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Серия и номер паспорта руководителя</label>
									<div>
										<input id="boss_passport_number" name="boss_passport_number" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["boss_passport_number"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата выдачи паспорта руководителя</label>
						<div>
							<input autocomplete="off" id="boss_passport_issued_date" placeholder="" name="boss_passport_issued_date" type="text" class="form-control datepicker "  data-timepicker="0"  data-format="Y-m-d" value="'.(isset($item["boss_passport_issued_date"])?((new DateTime($item["boss_passport_issued_date"]))->format("Y-m-d")):date("Y-m-d")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Код подразделения, выдавшего паспорт</label>
									<div>
										<input id="boss_passport_division_code" name="boss_passport_division_code" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["boss_passport_division_code"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Кем выдан паспорт руководителя</label>
									<div>
										<input id="boss_passport_issued_by" name="boss_passport_issued_by" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["boss_passport_issued_by"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата рождения руководителя</label>
						<div>
							<input autocomplete="off" id="boss_birth_date" placeholder="" name="boss_birth_date" type="text" class="form-control datepicker "  data-timepicker="0"  data-format="Y-m-d" value="'.(isset($item["boss_birth_date"])?((new DateTime($item["boss_birth_date"]))->format("Y-m-d")):date("Y-m-d")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Место рождения руководителя</label>
									<div>
										<input id="boss_birth_place" name="boss_birth_place" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["boss_birth_place"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Email</label>
									<div>
										<input id="email" name="email" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["email"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Телефон</label>
							<div>
								<input id="phone" name="phone" type="text" data-inp="phone" class="form-control input-md  " placeholder=""  value="'.htmlspecialchars($item["phone"]).'">
							</div>
						</div>

					


						<div class="form-group">
							<label class="control-label" for="textinput">Документы проверены</label>
							<div>
								<input id="is_documents_checked" name="is_documents_checked" class=""  type="checkbox"  value="1" '.($item["is_documents_checked"]==1?"checked":"").'>
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
			$item = q("SELECT * FROM organizations WHERE id=?",[$id]);
			$item = $item[0];
		}
		else
		{
			die("Ошибка. Редактирование несуществующей записи (вы не указали id)");
		}

		
				$category_id_options = q("SELECT name as text, id as value FROM organization_categories",[]);
				$category_id_options_html = "";
				foreach($category_id_options as $o)
				{
					$category_id_options_html .= "<option value=\"{$o['value']}\" ".($o["value"]==$item["category_id"]?"selected":"").">{$o['text']}</option>";
				}
			


		$html = '
			<h1 style="line-height: 30px"> Редактирование <br /><small>'."Организации".' #'.$id.'</small></h1>
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
					<label class="control-label" for="textinput">Тип формы собственности</label>
					<div>
						<select id="type" name="type" class="form-control input-md ">
							<option value="entrepreneur" '.($item["type"]=="entrepreneur"?"selected":"").'>ИП</option> 
<option value="llc" '.($item["type"]=="llc"?"selected":"").'>ООО</option> 

						</select>
					</div>
				</div>

			



				<div class="form-group">
					<label class="control-label" for="textinput">НДС</label>
					<div>
						<select id="vat" name="vat" class="form-control input-md ">
							<option value="0" '.($item["vat"]=="0"?"selected":"").'>0</option> 
<option value="10" '.($item["vat"]=="10"?"selected":"").'>10%</option> 
<option value="18" '.($item["vat"]=="18"?"selected":"").'>18%</option> 
<option value="20" '.($item["vat"]=="20"?"selected":"").'>20%</option> 
<option value="" '.($item["vat"]==""?"selected":"").'>Без НДС</option> 

						</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">Юридической наименование</label>
									<div>
										<input id="legal_name" name="legal_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["legal_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">ИНН</label>
									<div>
										<input id="tin" name="tin" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["tin"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">КПП</label>
									<div>
										<input id="cio" name="cio" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["cio"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">ОГРН</label>
									<div>
										<input id="bin" name="bin" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["bin"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Подлежит ли деятельность лицензированию</label>
							<div>
								<input id="is_licensed_activity" name="is_licensed_activity" class=""  type="checkbox"  value="1" '.($item["is_licensed_activity"]==1?"checked":"").'>
							</div>
						</div>

					


								<div class="form-group">
									<label class="control-label" for="textinput">Тип лицензии</label>
									<div>
										<input id="license_type" name="license_type" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["license_type"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Номер лицензии</label>
									<div>
										<input id="license_number" name="license_number" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["license_number"]).'">
									</div>
								</div>

							

				<div class="form-group">
					<label class="control-label" for="textinput">Категория</label>
					<div>
						<select id="category_id" name="category_id" class="form-control input-md " >
							'.$category_id_options_html.'
							</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">Юридический адрес</label>
									<div>
										<input id="legal_address" name="legal_address" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["legal_address"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Адрес регистрации</label>
									<div>
										<input id="registration_address" name="registration_address" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["registration_address"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Фактический адрес</label>
									<div>
										<input id="fact_address" name="fact_address" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_address"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">БИК</label>
									<div>
										<input id="bik" name="bik" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["bik"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Название банка</label>
									<div>
										<input id="bank_name" name="bank_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["bank_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Корреспондентский счёт</label>
									<div>
										<input id="correspondent_account" name="correspondent_account" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["correspondent_account"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Расчётный счёт</label>
									<div>
										<input id="settlement_account" name="settlement_account" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["settlement_account"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">ФИО руководителя</label>
									<div>
										<input id="boss_full_name" name="boss_full_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["boss_full_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Должность руководителя</label>
									<div>
										<input id="boss_position" name="boss_position" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["boss_position"]).'">
									</div>
								</div>

							



				<div class="form-group">
					<label class="control-label" for="textinput">Руководитель действует на основании документа</label>
					<div>
						<select id="boss_basis_acts" name="boss_basis_acts" class="form-control input-md ">
							<option value="" '.($item["boss_basis_acts"]==""?"selected":"").'>-- выбрать --</option> 
<option value="statute" '.($item["boss_basis_acts"]=="statute"?"selected":"").'>Устава</option> 
<option value="proxy" '.($item["boss_basis_acts"]=="proxy"?"selected":"").'>Доверенности</option> 
<option value="trust_management_agreement" '.($item["boss_basis_acts"]=="trust_management_agreement"?"selected":"").'>Договора доверительного управления</option> 
<option value="certificate" '.($item["boss_basis_acts"]=="certificate"?"selected":"").'>Свидетельства</option> 

						</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">Номер документа</label>
									<div>
										<input id="boss_basis_acts_number" name="boss_basis_acts_number" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["boss_basis_acts_number"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата выдачи документа</label>
						<div>
							<input autocomplete="off" id="boss_basis_acts_issued_date" placeholder="" name="boss_basis_acts_issued_date" type="text" class="form-control datepicker "  data-timepicker="0"  data-format="Y-m-d" value="'.(isset($item["boss_basis_acts_issued_date"])?((new DateTime($item["boss_basis_acts_issued_date"]))->format("Y-m-d")):date("Y-m-d")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Серия и номер паспорта руководителя</label>
									<div>
										<input id="boss_passport_number" name="boss_passport_number" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["boss_passport_number"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата выдачи паспорта руководителя</label>
						<div>
							<input autocomplete="off" id="boss_passport_issued_date" placeholder="" name="boss_passport_issued_date" type="text" class="form-control datepicker "  data-timepicker="0"  data-format="Y-m-d" value="'.(isset($item["boss_passport_issued_date"])?((new DateTime($item["boss_passport_issued_date"]))->format("Y-m-d")):date("Y-m-d")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Код подразделения, выдавшего паспорт</label>
									<div>
										<input id="boss_passport_division_code" name="boss_passport_division_code" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["boss_passport_division_code"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Кем выдан паспорт руководителя</label>
									<div>
										<input id="boss_passport_issued_by" name="boss_passport_issued_by" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["boss_passport_issued_by"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата рождения руководителя</label>
						<div>
							<input autocomplete="off" id="boss_birth_date" placeholder="" name="boss_birth_date" type="text" class="form-control datepicker "  data-timepicker="0"  data-format="Y-m-d" value="'.(isset($item["boss_birth_date"])?((new DateTime($item["boss_birth_date"]))->format("Y-m-d")):date("Y-m-d")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Место рождения руководителя</label>
									<div>
										<input id="boss_birth_place" name="boss_birth_place" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["boss_birth_place"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Email</label>
									<div>
										<input id="email" name="email" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["email"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Телефон</label>
							<div>
								<input id="phone" name="phone" type="text" data-inp="phone" class="form-control input-md  " placeholder=""  value="'.htmlspecialchars($item["phone"]).'">
							</div>
						</div>

					


						<div class="form-group">
							<label class="control-label" for="textinput">Документы проверены</label>
							<div>
								<input id="is_documents_checked" name="is_documents_checked" class=""  type="checkbox"  value="1" '.($item["is_documents_checked"]==1?"checked":"").'>
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
			qi("UPDATE `organizations` SET `` = ? WHERE id = ?", [$i, $line[$i]]);
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


		$sql = "INSERT IGNORE INTO organizations () VALUES ()";

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
		$sql = "INSERT INTO organizations () VALUES ()";
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

			$set[] = is_null($_REQUEST['type'])?"`type`=NULL":"`type`='".addslashes($_REQUEST['type'])."'";
$set[] = is_null($_REQUEST['vat'])?"`vat`=NULL":"`vat`='".addslashes($_REQUEST['vat'])."'";
$set[] = is_null($_REQUEST['legal_name'])?"`legal_name`=NULL":"`legal_name`='".addslashes($_REQUEST['legal_name'])."'";
$set[] = is_null($_REQUEST['tin'])?"`tin`=NULL":"`tin`='".addslashes($_REQUEST['tin'])."'";
$set[] = is_null($_REQUEST['cio'])?"`cio`=NULL":"`cio`='".addslashes($_REQUEST['cio'])."'";
$set[] = is_null($_REQUEST['bin'])?"`bin`=NULL":"`bin`='".addslashes($_REQUEST['bin'])."'";
$set[] = is_null($_REQUEST['is_licensed_activity'])?"`is_licensed_activity`=NULL":"`is_licensed_activity`='".addslashes($_REQUEST['is_licensed_activity'])."'";
$set[] = is_null($_REQUEST['license_type'])?"`license_type`=NULL":"`license_type`='".addslashes($_REQUEST['license_type'])."'";
$set[] = is_null($_REQUEST['license_number'])?"`license_number`=NULL":"`license_number`='".addslashes($_REQUEST['license_number'])."'";
$set[] = is_null($_REQUEST['category_id'])?"`category_id`=NULL":"`category_id`='".addslashes($_REQUEST['category_id'])."'";
$set[] = is_null($_REQUEST['legal_address'])?"`legal_address`=NULL":"`legal_address`='".addslashes($_REQUEST['legal_address'])."'";
$set[] = is_null($_REQUEST['registration_address'])?"`registration_address`=NULL":"`registration_address`='".addslashes($_REQUEST['registration_address'])."'";
$set[] = is_null($_REQUEST['fact_address'])?"`fact_address`=NULL":"`fact_address`='".addslashes($_REQUEST['fact_address'])."'";
$set[] = is_null($_REQUEST['bik'])?"`bik`=NULL":"`bik`='".addslashes($_REQUEST['bik'])."'";
$set[] = is_null($_REQUEST['bank_name'])?"`bank_name`=NULL":"`bank_name`='".addslashes($_REQUEST['bank_name'])."'";
$set[] = is_null($_REQUEST['correspondent_account'])?"`correspondent_account`=NULL":"`correspondent_account`='".addslashes($_REQUEST['correspondent_account'])."'";
$set[] = is_null($_REQUEST['settlement_account'])?"`settlement_account`=NULL":"`settlement_account`='".addslashes($_REQUEST['settlement_account'])."'";
$set[] = is_null($_REQUEST['boss_full_name'])?"`boss_full_name`=NULL":"`boss_full_name`='".addslashes($_REQUEST['boss_full_name'])."'";
$set[] = is_null($_REQUEST['boss_position'])?"`boss_position`=NULL":"`boss_position`='".addslashes($_REQUEST['boss_position'])."'";
$set[] = is_null($_REQUEST['boss_basis_acts'])?"`boss_basis_acts`=NULL":"`boss_basis_acts`='".addslashes($_REQUEST['boss_basis_acts'])."'";
$set[] = is_null($_REQUEST['boss_basis_acts_number'])?"`boss_basis_acts_number`=NULL":"`boss_basis_acts_number`='".addslashes($_REQUEST['boss_basis_acts_number'])."'";
$set[] = is_null($_REQUEST['boss_basis_acts_issued_date'])?"`boss_basis_acts_issued_date`=NULL":"`boss_basis_acts_issued_date`='".addslashes($_REQUEST['boss_basis_acts_issued_date'])."'";
$set[] = is_null($_REQUEST['boss_passport_number'])?"`boss_passport_number`=NULL":"`boss_passport_number`='".addslashes($_REQUEST['boss_passport_number'])."'";
$set[] = is_null($_REQUEST['boss_passport_issued_date'])?"`boss_passport_issued_date`=NULL":"`boss_passport_issued_date`='".addslashes($_REQUEST['boss_passport_issued_date'])."'";
$set[] = is_null($_REQUEST['boss_passport_division_code'])?"`boss_passport_division_code`=NULL":"`boss_passport_division_code`='".addslashes($_REQUEST['boss_passport_division_code'])."'";
$set[] = is_null($_REQUEST['boss_passport_issued_by'])?"`boss_passport_issued_by`=NULL":"`boss_passport_issued_by`='".addslashes($_REQUEST['boss_passport_issued_by'])."'";
$set[] = is_null($_REQUEST['boss_birth_date'])?"`boss_birth_date`=NULL":"`boss_birth_date`='".addslashes($_REQUEST['boss_birth_date'])."'";
$set[] = is_null($_REQUEST['boss_birth_place'])?"`boss_birth_place`=NULL":"`boss_birth_place`='".addslashes($_REQUEST['boss_birth_place'])."'";
$set[] = is_null($_REQUEST['email'])?"`email`=NULL":"`email`='".addslashes($_REQUEST['email'])."'";
$set[] = is_null($_REQUEST['phone'])?"`phone`=NULL":"`phone`='".addslashes($_REQUEST['phone'])."'";
$set[] = is_null($_REQUEST['is_documents_checked'])?"`is_documents_checked`=NULL":"`is_documents_checked`='".addslashes($_REQUEST['is_documents_checked'])."'";

			if(count($set)>0)
			{
				$set = implode(", ", $set);
				$sql = "UPDATE organizations SET $set WHERE id=?";
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
			qi("DELETE FROM organizations WHERE id=?", [$id]);
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

		

		if(isset2($_REQUEST['type_filter']))
		{
			$filters[] = "`type` = '{$_REQUEST['type_filter']}'";
		}
				

		if(isset2($_REQUEST['vat_filter']))
		{
			$filters[] = "`vat` = '{$_REQUEST['vat_filter']}'";
		}
				

		if(isset2($_REQUEST['legal_name_filter']))
		{
			$filters[] = "`legal_name` LIKE '%{$_REQUEST['legal_name_filter']}%'";
		}
				

		if(isset2($_REQUEST['tin_filter_from']) && isset2($_REQUEST['tin_filter_to']))
		{
			$filters[] = "tin >= {$_REQUEST['tin_filter_from']} AND tin <= {$_REQUEST['tin_filter_to']}";
		}

		

		if(isset2($_REQUEST['cio_filter_from']) && isset2($_REQUEST['cio_filter_to']))
		{
			$filters[] = "cio >= {$_REQUEST['cio_filter_from']} AND cio <= {$_REQUEST['cio_filter_to']}";
		}

		

		if(isset2($_REQUEST['bin_filter_from']) && isset2($_REQUEST['bin_filter_to']))
		{
			$filters[] = "bin >= {$_REQUEST['bin_filter_from']} AND bin <= {$_REQUEST['bin_filter_to']}";
		}

		

if(isset2($_REQUEST['is_licensed_activity_filter']))
{
  $filters[] = "`is_licensed_activity` = '{$_REQUEST['is_licensed_activity_filter']}'";
}
    

		if(isset2($_REQUEST['license_type_filter']))
		{
			$filters[] = "`license_type` LIKE '%{$_REQUEST['license_type_filter']}%'";
		}
				

		if(isset2($_REQUEST['license_number_filter']))
		{
			$filters[] = "`license_number` LIKE '%{$_REQUEST['license_number_filter']}%'";
		}
				

		if(isset2($_REQUEST['category_id_filter']))
		{
			$filters[] = "`category_id` = '{$_REQUEST['category_id_filter']}'";
		}
				

		if(isset2($_REQUEST['legal_address_filter']))
		{
			$filters[] = "`legal_address` LIKE '%{$_REQUEST['legal_address_filter']}%'";
		}
				

		if(isset2($_REQUEST['registration_address_filter']))
		{
			$filters[] = "`registration_address` LIKE '%{$_REQUEST['registration_address_filter']}%'";
		}
				

		if(isset2($_REQUEST['fact_address_filter']))
		{
			$filters[] = "`fact_address` LIKE '%{$_REQUEST['fact_address_filter']}%'";
		}
				

		if(isset2($_REQUEST['bik_filter_from']) && isset2($_REQUEST['bik_filter_to']))
		{
			$filters[] = "bik >= {$_REQUEST['bik_filter_from']} AND bik <= {$_REQUEST['bik_filter_to']}";
		}

		

		if(isset2($_REQUEST['bank_name_filter']))
		{
			$filters[] = "`bank_name` LIKE '%{$_REQUEST['bank_name_filter']}%'";
		}
				

		if(isset2($_REQUEST['correspondent_account_filter_from']) && isset2($_REQUEST['correspondent_account_filter_to']))
		{
			$filters[] = "correspondent_account >= {$_REQUEST['correspondent_account_filter_from']} AND correspondent_account <= {$_REQUEST['correspondent_account_filter_to']}";
		}

		

		if(isset2($_REQUEST['settlement_account_filter_from']) && isset2($_REQUEST['settlement_account_filter_to']))
		{
			$filters[] = "settlement_account >= {$_REQUEST['settlement_account_filter_from']} AND settlement_account <= {$_REQUEST['settlement_account_filter_to']}";
		}

		

		if(isset2($_REQUEST['boss_full_name_filter']))
		{
			$filters[] = "`boss_full_name` LIKE '%{$_REQUEST['boss_full_name_filter']}%'";
		}
				

		if(isset2($_REQUEST['boss_position_filter']))
		{
			$filters[] = "`boss_position` LIKE '%{$_REQUEST['boss_position_filter']}%'";
		}
				

		if(isset2($_REQUEST['boss_basis_acts_filter']))
		{
			$filters[] = "`boss_basis_acts` = '{$_REQUEST['boss_basis_acts_filter']}'";
		}
				

		if(isset2($_REQUEST['boss_basis_acts_number_filter']))
		{
			$filters[] = "`boss_basis_acts_number` LIKE '%{$_REQUEST['boss_basis_acts_number_filter']}%'";
		}
				

		if(isset2($_REQUEST['boss_basis_acts_issued_date_filter_from']) && isset2($_REQUEST['boss_basis_acts_issued_date_filter_to']))
		{
			$filters[] = "boss_basis_acts_issued_date >= '{$_REQUEST['boss_basis_acts_issued_date_filter_from']}' AND boss_basis_acts_issued_date <= '{$_REQUEST['boss_basis_acts_issued_date_filter_to']}'";
		}

		

		if(isset2($_REQUEST['boss_passport_number_filter']))
		{
			$filters[] = "`boss_passport_number` LIKE '%{$_REQUEST['boss_passport_number_filter']}%'";
		}
				

		if(isset2($_REQUEST['boss_passport_issued_date_filter_from']) && isset2($_REQUEST['boss_passport_issued_date_filter_to']))
		{
			$filters[] = "boss_passport_issued_date >= '{$_REQUEST['boss_passport_issued_date_filter_from']}' AND boss_passport_issued_date <= '{$_REQUEST['boss_passport_issued_date_filter_to']}'";
		}

		

		if(isset2($_REQUEST['boss_passport_division_code_filter']))
		{
			$filters[] = "`boss_passport_division_code` LIKE '%{$_REQUEST['boss_passport_division_code_filter']}%'";
		}
				

		if(isset2($_REQUEST['boss_passport_issued_by_filter']))
		{
			$filters[] = "`boss_passport_issued_by` LIKE '%{$_REQUEST['boss_passport_issued_by_filter']}%'";
		}
				

		if(isset2($_REQUEST['boss_birth_date_filter_from']) && isset2($_REQUEST['boss_birth_date_filter_to']))
		{
			$filters[] = "boss_birth_date >= '{$_REQUEST['boss_birth_date_filter_from']}' AND boss_birth_date <= '{$_REQUEST['boss_birth_date_filter_to']}'";
		}

		

		if(isset2($_REQUEST['boss_birth_place_filter']))
		{
			$filters[] = "`boss_birth_place` LIKE '%{$_REQUEST['boss_birth_place_filter']}%'";
		}
				

		if(isset2($_REQUEST['email_filter']))
		{
			$filters[] = "`email` LIKE '%{$_REQUEST['email_filter']}%'";
		}
				

		if(isset2($_REQUEST['phone_filter']))
		{
			$filters[] = "`phone` LIKE '%{$_REQUEST['phone_filter']}%'";
		}
				

if(isset2($_REQUEST['is_documents_checked_filter']))
{
  $filters[] = "`is_documents_checked` = '{$_REQUEST['is_documents_checked_filter']}'";
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
		$type_values = '[{"text":"ИП", "value":"entrepreneur"},{"text":"ООО", "value":"llc"}]';
		$type_values_text = "";
		foreach(json_decode($type_values, true) as $opt)
		{
			$type_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		
$vat_values = '[
 {
 "text": "0",
 "value": "0"
 },
 {
 "text": "10%",
 "value": "10"
 },
 {
 "text": "18%",
 "value": "18"
 },
 {
 "text": "20%",
 "value": "20"
 },
 {
 "text": "Без НДС",
 "value": ""
 }
]';
		$vat_values_text = "";
		foreach(json_decode($vat_values, true) as $opt)
		{
			$vat_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		
$id_values = json_encode(q("SELECT IF (type = 'llc', legal_name, CONCAT('ИП «', boss_full_name, '»')) AS text, id AS value FROM organizations", []));
				$id_values_text = "";
					foreach(json_decode($id_values, true) as $opt)
					{
					  $id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
					}
$category_id_values = json_encode(q("SELECT name as text, id as value FROM organization_categories", []));
			$category_id_values_text = "";
				foreach(json_decode($category_id_values, true) as $opt)
				{
				  $category_id_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
				}
$boss_basis_acts_values = '[
 {
 "text": "Устава",
 "value": "statute"
 },
 {
 "text": "Доверенности",
 "value": "proxy"
 },
 {
 "text": "Договора доверительного управления",
 "value": "trust_management_agreement"
 },
 {
 "text": "Свидетельства",
 "value": "certificate"
 }
]';
		$boss_basis_acts_values_text = "";
		foreach(json_decode($boss_basis_acts_values, true) as $opt)
		{
			$boss_basis_acts_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
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
				

		$text_option = array_filter(json_decode($type_values, true), function($i)
		{
			return $i['value']==$_REQUEST['type_filter'];
		});
		$text_option = array_values($text_option)[0]['text'];
		if(isset2($_REQUEST['type_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='type_filter' value='{$_REQUEST['type_filter']}'>
					<span class='fa fa-times remove-tag'></span> Тип: <b>{$text_option}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		$text_option = array_filter(json_decode($vat_values, true), function($i)
		{
			return $i['value']==$_REQUEST['vat_filter'];
		});
		$text_option = array_values($text_option)[0]['text'];
		if(isset2($_REQUEST['vat_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='vat_filter' value='{$_REQUEST['vat_filter']}'>
					<span class='fa fa-times remove-tag'></span> НДС: <b>{$text_option}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['legal_name_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='legal_name_filter' value='{$_REQUEST['legal_name_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Юридической наименование: <b>{$_REQUEST['legal_name_filter']}</b>
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
				

		if(isset2($_REQUEST['cio_filter_from']) && isset2($_REQUEST['cio_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='cio_filter_from' value='{$_REQUEST['cio_filter_from']}'>
					<input type='hidden' class='filter' name='cio_filter_to' value='{$_REQUEST['cio_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> КПП: <b>{$_REQUEST['cio_filter_from']}–{$_REQUEST['cio_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['bin_filter_from']) && isset2($_REQUEST['bin_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='bin_filter_from' value='{$_REQUEST['bin_filter_from']}'>
					<input type='hidden' class='filter' name='bin_filter_to' value='{$_REQUEST['bin_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> ОГРН: <b>{$_REQUEST['bin_filter_from']}–{$_REQUEST['bin_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

if(isset2($_REQUEST['is_licensed_activity_filter']))
{
  $filter_divs .= "
  <div class='filter-tag'>
      <input type='hidden' class='filter' name='is_licensed_activity_filter' value='{$_REQUEST['is_licensed_activity_filter']}'>
       <span class='fa fa-times remove-tag'></span> Подлежит ли деятельность лицензированию: <b>".($_REQUEST['is_licensed_activity_filter']?"Вкл":"Выкл")."</b>
  </div>";

  $filter_caption = "Фильтры: ";
}



		if(isset2($_REQUEST['license_type_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='license_type_filter' value='{$_REQUEST['license_type_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Тип лицензии: <b>{$_REQUEST['license_type_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['license_number_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='license_number_filter' value='{$_REQUEST['license_number_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Номер лицензии: <b>{$_REQUEST['license_number_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		$text_option = array_filter(json_decode($category_id_values, true), function($i)
		{
			return $i['value']==$_REQUEST['category_id_filter'];
		});
		$text_option = array_values($text_option)[0]['text'];
		if(isset2($_REQUEST['category_id_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='category_id_filter' value='{$_REQUEST['category_id_filter']}'>
					<span class='fa fa-times remove-tag'></span> Категория: <b>{$text_option}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['legal_address_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='legal_address_filter' value='{$_REQUEST['legal_address_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Юридический адрес: <b>{$_REQUEST['legal_address_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['registration_address_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='registration_address_filter' value='{$_REQUEST['registration_address_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Адрес регистрации: <b>{$_REQUEST['registration_address_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['fact_address_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='fact_address_filter' value='{$_REQUEST['fact_address_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Фактический адрес: <b>{$_REQUEST['fact_address_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['bik_filter_from']) && isset2($_REQUEST['bik_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='bik_filter_from' value='{$_REQUEST['bik_filter_from']}'>
					<input type='hidden' class='filter' name='bik_filter_to' value='{$_REQUEST['bik_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> БИК: <b>{$_REQUEST['bik_filter_from']}–{$_REQUEST['bik_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['bank_name_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='bank_name_filter' value='{$_REQUEST['bank_name_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Название банка: <b>{$_REQUEST['bank_name_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['correspondent_account_filter_from']) && isset2($_REQUEST['correspondent_account_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='correspondent_account_filter_from' value='{$_REQUEST['correspondent_account_filter_from']}'>
					<input type='hidden' class='filter' name='correspondent_account_filter_to' value='{$_REQUEST['correspondent_account_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Корреспондентский счёт: <b>{$_REQUEST['correspondent_account_filter_from']}–{$_REQUEST['correspondent_account_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['settlement_account_filter_from']) && isset2($_REQUEST['settlement_account_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='settlement_account_filter_from' value='{$_REQUEST['settlement_account_filter_from']}'>
					<input type='hidden' class='filter' name='settlement_account_filter_to' value='{$_REQUEST['settlement_account_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Расчётный счёт: <b>{$_REQUEST['settlement_account_filter_from']}–{$_REQUEST['settlement_account_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['boss_full_name_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='boss_full_name_filter' value='{$_REQUEST['boss_full_name_filter']}'>
				   <span class='fa fa-times remove-tag'></span> ФИО руководителя: <b>{$_REQUEST['boss_full_name_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['boss_position_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='boss_position_filter' value='{$_REQUEST['boss_position_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Должность руководителя: <b>{$_REQUEST['boss_position_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		$text_option = array_filter(json_decode($boss_basis_acts_values, true), function($i)
		{
			return $i['value']==$_REQUEST['boss_basis_acts_filter'];
		});
		$text_option = array_values($text_option)[0]['text'];
		if(isset2($_REQUEST['boss_basis_acts_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='boss_basis_acts_filter' value='{$_REQUEST['boss_basis_acts_filter']}'>
					<span class='fa fa-times remove-tag'></span> Руководитель действует на основании документа: <b>{$text_option}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['boss_basis_acts_number_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='boss_basis_acts_number_filter' value='{$_REQUEST['boss_basis_acts_number_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Номер документа: <b>{$_REQUEST['boss_basis_acts_number_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['boss_basis_acts_issued_date_filter_from']))
		{
			$from = date('d.m.Y', strtotime($_REQUEST['boss_basis_acts_issued_date_filter_from']));
			$to = date('d.m.Y', strtotime($_REQUEST['boss_basis_acts_issued_date_filter_to']));
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='boss_basis_acts_issued_date_filter_from' value='{$_REQUEST['boss_basis_acts_issued_date_filter_from']}'>
					<input type='hidden' class='filter' name='boss_basis_acts_issued_date_filter_to' value='{$_REQUEST['boss_basis_acts_issued_date_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Дата выдачи документа: <b>{$from}–{$to}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['boss_passport_number_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='boss_passport_number_filter' value='{$_REQUEST['boss_passport_number_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Серия и номер паспорта руководителя: <b>{$_REQUEST['boss_passport_number_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['boss_passport_issued_date_filter_from']))
		{
			$from = date('d.m.Y', strtotime($_REQUEST['boss_passport_issued_date_filter_from']));
			$to = date('d.m.Y', strtotime($_REQUEST['boss_passport_issued_date_filter_to']));
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='boss_passport_issued_date_filter_from' value='{$_REQUEST['boss_passport_issued_date_filter_from']}'>
					<input type='hidden' class='filter' name='boss_passport_issued_date_filter_to' value='{$_REQUEST['boss_passport_issued_date_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Дата выдачи паспорта руководителя: <b>{$from}–{$to}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['boss_passport_division_code_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='boss_passport_division_code_filter' value='{$_REQUEST['boss_passport_division_code_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Код подразделения, выдавшего паспорт: <b>{$_REQUEST['boss_passport_division_code_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['boss_passport_issued_by_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='boss_passport_issued_by_filter' value='{$_REQUEST['boss_passport_issued_by_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Кем выдан паспорт руководителя: <b>{$_REQUEST['boss_passport_issued_by_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['boss_birth_date_filter_from']))
		{
			$from = date('d.m.Y', strtotime($_REQUEST['boss_birth_date_filter_from']));
			$to = date('d.m.Y', strtotime($_REQUEST['boss_birth_date_filter_to']));
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='boss_birth_date_filter_from' value='{$_REQUEST['boss_birth_date_filter_from']}'>
					<input type='hidden' class='filter' name='boss_birth_date_filter_to' value='{$_REQUEST['boss_birth_date_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Дата рождения руководителя: <b>{$from}–{$to}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['boss_birth_place_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='boss_birth_place_filter' value='{$_REQUEST['boss_birth_place_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Место рождения руководителя: <b>{$_REQUEST['boss_birth_place_filter']}</b>
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

		

if(isset2($_REQUEST['is_documents_checked_filter']))
{
  $filter_divs .= "
  <div class='filter-tag'>
      <input type='hidden' class='filter' name='is_documents_checked_filter' value='{$_REQUEST['is_documents_checked_filter']}'>
       <span class='fa fa-times remove-tag'></span> Документы проверены: <b>".($_REQUEST['is_documents_checked_filter']?"Вкл":"Выкл")."</b>
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

		$sql = "SELECT 1 as stub  FROM (SELECT main_table.* , (select text FROM (SELECT IF (type = 'llc', legal_name, CONCAT('ИП «', boss_full_name, '»')) AS text, id AS value FROM organizations) tmp_e427dd5e WHERE value=main_table.id) as id_text FROM organizations main_table) temp $srch $filter $where $order";

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


		
				$default_sort_by = '';
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
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT  main_table.* , (select text FROM (SELECT IF (type = 'llc', legal_name, CONCAT('ИП «', boss_full_name, '»')) AS text, id AS value FROM organizations) tmp_e427dd5e WHERE value=main_table.id) as id_text FROM organizations main_table) temp $srch $filter $where $order LIMIT :start, :limit";
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
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT main_table.* , (select text FROM (SELECT IF (type = 'llc', legal_name, CONCAT('ИП «', boss_full_name, '»')) AS text, id AS value FROM organizations) tmp_e427dd5e WHERE value=main_table.id) as id_text FROM organizations main_table) temp $srch $filter $where $order";
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
	echo masterRender("Организации", $content, 0);
