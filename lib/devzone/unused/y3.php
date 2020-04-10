<pre><?php

ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.

	$ebayObj = new Ebay_shopping2();
	// $res = $ebayObj->updateProductPrice2('111568744817', 19.98);
	// print_r($res);
	//sa($ebayObj->GetSellerListRequest(1, 200));
	//$res = $ebayObj->test(1);
	//$res = $ebayObj->getToken(1);
	//print_r($res);

	// $url = 'http://store.steampowered.com/app/6270/';
	// $url = preg_replace('/\?.+/', '', $url);
	// $game_id = preg_replace('/.*\/(\d+)\/.*/', '\1', $url);
	// $header_path = 'http://cdn.akamai.steamstatic.com/steam/apps/'.$game_id.'/header.jpg';
	// $img_generator_res = file_get_contents('http://hot-body.net/img-generator/?url2017='.$url);

	// print_r(json_decode($img_generator_res,1));


//print_r($ebayObj->updateItemPictureDetails('121962440805', ['http://funkyimg.com/i/2qXcP.jpg']));

// 5 plex http://funkyimg.com/i/2qXcL.jpg
// 10 plex http://funkyimg.com/i/2qXcN.jpg
// 3M isk http://funkyimg.com/i/2qXcP.jpg
// SI http://funkyimg.com/i/2r3gm.jpg

	$ord_obj = new EbayOrders;

	//$ord_arr = $ord_obj->getOrders(['NumberOfDays'=>2,'SortingOrder'=>'Ascending']);

	// $ord_arr = $ord_obj->getOrders(['order_status'=>'All',
	// 	'CreateTimeFrom'=>date('Y-m-d\TH:i:s.000\Z', time()-(2*24*60*60)),
	// 	'CreateTimeTo'=>date('Y-m-d\TH:i:s.000\Z', time())]);

// $ord_array = getOrderArray();
// 	 print_r($ord_array);
//print_r($_SERVER);
	// var_dump(date('Y-m-d\TH:i:s.000\Z', time()));

	print_r(_get_item_specs(111568744817));

?></pre>