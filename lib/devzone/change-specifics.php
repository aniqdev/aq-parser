<?php

ini_get('safe_mode') or set_time_limit(2500); // Указываем скрипту, чтобы не обрывал связь.


function get_item_specs($item_id=''){

	if(!$item_id) return false;
	$res = Ebay_shopping2::getSingleItem($item_id);

	$specifics = json_decode($res, true)['Item']['ItemSpecifics']['NameValueList'];

	$specs = [];
	foreach ($specifics as $val) $specs[$val['Name']] = $val['Value'][0];

	return $specs;
}

function set_item_specifics($item_id, $steam_arr = []){

	$ebayObj = new Ebay_shopping2();

	$specifics = [];

	$specs = get_item_specs($item_id);

	if($steam_arr['usk_links']) {
		$specifics['USK-Einstufung'] = 'USK ab '.$steam_arr['usk_age'];
	}elseif(isset($specs['USK-Einstufung'])) {
		$specifics['USK-Einstufung'] = $specs['USK-Einstufung'];
	}else{
		$specifics['USK-Einstufung'] = 'Unbekannt';
	}

	if($steam_arr['pegi_links']) {
		$specifics['PEGI-Einstufung'] = 'PEGI ab '.$steam_arr['pegi_age'];
	}

	if($steam_arr['os']){
		$from = ['win','mac','linux','htcvive','oculusrift','razerosvr'];
		$to = ['PC','Mac','Linux','HTC Vive','Oculus Rift','Razer OSVR'];
		$specifics['Plattform'] = str_ireplace($from, $to, $steam_arr['os']);
	}

	if($steam_arr['genres']){

		$genres_from = ['Simulation', // 1
			'Strategie', // 2
			'Action', // 3
			'Indie', // 4
			'RPG', // 5
			'Bildung', // 6
			'Animation & Modellierung', // 7
			'Design & Illustration', // 8
			'Fotobearbeitung', // 9
			'MMO', // 10
			'Brutal', // 11
			'Gewalt'];

		$genres_to = ['Simulationen', // 1
			'Strategiespiele,Battle', // 2
			'Action/Abenteuer', // 3
			"Arcade,Jump 'n' Run", // 4
			'Rollenspiele', // 5
			'Familie & Kinder', // 6
			'Familie & Kinder', // 7
			'Familie & Kinder', // 8
			'Familie & Kinder', // 9
			'Battle,Action/Abenteuer', // 10
			'Kampfspiele,Battle', // 11
			'Action/Abenteuer,Kampfspiele']; 

		$steam_arr['genres'] = str_ireplace($genres_from, $genres_to, $steam_arr['genres']);

		if(strpos($steam_arr['tags'], 'Shooter') !== false) $steam_arr['genres'] .= ',Shooter';
		if(strpos($steam_arr['tags'], 'Fighting') !== false) $steam_arr['genres'] .= ",Beat 'Em Up";
		if(strpos($steam_arr['tags'], 'Brettspiel') !== false) $steam_arr['genres'] .= ',Brettspiele';
		if(strpos($steam_arr['tags'], 'Zombies') !== false) $steam_arr['genres'] .= ',Survival Horror';

		$steam_arr['genres'] = substr($steam_arr['genres'], 0, 65);
		$steam_arr['genres'] = preg_replace('/,[^,]*$/', '', $steam_arr['genres']);
		$specifics['Genre'] = implode(',', array_flip(array_flip(explode(',', $steam_arr['genres']))));
	}

	if($steam_arr['publisher']){
		$specifics['Herausgeber'] = $steam_arr['publisher'];
	}

	if($steam_arr['developer']){
		$specifics['Marke'] = $steam_arr['developer'];
	}

	$specifics['Regionalcode'] = 'Regionalcode-frei';

	if($steam_arr['lang']){
		$steam_arr['lang'] = str_ireplace('#lang_german;', 'Deutsch', $steam_arr['lang']);
		// $steam_arr['lang'] = substr($steam_arr['lang'], 0, 65);
		// $steam_arr['lang'] = preg_replace('/,[^,]*$/', '', $steam_arr['lang']);
		// $specifics['Language'] = implode(',', array_flip(array_flip(explode(',', $steam_arr['lang']))));

		$specifics['Language'] = explode(',', $steam_arr['lang']);
	}

	$specifics['Downloade Site'] = 'http://store.steampowered.com';

	$mods = [
		'Einzelspieler' => 1,
		'Koop' => 2,
		'Plattformübergreifender Mehrspieler' => 3,
		'Mehrspieler' => 4,
		'Mehrspielermodus (online)' => 5,
		'Online-Koop' => 6,
		'MMO' => 7,
	];
	$specifics['Spielmodus'] = [];
	foreach (explode(',', $steam_arr['specs']) as $s => $spec) {
		if(isset($mods[$spec])) $specifics['Spielmodus'][] = $spec;
	}

	unset($mods['Einzelspieler']);
	$specifics['Besonderheiten'] = 'Download-Code';
	$is_multi = false;
	foreach (explode(',', $steam_arr['specs']) as $s => $spec) {
		if(isset($mods[$spec])) $is_multi = true;
	}
	if($is_multi) $specifics['Besonderheiten'] .= ', Multi-player';

	$specifics['Tags'] = []; // первые 5
	if($steam_arr['tags']){
		foreach (explode(',', $steam_arr['tags']) as $t => $tag) {
			$specifics['Tags'][] = $tag;
			if($t > 3) break;
		}
	}

	$specifics['Erscheinungsjahr'] = $steam_arr['year'];

	$output = '<pre>'.print_r($specifics,1).'</pre>';
	$res = $ebayObj->UpdateCategorySpecifics($item_id, $specifics);
	// if(!isset($res['Ack']) || $res['Ack'] !== 'Success'){
	// 	$res = $ebayObj->UpdateCategorySpecifics($item_id, $specifics);
	// }
	// if(!isset($res['Ack']) || $res['Ack'] !== 'Success'){
	// 	$res = $ebayObj->UpdateCategorySpecifics($item_id, $specifics);
	// }

	if (!isset($res['Ack']) || $res['Ack'] !== 'Success') {
		$file = 'E:\xamp\htdocs\info-rim.ru\www\Specs-Fails.txt';
		file_put_contents($file, $item_id.PHP_EOL, FILE_APPEND | LOCK_EX);
	}

	unset($res['Fees']);
	return $output.'<pre>'.print_r($res,1).'</pre>';
}




$excel_list = readExcel('csv/ebay_steam1.xlsx', 0);

$fails = '121531573314
111977393666
121965995104
121965995153
111977928469
121965995251
121772453698
111730686106
121968062501
121968062431
112262376884
122175707094
122179208018
121526107522
111583549338
121606660970';
$fails = explode(PHP_EOL, $fails);

foreach ($excel_list as $row => $col) {
	//break;
	$ebay_id = trim($col['B']);
	//if(!in_array($row, [1104,1136])) continue;
	if($row < 0 || $row > 120) continue;
	if(!in_array($ebay_id, $fails)) continue;
	echo '<hr><b>row = ',$row,'</b><br>';
	$link = $col['F'];
	if(!$link) {echo "<i>! в excel нет ссылки</i>"; continue;}
	$steam_arr = arrayDB("SELECT steam_de.*,steam.usk_links as pegi_links,steam.usk_age as pegi_age 
						FROM steam_de
						JOIN steam
						ON steam_de.link = steam.link
						WHERE steam_de.link = '$link'");
	echo '<div>===> <a href="',$col['C'],'" target="_blank">',$col['D'],'</a></div>';
	echo '<div>===> <a href="',$col['F'],'" target="_blank">',$col['F'],'</a></div>';
	if(count($steam_arr) < 1) {echo "<i>! в базе нет записи</i>"; continue;}
	if(count($steam_arr) > 1) {echo "<i>! в базе больше 1 записи</i>"; continue;}
	$steam_arr = $steam_arr[0];

	echo set_item_specifics($ebay_id, $steam_arr);
}

//68
?>





<?php

//print_r($excel_list);

?>
