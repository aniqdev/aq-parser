<?php
header('Access-Control-Allow-Origin: http://parser.gig-games.de');

if (isset($_POST['wooId']) && isset($_POST['action']) && $_POST['action'] == 'check') {

	// $Woo = new WooCommerceApi();
	// $woo_item = $Woo->checkProductById($_POST['wooId']);
	// get('products/'.(int)$item_id);

	$woo_item = post_curl('https://hot-body.net/parser/ajax-controller.php', [
		'function' => 'ajax_hot_do_woocommerce_api_request',
		'method' => 'get',
		'endpoint' => 'products/'.(int)$_POST['wooId'],
	]);
	if($woo_item && isset($woo_item['res'])){
		$woo_title = $woo_item['res']['name'];
		$woo_price = $woo_item['res']['regular_price'];
		$answer = 'good';
		$game_id = _esc($_POST['gameId']);
		$woo_id = _esc($_POST['wooId']);
		if(isset($_POST['update'])) arrayDB("UPDATE games SET woo_id=$woo_id WHERE id=$game_id");
	}else{
		$woo_title = 'No such Product exists!';
		$answer = 'bad';
	}

	$send = array(
			'answer' => $answer,
			'post' => $_POST,
			'woo_title' => $woo_title,
			'price' => $woo_price,
		);

	echo json_encode($send);
}


if (isset($_POST['wooId']) && isset($_POST['action']) && $_POST['action'] == 'change') {

	$Woo = new WooCommerceApi();
	$woo_item = $Woo->updateProductPrice((int)$_POST['wooId'], (float)$_POST['price']);
	
	if($woo_item){
		$woo_title = $woo_item['product']['title'];
		$answer = 'good';
	}else{
		$woo_title = 'No such Product exists!';
		$answer = 'bad';
	}
	$send = array(
			'answer' => $answer,
			'post' => $_POST,
			'woo_title' => $woo_title
		);

	echo json_encode($send);
}


if (isset($_POST['wooId']) && isset($_POST['action']) && $_POST['action'] == 'remove') {


	$Woo = new WooCommerceApi();
	$woo_item = $Woo->removeFromSale((int)$_POST['wooId']);
	if($woo_item){
		$woo_title = $woo_item['product']['title'];
		$answer = 'good';
	}else{
		$woo_title = 'No such Product exists!';
		$answer = 'bad';
	}
	$send = array(
			'answer' => $answer,
			'post' => $_POST,
			'woo_title' => $woo_title
		);

	echo json_encode($send);

}


if (isset($_POST['plati_id']) && isset($_POST['action']) && $_POST['action'] === 'ban') {
	$plati_id = (int)$_POST['plati_id'];
	if(!$plati_id) die;
	$exist = arrayDB("SELECT id FROM blacklist WHERE item_id=$plati_id AND category='item' LIMIT 1");
	if(!$exist) arrayDB("INSERT INTO blacklist VALUES (NULL, '$plati_id', 'item')");
}


if (isset($_POST['plati_id']) && isset($_POST['game_id']) && isset($_POST['action']) && $_POST['action'] === 'banaddon') {
	$plati_id = (int)$_POST['plati_id'];
	$game_id = (int)$_POST['game_id'];
	if(isset($_POST['table'])) $table = $_POST['table'];
	else $table = 'blacklist';
	$category = 'game_id='.$game_id.'&game_name='._esc(urlencode($_POST['game_name']));
	if(!$plati_id || !$game_id) die;
	$exist = arrayDB("SELECT id FROM $table WHERE item_id=$plati_id AND category LIKE 'game_id=$game_id%' LIMIT 1");
	if(!$exist) arrayDB("INSERT INTO $table VALUES (NULL, '$plati_id', '$category')");
}


if (isset($_POST['exrate']) && isset($_POST['action']) && $_POST['action'] === 'change_exrate') {
	$exrate = arrayDB("SELECT value FROM aq_settings WHERE name='exrate'");
	$newval = $_POST['exrate'];
	if (isset($exrate[0]['value'])) {
		arrayDB("UPDATE aq_settings SET value = '$newval' WHERE name = 'exrate'");
	}else{
		arrayDB("INSERT INTO aq_settings VALUES(null,'exrate','$newval','exrate')");
	}
	print_r($_ERRORS);
}