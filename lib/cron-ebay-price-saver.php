<?php
ini_get('safe_mode') or set_time_limit(600); // Указываем скрипту, чтобы не обрывал связь.

$start = time();



$_GET['items_arr'] = [];
$_GET['rr'] = ['/\sPC\s.+/i', '/\ssteam\sd.+/i', '/\ssteam\sli.+/i', '/\ssteam\seu.+/i'];
function u_build_arr(&$item){

	// sa($item);
    $title_clean = preg_replace($_GET['rr'], ' ', $item['Title']);
    $title_clean = str_ireplace('Add-On', ' ', $title_clean);
    $title_clean = trim(preg_replace('/\s+/', ' ', $title_clean));

	$found = preg_match('#/[^s]/(.+)/#', $item['PictureDetails']['PictureURL'][0], $matches);

	if(isset($item['ItemID']) && strlen($item['ItemID']) < 5) return; // костыль, если что-то не так с ebay_id
	$_GET['items_arr'][] = [
		'ItemID' => _esc($item['ItemID']),
		'Title' => _esc($item['Title']),
		'title_clean' => _esc($title_clean),
		'SellingStatus' => _esc($item['SellingStatus']['ListingStatus']),
		'CurrentPrice' => _esc($item['SellingStatus']['CurrentPrice']),
		'Quantity' => _esc($item['Quantity']),
		'QuantitySold' => _esc($item['SellingStatus']['QuantitySold']),
		'quantity_actual' => _esc((+$item['Quantity'] - +$item['SellingStatus']['QuantitySold'])),
		'picture_hash' => _esc($found ? $matches[1] : ''),
	];
}



$res = (new Ebay_shopping2())->GetSellerListRequest(1, 200);

// sa($res);
// return;


if(!isset($res['ItemArray']['Item'][0])) $res['ItemArray']['Item'] = [$res['ItemArray']['Item']];
foreach ($res['ItemArray']['Item'] as $key => &$item) {
	// if($key > 50) break;
	u_build_arr($item);
}


$pages = $res['PaginationResult']['TotalNumberOfPages'];
if(!$pages) return;

sa($pages);


$multi_curl = ef_get_milticurl_handler();
$multi_curl->success(function($instance) {
	$item_arr = json_decode(json_encode($instance->response->ItemArray), true)['Item'];
	if(!isset($item_arr[0])) $item_arr = [$item_arr];
 	foreach ($item_arr as &$item) u_build_arr($item);
});


$ebay_api_url = 'https://api.ebay.com/ws/api.dll';

for ($i=2; $i <= $pages; $i++) {
	// if($i > 5) break;
	$multi_curl->addPost($ebay_api_url, ef_build_post_data($i));
}

$multi_curl->start(); // Blocks until all items in the queue have been processed.










$ebay_prices = arrayDB("SELECT item_id FROM ebay_prices");
$ebay_prices = array_column($ebay_prices, 'item_id');
$report = ['UPDATED' => 0, 'INSERTED' => 0];
foreach ($_GET['items_arr'] as $k => $item) {
	// if($k > 10) break;
	// sa($item);
	// [
	//     [ItemID] => 122712179539
	//     [Title] => 0RBITALIS PC spiel Steam Download Digital Link DE/EU/USA Key Code Gift
	//     [title_clean] => 0RBITALIS
	//     [SellingStatus] => Completed
	//     [CurrentPrice] => 3.39
	//     [Quantity] => 0
	//     [QuantitySold] => 0
	//     [quantity_actual] => 0
	//     [picture_hash] => z6UAAOSwdI9Y8ctG
	// ]
	if (in_array($item['ItemID'], $ebay_prices)) {
		$report['UPDATED'] += 1;
		arrayDB("UPDATE ebay_prices
				 SET price = '$item[CurrentPrice]', status = '$item[SellingStatus]'
				 WHERE item_id = '$item[ItemID]'");
	}else{
		$report['INSERTED'] += 1;
		arrayDB("INSERT INTO ebay_prices (item_id,title,title_clean,price,picture_hash)
			VALUES('$item[ItemID]','$item[Title]','$item[title_clean]',
				'$item[CurrentPrice]','$item[picture_hash]')");
	}
}

sa(['start' => $start, 'end' => time(), 'seconds' => time()-$start]);

sa($report);

sa($_ERRORS);
?>