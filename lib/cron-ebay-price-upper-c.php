<?php
ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.

$start = time();


$exrate = arrayDB("SELECT value FROM aq_settings WHERE name='exrate'");
if($exrate) $exrate = $exrate[0]['value']; // 67
else return;
echo('exrate');
sa($exrate);


$scanNew = arrayDB('SELECT DISTINCT scan FROM items ORDER BY id DESC LIMIT 1')[0]['scan'];


$games_arr = arrayDB("SELECT 
		games.name, new.item1_price, 
		games.id as game_id, games.ebay_id,
		ebay_prices.price
	FROM games 
	JOIN (SELECT game_id,item1_price
			FROM items 
			WHERE scan='$scanNew' AND item1_price > 0) as new
	ON games.id = new.game_id

	JOIN ebay_prices
	ON games.ebay_id = ebay_prices.item_id
	WHERE ebay_id > 0 AND ebay_prices.status = 'Active'");





$items_arr = [];
foreach ($games_arr as &$game) {
	$elements_arr = [];

	$game['recom_price'] = formula($game['item1_price'],$exrate);
	$game['set_price'] = ($game['recom_price'] > $game['price']) ? $game['recom_price'] : 0;
	//         [
	//             [ItemID] => 253201322474
	//             [StartPrice] => 40.03
	//             [Quantity] => 4
	//         ]
	if ($game['set_price'] > 1.5) {
		$elements_arr['ItemID'] = $game['ebay_id'];
		$elements_arr['StartPrice'] = $game['set_price'];
		$elements_arr['Quantity'] = '3';
		pc_add_to_items_arr($items_arr, $elements_arr);
	}
}

sa($items_arr);
// sa($games_arr);

if(!defined('DEV_MODE') && $items_arr) {
	foreach ($items_arr as $child_arr) {
		$resp = (new Ebay_shopping2())->reviseInventoryStatus($child_arr);
		unset($resp['Fees']);
		sa($resp);
	}
}

sa($_ERRORS);