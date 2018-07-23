<?php
ini_get('safe_mode') or set_time_limit(200); // Указываем скрипту, чтобы не обрывал связь.


$filename = __DIR__.'/filter-report.txt';
// file_put_contents($filename, date('d-m-y H:i:s').' started' . PHP_EOL);


$_GET['old_prices'] = arrayDB("SELECT ebay_id,ebay_price,instock FROM steam_de WHERE ebay_id <> ''");
$_GET['old_prices'] = array_column($_GET['old_prices'], null, 'ebay_id');
// sa($_GET['old_prices']);


function f_build_query(&$item){

	// sa([
	// 	'ItemID' => $item['ItemID'],
	// 	'Title' => $item['Title'],
	// 	'SellingStatus' => $item['SellingStatus']['ListingStatus'],
	// 	'CurrentPrice' => $item['SellingStatus']['CurrentPrice'],
	// 	'Quantity' => $item['Quantity'],
	// 	'QuantitySold' => $item['SellingStatus']['QuantitySold'],
	// ]);
	// return '';
	
	$sql = '';
	$item_id = _esc($item['ItemID']);
	$new_price = (float)$item['SellingStatus']['CurrentPrice'];
	$new_instock = (($item['Quantity'] - $item['SellingStatus']['QuantitySold']) === 0)?'no':'yes';

	if($item['SellingStatus']['ListingStatus'] === 'Completed') { // удаляем закрытые аукционы
		if(!isset($_GET['old_prices'][$item_id])) return '';
		$sql .= "UPDATE steam_de SET ebay_id = '' WHERE ebay_id = '$item_id';".PHP_EOL;
		$sql .= "UPDATE steam_en SET ebay_id = '' WHERE ebay_id = '$item_id';".PHP_EOL;
		$sql .= "UPDATE steam_fr SET ebay_id = '' WHERE ebay_id = '$item_id';".PHP_EOL;
		$sql .= "UPDATE steam_es SET ebay_id = '' WHERE ebay_id = '$item_id';".PHP_EOL;
		$sql .= "UPDATE steam_it SET ebay_id = '' WHERE ebay_id = '$item_id';".PHP_EOL;
	}elseif($new_instock === 'no'){ // помечаем отсутствующий товар
		if(!isset($_GET['old_prices'][$item_id]) || $_GET['old_prices'][$item_id]['instock'] === 'no') return '';
		$sql .= "UPDATE steam_de SET instock = 'no' WHERE ebay_id = '$item_id';".PHP_EOL;
		$sql .= "UPDATE steam_en SET instock = 'no' WHERE ebay_id = '$item_id';".PHP_EOL;
		$sql .= "UPDATE steam_fr SET instock = 'no' WHERE ebay_id = '$item_id';".PHP_EOL;
		$sql .= "UPDATE steam_es SET instock = 'no' WHERE ebay_id = '$item_id';".PHP_EOL;
		$sql .= "UPDATE steam_it SET instock = 'no' WHERE ebay_id = '$item_id';".PHP_EOL;
	}else{
		if(!isset($_GET['old_prices'][$item_id]) || 
			($_GET['old_prices'][$item_id]['instock'] === $new_instock &&
			$_GET['old_prices'][$item_id]['ebay_price'] == $new_price)) return '';
		$sql .= "UPDATE steam_de SET instock = '$new_instock', ebay_price = '$new_price' WHERE ebay_id = '$item_id';".PHP_EOL;
		$sql .= "UPDATE steam_en SET instock = '$new_instock', ebay_price = '$new_price' WHERE ebay_id = '$item_id';".PHP_EOL;
		$sql .= "UPDATE steam_fr SET instock = '$new_instock', ebay_price = '$new_price' WHERE ebay_id = '$item_id';".PHP_EOL;
		$sql .= "UPDATE steam_es SET instock = '$new_instock', ebay_price = '$new_price' WHERE ebay_id = '$item_id';".PHP_EOL;
		$sql .= "UPDATE steam_it SET instock = '$new_instock', ebay_price = '$new_price' WHERE ebay_id = '$item_id';".PHP_EOL;
	}
	return $sql;
}




$report = [];
$ebayObj = new Ebay_shopping2();

$res = $ebayObj->GetSellerListRequest(1, 200);

$sql = '';
foreach ($res['ItemArray']['Item'] as $key => &$item) {
	// if($key > 50) break;
	$sql .= f_build_query($item);
}
sa($sql);
if($sql) var_dump(arrayDB($sql, true));


// return;
$pages = $res['PaginationResult']['TotalNumberOfPages'];
if(!$pages) return;

sa($pages);


$multi_curl = ef_get_milticurl_handler();
$multi_curl->success(function($instance) {
	$sql = '';
	foreach (json_decode(json_encode($instance->response->ItemArray), true)['Item'] as $item) {
    	$sql .= f_build_query($item);
	}
	sa($sql);
	if($sql) var_dump(arrayDB($sql, true));
});


$ebay_api_url = 'https://api.ebay.com/ws/api.dll';

for ($i=2; $i <= $pages; $i++) {
	// if($i > 5) continue;
	$multi_curl->addPost($ebay_api_url, ef_build_post_data($i));
}

$multi_curl->start(); // Blocks until all items in the queue have been processed.

// $steam_table = 'steam_en';
// arrayDB("UPDATE `$steam_table`
// inner join steam_de
// on  `$steam_table`.link = steam_de.link
// set `$steam_table`.ebay_price = steam_de.ebay_price,
//     `$steam_table`.instock = steam_de.instock
// WHERE `$steam_table`.ebay_id <> '' AND steam_de.ebay_id <> ''");

// $steam_table = 'steam_fr';
// arrayDB("UPDATE `$steam_table`
// inner join steam_de
// on  `$steam_table`.link = steam_de.link
// set `$steam_table`.ebay_price = steam_de.ebay_price,
//     `$steam_table`.instock = steam_de.instock
// WHERE `$steam_table`.ebay_id <> '' AND steam_de.ebay_id <> ''");

// $steam_table = 'steam_es';
// arrayDB("UPDATE `$steam_table`
// inner join steam_de
// on  `$steam_table`.link = steam_de.link
// set `$steam_table`.ebay_price = steam_de.ebay_price,
//     `$steam_table`.instock = steam_de.instock
// WHERE `$steam_table`.ebay_id <> '' AND steam_de.ebay_id <> ''");

// $steam_table = 'steam_it';
// arrayDB("UPDATE `$steam_table`
// inner join steam_de
// on  `$steam_table`.link = steam_de.link
// set `$steam_table`.ebay_price = steam_de.ebay_price,
//     `$steam_table`.instock = steam_de.instock
// WHERE `$steam_table`.ebay_id <> '' AND steam_de.ebay_id <> ''");


sa($_ERRORS);
?>