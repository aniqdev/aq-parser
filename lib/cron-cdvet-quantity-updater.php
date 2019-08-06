<?php
ini_get('safe_mode') or set_time_limit(1000); // Указываем скрипту, чтобы не обрывал связь.


$ebay_item_arr = Cdvet::GetSellerList();


$cdvet_arr = arrayDB("SELECT ebay_id,shop_id FROM cdvet");
$cdvet_arr = array_column($cdvet_arr, 'shop_id', 'ebay_id');

// sa($cdvet_arr);

$feed_new = csvToArr('http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129', ['max_str' => 0,'encoding' => 'windows-1250']);
$feed_new = array_column($feed_new, null, 0);

// sa($feed_new);
$file = ROOT.'/Files/cdvet-quantity-updater-report.txt';
file_put_contents($file, '');

$items_arr = [];

// данный скрипт для перевыставления проданных на ибее позиций
// проверяет наличие товара в фиде и ствит количество 1 если instock
foreach ($ebay_item_arr as $key => $ebay_item) {

	// if($key > 500) break;

	$ebay_id = $ebay_item['ItemID'];
	if(isset($cdvet_arr[$ebay_id])) $shop_id = $cdvet_arr[$ebay_id];
	else continue;
	
	// For GetItem and related calls: This is the total of the number of items available for sale plus the quantity already sold. To determine the number of items available, subtract SellingStatus.QuantitySold from this value. 
	$quantity = $ebay_item['Quantity'] - $ebay_item['SellingStatus']['QuantitySold'];

	$elements_arr = [];
	if (!isset($feed_new[$shop_id])) {
		$elements_arr['ItemID'] =  $ebay_id;
		$elements_arr['Quantity'] = '0';
		pc_add_to_items_arr($items_arr, $elements_arr);
		continue;
	}
	if ($feed_new[$shop_id][17] === '2 Tage' && $quantity != '2') {
		$elements_arr['ItemID'] =  $ebay_id;
		$elements_arr['Quantity'] = '2';
	}
	if ($feed_new[$shop_id][12] == 0) {
		$elements_arr['ItemID'] =  $ebay_id;
		$elements_arr['Quantity'] = '0';
		file_put_contents($file, $ebay_id.PHP_EOL, FILE_APPEND);
	}
	if($elements_arr) pc_add_to_items_arr($items_arr, $elements_arr);

	sa(['$ebay_id' => $ebay_id,
		'$feed_new' => $feed_new[$shop_id][17],
		'$quantity' => $quantity]);
}

sa($items_arr);

if(defined('DEV_MODE')) return;

foreach ($items_arr as $child_arr) {
	$resp = Cdvet::reviseInventoryStatus($child_arr);
	unset($resp['Fees']);
	sa($resp);
}

sa($_ERRORS);

?>