<?php
ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.
error_reporting(E_ALL);
ini_set('display_errors', 1);



$items = arrayDB("SELECT * FROM cdvet");

$feed_new = csvToArr('http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129', ['max_str' => 0,'encoding' => 'windows-1250']);

$feed_new = array_column($feed_new, null, 0);

$feed_old = arrayDB("SELECT * FROM cdvet_feed");

$feed_old = array_column($feed_old, null, 'shop_id');

// var_dump($feed_new[175][5] === $feed_old[175]['desc']);

// sa($feed_new);
// sa($feed_old);
// return;

if(!$items || !$feed_new || !$feed_old) return;

$msg = ''; $items_arr = [];

foreach ($items as $k => $item) {

	$shop_id = $item['shop_id'];
	$elements_arr = [];
	$changed = false;

	if (!isset($feed_new[$shop_id])) {
		$msg .= '<p>There is no data in the feed for eBayID: '.$item['ebay_id'].' , shopId: '.$shop_id.'</p>'.PHP_EOL;
		$elements_arr['ItemID'] =  $item['ebay_id'];
		$elements_arr['Quantity'] = '0';
		pc_add_to_items_arr($items_arr, $elements_arr);
		$log_msg = 'There is no data in the feed for eBayID: '.$item['ebay_id'].' , shopId: '.$shop_id;
		pc_add_to_log($item, false, $log_msg, 1);
		continue;
	}

	$title = _esc($feed_new[$shop_id][4]);
	$price = str_replace(',', '.', $feed_new[$shop_id][12]);
	$desc = _esc($feed_new[$shop_id][9]);
	$link = _esc($feed_new[$shop_id][14]);
	$image = _esc($feed_new[$shop_id][15]);
	$short_desc = _esc($feed_new[$shop_id][19]);
	$instock = ($feed_new[$shop_id][17] === '2 Tage') ? 'instock' : 'outofstock';

	//         [
	//             [ItemID] => 253201322474
	//             [StartPrice] => 40.03
	//             [Quantity] => 4
	//         ]
	if (isset($feed_old[$shop_id])) { // производим сравнение
		
		// сравнение цены
		if ($price !== $feed_old[$shop_id]['price']) {
			// var_dump('expression<br>');
			$elements_arr['ItemID'] =  $item['ebay_id'];
			$elements_arr['StartPrice'] =  $price + 3;
			$changed = true;
			$log_msg = 'Price changed for eBayID: '.$item['ebay_id'].' , shopId: '.$shop_id.' ('.$item['title'].')';
			pc_add_to_log($item, $feed_new[$shop_id], $log_msg, 2);
		}

		// сравнение наличия
		if ($instock !== $feed_old[$shop_id]['instock']) {
			$elements_arr['ItemID'] =  $item['ebay_id'];
			$elements_arr['Quantity'] =  ($feed_new[$shop_id][17] === '2 Tage') ? '1' : '0';
			$changed = true;
			$log_msg = 'Quantity changed for eBayID: '.$item['ebay_id'].' , shopId: '.$shop_id.' ('.$item['title'].')';
			pc_add_to_log($item, $feed_new[$shop_id], $log_msg, 3);
		}

		// сравнение названия
		if ($feed_new[$shop_id][4] !== $feed_old[$shop_id]['title']) {
			$msg .= '<p>Title changed for eBayID: '.$item['ebay_id'].' ('.$item['title'].')</p>'.PHP_EOL;
			$changed = true;
			$log_msg = 'Title changed for eBayID: '.$item['ebay_id'].' , shopId: '.$shop_id.' ('.$feed_new[$shop_id][4] .'!=='. $feed_old[$shop_id]['title'].')';
			pc_add_to_log($item, $feed_new[$shop_id], $log_msg, 4);
		}

		// сравнение описания
		if ($feed_new[$shop_id][9] !== $feed_old[$shop_id]['desc']) {
			$msg .= '<p>Description changed for eBayID: '.$item['ebay_id'].' ('.$item['title'].')</p>'.PHP_EOL;
			$changed = true;
			$log_msg = 'Description changed for eBayID: '.$item['ebay_id'].' , shopId: '.$shop_id.' ('.$item['title'].')';
			pc_add_to_log($item, $feed_new[$shop_id], $log_msg, 5);
		}

		if($elements_arr) pc_add_to_items_arr($items_arr, $elements_arr);


		if($changed){
			arrayDB("UPDATE cdvet_feed 
				SET title = '$title', price = '$price', `desc` = '$desc', instock = '$instock'
				WHERE shop_id = '$shop_id'");
		} 
	}else{
		$UnitQuantity =  _esc($feed_new[$shop_id][8]);
		$UnitType =  _esc($feed_new[$shop_id][7]);
		arrayDB("INSERT INTO cdvet_feed(shop_id,title,UnitQuantity,UnitType,price,link,image,`desc`,short_desc,instock)
				VALUES('$shop_id','$title','$UnitQuantity','$UnitType','$price','$link','$image','$desc','$short_desc','$instock')");
	}
}

echo $msg;
sa($items_arr);

if(defined('DEV_MODE')) return;

if($items_arr) {
	foreach ($items_arr as $child_arr) {
		$resp = Cdvet::reviseInventoryStatus($child_arr);
		unset($resp['Fees']);
		sa($resp);
		$ebay_id = $child_arr[0]['ItemID'];
		$api_resp = _esc(json_encode($resp));
		arrayDB("UPDATE cdvet_checker_log set api_resp = '$api_resp' where ebay_id = '$ebay_id' order by id desc limit 1");
	}
}


// узнаем расхождение между старым и новым фидами
$feed_new_compare = array_column($feed_new, 0);

$feed_file = ROOT.'/lib/adds/cdvet_feed.txt';

$feed_old_compare = explode(',', file_get_contents($feed_file));


$in_feed_new_absent = array_diff($feed_old_compare, $feed_new_compare);
$in_feed_old_absent = array_diff($feed_new_compare, $feed_old_compare);

if ($in_feed_old_absent) {
	sa($in_feed_old_absent);
	$ids = _esc(implode(',', $in_feed_old_absent));
	arrayDB("INSERT INTO cdvet_feed_log(dir,ids) VALUES('appeared','$ids')");
}

if ($in_feed_new_absent) {
	sa($in_feed_new_absent);
	$ids = _esc(implode(',', $in_feed_new_absent));
	arrayDB("INSERT INTO cdvet_feed_log(dir,ids) VALUES('removed','$ids')");
}

file_put_contents($feed_file, implode(',', $feed_new_compare));

// отправляем отчет на имейл
if ($msg) {
	$to      = 'eBay-cdvet@koeln-webstudio.de,thenav@mail.ru';
	$subject = 'cdVet ebay checker report';
	// Для отправки HTML-письма должен быть установлен заголовок Content-type
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Дополнительные заголовки
	$headers .= 'To: ' . $to . "\r\n";
	$headers .= 'From: Ebay checker <support@gig-games.de>' . "\r\n";
	// $headers[] = 'Bcc: birthdaycheck@example.com';

	mail($to, $subject, $msg, $headers);
}




// вывод ошибок
sa($_ERRORS);

?>