<?php
ini_get('safe_mode') or set_time_limit(1000); // Указываем скрипту, чтобы не обрывал связь.





$feed_new = csvToArr('http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129', ['max_str' => 0,'encoding' => 'windows-1250']);


foreach ($feed_new as $val) {
	$shop_id = $val[0];
	$short_desc = _esc($val[19]);
	arrayDB("UPDATE cdvet_feed 
		SET `short_desc` = '$short_desc'
		WHERE shop_id = '$shop_id'");
}




return;
	$ord_obj = new EbayOrders;

	$ord_arr = $ord_obj->getOrders(['NumberOfDays'=>1,'SortingOrder'=>'Ascending','PageNumber'=>'1']);


sa($ord_arr);


return;
$feed_new = csvToArr('http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129', ['max_str' => 0,'encoding' => 'windows-1250']);

// draw_table_with_sql_results($feed_new, $first_row_thead = true);

// $feed_new = array_column($feed_new, null, 0);

sa($feed_new);

return;
$img_url = 'https://www.cdvet.de/media/image/10/26/34/spruehkopf-groSS-fuer-flaschen_89028_1_1280x1280.png';

	@mkdir('cdvet-images/'.'asd');
$imagine = new Imagine\Gd\Imagine();

$image = $imagine->open($img_url);


$width = $image->getSize()->getWidth();
$height = $image->getSize()->getHeight();

$size  = new Imagine\Image\Box($width, $height);

$background = $imagine->create($size);

$point = new Imagine\Image\Point(0, 0);
$background->paste($image, $point);

$background->save('cdvet-images/'.'asd'.'/'.(0+1).'.jpg', ['jpeg_quality' => 100]);


return;
$o = ['NumberOfDays'=>3,'SortingOrder'=>'Ascending','PageNumber'=>'2'];


	$ord_obj = new EbayOrders;

	$ord_arr = $ord_obj->getOrders($o);
sa($ord_arr );



return;
$str = 'asd,qwe';
sa(explode(',', $str));


return;
$long_desc = '"<div>Geweihabwurfstangen verliert Rotwild einmal im Jahr auf natürliche Weise. Für diese Kaufreude muss kein Tier sterben. <span><strong>cdVet Geweihmineral-Snack</strong></span> enthält weder Farb- noch Konservierungsstoffe und ist antiallergen - eben 100% Natur.</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div>Das natürliche Hunde-Kauspielzeug für gesunde Zähne und Zahnfleisch. Für diesen Kauartikel musste kein Tier sterben! 100% Abwurfstangen.</div>
<p>&nbsp;</p>
<div>Einzelfuttermittel für Hunde</div>
<p>&nbsp;</p>
<div><strong><span style=""text-decoration: underline;"">Zusammensetzung:</span></strong> 100% reine Geweihabwurfstange</div>
<p>&nbsp;</p>
<div><strong><span style=""text-decoration: underline;"">Analytische Bestandteile und Gehalte:</span></strong> Rohasche 50,5%, Rohprotein, 35,21%, Feuchtigkeit 12,87%, Rohfaser 0,17%, Fettgehalt 0,15%; Mineralstoffe pro 100g: Calcium 19,5g, Phosphor 8g, Eisen 9,1mg, Magnesium 426mg, Natrium 0,5g, Kalium 0,04g</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div><strong><span style=""text-decoration: underline;"">Größenempfehlung:</span></strong></div>
<div>Gewicht des Hundes:</div>
<div>XS 25 – 50g &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hunde bis 6kg</div>
<div>S 50 – 80g &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hunde bis 12kg</div>
<div>M 80 – 120g &nbsp;&nbsp;&nbsp;&nbsp;Hunde bis 25kg</div>
<div>L 120 – 160g &nbsp;&nbsp;&nbsp;Hunde bis 35kg</div>
<div>XL 160 – 250g &nbsp;Hunde ab 35kg</div>
<p>&nbsp;</p>
<p>&nbsp;</p>"
';

		if(stripos($long_desc, 'Zusammensetzung') === false) return '';

		$pattern = '/.+Zusammensetzung(.+?)<\/div>.*/s';

		preg_match($pattern, $long_desc, $matches);
		sa($matches);

		$long_desc = preg_replace($pattern, '${1}', $long_desc);

		// $long_desc = strip_tags($long_desc);
		$long_desc = preg_replace('/<[^>].+?>/', '', $long_desc);

		// $strpos = strpos($long_desc, 'Anwendungsempfehlung');
		// if($strpos) $long_desc = substr($long_desc, 0, $strpos);

		// $strpos = strpos($long_desc, 'terungsempfehlung');
		// if($strpos) $long_desc = substr($long_desc, 0, $strpos-3);

		$long_desc = trim(str_replace(':', '', $long_desc));

		sa(htmlspecialchars($long_desc));


return;
$receive_item_link = 'https://shop.digiseller.ru/xml/purchase.asp?id_i=73208481&uid=A8649F57C4AE46E281E2FFF1BD90536B';

		$received_item = get_item_xml($receive_item_link);

sa($received_item);

return;
$link_dlc = 'http://store.steampowered.com/app/575300/Sniper_Ghost_Warrior_3__The_Sabotage/';

$link_sub = 'http://store.steampowered.com/sub/166591/';

$link_bundle = 'http://store.steampowered.com/bundle/2433/Strategy_Game_of_the_Year_Bundle/';

$link_app = 'http://store.steampowered.com/app/769340/Zomby_Soldier/';

$link_test = 'http://store.steampowered.com/app/391220/';

$steam_table = 'steam_de';

$steam_game = new SteamGame($link_bundle, $steam_table);

$steam_game
	->getDOM()
	->getTitle()
	->getDescription()
	->getType()
	->getAppId()
	->getPrices()
	->getReleaseDate()
	->getNotice()
	->getSpecs()
	->getLanguages()
	->getGenres()
	->getDeveloper()
	->getPublisher()
	->getOs()
	->getSysReq()
	->getRatingReviews()
	->getTags()
	->getUsk()
	->getIncludes()
	->savePictures()
	->save();


echo $steam_game;

var_dump($steam_game->isGameExists());





return;
	$strategie_top = get_top_by_genre('strategie', $limit = 50);
sa($strategie_top);

return;
$items_arr = Array
(
    0 => Array
        (
            'ItemID' => '253202702626',
            'Quantity' => 1,
        ),

    5 => Array
        (
            'ItemID' => '253288440184',
            'Quantity' => 0
        ),

    6 => Array
        (
            'ItemID' => '253288477629',
            'Quantity' => 0
        ),

    7 => Array
        (
            'ItemID' => '253288644764',
            'Quantity' => 0
        ),

    8 => Array
        (
            'ItemID' => '253288648512',
            'Quantity' => 0
        ),

);

sa($items_arr);


$resp = Cdvet::reviseInventoryStatus($items_arr);
unset($resp['Fees']);
sa($resp);



return;
$ebay_id = '112567993155';

function getDescription()
{
	$steam_link = 'http://store.steampowered.com/app/40500/';
//http://store.steampowered.com/app/205070/
	$app_id = '40500';
	$app_sub = 'app';

	// steam-images checker
	$checker_url = 'http://parser.gig-games.de/steam-images-checker.php?app_id='.$app_id.'&app_sub='.$app_sub;
	sa($checker_url);
	$checker = file_get_contents($checker_url);
	sa($checker);
	$chr = json_decode($checker, true);

	// Описание товара
	$desc_obj = new CreateDesc2017(0);

	$desc_obj->setSteamLink($steam_link);

	$desc_obj->setImagesArr([
			in_array('small1.jpg',$chr)?'//parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/small1.jpg':'//parser.gig-games.de/images/no-image-available.png',
			in_array('small2.jpg',$chr)?'//parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/small2.jpg':'//parser.gig-games.de/images/no-image-available.png',
			in_array('small3.jpg',$chr)?'//parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/small3.jpg':'//parser.gig-games.de/images/no-image-available.png',
		]);

	if (!$desc_obj->readSteamDe())  return ['success' => 0, 'resp' => 'no readSteamDe'];
	if (!$desc_obj->readSteamEn())  return ['success' => 0, 'resp' => 'no readSteamEn'];
	if (!$desc_obj->readSteamFr())	return ['success' => 0, 'resp' => 'no readSteamFr'];
	if (!$desc_obj->readSteamEs())	return ['success' => 0, 'resp' => 'no readSteamEs'];
	if (!$desc_obj->readSteamIt())	return ['success' => 0, 'resp' => 'no readSteamIt'];

	if (!$desc_obj->getDataArray())	return ['success' => 0, 'resp' => 'no getDataArray!'];

	if(!$desc = $desc_obj->getNewFullDesc()) return ['success' => 0, 'resp' => 'no getNewFullDesc!'];

	return ['success' => 1, 'resp' => $desc];
}

$description = getDescription();

sa($description['resp']);

if ($description['success']) {
	$ebayObj = new Ebay_shopping2();

	$resp = $ebayObj->updateItemDescription($ebay_id, $description['resp']);
	unset($resp['Fees']);

	sa($resp);
}
















return;
$ebay_id = '122716130121';

$item_info = getSingleItem($ebay_id, ['as_array'=>true,'IncludeSelector'=>'Description']);

$description = $item_info['Item']['Description'];

$title = 'description backup';
$full_desc = _esc($description);
arrayDB("INSERT INTO ebay_data 
	(ebay_id,title,full_desc)
	VALUES
	('$ebay_id','$title','$full_desc')");


	sa(strlen($description));
	sa(htmlspecialchars($description));


$filter_link_de = file_get_contents('Files/filter_link_de.html');
$filter_link_en = file_get_contents('Files/filter_link_en.html');
$filter_link_fr = file_get_contents('Files/filter_link_fr.html');
$filter_link_es = file_get_contents('Files/filter_link_es.html');
$filter_link_it = file_get_contents('Files/filter_link_it.html');

$description = preg_replace('/(--sys-de.+?<div class="col-md-3 pos-rel">)\s+(<\/div>)/s','${1}
'.$filter_link_de.'
			</div>', $description);

$description = preg_replace('/(--sys-en.+?<div class="col-md-3 pos-rel">)\s+(<\/div>)/s','${1}
'.$filter_link_en.'
			</div>', $description);
$description = preg_replace('/(--sys-fr.+?<div class="col-md-3 pos-rel">)\s+(<\/div>)/s','${1}
'.$filter_link_fr.'
			</div>', $description);

$description = preg_replace('/(--sys-es.+?<div class="col-md-3 pos-rel">)\s+(<\/div>)/s','${1}
'.$filter_link_es.'
			</div>', $description);

$description = preg_replace('/(--sys-it.+?<div class="col-md-3 pos-rel">)\s+(<\/div>)/s','${1}
'.$filter_link_it.'
			</div>', $description);




$description = preg_replace('/\S+?ber 3500 PC-Spiele.+?splash">Keine CD\/DVD<\/div>/s', '<div class="splash">Keine CD/DVD</div><br>
				deutsches Support 24/7 <br>
				Sie erhalten digitale Aktivierungsdaten.', $description);

$description = preg_replace('/over 3500 PC games.+?No CD \/ DVD<\/div>/s', '<div class="splash">No CD / DVD</div><br>
				English support 24/7<br>
				You will receive digital activation data.', $description);

$description = preg_replace('/Plus de 3500 jeux.+?Pas de CD \/ DVD<\/div>/s', '<div class="splash">Pas de CD / DVD</div><br>
				Support allemand / anglais 24/7<br>
				Vous recevrez des données d\'activation numérique.', $description);

$description = preg_replace('/Más de 3500 juegos.+?No hay CD\/DVD<\/div>/s', '<div class="splash">No hay CD/DVD</div><br>
				Soporte inglés 24/7<br>
				Recibirá datos de activación digital.', $description);

$description = preg_replace('/Più di 3500 giochi.+?Nessun CD\/DVD<\/div>/s', '<div class="splash">Nessun CD/DVD</div><br>
				Supporto Inglese 24/7<br>
				Si ottiene l\'attivazione di dati digitali.', $description);

	sa(strlen($description));
	sa(htmlspecialchars($description));

// $ebayObj = new Ebay_shopping2();

// $resp = $ebayObj->updateItemDescription($ebay_id, $description);
// unset($resp['Fees']);

// sa($resp);




?>