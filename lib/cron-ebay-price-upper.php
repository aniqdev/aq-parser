<?php ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.

// тз:
// https://docs.google.com/drawings/d/11Bv28JrqoOEVXv-NNtUF9DC4yDLA1TToCa2-T06H6vI/edit

$scanNew = arrayDB('SELECT DISTINCT scan FROM items ORDER BY id DESC LIMIT 1')[0]['scan'];
$ebay_scan = arrayDB('SELECT scan FROM ebay_results ORDER BY id DESC LIMIT 1')[0]['scan'];

$ebay_prices = arrayDB("SELECT item_id,price FROM ebay_prices");
$ebay_prices = array_column($ebay_prices, 'price', 'item_id');
if(count($ebay_prices) < 1000) return;

$ebay_games = arrayDB('SELECT item_id FROM ebay_games');
$ebay_games = array_column($ebay_games, 'item_id');
if(count($ebay_games) < 1000) return;


$games_arr = arrayDB("SELECT 
	games.name, new.item1_price, 
	games.id as game_id, games.ebay_id,
	itemid1, title1, price1,
	itemid2, title2, price2
	FROM games 
	JOIN (SELECT game_id,item1_price
			FROM items 
			WHERE scan='$scanNew' AND item1_price > 0) as new
	ON games.id=new.game_id

	JOIN (SELECT * FROM ebay_results WHERE scan='$ebay_scan' AND price1 > 0) as ebay
	ON games.id=ebay.game_id
	WHERE ebay_id > 0");

$exrate = arrayDB("SELECT value FROM aq_settings WHERE name='exrate'");
if($exrate) $exrate = $exrate[0]['value']; // 67
else return;
echo('exrate');
sa($exrate);

$white_list = arrayDB("SELECT game_id,ebay_id FROM ebay_black_white_list WHERE category = 'white'");

$white_list_sorted = [];
foreach ($white_list as $val) {
	$white_list_sorted[$val['game_id']][$val['ebay_id']] = 1;
}


echo('white_list count');
sa(count($white_list));

echo('white_list_sorted count');
sa(count($white_list_sorted));

// echo('white_list_sorted');
// sa($white_list_sorted);

$items_arr = [];
foreach ($games_arr as $game) {

	$game['set_price'] = 0;
	$elements_arr = [];
	// есть ли наименьшая цена конкурирующего товара в белом списке
	// если price1-recom_price≥0,1 то с=price1-0,1 но с(max)=1,2recom_price
	// если 0<price1-recom_price<0,1 то с=price1-0,01  но с(max)=1,2recom_price
	// где
	// a - рекомендованная цена
	// b - наименьшая цена конкурирующего товара 
	// с - цена нашего товара
	$game['recom_price'] = formula($game['item1_price'],$exrate);

	if (isset($ebay_prices[$game['itemid1']])) {
		if (isset($ebay_prices[$game['itemid2']])) {
			continue;
		}else{
			$game['competitor_price'] = $game['price2'];
			$game['competitor_id'] = $game['itemid2'];
		}	
	}else{
		$game['competitor_price'] = $game['price1'];
		$game['competitor_id'] = $game['itemid1'];
	}

	$game['is_white'] = isset($white_list_sorted[$game['game_id']][$game['competitor_id']])?1:0;

	$dif = $game['competitor_price'] - $game['recom_price'];

	if ($dif >= 0.1) $game['set_price'] = $game['competitor_price'] - 0.1;
	elseif ($dif > 0 && $dif < 0.1) $game['set_price'] = $game['competitor_price'] - 0.01;

	if($game['set_price'] > $game['recom_price'] * 1.2) $game['set_price'] = $game['recom_price'] * 1.2;
	// sa($game);

	//         [
	//             [ItemID] => 253201322474
	//             [StartPrice] => 40.03
	//             [Quantity] => 4
	//         ]
	if ($game['is_white'] && $game['set_price'] > 1.5 && $game['set_price'] > $ebay_prices[$game['ebay_id']]) {
		$elements_arr['ItemID'] = $game['ebay_id'];
		$elements_arr['StartPrice'] = round($game['set_price'], 2);
		$elements_arr['Quantity'] = '3';
		pc_add_to_items_arr($items_arr, $elements_arr);
	}
}

echo('items_arr');
sa($items_arr);

// return;
if(!defined('DEV_MODE') && $items_arr) {
	foreach ($items_arr as $child_arr) {
		$resp = (new Ebay_shopping2())->reviseInventoryStatus($child_arr);
		unset($resp['Fees']);
		sa($resp);
	}
}


sa($_ERRORS);