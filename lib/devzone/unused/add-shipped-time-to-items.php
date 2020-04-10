<?php

$res = arrayDB("SELECT id,ShippedTime from ebay_orders");

foreach ($res as $k => $val) {
	$gig_order_id = $val['id'];
	$ShippedTime = $val['ShippedTime'];
	arrayDB("UPDATE ebay_order_items SET shipped_time = '$ShippedTime' WHERE gig_order_id = '$gig_order_id'");
}

?>