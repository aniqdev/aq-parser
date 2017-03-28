<?php
ini_get('safe_mode') or set_time_limit(2500); // Указываем скрипту, чтобы не обрывал связь.

function url_exist($url='')
{
	if (!@fopen('http://site.ru/images/image.png','r')) return false;
	return true;
}

	// $url = 'http://store.steampowered.com/app/6270/';
	// $url = preg_replace('/\?.+/', '', $url);
	// $game_id = preg_replace('/.*\/(\d+)\/.*/', '\1', $url);
	// $header_path = 'http://cdn.akamai.steamstatic.com/steam/apps/'.$game_id.'/header.jpg';
	// $imgtype = getimagesize($header_path);
	// sa($imgtype);

	// var_dump(!!@file_get_contents('http://cdn.akamai.steamstatic.com/steam/apps/6270/header.jpg'));


$items = arrayDB("SELECT ebay_games.*, games.id as gid, games.steam_link, games.extra_field from ebay_games
				join games
				on ebay_games.item_id = games.ebay_id
				where picture_hash = '' AND steam_link <> ''");


foreach ($items as $key => $val) {
	if($key < 0 || $key > 180 || $val['extra_field'] === 'updated' || $val['extra_field'] === 'DescPicsed') continue;
	do_update($val['item_id'], $val['steam_link'], $val['title'], $val['gid']);
}

function do_update($item_id, $steam_link, $title, $gid)
{
	// $item_id = '122352311179';
	// $steam_link = 'http://store.steampowered.com/app/92000/';


	$img_generator_res = file_get_contents('http://hot-body.net/img-generator/?url2017='.$steam_link);
	sa($images = json_decode($img_generator_res,true));
	if(!$images['msg'])$pics = [$images['image_link']];
	else{
		echo "<h3>Failure!</h3>";
		echo '<br><a href="http://www.ebay.de/itm/'.$item_id.'" target="_blank">'.$title.'</a><hr>';
		return;
	}

	$res = _get_steam_images($steam_link);
	sa($res);
	$pics = $pics + $res;

	//sa($pics);

	$ebayObj = new Ebay_shopping2();
	$res = $ebayObj->updateItemPictureDetails($item_id, $pics);
	unset($res['Fees']);
	sa($res);
	if(isset($res['Ack']) && $res['Ack'] === 'Success'){
		arrayDB("UPDATE games SET extra_field='updated' WHERE id = '$gid'");
	}
	echo '<br><a href="http://www.ebay.de/itm/'.$item_id.'" target="_blank">'.$title.'</a><hr>';
}

//do_update('112306447002', $url, 'Asd');

?>