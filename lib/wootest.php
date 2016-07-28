<?php





echo "<pre>";


$Woo = new WooCommerceApi();

// print_r($Woo->updateProductPrice(10262, 27.33));

// print_r($Woo->checkProductById(10262));

// print_r($Woo->removeFromSale(10262));




	$Ebay = new Ebay_shopping2();
	$ibey_item = $Ebay->getSingleItem('121736746804');
	print_r(json_decode($ibey_item, true)['Item']['ConvertedCurrentPrice']['Value']);



echo "</pre>";

