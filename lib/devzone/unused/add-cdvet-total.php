<?php



function act_add_item(&$item){

	$title = html_entity_decode('cdVet® '.str_replace($item[8].$item[7], '', $item[4]).' '.$item[8].$item[7].''.)

	$desc_params = [
		'desc_title' => '',
		'chosen_desc_pics' => [],
		'desc_top' => '',
		'desc_bot' => '',
	}

	$item = [
	    'Title' => $title,
	    'Quantity' => 1,
	    'ConditionID' => 1000,
	    'Description' => Cdvet::prepare_description($desc_params),
	    'price' => $_POST['item']['price'],
	    'PictureURL' => $main_pics,
	    'BestOfferEnabled' => 'false',
	    'SalesTaxPercent' => 0,
	    'ListingDuration' => 'GTC',
	    'specific' => $_POST['item']['specifics'],
	    'CategoryID' => $_POST['item']['chosen_cat'][0]['eBayKategorie'],
	    'StoreCategory1' => $_POST['item']['chosen_cat'][0]['eBayShopKAtegorieID'],
	    'StoreCategory2' => @$_POST['item']['chosen_cat'][1]['eBayShopKAtegorieID'],
	    'VATPercent' => $_POST['item']['tax_percent'],
	    'SKU' => $_POST['item']['shop_id'],
	];
	// sa($item);
	$res = Cdvet::addItem($item);
	unset($res['Fees']);
	unset($item['Description']);
}



$cdvet_feed = csvToArr('http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129', ['max_str' => 0,'encoding' => 'windows-1250']);


foreach ($cdvet_feed as $item) {
	break;
	act_add_item(&$item);
}