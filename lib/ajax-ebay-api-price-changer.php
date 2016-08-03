<?php
if (isset($_POST['ebayId']) && isset($_POST['action']) && $_POST['action'] == 'check') {

	$Ebay = new Ebay_shopping2();
	$ibey_item = $Ebay->getSingleItem($_POST['ebayId']);
	$itemArr = json_decode($ibey_item, true);
	$game_id = _esc($_POST['gameId']);
	$ebay_id = _esc($_POST['ebayId']);
	if($itemArr['Ack'] === 'Success'){
		$ebay_title = $itemArr['Item']['Title'];
		$ebay_price = $itemArr['Item']['ConvertedCurrentPrice']['Value'];
		$answer = 'good';
		if(isset($_POST['update'])) arrayDB("UPDATE games SET ebay_id=$ebay_id WHERE id=$game_id");
	}else{
		$ebay_title = 'No such Product exists!';
		$answer = 'bad';
	}

	$game_line = arrayDB("SELECT * FROM items WHERE game_id=$game_id ORDER BY id DESC LIMIT 1")[0];

	$send = array(
			'answer' => $answer,
			'post' => $_POST,
			'ebay_title' => $ebay_title,
			'price' => $ebay_price,
			'game_line' => $game_line,
		);

	echo json_encode($send);
}


elseif (isset($_POST['ebayId']) && isset($_POST['action']) && $_POST['action'] == 'change') {

	$Ebay = new Ebay_shopping2();
	$response = $Ebay->updateProductPrice($_POST['ebayId'], (float)$_POST['price']);
	if($response){
		$answer = 'good';
	}else{
		$answer = 'bad';
	}
	$send = array(
			'answer' => $answer,
			'post' => $_POST,
			'$response' => $response,
		);

	echo json_encode($send);
}


elseif (isset($_POST['ebayId']) && isset($_POST['action']) && $_POST['action'] == 'remove') {


	$Ebay = new Ebay_shopping2();
	$response = $Ebay->removeFromSale($_POST['ebayId']);
	if($response){
		$answer = 'good';
	}else{
		$answer = 'bad';
	}
	$send = array(
			'answer' => $answer,
			'post' => $_POST,
		);

	echo json_encode($send);

}

else echo '{"answer":"bad"}';