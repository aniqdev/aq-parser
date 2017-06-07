<?php
ini_get('safe_mode') or set_time_limit(4000); // Указываем скрипту, чтобы не обрывал связь.

$games = arrayDB("SELECT * from games
where steam_link<>'' and ebay_id<>'' and extra_field='regenerated'");



$ebayObj = new Ebay_shopping2();

foreach ($games as $key => $game) {
	if($key < 0 || $key > 1000) continue;

	$url = trim($game['steam_link']);
	$url = preg_replace('/\?.+/', '', $url);
	$game_id = preg_replace('/.*\/(\d+)\/.*/', '\1', $url);
	$header_path = 'http://cdn.akamai.steamstatic.com/steam/apps/'.$game_id.'/header.jpg';

	//echo '<img src="http://hot-body.net/img-generator/folders/v'.$game_id.'/ramka.jpg?time=four">';
	// echo '<img src="http://hot-body.net/img-generator/folders/v'.$game_id.'/1.jpg">';
	// echo '<img src="http://hot-body.net/img-generator/folders/v'.$game_id.'/2.jpg">';
	// echo '<img src="http://hot-body.net/img-generator/folders/v'.$game_id.'/3.jpg">';
	// echo '<img src="http://hot-body.net/img-generator/folders/v'.$game_id.'/4.jpg">';
	// echo '<img src="http://hot-body.net/img-generator/folders/v'.$game_id.'/3d.png">';

	$otras_arr = file_get_contents('http://hot-body.net/img-generator/checker.php?app_id='.$game_id);
	sa($otras_arr);
	$otras_arr = json_decode($otras_arr,1);
	$otras_arr = array_flip($otras_arr);
	//sa($otras_arr);

	$srcs = [];
	if(isset($otras_arr['ramka.jpg'])) $srcs[] = 'http://hot-body.net/img-generator/folders/v'.$game_id.'/ramka.jpg?numero=tres';
	else continue;

	if(isset($otras_arr['1.jpg'])) $srcs[] = 'http://hot-body.net/img-generator/folders/v'.$game_id.'/1.jpg';
	if(isset($otras_arr['2.jpg'])) $srcs[] = 'http://hot-body.net/img-generator/folders/v'.$game_id.'/2.jpg';
	if(isset($otras_arr['3.jpg'])) $srcs[] = 'http://hot-body.net/img-generator/folders/v'.$game_id.'/3.jpg';
	if(isset($otras_arr['4.jpg'])) $srcs[] = 'http://hot-body.net/img-generator/folders/v'.$game_id.'/4.jpg';		


	sa($srcs);

	if(count($srcs) < 1) continue;
	$res = $ebayObj->updateItemPictureDetails($game['ebay_id'], $srcs);
	unset($res['Fees']);
	echo '<pre>'.print_r($res,1).'</pre>
		<a href="http://www.ebay.de/itm/'.$game['ebay_id'].'" target="_blank">'.$game['name'].'</a><hr>';

	$gid = $game['id'];
	if(isset($res['Ack']) && $res['Ack'] === 'Success'){
		arrayDB("UPDATE games SET extra_field='april updated' WHERE id = '$gid'");
	}
}











?>