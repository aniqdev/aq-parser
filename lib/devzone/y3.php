<pre><?php

ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.

	// $ebayObj = new Ebay_shopping2();
	// $res = $ebayObj->GetSellerListRequest(1, 200);
	// sa($res);

	// $url = 'http://store.steampowered.com/app/6270/';
	// $url = preg_replace('/\?.+/', '', $url);
	// $game_id = preg_replace('/.*\/(\d+)\/.*/', '\1', $url);
	// $header_path = 'http://cdn.akamai.steamstatic.com/steam/apps/'.$game_id.'/header.jpg';
	// $img_generator_res = file_get_contents('http://hot-body.net/img-generator/?url2017='.$url);

	// print_r(json_decode($img_generator_res,1));


print_r(date_interval_create_from_date_string('1 hour'));

?></pre>