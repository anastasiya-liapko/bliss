<?php

include_once __DIR__."/core.php";

$action = $_GET['action'];
function editable_ajax($pk, $table, $name, $value, $hash)
{
	if(function_exists("onInlineValueChange"))
	{
		onInlineValueChange($table, $pk, $name, $value);
	}

	if($hash==1)
	{
		$value = password_hash($value, PASSWORD_DEFAULT);
	}

    if($value=="NULL") $value = null;
    if(qi("UPDATE `{$table}` SET `{$name}` = :val WHERE id = :id", array('id' => $pk, 'val' => $value), 1)) {

        return array(
            'status' => 1
        );
    } else {
        return array(
            'status' => 0,
            'msg' => 'Произошла ошибка'
        );
    }

}

function onInlineValueChange($table, $id, $column, &$value)
{
 if($table == 'organizations' && $column == 'is_documents_checked' && $value == 1) {
 /**
 * Composer.
 */
 require_once(__DIR__ . '/../../../vendor/autoload.php');
 
 \App\Email::sendAboutAdminConfirmedOrganizationDocuments('sf@bliss24.ru', $id);
 }
}

switch($action)
{

    case 'editable':
      try
      {
        $req = editable_ajax($_REQUEST['pk'], $_REQUEST['table'], $_REQUEST['name'], $_REQUEST['value'], $_REQUEST['hash']);

        if($req['status'] != 1)
        {
            header('HTTP 400 Bad Request', true, 400);
            die($req['msg']);
        }
      }
      catch (Exception $e)
      {
        header('HTTP 400 Bad Request', true, 400);
        die("Ошибка");
      }

        break;
    case 'tags_editable':
        $formula = $_REQUEST['formula'];
        $id = $_REQUEST['pk'];
        $values = explode(", ", $_REQUEST['value']);

        $parts = explode("->", $formula);
        $middle = trim($parts[0]);
        $target = trim($parts[1]);

        $parts = explode("(", $target);
        $target_table = trim($parts[0]);
        $target_table_field = trim(str_replace(')', '', $parts[1]));

        $parts = explode("(", $middle);

        $middle_table = trim($parts[0]);
        $middle_fields = trim(str_replace(')', '', $parts[1]));
        $parts = explode(",", $middle_fields);

        $middle_table_field1 = trim($parts[0]);
        $middle_table_field2 = trim($parts[1]);

        if(count($values)>0)
        {
          foreach ($values as $k)
          {
            $vals[] = '("'.$k.'")';
            $vals2[] = '"'.$k.'"';
          }
          $vals = implode(", ", $vals);
          $vals2 = implode(", ", $vals2);
		  qi("DELETE FROM {$middle_table} WHERE {$middle_table_field1}=?",[$id]);
 		 qi("INSERT IGNORE INTO {$target_table} ({$target_table_field}) VALUES {$vals}", [], 1);
 		 qi("INSERT INTO {$middle_table} ({$middle_table_field1}, {$middle_table_field2}) SELECT $id, id FROM {$target_table} WHERE {$target_table_field} IN ({$vals2})",[]);
        }
    break;
}

?>
