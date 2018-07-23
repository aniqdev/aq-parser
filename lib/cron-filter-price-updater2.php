<?php
ini_get('safe_mode') or set_time_limit(1200); // Указываем скрипту, чтобы не обрывал связь.


$filename = __DIR__.'/filter-report.txt';
file_put_contents($filename, 'started' . PHP_EOL);


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
	// 	// 'Title' => $item['Title'],
	// ]);
	// return '';

	$item_id = _esc($item['ItemID']);
	$new_price = (float)$item['SellingStatus']['CurrentPrice'];
	$new_instock = ($item['Quantity'] == '0')?'no':'yes';

	if($item['SellingStatus']['ListingStatus'] === 'Completed') { // удаляем закрытые аукционы
		if(!isset($_GET['old_prices'][$item_id])) return '';
		$sql = "UPDATE steam_de SET ebay_id = '' WHERE ebay_id = '$item_id';".PHP_EOL;
	}elseif($item['Quantity'] == '0'){ // помечаем отсутствующий товар
		file_put_contents(__DIR__.'/filter-report.txt', "$item_id instock $new_instock".PHP_EOL, FILE_APPEND);
		if(!isset($_GET['old_prices'][$item_id]) || $_GET['old_prices'][$item_id]['instock'] === 'no') return '';
		file_put_contents(__DIR__.'/filter-report.txt', 'done'.PHP_EOL, FILE_APPEND);
		$sql = "UPDATE steam_de SET instock = 'no' WHERE ebay_id = '$item_id';".PHP_EOL;
	}else{
		if(!isset($_GET['old_prices'][$item_id]) || 
			($_GET['old_prices'][$item_id]['instock'] === $new_instock &&
			$_GET['old_prices'][$item_id]['ebay_price'] == $new_price)) return '';
		$sql = "UPDATE steam_de SET instock = '$new_instock', ebay_price = '$new_price' WHERE ebay_id = '$item_id';".PHP_EOL;
	}
	return $sql;
}


$report = [];
$ebayObj = new Ebay_shopping2();

$res = $ebayObj->GetSellerListRequest(1, 200);
$sql = '';

foreach ($res['ItemArray']['Item'] as $key => &$item) {
	arrayDB(f_build_query($item));
}

unset($sql);
file_put_contents($filename, '1 saved'.PHP_EOL, FILE_APPEND);


$pages = $res['PaginationResult']['TotalNumberOfPages'];
if(!$pages) return;

for ($i=2; $i <= $pages; $i++) {
	$res = $ebayObj->GetSellerListRequest($i, 200);
	foreach ($res['ItemArray']['Item'] as $key => &$item) {
		arrayDB(f_build_query($item));
	}
	file_put_contents($filename, $i.' saved'.PHP_EOL, FILE_APPEND);
}

sa($report);
sa($_ERRORS);









?>