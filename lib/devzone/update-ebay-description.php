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
	echo '<hr><b>'.$key.'</b><br> <a href="http://www.ebay.de/itm/'.$val['item_id'].'" target="_blank">'.$val['title'].'</a><br>';
	if($key < 4 || $key > 180 || $val['extra_field'] === 'DescPicsed') continue;
	$url = preg_replace('/\?.+/', '', $val['steam_link']);
	$steam_de = arrayDB("SELECT * from steam_de where link = '$url'");
	if ($steam_de) {
		$steam_de = $steam_de[0];
	}else{
		sa('continue');
		continue;
	}
	$game_id = preg_replace('/.*\/(\d+)\/.*/', '\1', $url);

	$img_generator_res = [];
    $img_generator_res['img1_src'] = 'http://hot-body.net/img-generator/folders/v'.$game_id.'/1.jpg';
    $img_generator_res['img2_src'] = 'http://hot-body.net/img-generator/folders/v'.$game_id.'/2.jpg';
    $img_generator_res['img3_src'] = 'http://hot-body.net/img-generator/folders/v'.$game_id.'/3.jpg';
    $img_generator_res['img3d_src'] = 'http://hot-body.net/img-generator/folders/v'.$game_id.'/3d.png';
	// Описание товара
	$desc_str = file_get_contents(__DIR__.'/../adds/responsive.html');
	$about = add_dlc_addon_to_desc($steam_de);

	if (strpos($steam_de['lang'], 'Deutsch') !== false) $de = ', DE';
	else $de = '';

	$search = [
		'{{TITLE}}',	'{{DE}}',	'{{ABOUT}}',
		'{{IMG1}}',	'{{IMG2}}',	'{{IMG3}}',
		'{{IMG3D}}',
		];
	$replace = [
		$val['title_clean'],	$de,    $about,
		$img_generator_res['img1_src'], $img_generator_res['img2_src'], $img_generator_res['img3_src'],
		$img_generator_res['img3d_src'],
		];
	$desc = str_replace($search, $replace, $desc_str);


	$ebayObj = new Ebay_shopping2();
	$res = $ebayObj->updateItemDescription($val['item_id'], $desc);
	unset($res['Fees']);
	sa($res);
	$gid = $val['gid'];
	if(isset($res['Ack']) && $res['Ack'] === 'Success'){
		arrayDB("UPDATE games SET extra_field='DescPicsed' WHERE id = '$gid'");
	}
}




?>