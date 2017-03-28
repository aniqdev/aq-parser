<?php



$order_items = arrayDB("SELECT * from ebay_order_items");

foreach ($order_items as $key => $order_item) {
	if ($order_item['shipped_time'] == 0) {
		// var_dump('ZERO!!');
		// sa($order_item);
		$order_item_id = $order_item['id'];
		$gig_order_id = $order_item['gig_order_id'];
		arrayDB("UPDATE ebay_order_items 
				SET shipped_time = (SELECT ShippedTime FROM ebay_orders WHERE id='$gig_order_id')
				WHERE id = '$order_item_id'");
	}else{
		// var_dump('elllsseeeeeeeeeee');
		// sa($order_item);
	}
}

//sa($order_items);



?>