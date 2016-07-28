<?php
if (isset($_POST['wooId']) && isset($_POST['action']) && $_POST['action'] == 'check') {

	$Woo = new WooCommerceApi();
	$woo_item = $Woo->checkProductById($_POST['wooId']);
	if($woo_item){
		$woo_title = $woo_item['product']['title'];
		$woo_price = $woo_item['product']['regular_price'];
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