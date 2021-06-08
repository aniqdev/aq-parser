<?php
function ajax_add_item()
{

	global $_ERRORS;
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
		return ['success' => 0, 'resp' => 'No steam info!'];
	}

	$steam_link = $steam_de['link'];

	$item_exists = arrayDB("SELECT id FROM games WHERE steam_link = '$steam_link' AND ebay_id <> ''");
	if ($item_exists) {
		return ['success' => 0, 'resp' => 'Item already exists!'];
	}

	$item = [
	    'Title' => 'Название',
	    'CategoryID' => '139973',
	    'Quantity' => 3,
	    'ConditionID' => 1000,
	    'Currency' => 'EUR', 
	    'Description' => 'Дескрипшн',
	    'price' => '9.99',
	    'PictureURL' => [],
	    'BestOfferEnabled' => 'true',
	    'SalesTaxPercent' => 0,
	    'ListingDuration' => 'GTC',
	    'specific' => [],
	    'StoreCategory1' => '10866044010',
	];

	$eBay_obj = new Ebay_shopping2();

	// Ниазвание товара
	$item['Title'] = isset($_REQUEST['title']) ? substr($_REQUEST['title'], 0, 80) : add_words_to_game_name($steam_de['title']);

	// Цена товара
	$item['price'] = (float)$_REQUEST['price'];

	// Картинки
	$app_id = $steam_de['appid'];
	$app_sub = $steam_de['type'];
	if($app_sub === 'dlc') $app_sub = 'app';
	$img_generator_url = 'http://hot-body.net/img-generator/?app_id='.$app_id.'&app_sub='.$app_sub.'&ramka_september_2017=true';
	$img_generator_res = file_get_contents($img_generator_url);
	$img_generator_res = json_decode($img_generator_res,1);
	if (!$img_generator_res['msg']) {
		return ['success' => 0, 'resp' => 'no images!', '$img_generator_url' => $img_generator_url];
	}

	// если это бандл то картинки берутся с первой игры
	if ($app_sub === 'sub' || $app_sub === 'bundle') {
		$includes_arr = explode(',', $steam_de['includes']);
		if($includes_arr){
			$app_id = $includes_arr[0];
			$app_sub = 'app';
		}
	}
	// steam-images checker
	$checker = file_get_contents('http://parser.gig-games.de/steam-images-checker.php?app_id='.$app_id.'&app_sub='.$app_sub);
	$chr = json_decode($checker, true);

	$item['PictureURL'][] = $img_generator_res['image_link'];
	if (in_array('big1.jpg', $chr))	$item['PictureURL'][] = 'http://parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/big1.jpg';
	if (in_array('big2.jpg', $chr))	$item['PictureURL'][] = 'http://parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/big2.jpg';
	if (in_array('big3.jpg', $chr))	$item['PictureURL'][] = 'http://parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/big3.jpg';
	if (in_array('big4.jpg', $chr))	$item['PictureURL'][] = 'http://parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/big4.jpg';

	// Описание товара
	$desc_obj = new CreateDesc2017(0);

	if (!$desc_obj->getSteamLinkBySteamId($sid))	return ['success' => 0, 'resp' => 'no steam link',
		'text' => $desc_obj->error_text, 'sl' => $desc_obj->_steam_link];

	$desc_obj->setImagesArr([
			in_array('small1.jpg',$chr)?'//parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/small1.jpg':'//parser.gig-games.de/images/no-image-available.png',
			in_array('small2.jpg',$chr)?'//parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/small2.jpg':'//parser.gig-games.de/images/no-image-available.png',
			in_array('small3.jpg',$chr)?'//parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/small3.jpg':'//parser.gig-games.de/images/no-image-available.png',
		]);

	$deuched = false;
	if (!$desc_obj->readSteamDe())  return ['success' => 0, 'resp' => 'no readSteamDe'];
	if (!$desc_obj->readSteamEn())  $deuched = $desc_obj->goDeutchToEn();
	if (!$desc_obj->readSteamFr())	$deuched = $desc_obj->goDeutchToFr();
	if (!$desc_obj->readSteamEs())	$deuched = $desc_obj->goDeutchToEs();
	if (!$desc_obj->readSteamIt())	$deuched = $desc_obj->goDeutchToIt();

	if (!$desc_obj->getDataArray())	return ['success' => 0, 'resp' => 'no getDataArray!'];

	if(!$desc = $desc_obj->getNewFullDesc()) return ['success' => 0, 'resp' => 'no getNewFullDesc!'];
	$item['Description'] = $desc;

	// Спецификации
	$item['specific'] = build_item_specifics_array($steam_de);

	$res = $eBay_obj->addItem($item);

	// =========== WooCommerce ==================
	$woo_data = [
		'name' => $steam_de['title'],
		'type' => 'simple',
		'regular_price' => $item['price'],
		'description' => $steam_de['desc'],
		'short_description' => $steam_de['specs'],
		'categories' => [['id'=>82]],
		// 'images' => [
		// 	['src' => $img_src]
		// ],
		// 'stock_status' => 'outofstock',
	];

	set_img_path($woo_data, $steam_de);

	$woo_ret = post_curl('https://hot-body.net/parser/ajax-controller.php', [
		'function' => 'ajax_hot_do_woocommerce_api_request',
		'method' => 'post',
		'endpoint' => 'products',
		'data' => $woo_data,
	]);
	// =========== /WooCommerce ==================

	$game_check = arrayDB("SELECT id FROM games WHERE steam_link = '$steam_link' AND ebay_id = ''");
	if (isset($res['Ack']) && $res['Ack'] !== 'Failure' && isset($res['ItemID'])) {
		$id = $steam_de['id'];

		$name = _esc($steam_de['title'] . ' steam');
		$ebay_id = _esc($res['ItemID']);
		$steam_link = _esc($steam_de['link']);
		$plati_id = _esc((int)$_POST['plati_id']);
		if ($game_check) {
			if (isset($woo_ret['res']['id'])) {
				$woo_id = _esc($woo_ret['res']['id']);
				arrayDB("UPDATE games SET ebay_id = '$ebay_id', woo_id = '$woo_id' WHERE steam_link = '$steam_link'");
			}else{
				arrayDB("UPDATE games SET ebay_id = '$ebay_id' WHERE steam_link = '$steam_link'");
			}
		}else{
			if (isset($woo_ret['res']['id'])) {
				$woo_id = _esc($woo_ret['res']['id']);
				arrayDB("INSERT INTO games SET name = '$name', steam_link = '$steam_link', ebay_id = '$ebay_id', woo_id = '$woo_id'");
			}else{
				arrayDB("INSERT INTO games SET name = '$name', steam_link = '$steam_link', ebay_id = '$ebay_id'");
			}
		}
		
		$price = $item['price'];
		arrayDB("UPDATE steam_de 
					SET is_on_ebay = 'yes', instock = 'yes', ebay_price = '$price', ebay_id = '$ebay_id' 
				 		WHERE id = '$id';
				 UPDATE steam_en 
				 	SET is_on_ebay = 'yes', instock = 'yes', ebay_price = '$price', ebay_id = '$ebay_id' 
				 		WHERE id = '$id';
				 UPDATE steam_fr 
				 	SET is_on_ebay = 'yes', instock = 'yes', ebay_price = '$price', ebay_id = '$ebay_id' 
				 		WHERE id = '$id';
				 UPDATE steam_es 
				 	SET is_on_ebay = 'yes', instock = 'yes', ebay_price = '$price', ebay_id = '$ebay_id' 
				 		WHERE id = '$id';
				 UPDATE steam_it 
				 	SET is_on_ebay = 'yes', instock = 'yes', ebay_price = '$price', ebay_id = '$ebay_id' 
				 		WHERE id = '$id';", true);
		
		$name_t = _esc($steam_de['title']); 
		arrayDB("INSERT INTO gig_trustee_items (plati_id,`name`) VALUES('$plati_id','$name_t')");

		$success = 1;
		$resp = &$res;
	}elseif(isset($res['Ack']) && $res['Ack'] === 'Failure') {
		$success = 0;
		$resp = sa($res['Errors'], true);
	}else{
		$success = 0;
		$resp = 'Fail!';
	}

	if (!$success && isset($woo_ret['res']['id'])) {
		$woo_id = _esc($woo_ret['res']['id']);
		if ($game_check) {
			arrayDB("UPDATE games SET woo_id = '$woo_id' WHERE steam_link = '$steam_link'");
		}else{
			$name = _esc($steam_de['title'] . ' steam');
			$steam_link = _esc($steam_de['link']);
			arrayDB("INSERT INTO games SET name = '$name', steam_link = '$steam_link', woo_id = '$woo_id'");
		}
	}

	unset($res['Fees']);
	unset($item['Description']);
	return ['success' => $success,
			'item' => $item,
			'deuched' => $deuched,
			'res' => $res,
			'resp' => $resp,
			'$chr' => $chr,
			'errors' => $_ERRORS];
}


if (isset($_GET['sid']) && isset($_GET['price'])) {
	sa(ajax_add_item());
}elseif (isset($_POST['sid']) && isset($_POST['price'])) {
	echo json_encode(ajax_add_item());
}

//sleep(2);


function set_img_path(&$data, &$game)
{
	if (file_exists(get_steam_images_dir_path($game['type'], $game['appid']).'/header-80p.jpg')) {
		$img_src = get_steam_images_dir_url($game['type'], $game['appid']).'/header-80p.jpg';
		$data['images'] = [['src' => $img_src]];
	}elseif (file_exists(get_steam_images_dir_path($game['type'], $game['appid']).'/header.jpg')) {
		$img_src = get_steam_images_dir_url($game['type'], $game['appid']).'/header-80p.jpg';
		$data['images'] = [['src' => $img_src]];
	}else{

	}
}

?>