<?php

	function sendMail($email, $text, $topic = "")
	{

		include __DIR__."/../menu.php";
		if($topic == "")
		{
			$topic = $project_name;
		}

		if(!is_array($email))
		{
			$email = [$email];
		}
		foreach($email as $e)
		{

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL,"http://mailman.alef.im/api/ml-single-send.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

			if(!isset($mailman_key) || strlen($mailman_key)==0)
			{
				die("В настройках проекта необходимо указать ключ Mailman");
			}
	 		curl_setopt($ch, CURLOPT_POSTFIELDS,
	          http_build_query(
	          [
	          	'api_key' => $mailman_key,
	          	'to' => $e,
	          	'subject' => $topic,
	          	'html_body' => $text,
	          	'timestamp' => time()
	          ]));

			curl_exec($ch);
			curl_close ($ch);
		}

	}

	function sendSMS($phone, $text)
	{
		include __DIR__."/../menu.php";
		if(!is_array($phone))
		{
			$phone = [$phone];
		}

		if(!isset($smsru_key) || strlen($smsru_key)==0)
		{
			die("В настройках проекта необходимо указать ключ sms.ru");
		}

		foreach($phone as $p)
		{
			$res = file_get_contents("https://sms.ru/sms/send?api_id=".urlencode($smsru_key)."&to=".urlencode($p)."&msg=".urlencode($text)."&json=1");
		}
	}

	function sendSMSLimited($phone, $text)
	{
		$db = SMSDBLoad();
		if(array_key_exists($phone, $db))
		{
			if(time() - $db[$phone]<60)
			{
				return 60 - (time() - $db[$phone]);
			}
		}

		sendSMS($phone, $text);
		$arr[$phone] = time();
		SMSDBSave($arr);
		return true;
	}

	function sendMailLimited($mail, $text, $topic)
	{
		$db = SMSDBLoad();
		if(array_key_exists($mail, $db))
		{
			if(time() - $db[$mail]<60)
			{
				return 60 - (time() - $db[$mail]);
			}
		}

		sendMail($mail, $text, $topic);
		$arr[$mail] = time();
		SMSDBSave($arr);
		return true;
	}

	function SMSDBLoad()
	{
		if(file_exists(__DIR__."/sms.db.php"))
		{
			include_once(__DIR__."/sms.db.php");
			return SMSDBLoadInner();
		}
		else
		{
				return [];
		}
	}

	function SMSDBSave($arr)
	{
		$file = fopen(__DIR__."/sms.db.php", "w");
		fwrite($file, "<?php function SMSDBLoadInner(){return json_decode('".str_replace("'", "\'", json_encode($arr))."', true);}");
		fclose($file);
	}
