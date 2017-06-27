<?php
ini_get('safe_mode') or set_time_limit(2500); // Указываем скрипту, чтобы не обрывал связь.
error_reporting(E_ALL);

function url_exist($url='')
{
	if (!@fopen($url,'r')) return false;
	return true;
}

function updateOneDesc($ebay_id, $key, $title)
{
	$desc_obj = new CreateDesc2017($ebay_id);

	if (!$desc_obj->getSteamLink())	return "<h3>$ebay_id no steam link!</h3>";

	if (!$desc_obj->readEbayData())	return "<h3>$ebay_id no readEbayData!</h3>";

	if (!$desc_obj->readSteamDe())  return "<h3>$ebay_id no readSteamDe!</h3>";	

	// $desc_obj->goDeutchToAll();

	if (!$desc_obj->readSteamEn())  return "<h3>$ebay_id no readSteamEn!</h3>";
	if (!$desc_obj->readSteamFr())	return "<h3>$ebay_id no readSteamFr!</h3>";
	if (!$desc_obj->readSteamEs())	return "<h3>$ebay_id no readSteamEs!</h3>";
	if (!$desc_obj->readSteamIt())	return "<h3>$ebay_id no readSteamIt!</h3>";

	// $desc_obj->setImagesArr(['http://hot-body.net/img-generator/folders/s21130/1.jpg',
	// 						'http://hot-body.net/img-generator/folders/s21130/2.jpg',
	// 						'http://hot-body.net/img-generator/folders/s21130/3.jpg',]);
	if (!$desc_obj->getDataArray())	return "<h3>$ebay_id no getDataArray!</h3>";

	// if($desc_obj->scip()) return;
	// if($desc_obj->scip()) return "<h3>skipped!</h3>";

	//echo $desc_obj->getNewFullDesc();
	if(!$desc = $desc_obj->getNewFullDesc()) return "<h3>Fuck!</h3>";
	
	// echo $desc;
	echo "<BR>";
// return;
	echo '<b>'.$key.'</b><br> <a href="http://www.ebay.de/itm/'.$ebay_id.'" target="_blank">'.$title.'</a><hr>';
	$ebayObj = new Ebay_shopping2();
	$res = $ebayObj->updateItemDescription($ebay_id, $desc);
	unset($res['Fees']);
	sa($res);
	if ($res['Ack'] === 'Success') {
		arrayDB("UPDATE games SET extra_field = 'desc2017may' WHERE ebay_id = '$ebay_id'");
	}else{
		return "<h3>not Ack = Success!</h3>";;
	}
}

$ebay_id = '112187660970';
// echo updateOneDesc($ebay_id, 1, 'silent');

// $games = arrayDB("SELECT item_id,title,steam_link,extra_field
// from games
// join ebay_games
// on ebay_games.item_id = games.ebay_id
// where steam_link <> ''");

$games = arrayDB("SELECT item_id,title from ebay_games order by title limit 55");


foreach ($games as $key => $game) {
break;
	if($key < 0) continue;
	if($key > 1000) break;

	$ebay_id = $game['item_id'];
	$title = $game['title'];

	echo updateOneDesc($ebay_id, $key, $title);
}




?>