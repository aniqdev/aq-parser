<?php ini_get('safe_mode') or set_time_limit(2000); // Указываем скрипту, чтобы не обрывал связь.









$arr = file(ROOT.'/Files/words.txt');

$arr = array_map(function($val){
	$val = str_getcsv($val, ';');
	return trim($val[0]);
}, $arr);

sa($arr);


return;

$table = 'gp_keywords';
$table = 'gp_keywords_2';

foreach ($arr as $word) {
	$word = _esc($word);
	arrayDB("INSERT INTO $table SET word = '$word'");
}





return;
$word = 'ардуино уно';

$res = save_domens_multi($word);

var_dump($res);

return;
$word = 'wp content';

$multi_curl = new \Curl\MultiCurl();

$multi_curl->success(function($instance) {

	$res = preg_match_all('/"rh":"(.+?)"/', $instance->response, $matches);

	var_dump($res);

	sa($matches[1]);
});

$multi_curl->error(function($instance) {
	global $_ERRORS;
	$_ERRORS[] = 'THAT WAS multi_curl ERROR!!!';
    $_ERRORS[] = $instance->errorMessage;
});

for ($offs=0; $offs < 701; $offs += 100) { 
	$url = get_google_url($word, $offs);
	$multi_curl->addGet($url);
}

$multi_curl->start();
//------------------------

return;
$url = "https://www.google.com/search?ved=0ahUKEwjmnKSd_N7jAhVxlIsKHaPWAQEQxdoBCFw&hl=ru&yv=3&q=wp+content&lr=&tbm=isch&ei=SnBBXebPD_GorgSjrYcI&vet=10ahUKEwjmnKSd_N7jAhVxlIsKHaPWAQEQuT0IQCgB.SnBBXebPD_GorgSjrYcI.i&ijn=4&start=100&asearch=ichunk&async=_id:rg_s,_pms:s,_fmt:pc";

// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL,$url);
// curl_setopt($ch, CURLOPT_USERAGENT, "");
// curl_setopt($ch, CURLOPT_FAILONERROR, 1);
// curl_setopt($ch, CURLOPT_HEADER, 0);
// curl_setopt($ch, CURLOPT_REFERER, "http://www.google.ru/"); 
// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
// curl_setopt($ch, CURLOPT_TIMEOUT, 30);
// curl_setopt($ch, CURLOPT_POST, 0);

// $data = $data_title = $data_description = curl_exec($ch);
// curl_close($ch);

$data = getSslPage($url);

$res = preg_match_all('/"rh":"(.+?)"/', $data, $matches);

var_dump($res);

// echo $data_url;

sa($matches[1]);



return;
$arr = file(ROOT.'/Files/words.txt');
$arr = array_map(function($val){return trim($val);}, $arr);
sa($arr);

return;
foreach ($arr as $word) {
	$word = _esc($word);
	arrayDB("INSERT INTO gp_keywords SET word = '$word'");
}




return;
$date = new DateTime();
sa($date);
sa($date->getTimestamp());
$time = time();
$date->setTimestamp($time);
$date->add(date_interval_create_from_date_string('1 hour'));
$date->setTime($date->format('H'), 0);
$time_stamp = $date->getTimestamp();

sa($date);
sa($time_stamp);






return;
		$res = Cdvet::GetSellerListRequest(1, 200);
sa($res);


return;
$ebay_item_arr = Cdvet::GetSellerList();


$cdvet_arr = arrayDB("SELECT ebay_id,shop_id FROM cdvet");
$cdvet_arr = array_column($cdvet_arr, 'shop_id', 'ebay_id');

sa($ebay_item_arr);





return;
$revStr = 'Очень положительные<br>82% из 2,084 обзоров положительные.';

preg_match_all("/[\d]+/", $revStr, $matches);
sa($matches);





return;
$categoryId = '139973';

	$res = EbayGigGames::setTokenByName('gig-games')
			->GetCategorySpecifics($categoryId);

	// $res = EbayGigGames::GetSellerListRequest($page=1, $entires=25);


// $res = get_product_list_test($plattform = 'cdvet');


sa($res);




return;
	$ord_obj = new Ebay_shopping2;


	$ord_arr = $ord_obj->GetSellerItemsArray();

	sa($ord_arr);


	