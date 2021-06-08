<div class="containerr">
<?php ini_get('safe_mode') or set_time_limit(300);






$query = ('samo_action=PRICES&TOWNFROMINC=274286&STATEINC=210357&TOURTYPE=0&TOURINC=0&PROGRAMINC=0&CHECKIN_BEG=20210406&NIGHTS_FROM=7&CHECKIN_END=20210406&NIGHTS_TILL=10&ADULT=2&CURRENCY=1&CHILD=0&TOWNS_ANY=0&TOWNS=NaN%2C319474%2C434539%2C319480%2C319483%2C319486%2C355102%2C355101%2C319492&STARS_ANY=1&STARS=&hotelsearch=0&HOTELS_ANY=1&HOTELS=&MEALS_ANY=0&MEALS=10004&ROOMS_ANY=1&ROOMS=&FREIGHT=1&FILTER=1&MOMENT_CONFIRM=0&WITHOUT_PROMO=0&UFILTER=&HOTELTYPES=&PARTITION_PRICE=224&PRICEPAGE=1&rev=1641272844&_=1617555835793');
parse_str($query, $query_arr);

sa(($query_arr));

return;
$url = 'https://cdvet-parser.gig-games.de/b2b/input_complete.json';
$json = file_get_contents($url);
$cdvet_feed = json_decode($json, 1);

sa($cdvet_feed);





return;
$hund_count = 0;
foreach ($cdvet_feed as $key => $variants) {
	if(stripos($variants[0]['description_clean'], 'hund') !== false || stripos($variants[0]['name'], 'hund') !== false) $hund_count++;
}

sa(count($cdvet_feed));
sa(($hund_count));
sa($cdvet_feed);

return;
$feed_items = [];
foreach ($cdvet_feed as $key => $variants) {
	foreach($variants as $feed_item){
		$feed_items[] = $feed_item;
	}
}

sa(count($feed_items));

$feed_items = array_column($feed_items, null,'id');
sa($feed_items);


return;

$url = './csv/cdvet-products-09.12.20.json';
$json = file_get_contents($url);
$cdvet_products = json_decode($json, 1);

// $feed_items = [];
// foreach ($cdvet_feed as $key => $variants) {
// 	foreach($variants as $feed_item){
// 		$feed_items[] = $feed_item;
// 	}
// }

sa(count($cdvet_products));

$cdvet_products = array_column($cdvet_products,'F','A');
sa($cdvet_products);




return;
$feed_new = csvToArr('https://www.cdvet.de/backend/export/index/productckeck?feedID=47&hash=a4dc5afc43b82eefd412334d8ed3239e', ['max_str' => 0,'encoding' => 'windows-1250', 'del_first' => true]);


$cdvet_cats = [];

foreach ($feed_new as $key => $string) {
	// echo "<div style='border-top: 1px solid #797979;'></div>";
	// echo $string[10];
	@$cdvet_cats[$string[10]] += 1;
}
// $cdvet_cats = array_unique($cdvet_cats);

xa($cdvet_cats);
// sa($feed_new);


