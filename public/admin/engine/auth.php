<?php

// Попытка залогиниться
// возвращает либо FALSE либо все данные пользователя, взятые из БД
function engineLoginAttempt($mail, $pass)
{
	require __DIR__."/../menu.php";

	if($pass_encryption=="") $pass_encryption='md5';
	if($mysql_user_table!="")
	{
		if(($pass_encryption??'md5')=='md5')
		{
			$users = q("SELECT * FROM `{$mysql_user_table}` WHERE `{$mysql_user_login}` = :mail AND `{$mysql_user_pass}`=MD5(:pass)", array('mail'=>$mail, 'pass'=>$pass));
			if(count($users)!=0)
			{
				return $users[0];
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			$users = q("SELECT * FROM `{$mysql_user_table}` WHERE `{$mysql_user_login}` = :mail", array('mail'=>$mail));
			if(count($users)!=0)
			{
				$user = $users[0];
				if(password_verify($pass, $user[$mysql_user_pass]))
				{
					return $user;
				}
				else
				{
					return FALSE;
				}
			}
			else
			{
				return FALSE;
			}
		}
	}else
	{
		if($mysql_user_login==$mail && $mysql_user_pass==$pass)
		{
			return ["login"=>$mysql_user_login, "admin"=>1];
		}
		else
		{
			return FALSE;
		}
	}

}
