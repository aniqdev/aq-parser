<?php

// класс для 

class Gift_keys{

	static function save_key(){
		global $_ERRORS;
		
		$game = _esc($_POST['game']);
		$key = _esc($_POST['key']);
		self::add_key($key, $game);

		return galert('success', '<b>Success!</b> Saved!');
	}

	static function save_keys(){
		global $_ERRORS;

		$keys_arr = file($_FILES['file']['tmp_name']);

		foreach ($keys_arr as $str) {
			$keys = explode('|', $str);
			$key = _esc(trim($keys[0]));
			$game = isset($keys[1]) ? _esc(trim($keys[1])) : '';
			self::add_key($key, $game);
		}

		return galert('success', '<b>Success!</b> Saved!');
	}

	static function add_key($key, $game = '')
	{
		// время публикации в секундах юникс
		$last_key_rec = arrayDB("SELECT public_date FROM gift_keys ORDER BY public_date DESC LIMIT 1");
		$last_public_date = $last_key_rec ? $last_key_rec[0]['public_date'] : 0;
		$time = $last_public_date > time() ? $last_public_date : time();

		$date = new DateTime();
		$date->setTimestamp($time);
		$date->add(date_interval_create_from_date_string('3 hour'));
		$date->setTime($date->format('H'), 0);
		$public_date = $date->getTimestamp();

		if ($key && !arrayDB("SELECT id FROM gift_keys WHERE `key` = '$key'")) {
			$res = arrayDB("INSERT INTO gift_keys SET game = '$game', `key` = '$key', public_date = '$public_date'");
		}

		return @$res;
	}

	static function give_me_key()
	{
		$time = time();
		$timeM60 = $time - (30*60); // время 30 мин назад
		$key_rec = arrayDB("SELECT * FROM gift_keys WHERE public_date < $time AND public_date > $timeM60 LIMIT 1");
		return json_encode([
			'success' => $key_rec ? 1 : 0,
			'key' => $key_rec ? $key_rec[0] : [],
		]);
	}

} // class Gift_keys