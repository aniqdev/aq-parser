<?php ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.





$feed_new = csvToArr('http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129', ['max_str' => 0,'encoding' => 'windows-1250']);

// $feed_new = array_column($feed_new, null, 0);

$feed_old = arrayDB("SELECT * FROM cdvet_feed_full");

$feed_old = array_column($feed_old, null, 'shop_id');



foreach ($feed_new as $val) {
	if($val[0] === 'aid') continue;
	
	$shop_id = $val[0];
	$title = _esc($val[4]);
	$price = str_replace(',', '.', $val[12]);
	$desc = _esc($val[9]);
	$link = _esc($val[14]);
	$image = _esc($val[15]);
	$short_desc = _esc($val[19]);
	$instock = ($val[17] === '2 Tage') ? 'instock' : 'outofstock';

	if (isset($feed_old[$shop_id])) {
		arrayDB("UPDATE cdvet_feed_full 
				SET title = '$title', price = '$price', `desc` = '$desc', instock = '$instock'
				WHERE shop_id = '$shop_id'");
	}else{
		$UnitQuantity =  _esc($val[8]);
		$UnitType =  _esc($val[7]);
		arrayDB("INSERT INTO cdvet_feed_full (shop_id,title,UnitQuantity,UnitType,price,link,image,`desc`,short_desc,instock)
				VALUES('$shop_id','$title','$UnitQuantity','$UnitType','$price','$link','$image','$desc','$short_desc','$instock')");
	}
}



