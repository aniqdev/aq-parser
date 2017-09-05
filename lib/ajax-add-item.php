<?php
function ajax_add_item()
{

	if(!isset($_REQUEST['sid']) || $_REQUEST['sid'] < 1) return(['success' => 0]);

	if(!isset($_REQUEST['price']) || (float)$_REQUEST['price'] < 1.5) return(['success' => 0]);

	$ret = [];

	$sid = (int)$_REQUEST['sid'];

	$steam_de = arrayDB("SELECT steam_de.*,steam.usk_links as pegi_links,steam.usk_age as pegi_age 
						FROM steam_de
						LEFT JOIN steam
						ON steam_de.link = steam.link
						WHERE steam_de.id = '$sid' LIMIT 1");

	if ($steam_de) {
		$steam_de = $steam_de[0];
	}else{
		return ['success' => 0];
	}

	$item = [
	    'Title' => 'Название',
	    'CategoryID' => '139973',
	    'Quantity' => 3,
	    'ConditionID' => 1000,
	    'Currency' => 'EUR', 
	    'Description' => 'Дескрипшн',
	    'price' => '9.99',
	    'PictureURL' => array
	        (
	            '0' => 'http://hot-body.net/img-generator/folders/s340220/ramka.jpg'
	        ),
	    'BestOfferEnabled' => 'true',
	    'SalesTaxPercent' => 0,
	    'ListingDuration' => 'GTC',
	    'specific' => array
	        (
	            'Marke' => 'lego',
	            'Herstellernummer' => 'n',
	            'Plattform' => 'Multi-Plattform',
	            'Regionalcode' => 'Regionalcode-frei',
	            'Genre' => 'Arcade',
	            'USK-Einstufung' => 'USK ab 6',
	            'Erscheinungsjahr' => '2010',
	            'Besonderheiten' => 'Multiplayer',
	            'Herausgeber' => 'Microsoft',
	            'Herstellergarantie' => 'Keine',
	            'Herstellungsland und -region' => '',
	        ),
	    'StoreCategory1' => '10866044010',
	];

	$eBay_obj = new Ebay_shopping2();

	$steam_link = $steam_de['link'];
	$img_generator_res = file_get_contents('http://hot-body.net/img-generator/?url2017='.$steam_link);
	$img_generator_res = json_decode($img_generator_res,1);
	if ($img_generator_res['msg']) {
		return ['success' => 0, 'resp' => false];
	}

	// Ниазвание товара
	$item['Title'] = isset($_REQUEST['title']) ? substr($_REQUEST['title'], 0, 80) : add_words_to_game_name($steam_de['title']);

	// Цена товара
	$item['price'] = (float)$_REQUEST['price'];

	// Картинки
	$item['PictureURL'] = [
		$img_generator_res['image_link'],
		$img_generator_res['img1_src'],
		$img_generator_res['img2_src'],
		$img_generator_res['img3_src'],
		$img_generator_res['img4_src'],
	];

	// Описание товара
	$desc_obj = new CreateDesc2017(0);

	if (!$desc_obj->getSteamLinkBySteamId($sid))	return ['success' => 0, 'resp' => 'no steam link',
		'text' => $desc_obj->error_text, 'sl' => $desc_obj->_steam_link];

	$desc_obj->setImagesArr([
			$img_generator_res['img1_src'],
			$img_generator_res['img2_src'],
			$img_generator_res['img3_src'],
		]);

	if (!$desc_obj->readSteamDe())  return ['success' => 0, 'resp' => 'no readSteamDe'];
	if (!$desc_obj->readSteamEn())  return ['success' => 0, 'resp' => 'no readSteamEn'];
	if (!$desc_obj->readSteamFr())	return ['success' => 0, 'resp' => 'no readSteamFr'];
	if (!$desc_obj->readSteamEs())	return ['success' => 0, 'resp' => 'no readSteamEs'];
	if (!$desc_obj->readSteamIt())	return ['success' => 0, 'resp' => 'no readSteamIt'];

	if (!$desc_obj->getDataArray())	return ['success' => 0, 'resp' => 'no getDataArray!'];

	if(!$desc = $desc_obj->getNewFullDesc()) return ['success' => 0, 'resp' => 'no getNewFullDesc!'];
	$item['Description'] = $desc;

	// Спецификации
	$item['specific'] = build_item_specifics_array($steam_de);

	$res = $eBay_obj->addItem($item);

	if (isset($res['Ack']) && $res['Ack'] !== 'Failure' && isset($res['ItemID'])) {
		$id = $steam_de['id'];

		$name = _esc($steam_de['title'] . ' steam');
		$ebay_id = _esc($res['ItemID']);
		$steam_link = _esc($steam_de['link']);
		$plati_id = _esc((int)$_POST['plati_id']);
		arrayDB("INSERT INTO games (name, ebay_id, steam_link, extra_field) 
			VALUES('$name', '$ebay_id', '$steam_link', '$id')");
		arrayDB("UPDATE steam_de SET is_on_ebay = 'yes', ebay_id = '$ebay_id' WHERE id = '$id'");
		arrayDB("INSERT INTO gig_trustee_items (plati_id) VALUES('$plati_id')");

		$success = 1;
	}else{
		$success = 0;
	}
	unset($res['Fees']);
	unset($item['Description']);
	global $_ERRORS;
	return ['success' => $success,
			'item' => $item,
			'resp' => $res,
			'errors' => $_ERRORS];
}


if (isset($_GET['sid']) && isset($_GET['price'])) {
	sa(ajax_add_item());
}elseif (isset($_POST['sid']) && isset($_POST['price'])) {
	echo json_encode(ajax_add_item());
}

//sleep(2);

?>