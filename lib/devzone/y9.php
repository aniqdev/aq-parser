<?php ini_get('safe_mode') or set_time_limit(2000); // Указываем скрипту, чтобы не обрывал связь.



sa(round(2.73, 0, PHP_ROUND_HALF_DOWN));


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


	