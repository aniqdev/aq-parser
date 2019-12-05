<?php




$orders = arrayDB( "SELECT *
					FROM woo_orders 
					LEFT JOIN woo_order_items
					ON woo_orders.id = woo_order_items.gig_order_id 
					-- WHERE status = 'processing'
					LIMIT 500");

sa(count($orders));


foreach ($orders as $key => $order) {
	unset($order['goods_json']);
	sa($order);
}



return
$smarty = new Smarty();

if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
    $pro = 'https';
} else {
    $pro = 'http';
}
$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
$current_url =  $pro."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];

$smarty->assign("current_url",$current_url);
$smarty->display('test.tpl');

return
// return
// $item_id = '253453544903';

// $specs = parse_item_specifics($item_id);

// sa($specs);

// $specs['Kurzbeschreibung'] = explode(',', $specs['Kurzbeschreibung']);
// $specs['Zusammensetzung'] = explode(',', $specs['Zusammensetzung']);
// $specs['Analytische Bestandteile und Gehalte'] = explode(',', $specs['Analytische Bestandteile und Gehalte']);
// // unset($specs['Artikelzustand']);

// sa($specs);

// return
// $resp = Cdvet::updateItemSpecifics($item_id, $specs);

// sa($resp);


// return
$res = Cdvet::updateItemSubtitle('21021187702', 'CdVet');

sa($res);



return;
$res = Ebay_shopping2::getSingleItem('121946647051');


sa(json_decode($res, 1));

