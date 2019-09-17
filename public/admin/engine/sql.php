<?php

$debug = (isset($_REQUEST['alef_debug']) && $_REQUEST['alef_debug']==1);
if(!$_SERVER['SERVER_NAME'] || in_array($_SERVER['SERVER_NAME'], ["test-genesis.alef.im", "devtest-genesis.alef.im", "localhost"]) || $debug)
{
	ini_set("display_errors", "On");
	error_reporting(E_ALL & ~E_NOTICE);
}
else {
	ini_set("display_errors", "Off");
}

if(file_exists(__DIR__."/../db.cfg.php"))
{
	include __DIR__."/../db.cfg.php";
	$dbConnection = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST.';charset=utf8', DB_USER, DB_PASS);
}
else
{
	if(file_exists($_SERVER["DOCUMENT_ROOT"]."/db.cfg.php"))
	{
		include $_SERVER["DOCUMENT_ROOT"]."/db.cfg.php";
		$dbConnection = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST.';charset=utf8', DB_USER, DB_PASS);
	}
	else
	{
		$dbConnection = new PDO('mysql:dbname=admin;host=localhost;charset=utf8', 'root', 'root');
	}
}

$dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbConnection->exec("set names utf8");
$dbConnection->exec("SET SESSION group_concat_max_len = 1000000");
$dbConnection->exec("SET sql_mode=''");



function q($sql, $params) // запрос к базе — короткое имя для удобства
{
    global $dbConnection;
    try
    {

		$stmt = $dbConnection->prepare($sql);
		$stmt->execute($params);
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
	catch(Exception $e)
	{
		$debug = (isset($_REQUEST['alef_debug']) && $_REQUEST['alef_debug']==1);
		if(!$_SERVER['SERVER_NAME'] || in_array($_SERVER['SERVER_NAME'], ["test-genesis.alef.im", "devtest-genesis.alef.im", "localhost"]) || $debug)
		{
			echo "<pre>";
			print_r($e);
			echo "\n\n\n------- \n\n\n";
			echo $sql;
			echo "\n\n\n------- \n\n\n";
			print_r($params);
			die("<br /> <a href='engine/exit.php'>Выход</a>");
		}else
		{
			die("Произошла ошибка в SQL-запросе. Обратитесь к Вашему менеджеру. <br /> <a href='engine/exit.php'>Выход</a>");
		}
	}
}

function q1($sql, $params) //запрос одной строчки
{
	try
	{

		global $dbConnection;
		$stmt = $dbConnection->prepare($sql);
		$stmt->execute($params);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
    }
	catch(Exception $e)
	{
		$debug = (isset($_REQUEST['alef_debug']) && $_REQUEST['alef_debug']==1);
		if(!$_SERVER['SERVER_NAME'] || in_array($_SERVER['SERVER_NAME'], ["test-genesis.alef.im", "devtest-genesis.alef.im", "localhost"]) || $debug)
		{
			echo "<pre>";
			print_r($e);
			echo "\n\n\n------- \n\n\n";
			echo $sql;
			echo "\n\n\n------- \n\n\n";
			print_r($params);
			die("<br /> <a href='engine/exit.php'>Выход</a>");
		}else
		{
			die("Произошла ошибка в SQL-запросе. Обратитесь к Вашему менеджеру.<br /> <a href='engine/exit.php'>Выход</a>");
		}
	}
}

function qi($sql, $params, $ignore_exceptions=0) // Используется для insert и update
{
	try
	{
		global $dbConnection;
		$stmt = $dbConnection->prepare($sql);

		if($stmt->execute($params))
		{
					return true;
		}
		else return false;
    }
	catch(Exception $e)
	{
		if($ignore_exceptions)
		{
			return;
		}
		$debug = (isset($_REQUEST['alef_debug']) && $_REQUEST['alef_debug']==1);
		if(!$_SERVER['SERVER_NAME'] || in_array($_SERVER['SERVER_NAME'], ["test-genesis.alef.im", "devtest-genesis.alef.im", "localhost"]) || $debug)
		{
			echo "<pre>";
			print_r($e);
			echo "\n\n\n------- \n\n\n";
			echo $sql;
			echo "\n\n\n------- \n\n\n";
			print_r($params);
			die("<br /> <a href='engine/exit.php'>Выход</a>");
		}else
		{
			die("Произошла ошибка в SQL-запросе. Обратитесь к Вашему менеджеру.<br /> <a href='engine/exit.php'>Выход</a>");
		}
	}


}

function qCount($sql, $params){ // Выводит количество записей
    global $dbConnection;
    $stmt = $dbConnection->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn();
}

function qRows(){ // Выводит кол-во строк другим способом
    global $dbConnection;
    $stmt = $dbConnection->query('SELECT FOUND_ROWS() as num');
    return $stmt->fetchColumn(0);
}

function qInsertId(){ // Последнйи автоинкриментный ID
    global $dbConnection;
    return $dbConnection->lastInsertId();
}




?>
