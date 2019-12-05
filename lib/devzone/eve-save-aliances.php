<?php ini_get('safe_mode') or set_time_limit(2000); // Указываем скрипту, чтобы не обрывал связь.




return;

$ali_arr = arrayDB("SELECT alliance_id FROM eve_alliances");

foreach ($ali_arr as $key => $ali) {

	$ali_id = $ali['alliance_id'];

	if(arrayDB("SELECT id FROM eve_corporations WHERE alliance_id = '$ali_id'")) continue;
	
	$url = 'https://esi.evetech.net/latest/alliances/'.$ali_id.'/corporations/';

	$corp_arr = file_get_contents($url);

	$corp_arr = json_decode($corp_arr, true);

	foreach ($corp_arr as $corp_id) {
		
		$check = arrayDB("SELECT id FROM eve_corporations WHERE corporation_id = '$corp_id'");

		if (!$check) {
			// $url = "https://esi.evetech.net/latest/alliances/$ali_id/";

			// $res = file_get_contents($url);

			// $res = json_decode($res, true);

			// foreach ($res as &$val) {
			// 	$val = _esc($val);
			// }

			arrayDB("INSERT INTO eve_corporations SET
				alliance_id = '$ali_id',
				corporation_id = '$corp_id'");
		}
	}
}


return

$res = arrayDB("SELECT * FROM eve_alliances");

$lenthes = [];
foreach ($res as $key => $value) {
	$lenthes[] = strlen($value['name']);
}

sort($lenthes);

sa($lenthes);

return;


$ali_arr = file_get_contents('https://esi.evetech.net/latest/alliances/');

$ali_arr = json_decode($ali_arr, true);

sa($ali_arr);

foreach ($ali_arr as $key => $ali) {

	$check = arrayDB("SELECT id FROM eve_alliances WHERE alliance_id = '$ali'");

	if (!$check) {
		$url = "https://esi.evetech.net/latest/alliances/$ali/";

		$res = file_get_contents($url);

		$res = json_decode($res, true);

		foreach ($res as &$val) {
			$val = _esc($val);
		}

		arrayDB("INSERT INTO eve_alliances SET
			alliance_id = '$ali',
			name = '$res[name]',
			ticker = '$res[ticker]',
			creator_id = '$res[creator_id]',
			creator_corporation_id = '$res[creator_corporation_id]',
			executor_corporation_id = '$res[executor_corporation_id]',
			date_founded = '$res[date_founded]'");
	}

}



