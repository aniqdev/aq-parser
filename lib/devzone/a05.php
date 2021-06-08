<div class="containerr">
<?php ini_get('safe_mode') or set_time_limit(300);




$url = 'https://cdvet-parser.gig-games.de/b2b/input.json';
$json = file_get_contents($url);
$cdvet_feed = json_decode($json, 1);

sa($cdvet_feed);


return;
$file = csvToArr('./Files/wp_posts.csv',['delimetr' => ',']);

sa(count($file));
$shop_ids = array_column($file,2,0);
// sa($file);

$file = csvToArr('./Files/wc-product-export-11-2-2021-1613032085480.csv',['delimetr' => ',']);
// $file = csvToArr('./Files/wc-product-export-11-2-2021-1613035865134.csv',['delimetr' => ',']);
sa(count($file));
// sa($file);
//********************************************************
$url = 'https://cdvet-parser.gig-games.de/b2b/input.json';
$json = file_get_contents($url);
$cdvet_feed = json_decode($json, 1);

$feed_items = [];
foreach ($cdvet_feed as $key => $variants) {
	foreach($variants as $feed_item){
		$feed_items[] = $feed_item;
	}
}
sa(count($feed_items));
$feed_items = array_column($feed_items,'price','id');
// sa($feed_items);
//********************************************************
$url = './csv/cdvet-products-09.12.20.json';
$json = file_get_contents($url);
$cdvet_products = json_decode($json, 1);
sa(count($cdvet_products));
$cdvet_products = array_column($cdvet_products,'F','A');
//********************************************************

$match_count = 0;
foreach ($file as $key => &$row) {
	$price = (float)str_replace(',', '.', $row[2]);
	$shop_id = @$shop_ids[$row[0]];
	$feed_price = @$feed_items[$shop_id];
	$tax = @$cdvet_products[$shop_id];
	if ($price && $shop_id && $tax) {
		$new_price = calc_price($feed_price, $tax);
		$new_price = str_replace('.', ',', $new_price);
		$row[2] = $new_price;
		$match_count++;
	}else{
		$new_price = 'not calculated';
	}
	// $row['old_price'] = $price;
	// $row['feed_price'] = $feed_price;
	// $row['new_price'] = $new_price;
	// $row['shop_id'] = $shop_id;
	// $row[] = $tax;
}
sa($match_count);
sa($file);

arrToCsv($file, './Files/cdvet-new-prices.csv',['keys_first_row' => false]);

function calc_price($old_price, $tax)
{
	$price = (float)$old_price * 1.25;
	if($tax == 5) $price = $price * 1.07;
	if($tax == 16) $price = $price * 1.19;
	$price = round($price, 2);
	$int = (int)$price;
	$cents = $price*100 % 100;
	$cents = $cents < 50 ? 49 : 99;
	$cents = $cents / 100;
	return (string)($int + $cents);
}
return;
$feed_new = csvToArr('https://www.cdvet.de/backend/export/index/productckeck?feedID=47&hash=a4dc5afc43b82eefd412334d8ed3239e', ['max_str' => 0,'encoding' => 'windows-1250', 'del_first' => false]);

// $feed_new = file_get_contents('cdvet/cdvet-feed-3239e.json');

// $feed_new = json_decode($feed_new, true);

$new_arr = [];
foreach ($feed_new as $key => $value) {
	if($key === 0) continue;
	$value[9] = '!!!Description here!!!';
	$new_arr[$value[14]][] = $value;
}

sa($feed_new[0]);
sa(count($new_arr));
sa($new_arr['https://www.cdvet.de/toxisan']);






return;
sa(rawurldecode('https%3A%2F%2Fwww.ebay.de%2Fsch%2FPC-Videospiele%2F1249%2Fi.html%3F_from%3DR40%26_nkw%3D'));

sa(rawurldecode('%26_dcat%3D1249%26Plattform%3DPC%26rt%3Dnc%26_trksid%3Dp2045573.m1684'));


return;
sa($res = Cdvet::GetSellerListRequest(1, 20));






return;
sa(base64url_encode(md5('http://store.steampowered.com/app/279740/')));
sa(base64_encode('http://store.steampowered.com/app/279740/'));

// MDg5MzcwNWZjNTMzNTMxYmVhNmFjNGIxMTdiYjRjYzc


return;
$res = arrayDB("SELECT title,type,appid,link,pics,updated_at from steam_de order by updated_at desc limit 300");
$res = arrayDB("select title,type,appid,link,pics,updated_at from steam_de where title = '112 Operator' order by updated_at desc limit 300");
foreach ($res as $key => $row) {
	$url = get_steam_images_url($row['type'], $row['appid'], '/header-80p.jpg');
	echo '<img src="'.$url.'" alt="">';
}

?>
</div>
<?php



return;
$copied = copy(is_dev('http://parser/moda-files/moda-arr.txt','http://parser.modetoday.de/moda-files/moda-arr.txt'), ROOT . '/moda-files/moda-arr.copy.txt');

if ($copied) {
	$handle = @fopen(ROOT . '/moda-files/moda-arr.copy.txt', "r");
	if ($handle) {
		$n = 0;
	    while (($str = fgets($handle)) !== false) {
	        if($n < 10) sa(json_decode($str,1));
	    	$n++;
	    }
	    if (!feof($handle)) {
	        echo "Ошибка: fgets() неожиданно потерпел неудачу\n";
	    }
	    fclose($handle);
	    sa($n);
	}
}