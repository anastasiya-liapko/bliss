<?php
	include "engine/core.php";
	include "master-include.php";
	if(!in_array($_SESSION['user']['role'], array (
  0 => 'super_admin',
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

	function renderPage()
{
 return '<div id="page-document-templates"></div>
 <link rel="stylesheet" href="/assets/admin-panel/bundle.css?ver=' . PANEL_VERSION . '">
 <script src="/assets/admin-panel/commons.js?ver=' . PANEL_VERSION . '"></script>
 <script src="/assets/admin-panel/document-templates.js?ver=' . PANEL_VERSION . '"></script>';
}






	$actions[''] = function()
	{
		$show = '
		
		<style>
			html body.concept .programmer-generated-content
			{
				background-color:;
				color:;
			}
		</style>
		<div class="content-header">
			<div class="btn-wrap">
				<h2><a href="#" class="back-btn"><span class="fa fa-arrow-circle-left"></span></a> '."Бланки документов".' </h2>
			</div>
		</div>
		<div>
	</div>';

		if(function_exists("renderPage"))
		{
			$show.= "<div class='programmer-generated-content'>".renderPage()."</div>";
		}

		$show.="
		<style></style>
		<script></script>";

		return $show;

	};








	$content = $actions[$action]();
	echo masterRender("Бланки документов", $content, 10);
