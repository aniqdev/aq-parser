<?php

if (isset($_REQUEST['operation']) && $_REQUEST['operation'] === 'info') {

	$ids = explode('-', $_REQUEST['ids']);
	if(!(int)$ids[0] || !(int)$ids[0]) die;
	$order = new GigOrder([ 'gig_order_id' => $ids[0],
							'gig_order_item_id' => $ids[1],
							]);
	$order->setCurrentPrice();

	if($_SERVER['REQUEST_METHOD'] === 'POST') echo $order;
	if($_SERVER['REQUEST_METHOD'] === 'GET') sa($order);

}

if (isset($_POST['operation']) && $_POST['operation'] === 'buy') {

	$ids = explode('-', $_REQUEST['ids']);
	if(!(int)$ids[0] || !(int)$ids[0]) die;
	$order = new GigOrder([ 'gig_order_id' => $ids[0],
							'gig_order_item_id' => $ids[1],
							]);

	$order->buy($_POST['plati_id']);

	echo $order;
}

if (isset($_POST['operation']) && $_POST['operation'] === 'send') {

	$ids = explode('-', $_REQUEST['ids']);
	if(!(int)$ids[0] || !(int)$ids[0]) die;
	$order = new GigOrder([ 'gig_order_id' => $ids[0],
							'gig_order_item_id' => $ids[1],
							]);

	//$order->send($_POST['?']);

	echo $order;
}

if (isset($_POST['operation']) && $_POST['operation'] === 'curr_price') {

	$ids = explode('-', $_REQUEST['ids']);
	if(!(int)$ids[0] || !(int)$ids[0]) die;
	$order = new GigOrder([ 'gig_order_id' => $ids[0],
							'gig_order_item_id' => $ids[1],
							]);

	echo json_encode(['curr_price' => $order->current_price_only()]);
}








?>