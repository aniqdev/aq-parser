<?php
ini_get('safe_mode') or set_time_limit(1200); // Указываем скрипту, чтобы не обрывал связь.






$Woo = new WooCommerceApi();

// $woo_item = $Woo->updateProductPrice($wooId, $price);

// $woo_item = $Woo->removeFromSale($wooId);



$games = arrayDB("SELECT * FROM games 
	join (select * from items where scan = (select scan from items order by id desc limit 1)) its
	ON games.id = its.game_id
	WHERE woo_id <> ''");


foreach ($games as $key => $game) {
	if($key < 10 && $key > 500) continue;
	if ($game['item1_price']) {
		$price = formula($game['item1_price'], 67);
		$woo_item = $Woo->updateProductPrice($game['woo_id'], $price);
		if($woo_item) echo $game['name'].' | '.$price.'<br>U true<hr>';
		else  echo $game['name'].'<br>U false<hr>';
	}else{
		$woo_item = $Woo->removeFromSale($game['woo_id']);
		if($woo_item) echo $game['name'].'<br>R true<hr>';
		else  echo $game['name'].'<br>R false<hr>';
	}
}



// sa($games);


?>