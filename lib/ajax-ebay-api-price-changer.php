<?php
ini_get('safe_mode') or set_time_limit(180); // Указываем скрипту, чтобы не обрывал связь.

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

}elseif (isset($_POST['update_ebay_games'])) {

	$ebayObj = new Ebay_shopping2();
	$res = $ebayObj->GetSellerItemsArray();

	$words_to_del = array(
		'(PC)',' PC ','-Region free-','Region free','Multilanguage','steam',
		'Multilang','Regfree','ENGLISH','-','–','&','Uplay','Game Of The Year Edition',
		'DLC','regfr','Add On','Addon',' goty','Regionfree',':',"’s","'s","'","’",'Uplay');

	$sql = 'TRUNCATE ebay_games;';
	foreach ($res as $id => $title) {
	    $title_clean = str_ireplace( $words_to_del, ' ', $title);
	    $title_clean = trim(preg_replace('/\s+/', ' ', $title_clean));
		$sql .= "INSERT INTO ebay_games (item_id,title,title_clean) 
			VALUES ('$id','"._esc($title)."','"._esc($title_clean)."');";
	}
	arrayDB($sql, true);
	echo json_encode(['answer'=>'good','errors'=>$_ERRORS]);

}elseif (isset($_POST['insert_ebayID_to_games'])) {

	$game_id = _esc($_POST['game_id']);
	$ebay_id = _esc($_POST['ebay_id']);
	arrayDB("UPDATE games SET ebay_id='$ebay_id' WHERE id='$game_id'");
	echo '{"answer":"good"}';

}elseif (isset($_POST['ebayId']) && isset($_POST['action']) && $_POST['action'] == 'change_quantity3') {

	$Ebay = new Ebay_shopping2();
	$response = $Ebay->updateQuantity($_POST['ebayId'], 3);
	if($response && $response['Ack'] === 'Success'){
		$answer = 'good';
	}else{
		$answer = 'bad';
	}
	$send = array(
			'answer' => $answer,
			'response' => $response,
			'post' => $_POST,
		);

	echo json_encode($send);

}

else echo '{"answer":"bad"}';