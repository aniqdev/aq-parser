<select name="select-city" onchange="location = this.value;">
<option value="">Select-City</option>
 <option value="https://en.wikipedia.org/wiki/New_Delhi">New Delhi</option>
 <option value="https://en.wikipedia.org/wiki/New_York">New York</option>
 <option value="https://en.wikipedia.org/wiki/Bern">Bern</option>
 <option value="https://en.wikipedia.org/wiki/Beijing">Beijing</option>
</select>
<?php ini_get('safe_mode') or set_time_limit(2000); // Указываем скрипту, чтобы не обрывал связь.



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


	