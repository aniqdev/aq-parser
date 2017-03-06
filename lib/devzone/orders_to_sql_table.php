<?php




$res = arrayDB("SELECT id,goods,ShippedTime FROM ebay_orders");

foreach ($res as $k => $val) {
	break;
	$goods = json_decode($val['goods'], 1);
	foreach ($goods as $key => $good) {
		for ($i=0; $i < $good['amount']; $i++) { 
			echo "<br>",$val['id'],'<br>';
			//sa($good);
			$gig_order_id = $val['id'];
			$title = _esc($good['title']);
			$price = _esc($good['price']);
			$amount = _esc($good['amount']);
			$ebay_id = _esc($good['itemid']);
			$shipped_time = _esc($val['ShippedTime']);
			arrayDB("INSERT INTO ebay_order_items (gig_order_id,title,price,amount,ebay_id,shipped_time)
					VALUES('$gig_order_id','$title','$price','$amount','$ebay_id','$shipped_time')");
		}
	}
}
	var_dump(DB::getInstance()->lastid());

//sa($res);





?>