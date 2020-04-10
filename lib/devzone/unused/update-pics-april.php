<style>
	img{
	    height: 80px;
	    width: 80px;
	    border: 1px solid #000;
	}
	hr {
	    margin-top: 10px;
	    margin-bottom: 10px;
	}
</style>
<?php

ini_get('safe_mode') or set_time_limit(4000); // Указываем скрипту, чтобы не обрывал связь.

$games = arrayDB("SELECT * from games
where steam_link<>'' and ebay_id<>'' and extra_field = 'ready'");

// $item_id = '112094640949';

// $res = _get_url_of_real_img($item_id);

// sa($res);

function regenerate($steam_link)
{
	return file_get_contents('http://hot-body.net/img-generator/?ramka_only2017='.$steam_link);
}

$ebayObj = new Ebay_shopping2();

foreach ($games as $key => $game) {
	if($key < 0 || $key > 2280) continue;

	$url = trim($game['steam_link']);
	if ($game['extra_field'] !== 'regenerated') {
		$gener_res = regenerate($url);
		$gener_res = json_decode($gener_res,1);
		//sa($gener_res);
		if(isset($gener_res['$header_path']) && !$gener_res['msg']){
			usleep(rand(1000,1500000));
			$gid = $game['id'];
			arrayDB("UPDATE games SET extra_field='regenerated' WHERE id = '$gid'");
		}
	}

	$url = preg_replace('/\?.+/', '', $url);
	$game_id = preg_replace('/.*\/(\d+)\/.*/', '\1', $url);
	$header_path = 'http://cdn.akamai.steamstatic.com/steam/apps/'.$game_id.'/header.jpg';

	echo '<img src="http://hot-body.net/img-generator/folders/v'.$game_id.'/ramka.jpg?time=four">';
	// echo '<img src="http://hot-body.net/img-generator/folders/v'.$game_id.'/1.jpg">';
	// echo '<img src="http://hot-body.net/img-generator/folders/v'.$game_id.'/2.jpg">';
	// echo '<img src="http://hot-body.net/img-generator/folders/v'.$game_id.'/3.jpg">';
	// echo '<img src="http://hot-body.net/img-generator/folders/v'.$game_id.'/4.jpg">';
	// echo '<img src="http://hot-body.net/img-generator/folders/v'.$game_id.'/3d.png">';

	$srcs = [
		'http://hot-body.net/img-generator/folders/v'.$game_id.'/ramka.jpg?numero=dos',
		'http://hot-body.net/img-generator/folders/v'.$game_id.'/1.jpg',
		'http://hot-body.net/img-generator/folders/v'.$game_id.'/2.jpg',
		'http://hot-body.net/img-generator/folders/v'.$game_id.'/3.jpg',
		'http://hot-body.net/img-generator/folders/v'.$game_id.'/4.jpg',		
	];

	//sa($srcs);

	//if(@$gener_res['msg']) continue;
	// $gid = $game['id'];
	// arrayDB("UPDATE games SET extra_field='regenerated' WHERE id = '$gid'");
	// $res = $ebayObj->updateItemPictureDetails($game['ebay_id'], $srcs);
	// unset($res['Fees']);
	// echo '<pre>'.print_r($res,1).'</pre><a href="http://www.ebay.de/itm/'.$game['ebay_id'].'" target="_blank">'.$game['name'].'</a><hr>';

	// $gid = $game['id'];
	// if(isset($res['Ack']) && $res['Ack'] === 'Success'){
	// 	arrayDB("UPDATE games SET extra_field='regenerated' WHERE id = '$gid'");
	// }
}











?>