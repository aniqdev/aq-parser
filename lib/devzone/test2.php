<pre>
<?php
ini_get('safe_mode') or set_time_limit(180); // Указываем скрипту, чтобы не обрывал связь.

//=============================================================================================

$steam_arr['genres'] = 'Gelegenheitsspiele,Kostenlos,Indie,Simulation,Strategie,Early Access';

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
		$steam_arr['genres'] = substr($steam_arr['genres'], 0, 65);
		$steam_arr['genres'] = preg_replace('/,[^,]*$/', '', $steam_arr['genres']);
		$specifics['Genre'] = implode(',', array_flip(array_flip(explode(',', $steam_arr['genres']))));
	}

	var_dump($specifics['Genre']);
//=============================================================================================
	$steam_arr['lang'] = 'Deutsch,Englisch,Französisch,Italienisch,Spanisch,Dänisch,Norwegisch,Polnisch,Brasilianisches Portugiesisch';

	if($steam_arr['lang']){
		$steam_arr['lang'] = str_ireplace('#lang_german;', 'Deutsch', $steam_arr['lang']);
		$steam_arr['lang'] = substr($steam_arr['lang'], 0, 65);
		$steam_arr['lang'] = preg_replace('/,[^,]*$/', '', $steam_arr['lang']);
		$specifics['Language'] = implode(',', array_flip(array_flip(explode(',', $steam_arr['lang']))));
	}
	var_dump($specifics['Language']);

// $str = 'asd,qwer,zxcv,qwer,dfgh';

// var_dump($str);

// $str = preg_replace('/\W(\w{3,9})\W+\1\W/si', "$1", $str);

// var_dump($str);



// var_dump(implode(',', array_flip(array_flip(explode(',', $str)))));





// $res = Ebay_shopping2::findItemsAdvanced(0,'igx4u_com',1,200);

// $res = json_decode($res, true);

// foreach ($res['findItemsAdvancedResponse'][0] as $key => $value) {
// 	var_dump($key);
// }


// print_r($received_item = get_item_xml('https://shop.digiseller.ru/xml/purchase.asp?id_i=56701909&uid=76BA7F0B4894423C988B9510D80D637A'));

// 	$product = $received_item['result'];
// var_dump(is_utf8($product));
// 	$product = get_steam_key_from_text($product);
// 	$product = get_urls_from_text($product);

// 	var_dump($product);

$ebayObj = new Ebay_shopping2();

// $res = $ebayObj->GetCategorySpecifics(139973);

// print_r($res);


// $PictureURL = 'http://i.ebayimg.com/images/g/kEQAAOSwPcVVuexY/s-l500.jpg';
// $PictureURL = 'http://i.ebayimg.com/00/s/NTAwWDUwMA==/z/ApQAAOSw~bFWIWOW/$_1.JPG?set_id=8800005007';
// $found = preg_match('#/[^s]/(.+)/#', $PictureURL, $matches);
// print_r($matches);

	// $item_id = '111978074501';

	// $res = $ebayObj->getSingleItem($item_id, JSON_OBJECT_AS_ARRAY);

	// print_r($res);

	// $res = $ebayObj->GetSellerListRequest(1, 20);

	// print_r($res);

//$res = $ebayObj->getSingleItem('122191975579');

// $new_title = "Babel Rising + Sky's The Limit DLC PC spiel Steam Download Link DE/EU/USA Key";

// $res = $ebayObj->updateItemTitle('121606418036', $new_title);

function get_item_specs($item_id=''){

	if(!$item_id) return false;
	$res = Ebay_shopping2::getSingleItem($item_id);

	$specifics = json_decode($res, true)['Item']['ItemSpecifics']['NameValueList'];

	$specs = [];
	foreach ($specifics as $val) $specs[$val['Name']] = $val['Value'][0];

	return $specs;
}

// $specs = get_item_specs('122191973443');

// $item_usk = $specs['USK-Einstufung'];

// print_r($item_usk);

//print_r(json_decode($res, true)['Item']['ItemSpecifics']['NameValueList']);


function set_item_specifics($item_id, $steam_arr = []){

	$ebayObj = new Ebay_shopping2();

	$specifics = [];

	$specs = get_item_specs('112174430780');

	if (isset($specs['USK-Einstufung'])) {
		$specifics['USK-Einstufung'] = $specs['USK-Einstufung'];
	}elseif($steam_arr['usk_links']) {
		$specifics['USK-Einstufung'] = 'USK ab '.$steam_arr['usk_age'];
	}else{
		$specifics['USK-Einstufung'] = 'Unbekannt';
	}

	if($steam_arr['os']){
		$steam_arr['os'] = str_ireplace('win', 'PC', $steam_arr['os']);
		$specifics['Plattform'] = explode(',', $steam_arr['os']);
	}

	if($steam_arr['genres']){
		if(stripos($steam_arr['genres'], 'rpg') !== false) $steam_arr['genres'] .= ',Rollenspiel';
		if(stripos($steam_arr['genres'], 'Action') !== false && stripos($steam_arr['genres'], ',Abenteuer') !== false){
			$steam_arr['genres'] = str_ireplace(',Abenteuer', '', $steam_arr['genres']);
		}
		$steam_arr['genres'] = str_ireplace('Simulation', 'Simulationen', $steam_arr['genres']);
		$specifics['Genre'] = explode(',', $steam_arr['genres']);
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
		$specifics['Language'] = explode(',', $steam_arr['lang']);
	}

	$specifics['Downloade Site'] = 'http://store.steampowered.com';

	$mods = [
		'Einzelspieler' => 1,
		'Koop' => 1,
		'Plattformübergreifender Mehrspieler' => 1,
		'Mehrspieler' => 1,
		'Mehrspielermodus (online)' => 1,
		'Online-Koop' => 1,
		'MMO' => 1,
	];
	$specifics['Spielmodus'] = [];
	foreach (explode(',', $steam_arr['specs']) as $s => $spec) {
		if(isset($mods[$spec])) $specifics['Spielmodus'][] = $spec;
	}

	$specifics['Besonderheiten'] = 'Download-Code';

	$specifics['Tags'] = []; // первые 5
	if($steam_arr['tags']){
		foreach (explode(',', $steam_arr['tags']) as $t => $tag) {
			$specifics['Tags'][] = $tag;
			if($t > 3) break;
		}
	}

	$specifics['Erscheinungsjahr'] = $steam_arr['year'];

	return $ebayObj->UpdateCategorySpecifics($item_id, $specifics);
}



?>
</pre>