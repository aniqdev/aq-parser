<?php ini_get('safe_mode') or set_time_limit(2000); // Указываем скрипту, чтобы не обрывал связь.





sa(time());
sa(date('Y-m-d H:i'));


return;
	$type = 'app';
	$appid = '17430';
	$dir_path = ROOT.'/steam-images/'.$type.'s-'.$appid;
	// steam-images checker

	cerate_thumbs($dir_path, 1);
	cerate_thumbs($dir_path, 2);
	cerate_thumbs($dir_path, 3);
	cerate_thumbs($dir_path, 4);

function cerate_thumbs($dir_path, $i)
{
	$big1_path = $dir_path.'/big'.$i.'.jpg';

    $imagine = new Imagine\Gd\Imagine();
    $mode = Imagine\Image\ImageInterface::THUMBNAIL_INSET;

    $size = new Imagine\Image\Box(470, 1000);

    $imagine->open($big1_path)
        ->thumbnail($size, $mode)
        ->save($dir_path.'/thumb-'.$i.'-m.jpg');

    $size = new Imagine\Image\Box(1000, 120);

    $imagine->open($big1_path)
        ->thumbnail($size, $mode)
        ->save($dir_path.'/thumb-'.$i.'-s.jpg');
}


return;
function delTree($dir) { 
   $files = array_diff(scandir($dir), array('.','..')); 
    foreach ($files as $file) { 
      (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
    } 
    return rmdir($dir); 
  } 
var_dump(delTree(ROOT.'/steam-images/sub'));




return;
$table = 'steam_de';
$options = array('http' => array('method' => "GET", 'header' => "Accept-language: en-US\r\n" . "Cookie: Steam_Language=".get_language_by_table($table)."; mature_content=1; birthtime=238921201; lastagecheckage=28-July-1977\r\n"));
$context = stream_context_create($options);
$link = 'http://store.steampowered.com/app/253230/';
 $game_item = aqs_file_get_html($link, false, $context);

$languages = []; // для игр
foreach ($game_item->find('.game_language_options tr[class!=unsupported] .ellipsis') as $lang_item) {
	sa(trim($lang_item->innertext));
    $languages[] = trim($lang_item->innertext);
}
$languages = implode(',', $languages);
sa($languages);







return;
$str1 = "Tom Clancy's Rainbow Six® Siege";
$str1 = "";

$str2 = slugify($str1 or 'none');

sa($str2);




return;

$ebay_id = '253732048914';
	$res = getSingleItem($ebay_id, ['as_array'=>true,'IncludeSelector'=>'Details,ItemSpecifics,Compatibility']);
	sa($res);



return;
$_POST['q'] = 'micro mineral';

sa(json_decode(Cdvet::filter_search()));




return;
$res = Ebay_shopping2::findItemsAdvanced(0, 'gig-games', $page = 1, $perPage = 100);
sa(json_decode($res, 1));

return;
$itemId = '122716027161';

				$url = 'http://open.api.ebay.com/shopping';
				$url .= '?callname=GetSingleItem';
				$url .= '&responseencoding=JSON';
				$url .= '&appid=Konstant-Projekt1-PRD-bae576df5-1c0eec3d';
		 $url .= '&siteid=77';
				$url .= '&version=1079';
				$url .= '&ItemID='.$itemId;
				$url .= '&IncludeSelector=Details,ItemSpecifics';

				sa($url);


return;
$categoryId = '139973';
$categoryId = '22189';
$categoryId = '63071';

	// $res = EbayGigGames::GetCategorySpecifics($categoryId);

	// $res = EbayGigGames::GetSellerListRequest($page=1, $entires=25);


// $res = get_product_list_test($plattform = 'cdvet');

	$arr = get_category_specifics_sorted($categoryId);

sa(count($arr));
sa($arr);






return;
$res = Ebay_shopping2::findItemsAdvanced(0, 'gig-games');

sa(json_decode($res,1));



return;
$feed_new = csvToArr('http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129', ['max_str' => 0,'encoding' => 'windows-1250']);
$feed_new = array_column($feed_new, null, 0);

sa($feed_new);








return;
	$ord_obj = new Ebay_shopping2;


	$ord_arr = $ord_obj->GetSellerItemsArray();

	sa($ord_arr);



return;
$added_arr = Cdvet::get_added_shop_ids();

sa($added_arr);


return;
$added_arr_sorted = Cdvet::sort_added();

sa(
$added_arr_sorted);



return;
$item_id = '253201256795';


$res = parse_item_specifics($item_id);

sa($res);




return;
$str = <<<EOL
<p><span style="color:#00B050;font-weight:bold;">Fit-BARF Öl D3</span> ist eine Mischung kaltgepresster Pflanzenöle aus Hanföl und  Nachtkerzenöl, welche für eine optimale, ausgewogene Versorgung mit essentiellen Fettsäuren sorgen können. Die zusätzliche Ergänzung mit Dorschlebertran als Vitamin D3 Lieferant macht diese Futteröl zu einer vollwertigen Quelle essentieller Fettsäuren.</p>
<p> </p>
<p><span style="color:#00B050;font-weight:bold;">Fit-BARF Öl D3</span> sorgt ernährungsbedingt für:</p>
<p>- einen ausgeglichenen Calcium-Haushalt</p>
<p>- starke Knochen und Zähne</p>
<p>- eine Unterstützung des Immunsystems</p>
<p>- eine Aufnahme fettlöslicher Vitamine (E,D,A,K – Vitamine)</p>
<p>- mehr Energie</p>
<p> </p>
<p>Ergänzungsfuttermittel für Hunde und Katzen</p>
<p> </p>
<p><span style="font-weight:bold;text-decoration: underline;">Zusammensetzung:</span> Hanföl, Dorschlebertran, Nachtkerzenöl</p>
<p> </p>
<p><span style="font-weight:bold;text-decoration: underline;">Analytische Bestandteile und Gehalte:</span> Rohprotein < 0,3%, Rohfett 99,6%, Rohfaser < 0,5%, Rohasche < 0,4%</p>
<p> </p>
<p><span style="font-weight:bold;text-decoration: underline;">Fütterungsempfehlung:</span> 1 - 2 mal wöchentlich kleine Hunde, Katzen ½ Tl., mittlere Hunde 1 Tl., große Hunde 1 ½ Tl.</p>
<p>Bei täglicher Fütterung empfehlen wir einen Zeiraum von 4 - 6 Wochen.</p>
<p> </p>
<p>Nach dem Öffnen gekühlt und dunkel aufbewahren und innerhalb von 6 Wochen verbrauchen!</p>
EOL;

sa(htmlspecialchars($str));

$str = preg_replace('/<span[^>]*?>(.+?)<\/span>/', '${1}', $str);
sa(htmlspecialchars($str));

$zus = Cdvet::get_zusammen($str);
sa($zus);

$cd_arr = json_decode(file_get_contents('csv/eBayArtikel.json'), true);
$zus = Cdvet::get_zusammen($cd_arr[465]['I']);
sa($zus);


return;
// 	$cd_arr = json_decode(file_get_contents('csv/eBayArtikel.json'), true);

// 	foreach ($cd_arr as $key => $row) {
// 		if (in_array('300698', explode('|', $row['L']))) {
// 			sa($row['C'].);
// 		}
// 	}




// return;
$categories = readExcel('csv/eBayArtikel.xlsx', 1); // сохранение категорий

$sorted_cats = Cdvet::cd_ebay_cat_sort($categories);
sa($sorted_cats);
    $cat_ids = Cdvet::get_ebay_cat('300687|300718|300771|300805|300809|300823|300825|300826|300844|300859|300861|300863|300864|300887|300888|300939|301127|301360|301394|301398|301412|301414|301415|301433|301448|301450|301452|301453|301476|301477|301528|301777|302256|302288|302313|302317|302331|302333|302352|302367|302369|302371|302372|302395|302396|302447|302498|302747|30274', $sorted_cats);
sa($cat_ids);


return;
// <PictureDetails>
// 	<GalleryDuration></GalleryDuration> 'Days_7' and 'Lifetime'
// 	<GalleryType></GalleryType>
// 	<PhotoDisplay></PhotoDisplay>
// 	<PictureURL></PictureURL>
// </PictureDetails>


$ebay_id = '253453464293';

$res = EbayGigGames::setTokenByName('cdvet')
         ->updateItem__TEST__($ebay_id, '
         	<PictureDetails>
		      <GalleryDuration>Lifetime</GalleryDuration>
		      <GalleryType>Featured</GalleryType>
		    </PictureDetails>');

sa($res);





return;
$ebay_id = '253247197461';

$res = Cdvet::updateItemSubtitle($ebay_id, '★Direkt vom Hersteller ★Made in Germany ★inkl. Beratung');

sa($res);







return;
$ebay_id = '253202702626';


	$item_info = getSingleItem($ebay_id, ['as_array'=>true,'IncludeSelector'=>'Description']);

	$description = $item_info['Item']['Description'];

	// var_dump($description);


	$top_desc = trim(str_get_html($description)->find('.cv-desc-top', 0)->innertext);

	var_dump($top_desc);

$top_desc = $top_desc;
$top_desc = str_replace(['</div>','</p>','&nbsp;'], ['</div><br>','</p><br>',' '], $top_desc);

$top_desc = trim(preg_replace('/<br>$/', '', $top_desc));
$top_desc = trim(preg_replace('/<br>$/', '', $top_desc));

$top_desc = strip_tags($top_desc, '<br>');

$top_desc = str_replace(['<br>','<br/>'], '<br>', $top_desc);

$top_desc = str_replace("\r\n", ' ', $top_desc);

$top_desc = preg_replace('/\s{2,}/', ' ', $top_desc);

$top_desc = str_replace(['<br><br>','<br> <br>'], '<br>', $top_desc);
str_replace(['<br>','<br/>'], '<br>', $top_desc, $br_count);
	// каждый <br> стоит 50 символов
	$char_limit = 750 - ($br_count * 5);
	// $char_limit = 750;
	if (strlen($top_desc) > $char_limit) {
		$top_desc = strip_tags($top_desc);
		if (strlen($top_desc) > $char_limit) {
			$top_desc = substr($top_desc, 0, $char_limit);
		}
	}
echo('<hr>'.$top_desc);

sa($br_count.'|'.strlen($top_desc));

return;
$top_desc = '<div class="cv-desc cv-desc-top">
<p>- Zusätzlicher Schutz vor Flöhen, Milben, Haarlingen und fliegenden Insekten wie Stechmücken und Bremsen</p><p>- Enthält rein pflanzliche Inhaltsstoffe</p><p>- Einfach in der Anwendung</p><p>&nbsp;</p><p><span style="color:#00B050;font-weight:bold;">ZeckEx SpotOn</span> ist die pflanzliche Alternative zu den chemischen Repellentien.</p><p>- Hervorragender Langzeitschutz für alle Wirbeltiere</p><p>- Auch für Katzen und Junghunde geeignet</p><p>&nbsp;</p><p>Repellent</p><p>&nbsp;</p>
</div>';

echo $top_desc;
echo "<hr>";
$top_desc = str_replace(['</div>','</p>'], ['</div><br><br>','</p><br><br>'], $top_desc);

echo strip_tags($top_desc, '<br>');

str_replace(['<br>','<br/>'], '<br>', $top_desc, $count);

sa($count);




return;
	$ord_obj = new EbayOrders;

		$user = $ord_obj->GetUser('whaboom', '112570774366');
sa($user);
		return;

	$c = array_merge(['NumberOfDays'=>1,'SortingOrder'=>'Ascending','PageNumber'=>'1'],[]);

	$ord_obj = new EbayOrders;

	$ord_arr = $ord_obj->getOrders($c);

	sa($ord_arr);



return;
$steam_desc = "<h2>Über dieses Spiel</h2>  							What <h2>Über dieses Spiel</h2> kills you makes you stronger! Play as the red shirted fool as he jumps on spikes, drowns, ingests poison and finds other horrible ways to die, and then uses his corpses to reach the end! Play through over 30 levels while listening to a great retro soundtrack by VVVVVV composer, SoulEye. Are the story mode and bonus stages not enough for you? Try one of the levels that didn't make the cut on the steam workshop, or make your own for others to play! And once you've died a bunch, try flinging your corpses around on the title screen! It's quite satisfying.";

$steam_desc = trim(preg_replace('/<h2>.+?<\/h2>/', '', $steam_desc, 1));

sa($steam_desc);


return;
		$user = (new EbayOrders())->GetUser('hermzone', '122791233215');
sa($user);

return;
$ebay_id = '112567976204';
$ebay_id = '253201262577';

//'IncludeSelector'=> Details,Description,ItemSpecifics,TextDescription
$res = getSingleItem($ebay_id, ['as_array'=>true,'IncludeSelector'=>'Details,ItemSpecifics,TextDescription,ShippingCosts,Compatibility']);

sa($res);



return;
$path = 'http://www.oplata.info/download/6544503_e133x4151i539t4270l1736x3507z2359.tXt';

$res = file_get_contents('http://www.oplata.info/download/6544503_e133x4151i539t4270l1736x3507z2359.txt');
 $extension = pathinfo($path, PATHINFO_EXTENSION);
var_dump($extension);
sa($res);


return;
$res = arrayDB("select * from ebay_automatic_log where received_item	 like '%typegood\":\"2%' order by id desc limit 100");

foreach ($res as $key => $val) {
	sa(json_decode($val['invoice_resp']));
	sa(json_decode($val['received_item']));
	echo "<hr>";
}



return;
$res = (new Ebay_shopping2)->GetCategorySpecifics('134754');

sa($res);



return;
	$app_sub = 'app';
	$app_id = '485700';

	// steam-images checker
	$checker = file_get_contents('http://parser.gig-games.de/steam-images-checker.php?app_id='.$app_id.'&app_sub='.$app_sub);
	$chr = json_decode($checker, true);

	sa($chr);


	$desc_obj = new CreateDesc2017(0);

	if (!$desc_obj->getSteamLinkBySteamId(13825))	sa(['success' => 0, 'resp' => 'no steam link',
		'text' => $desc_obj->error_text, 'sl' => $desc_obj->_steam_link]);


	$desc_obj->setImagesArr([
			in_array('small1.jpg',$chr)?'//parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/small1.jpg':'//parser.gig-games.de/images/no-image-available.png',
			in_array('small2.jpg',$chr)?'//parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/small2.jpg':'//parser.gig-games.de/images/no-image-available.png',
			in_array('small3.jpg',$chr)?'//parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/small3.jpg':'//parser.gig-games.de/images/no-image-available.png',
		]);

	$deuched = false;
	if (!$desc_obj->readSteamDe())  sa(['success' => 0, 'resp' => 'no readSteamDe']);
	if (!$desc_obj->readSteamEn())  $deuched = $desc_obj->goDeutchToEn();
	if (!$desc_obj->readSteamFr())	$deuched = $desc_obj->goDeutchToFr();
	if (!$desc_obj->readSteamEs())	$deuched = $desc_obj->goDeutchToEs();
	if (!$desc_obj->readSteamIt())	$deuched = $desc_obj->goDeutchToIt();

$desc = $desc_obj->getNewFullDesc();

if (!$desc) {
	sa('NO Description!');
	return;
}

sa($desc);
return;

$ebay_id = '112567996286';

	$resp = $ebayObj->updateItemDescription($ebay_id, $desc);
	unset($resp['Fees']);

	sa($resp);


return;
$url = 'http://open.api.ebay.com/shopping?callname=GetSingleItem&responseencoding=JSON&appid=Konstant-Projekt1-PRD-bae576df5-1c0eec3d&siteid=77&version=515&ItemID=122873574286&IncludeSelector=Details';

$res = file_get_contents($url);

sa($res);


return;
$ebay_id = '1234567890';

$description = '							<div class="col-xs-7">
								<a href="//offfer.ebay.de/ws/eBayISAPI.dll?BinConfirm&rev=38&fromPage=2047675&item=0&fb=1" class="btn-kaufen" rel="nofollow" target="_blank">sofort-kaufen</a>
							</div>';

$is_match = preg_match('/offer\.ebay\.de.+?fb=1/s', $description);

var_dump($is_match);

	$description = preg_replace('/offer\.ebay\.de.+?fb=1/s',
		'offer.ebay.de/ws/eBayISAPI.dll?BinConfirm&fromPage=2047675&item='.$ebay_id.'&fb=1', $description);

sa(htmlspecialchars($description));



return;
$start = time();

$res = (new Ebay_shopping2())->GetSellerItemsArray();

sa(time()-$start);

sa('count: ' . count($res['completed']));

sa($res['completed']);

$file = __DIR__.'/../adds/to-relist.json';

file_put_contents($file, json_encode($res['completed']));






return;
	$o = [];

	$c = array_merge(['NumberOfDays'=>1,'SortingOrder'=>'Ascending','PageNumber'=>'1'],$o);

	$ord_obj = new EbayOrders;

	$ord_arr = $ord_obj->getOrders($c);

sa($ord_arr);

return;
$res = arrayDB("SELECT *, MIN(price) from ak_keys where status = 'active' group by ebay_id");

sa($res);

return;
$text = 'KCDN5-C6JHK-KNFLR';

$res = get_urls_from_text($text);

var_dump($res);







return;
$urlll = '2F0IT-8FZ5D-9VNY6';

var_dump(filter_var($urlll, FILTER_VALIDATE_URL));

return;
$ids_arr = arrayDB("SELECT item_id FROM ebay_prices");
$ids_arr = array_column($ids_arr, 'item_id');


sa($ids_arr);


return;
	$suitables = get_suitables2($ebay_item_id);
	sa($suitables);
	$wh_price = @$suitables[0]['item1_price'];
	sa($wh_price);


return;
$_GET['limit'] = @$_GET['limit'] ? $_GET['limit'] : 50; // типо насройка лимита по умолчанию
sa($_GET['limit']);

return;
	$excel_s2 = readExcel('csv/ebayartikel05-07-2018.xlsx', 0);
sa($excel_s2);


// $categories = json_decode(file_get_contents('csv/eBayArtikel_s2.json'), true);
// sa($categories);

// 	$excel = readExcel('csv/eBayArtikel21-02-2018.xlsx', 1); // новый файл

// sa($excel);
return;
$res = get_text_template('mail', 'DE');

$res = str_replace('<!-- facebook_paragraph -->', get_facebook_paragraph('112630283155','DE'), $res);

echo $res;


return;
$ebay_prices = arrayDB("SELECT item_id,price FROM ebay_prices");
$ebay_prices = array_column($ebay_prices, 'price', 'item_id');


sa($ebay_prices);


return;
$ebay_games = arrayDB('SELECT title,ebay_price,ebay_id FROM steam_de WHERE ebay_id<>""');
// $ebay_games = array_column($ebay_games, 'item_id');

draw_table_with_sql_results($ebay_games,5);

return;
$white_list = arrayDB("SELECT game_id,ebay_id FROM ebay_black_white_list WHERE category = 'white'");
$white_lists = [];
foreach ($white_list as $val) $white_lists[$val['game_id']][] = $val['ebay_id'];

sa($white_lists);

return;
$res = (new Ebay_shopping2)->GetMessages('inbox', 100);

sa($res);


return;
$res = get_ebay_black_list(3589);

sa($res);


return;
sa($_SERVER);


return;
	$ret = arrayDB("SELECT title FROM steam_de WHERE title LIKE '%a%' LIMIT 5");
sa($ret);
	$ret = array_column($ret, 'title');

sa($ret);

	return;
$res = get_text_template('mail', 'DE');

$res = str_replace('<!-- facebook_paragraph -->', get_facebook_paragraph('112630283155','test'), $res);

echo $res;

return;
var_dump('2000-00-00 00:00:00' > 0);

return;
$res = Cdvet::filter_search_site();

sa(json_decode($res));




return;
// $cd_arr = json_decode(file_get_contents('csv/eBayArtikel.json'), true);
$xcel = readExcel('csv/produkte-kategorien-020518.xlsx');

// sa($xcel);

// return;

foreach ($xcel as $val) {
	sa($val['A']);
	sa($val['C']);
	$shop_id = _esc(trim($val['A']));
	$cats = _esc($val['C']);
	arrayDB("UPDATE cdvet_feed_full SET categories = '$cats'
		WHERE shop_id = '$shop_id'");
}


return;
echo "<pre>";
// var_dump(arrayDB("SELECT * FROM cdvet_feed"));
echo "</pre>";

echo(str_replace('.', ',', +'1.00').'g');






return;
$feed_new = csvToArr('http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129', ['max_str' => 0,'encoding' => 'windows-1250']);


$broken = [];
$no_img = [0];

foreach ($feed_new as $val) {
	$image = trim($val[15]);
	$link = trim($val[14]);
	if ($image) {
		if (!@fopen($image,'r')) {
			$broken[] = $link;
		}
	}else{
		$no_img[] = $link;
	}
}
sa($broken);
sa($no_img);


return;
$feed_new = csvToArr('http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129', ['max_str' => 0,'encoding' => 'windows-1250']);

// sa($feed_new);

// $cd_arr = json_decode(file_get_contents('csv/eBayArtikel.json'), true);

// sa($cd_arr);



foreach ($feed_new as $val) {
	$shop_id = _esc(trim($val[0]));
	$link = _esc($val[14]);
	$image = _esc($val[15]);
	arrayDB("UPDATE cdvet_feed SET image = '$image', link = '$link'
		WHERE shop_id = '$shop_id'");
	sa(['link'=>$link,'image'=>$image,]);
}


return;
$words = [];
foreach (json_decode(Cdvet::filter_search(),1) as $key => $val) {
	$words = array_merge($words, explode(' ', $val['title']), explode(' ', $val['short_desc']));
}
usort($words, function ($a, $b)
{
    return (strlen($a) > strlen($b)) ? -1 : 1;
});
sa(array_unique($words));






return;
$start = time();
$ebay_item_arr = Cdvet::GetSellerList();

sa(time()-$start);
sa($ebay_item_arr);

foreach ($ebay_item_arr as $item) {
	$ebay_id = $item['ItemID'];
	$ebay_cat = $item['Storefront']['StoreCategoryID'];
	arrayDB("UPDATE cdvet SET ebay_cat = '$ebay_cat' WHERE ebay_id = '$ebay_id'");
}

return;
$res = get_cdvet_cats();

sa($res);




return;
$cd_arr = json_decode(file_get_contents('csv/eBayArtikel.json'), true);

// sa($cd_arr);

$max_len = 0;

foreach ($cd_arr as $val) {
	sa($val['A']);
	sa($val['L']);
	$shop_id = _esc(trim($val['A']));
	$cats = _esc($val['L']);
	arrayDB("UPDATE cdvet_feed SET categories = '$cats'
		WHERE shop_id = '$shop_id'");
}


return;
$categories = json_decode(file_get_contents('csv/eBayArtikel_s2.json'), true);

$sorted_cats = Cdvet::cd_ebay_cat_sort($categories);

sa($sorted_cats);



return;
	$categories = json_decode(file_get_contents('csv/eBayArtikel_s2.json'), true);

sa($categories);

foreach ($categories as $k => $val) {
	if(in_array($k, [1,2,22,23,38,39,58,59,65,66])) continue;

	$section = _esc($val['F']);
	$cat_name = _esc($val['A']);
	$eBayShopKAtegorieID = _esc($val['B']);
	$eBayKategorie = _esc($val['C']);
	$shop_parent = _esc($val['D']);
	$shop_category = _esc($val['E']);

	arrayDB("INSERT INTO 
		cdvet_cats (section,cat_name,eBayShopKAtegorieID,eBayKategorie,shop_parent,shop_category)
		VALUES ('$section','$cat_name','$eBayShopKAtegorieID','$eBayKategorie','$shop_parent','$shop_category')");
}


return;
$feed_old = arrayDB("SELECT * FROM cdvet_feed");

$feed_old = array_column($feed_old, null, 'shop_id');

sa(count($feed_old));
sa($feed_old);




return;
$user_id = 'm.claus79';

var_dump(is_trusted_user($user_id));



return;
$res = get_text_template('mail', 'DE');

$res = str_replace('<!-- facebook_paragraph -->', get_facebook_paragraph(), $res);

echo $res;

return;
$order_arr = GetItemsAwaitingFeedbacks();

sa($order_arr);


return;
// $ebay_arr = EbayOrders::GetFeedbackByTransactionId('253453548060');
// $ebay_arr = EbayOrders::GetFeedbackByItemId('122885247159');
// $ebay_arr = EbayOrders::GetFeedbackByOrderLineItemID('122885247159-1924962981002');

$ebay_arr = EbayOrders::GetItemsAwaitingFeedbackRequest(['PageNumber'=>'1']);

sa($ebay_arr);





return;
$ebay_arr = Cdvet::GetSellerList();

sa($ebay_arr);




return;
$title = 'cdVet® insektoVet Spray 100ml Pferd Parasitenabwehr';
	
	$title = Cdvet::replace_parasite($title);


sa($title);

return;
	$item_info = getSingleItem('253453573028', ['as_array'=>true]);

	sa($item_info);



return;
$feed_new = csvToArr('http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129', ['max_str' => 0,'encoding' => 'windows-1250']);

// draw_table_with_sql_results($feed_new, $first_row_thead = true);

// $feed_new = array_column($feed_new, null, 0);

sa($feed_new);

return;
$top_desc = '<div>
	<p>
		<span style="color:#00B050;font-weight:bold;">ArthroGreen Gelenkfit HD</span><span> dient der ern&auml;hrungsbedingten Unterst&uuml;tzung der Funktion des Bewegungsapparates. Es unterst&uuml;tzt intensiv die Versorgung der B&auml;nder, Sehnen und Bindegewebe.</span></p>
	<p><span>&nbsp;</span></p>
	<p><span>- synergetische Kr&auml;utermischung</span></p>
	<p><span>- </span><span style="color:#00B050;font-weight:bold;">ArthroGreen Gelenkfit HD</span><span> enth&auml;lt Kr&auml;uterausz&uuml;ge, die f&uuml;r die Versorgung der Gelenke und Sehnen wichtig sind. Insbesondere die Informationen im K&ouml;rper, die zur Straffung des B&auml;nder- und Sehnenapparates hilfreich sind, werden durch ern&auml;hrungsbedingte Unterst&uuml;tzung optimiert.</span></p>
	<p><span>- hervorragende ern&auml;hrungsbedingte Unterst&uuml;tzung bei durchtrittigen oder weichgefesselten Pferden</span></p>
	<p><span>&nbsp;</span></p>
	<p>
		<span style="font-weight:bold;text-decoration: underline;">Expertentipp:</span>
		<span> Gerade Welpen/Junghunde einer gro&szlig;en Rasse k&ouml;nnen Sie zus&auml;tzlich noch mit </span>
		<span style="color:#00B050;font-weight:bold;">ArthroGreen Gelenkfit HD</span>
		<span> unterst&uuml;tzen. Es stabilisiert auf nat&uuml;rliche Weise den Sehnen- und B&auml;nderapparat im Wachstum. Eine Zuf&uuml;tterung ab der 8. Lebenswoche ist empfehlenswert.</span>
	</p>
	<p><span>&nbsp;</span></p>
	<p><span>Erg&auml;nzungsfuttermittel f&uuml;r Hunde, Katzen und Pferde</span></p>
	<p><span>&nbsp;</span></p>
	<p><span style="font-weight:bold;text-decoration: underline;">Zusammensetzung:</span><span> Dextrose, Luzerne, Brennnessel</span></p>
	<p><span>&nbsp;</span></p>
	<p><span style="font-weight:bold;text-decoration: underline;">Analytische Bestandteile und Gehalte:</span><span> Rohprotein &lt; 0,3%, Rohfett &lt; 0,2%, Rohfaser &lt; 0,5%, Rohasche 0,12%, Natrium &lt; 0,02%</span></p>
	<p><span>&nbsp;</span></p>
	<p><span style="font-weight:bold;text-decoration: underline;">Zusatzstoffe je kg:</span><span> Technologischer Zusatzstoff: Kieselgur E551c 2500mg</span></p>
	<p><span>&nbsp;</span></p>
	<p><span style="font-weight:bold;text-decoration: underline;">F&uuml;tterungsempfehlung:</span><span> Hunde und Katzen 1/2 - 1 Messl&ouml;ffel, Pferde 1 - 2 Messl&ouml;ffel 1 mal t&auml;glich f&uuml;r 2 Monate &uuml;ber das Futter geben</span></p>
	<p><span>&nbsp;</span></p>
	<p><span>1 Messl&ouml;ffel entspricht ca. 1g</span></p>
</div>';

	$top_desc = preg_replace('/<span>(.+?)<\/span>/', '${1}', $top_desc);

sa(htmlspecialchars($top_desc));

return;
$top_desc = 'https://i.ebayimg.com/00/s/ODAwWDk1NA==/z/bYAAAOSwQN5ad0ZJ/$_1.PNG?set_id=2';

var_dump(preg_match('/\/z\/(.+?)\//', $top_desc, $matches));

sa($matches);
sa('https://i.ebayimg.com/images/g/'.$matches[1].'/s-l1600.jpg');

return;
$ord_array = getOrderArray();

sa($ord_array);

return;
$text = 'Obstessig, Dextrose, Holunderbeersaft, Rote Bete Saft, Fermentgetreide flüssig, Brennnesselextrakt, Spitzwegerichextrakt, Acerola Analytische Bestandteile und, Gehalte Rohfett  Zusatzstoffe je kg Konservierungsmittel, Milchsäure E270 9500mg, sensorische Zusatzstoffe, Grapefruitextrakt 60000mg, Oreganumöl 800mg, Ginkgotinktur4800mg, Artischockentinktur 2000mg, Johanniskrauttinktur 900mg, Anistinktur 900mg, Echinaceatinktur 900mg, Thymiantinktur, 900mg Fütterungsempfehlung über einen Zeitraum von 30 Tagen, täglich 1 - 3ml je Liter Trinkwasser, oder täglich 2 - 5, Tropfen je 50ml Trinkwasser';



sa(explode(' ', $text));

// $text = insert_comas($text);
sa($text);
$text = explode(',', $text);
sa($text);
$text = array_map(function($el){return strlen($el);}, $text);
sa($text);


return;
$feed_new = csvToArr('http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129', ['max_str' => 0,'encoding' => 'windows-1250']);

// draw_table_with_sql_results($feed_new, $first_row_thead = true);

$feed_new = array_column($feed_new, null, 0);

sa($feed_new);









return;
$multi_curl = ef_get_milticurl_handler();

$_GET['ef_res_arr'] = [];

$multi_curl->success(function($instance) {
    $_GET['ef_res_arr'][] = json_decode(json_encode($instance->response->ItemArray->Item), true);
});


$ebay_api_url = 'https://api.ebay.com/ws/api.dll';

$multi_curl->addPost($ebay_api_url, ef_build_post_data(1));

$multi_curl->addPost($ebay_api_url, ef_build_post_data(2));

$multi_curl->addPost($ebay_api_url, ef_build_post_data(3));

$multi_curl->addPost($ebay_api_url, ef_build_post_data(4));

$multi_curl->addPost($ebay_api_url, ef_build_post_data(5));


$multi_curl->start(); // Blocks until all items in the queue have been processed.

sa($_GET['ef_res_arr']);

return;
$category = 'ebay_messages';


$res = get_text_template($category);

sa($res);

$tpl_name = 'hi';

$res = get_text_template($category, $tpl_name);

sa($res);






return;
$link_sub = 'http://store.steampowered.com/sub/166591/';

$link_bundle = '//store.steampowered.com/bundle/2433/Strategy_Game_of_the_Year_Bundle/';


        $appsub = explode('/', $link_bundle);

        sa($appsub);



return;
$receive_item_link = 'https://shop.digiseller.ru/xml/purchase.asp?id_i=72752008&uid=E2131F79A65A4271AE4F8DD1CA155361';
$received_item = get_item_xml($receive_item_link);

sa($received_item);



return;
$limit = 5;

$month_top = arrayDB("SELECT tt.*, ebay_games.title_clean, ebay_games.picture_hash, steam_de.ebay_price, steam_de.id
		FROM (select title,price,ebay_id,shipped_time,count(*) as count 
				from ebay_order_items
				where shipped_time > NOW() - INTERVAL 1 MONTH
				group by ebay_id) tt
	JOIN ebay_games
	ON tt.ebay_id = ebay_games.item_id
	JOIN steam_de
	ON tt.ebay_id = steam_de.ebay_id
	WHERE picture_hash <> ''
	order by count desc
	limit $limit");

$ids_arr = array_map(function($el){return $el['id'];}, $month_top);

$ids_str = implode(',', $ids_arr);

$where_and = '';
if($ids_str) $where_and = "steam_de.id NOT IN($ids_str) AND";


$month_top = arrayDB("SELECT tt.*, ebay_games.title_clean, ebay_games.picture_hash, steam_de.ebay_price, steam_de.id
		FROM (select title,price,ebay_id,shipped_time,count(*) as count 
				from ebay_order_items
				where shipped_time > NOW() - INTERVAL 1 MONTH
				group by ebay_id) tt
	JOIN ebay_games
	ON tt.ebay_id = ebay_games.item_id
	JOIN steam_de
	ON tt.ebay_id = steam_de.ebay_id
	WHERE $where_and picture_hash <> ''
	order by count desc
	limit $limit");

sa($month_top);

var_dump("SELECT tt.*, ebay_games.title_clean, ebay_games.picture_hash, steam_de.ebay_price, steam_de.id
		FROM (select title,price,ebay_id,shipped_time,count(*) as count 
				from ebay_order_items
				where shipped_time > NOW() - INTERVAL 1 MONTH
				group by ebay_id) tt
	JOIN ebay_games
	ON tt.ebay_id = ebay_games.item_id
	JOIN steam_de
	ON tt.ebay_id = steam_de.ebay_id
	WHERE $where_and picture_hash <> ''
	order by count desc
	limit $limit");


return;
// Requests in parallel with callback functions.
$multi_curl = new \Curl\MultiCurl();
$multi_curl->setOpt(CURLOPT_FOLLOWLOCATION, true);

$multi_curl->success(function($instance) {

	// sa(func_get_arg(1));
    // $_GET['results'][$jkey][$rkey] = $instance->response;
    sa('count: ' . count($instance->response->items));
    sa($instance->response);
    unset($instance->response);
	sa($instance);

});
$multi_curl->error(function($instance) {
	global $_ERRORS;
    $_ERRORS[] = $instance->errorMessage;
});



$request = 'Sid Meier’s Civilization® VI steam';
// $request = 'Civilization VI Steam';
sa($request);
$request = _requestFilter($request);
sa($request);
$requests = _requestToArr($request);

foreach ($requests as $k => $req) {
	if($k>0) break;
	sa('this is: ' . $k);

    // $reqEnc = urlencode($req);
    // $url = "http://www.plati.ru/api/search.ashx?query={$reqEnc}&pagesize=500&response=json";
    // $multi_curl->addGet($url);

    $url = 'http://www.plati.ru/api/search.ashx';
    $multi_curl->addGet($url, ['query'=>$req, 'response'=>'json', 'pagesize'=>'500'], 'qwerty');
}


$what = $multi_curl->start();

echo "<pre>what is: ";
var_dump($what);
echo "</pre>";











return;
$feed_new = csvToArr('http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129', ['max_str' => 0,'encoding' => 'windows-1250']);

$feed_new = array_column($feed_new, null, 0);




$feed_new_compare = array_column($feed_new, 0);

$feed_file = ROOT.'/lib/adds/cdvet_feed.txt';

$feed_old_compare = explode(',', file_get_contents($feed_file));


$in_feed_new_absent = array_diff($feed_old_compare, $feed_new_compare);
$in_feed_old_absent = array_diff($feed_new_compare, $feed_old_compare);

sa($in_feed_old_absent);
sa($in_feed_new_absent);

file_put_contents($feed_file, implode(',', $feed_new_compare));

return;

$res = arrayDB("SELECT * from ebay_data where ebay_id = '122712177776' order by id desc limit 3");

sa(count($res));

sa(strlen($res[0]['full_desc']));
sa(strlen($res[1]['full_desc']));
sa(strlen($res[2]['full_desc']));

sa(htmlspecialchars($res[2]['full_desc']));








return;
function dir_size($dir) {
	$totalsize=0;
	if ($dirstream = @opendir($dir)) {
		while (false !== ($filename = readdir($dirstream))) {
			if ($filename!="." && $filename!=".."){
				if (is_file($dir."/".$filename))
					$totalsize+=filesize($dir."/".$filename);
				 
				if (is_dir($dir."/".$filename))
					$totalsize+=dir_size($dir."/".$filename);
			}
		}
	}
	closedir($dirstream);
	return $totalsize;
}

$dir_size = dir_size('steam-images');

sa($dir_size . ' b');
sa($dir_size/1024 . ' Kb');
sa($dir_size/1024/1024 . ' Mb');
sa($dir_size/1024/1024/1024 . ' Gb');

return;
$platiObj = new PlatiRuBuy();
$chosen_item_id = '2201685';

		$inv_res = $platiObj->getInvoice($chosen_item_id);
var_dump($inv_res);

return;
		$includes_arr = explode(',', '27050,27020,27000');
			$app_id = $includes_arr[0];
			$app_sub = 'app';


	$checker = file_get_contents('http://parser.gig-games.de/steam-images-checker.php?app_id='.$app_id.'&app_sub='.$app_sub);
	$chr = json_decode($checker, true);

	sa($chr);



return;
		$query = [
			'chat_id' => '278472749',
			'text' => 'test <b><a href="http://parser.gig-games.de/index.php?action=ebay-messages&correspondent=sebi_ghost&message_id=1625861571019&can_be_published=0">teste45</a></b>',
		];
var_dump(AutomaticGroupBot::sendMessage($query));


return;
		$received_item = get_item_xml($receive_item_link);

		sa($received_item);

return;
$receive_item_link = 'https://shop.digiseller.ru/xml/purchase.asp?id_i=71353398&uid=88506F25840C49CAB5FBF039DBC7864D';

$res = simplexml_load_file($receive_item_link);

// $res = file_get_contents($receive_item_link);

sa($res);


return;
$res = simplexml_load_string('<?xml version="1.0" encoding="windows-1251"?><digiseller.response><retval>0</retval><retdesc></retdesc><typegood>1</typegood><file><name_in /><name /><size /></file><text><![CDATA[Luxor 2 HD Luxor 2 HD CG25F-VAY8J-20P76]]></text></digiseller.response>');

sa($res);

return;

// AutomaticGroupBot::sendMessage(date('H:i:s').' New order gewünscht'.$gig_order_id);

	// AutomaticGroupBot::sendMessage(substr(strip_tags(trim('test quotes " @ # \' >===')),0,200));
return;
// $botObj = new AutomaticBot('-195283152');
	// $botObj->sendMessage(['text' => date('H:i:s').' test 1']);
	// AutomaticBot::sendMessage(['text' => date('H:i:s').' test 4']);
	AutomaticGroupBot::sendMessage(date('H:i:s').' test 10');
	AutomaticGroupBot::sendMessage(['chat_id'=>'278472749','text' => date('H:i:s').' test 9']);


return;
arrayDB("UPDATE steam_de set advantage = ROUND((old_price-ebay_price)/old_price*100, 2) where ebay_id <> '' and old_price > 0;
	UPDATE steam_en set advantage = ROUND((old_price-ebay_price)/old_price*100, 2) where ebay_id <> '' and old_price > 0;
	UPDATE steam_fr set advantage = ROUND((old_price-ebay_price)/old_price*100, 2) where ebay_id <> '' and old_price > 0;
	UPDATE steam_es set advantage = ROUND((old_price-ebay_price)/old_price*100, 2) where ebay_id <> '' and old_price > 0;
	UPDATE steam_it set advantage = ROUND((old_price-ebay_price)/old_price*100, 2) where ebay_id <> '' and old_price > 0;", true);

arrayDB("UPDATE steam_de set advantage = ROUND((reg_price-ebay_price)/reg_price*100, 2) where ebay_id <> '' and old_price = 0 and reg_price > 0;
	UPDATE steam_en set advantage = ROUND((reg_price-ebay_price)/reg_price*100, 2) where ebay_id <> '' and old_price = 0 and reg_price > 0;
	UPDATE steam_fr set advantage = ROUND((reg_price-ebay_price)/reg_price*100, 2) where ebay_id <> '' and old_price = 0 and reg_price > 0;
	UPDATE steam_es set advantage = ROUND((reg_price-ebay_price)/reg_price*100, 2) where ebay_id <> '' and old_price = 0 and reg_price > 0;
	UPDATE steam_it set advantage = ROUND((reg_price-ebay_price)/reg_price*100, 2) where ebay_id <> '' and old_price = 0 and reg_price > 0;", true);

arrayDB("UPDATE steam_de set advantage = -1 where ebay_id <> '' and reg_price = 0;
	UPDATE steam_en set advantage = -1 where ebay_id <> '' and reg_price = 0;
	UPDATE steam_fr set advantage = -1 where ebay_id <> '' and reg_price = 0;
	UPDATE steam_es set advantage = -1 where ebay_id <> '' and reg_price = 0;
	UPDATE steam_it set advantage = -1 where ebay_id <> '' and reg_price = 0;", true);


return;
$grouped_by_steam_link = arrayDB("SELECT id,count(*) count from games group by steam_link");
$link_count = array_column($grouped_by_steam_link, 'count', 'id');

sa($link_count);



return;
$items_arr = [
	'253201238514',
	'253201250078',
	'253201256795',
	'253201262577',
	'253201268084',
	'253201270459',
	'253201276383',
	'253201277221',
	'253201277785',
	'253201282057',
	'253201284451',
	'253201289829',
	'253201306076',
	'253201315699',
	'253201320285',
	'253201322474',
	'253202672796',
	'253202677579',
	'253202680228',
	'253202681426',
	'253202682962',
	'253202684208',
	'253202686044',
	'253202687452',
	'253202698229',
	'253202702626',
	'253202705074',
	'253202706508',
	'253202707133',
	'253202709605',
	'253202711811',
	'253202713989',
	'253222299053',
	'253222302713',
	'253222307711',
	'253222308496',
	'253222330523',
	'253222331288',
	'253222781186',
	'253222799317',
];


$postal_code = '49584';

foreach ($items_arr as $key => $item_id) {

	$res = Cdvet::changePostalCode($item_id, $postal_code);
	unset($res['Fees']);
	sa($res);
}








return;
$ebay_id = '122712177776';

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

$description = preg_replace('/offer\.ebay\.de.+?fb=1/s',
		'offer.ebay.de/ws/eBayISAPI.dll?BinConfirm&fromPage=2047675&item='.$ebay_id.'&fb=1', $description);

	sa(strlen($description));
	sa(htmlspecialchars($description));

$ebayObj = new Ebay_shopping2();

$resp = $ebayObj->updateItemDescription($ebay_id, $description);
unset($resp['Fees']);

sa($resp);






return;
// sa(arrayDB("select steam_link from games where ebay_id = '$ebay_id' limit 1"));

	$steam_de = arrayDB("SELECT steam_de.*,steam.usk_links as pegi_links,steam.usk_age as pegi_age 
						FROM steam_de
						LEFT JOIN steam
						ON steam_de.link = steam.link
						WHERE steam_de.link = (select steam_link from games where ebay_id = '$ebay_id' limit 1) LIMIT 1");
	if ($steam_de) {
		$steam_de = $steam_de[0];
	}else{
		return ['success' => 0, 'resp' => 'No steam info!'];
	}

$specifics = build_item_specifics_array($steam_de);


sa($specifics);

// $ebayObj = new Ebay_shopping2();

// $res = $ebayObj->UpdateCategorySpecifics($ebay_id, $specifics);

// sa($res);










return;
function get_filesss($dir='.')
{
	$files = [];
	if ($handle = opendir($dir)){
		while(false !== ($item = readdir($handle))){
			$fpath = "$dir/$item";
			if (is_file($fpath)) {
				if (stripos($fpath, '.jpg') !== false) {
					$files[] = str_replace('E:\xamp\htdocs\parser\www\ignore\Produktbilder_JPG/', '', $fpath);
				}
			}elseif (is_dir($fpath) && ($item != ".") && ($item != "..")){
				$files = array_merge($files, get_filesss($fpath));
			}
		}
		closedir($handle);
	}
	return $files; 
}

$arr = get_filesss('E:\xamp\htdocs\parser\www\ignore\Produktbilder_JPG');

// sa($arr);

// return;
$xcel = readExcel('csv/eBayArtikel.xlsx');

$res = [];
foreach ($xcel as $row => $cell) {
	$img = $cell['C'] . '_' . $cell['D'];
	$img = str_replace('http://www.cdvet.de/media/image/', '', $img);
	$closest ='';
	$temp_res = [];
	$max_perc = 0;
	$temp_res = [
		'percents' => '',
		'excel_img' => '',
		'jpg_file' => '',
	];
	foreach ($arr as $file) {
		similar_text($file, $img, $percents);
		if ($percents > 50 && $percents > $max_perc) {
			$max_perc = $percents;
			$temp_res = [
				'percents' => $percents,
				'excel_img' => $img,
				'jpg_file' => $file,
			];
		}
	}
	if($max_perc) $res[$row]['pack'] = $temp_res;
	$max_perc = 0;
	$temp_res = [
		'percents' => '',
		'excel_img' => '',
		'jpg_file' => '',
	];
	foreach ($arr as $file) {
		similar_text($file, $img.'_Etikett', $percents);
		if ($percents > 50 && $percents > $max_perc) {
			$max_perc = $percents;
			$temp_res = [
				'percents' => $percents,
				'excel_img' => $img,
				'jpg_file' => $file,
			];
		}
	}
	if($max_perc) $res[$row]['ettik'] = $temp_res;
	// ksort($temp_res, SORT_NUMERIC);
	// $temp_res = array_reverse($temp_res);
	// array_splice($temp_res, 3);
	// $res['pack'] = $temp_res;
}

file_put_contents('csv/jpeg_matches.json', json_encode($res));

sa($res);


return;
// $jpegs = json_decode(file_get_contents('csv/jpegs.json'), true);

// sa($jpegs);

// return;
// function get_filess($dir='.')
// {
// 	$files = [];
// 	if ($handle = opendir($dir)){
// 		while(false !== ($item = readdir($handle))){
// 			$fpath = "$dir/$item";
// 			if (is_file($fpath)) {
// 				if (stripos($fpath, '.jpg') !== false) {
// 					$files[] = 'http://hot-body.net/Produktbilder_JPG/'.str_replace('E:\xamp\htdocs\parser\www\ignore\Produktbilder_NEU/', '', $fpath);
// 				}
// 			}elseif (is_dir($fpath) && ($item != ".") && ($item != "..")){
// 				$files = array_merge($files, get_filess($fpath));
// 			}
// 		}
// 		closedir($handle);
// 	}
// 	return $files; 
// }

// $arr = get_filess('E:\xamp\htdocs\parser\www\ignore\Produktbilder_NEU');

// file_put_contents('csv/jpegs.json', json_encode($arr));

// sa($arr);



return;
$xcel = readExcel('csv/eBayArtikel.xlsx');

$res = [];
foreach ($xcel as $row => $cell) {
	$images = explode('|', $cell['M']);
	if(isset($res[count($images)])) $res[count($images)]++;
	else $res[count($images)] = 1;
}
sa($res);

return;
$res = file_get_contents('csv/matches.json');
$res = json_decode($res, true);
sa($res);


return;
$howmach = arrayDB("SELECT count(*) FROM steam_de");sa($howmach[0]['count(*)']);
$howmach = arrayDB("SELECT count(*) FROM steam_en");sa($howmach[0]['count(*)']);
$howmach = arrayDB("SELECT count(*) FROM steam_es");sa($howmach[0]['count(*)']);
$howmach = arrayDB("SELECT count(*) FROM steam_fr");sa($howmach[0]['count(*)']);
$howmach = arrayDB("SELECT count(*) FROM steam_it");sa($howmach[0]['count(*)']);


return;
$fff = new PlatiRuBuy();

$item_id = '184739';

$aa = $fff->isItemOnPlati($item_id);
var_dump($aa);

return;
$url = 'https://plati.market/itm/1847398';

var_dump(isItemOnPlati('184798'));


return;
$curl = new \Curl\Curl();
$curl->get($url);
$curl->setOpt(CURLOPT_FOLLOWLOCATION, true);
$curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
$curl->setOpt(CURLOPT_HEADER, false);
$curl->setOpt(CURLOPT_REFERER, $url);
$curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);

if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
    echo 'Response:' . "\n";
    var_dump($curl->response);
}

// $dom = file_get_contents('https://plati.market/itm/1847398');
// https://plati.market/itm/1847398

// echo $dom; 
return;


$url = 'https://plati.market/itm/1847398';

echo(getSslPage($url));

return;
echo "<pre>";
$w = stream_get_wrappers();
echo 'openssl: ',  extension_loaded  ('openssl') ? 'yes':'no', "\n";
echo 'http wrapper: ', in_array('http', $w) ? 'yes':'no', "\n";
echo 'https wrapper: ', in_array('https', $w) ? 'yes':'no', "\n";
echo 'wrappers: ', var_export($w);
echo "</pre>";



return;
$fff = new PlatiRuBuy();

$item_id = '1847398';

$aa = $fff->isItemOnPlati($item_id);
var_dump($aa);

return;
$appid = '347880';
$app_sub = 'app';
	mkdir('steam-images/dlcs-'.$appid);
	$cpd = copy('steam-images/'.$app_sub.'s-'.$appid.'/header.jpg', 'steam-images/dlcs-'.$appid.'/header.jpg');
	sa(var_dump($cpd));



return;
$games = arrayDB("SELECT * from games where updated_at > '2017-09-21' AND ebay_id <> '' AND steam_link <> ''");


foreach ($games as $k => $game) {
	$link = $game['steam_link'];
	$s_de = arrayDB("SELECT appid, type from steam_de WHERE link = '$link'");
	if(!$s_de) continue;
	$appid = $s_de[0]['appid'];
	$app_sub = $s_de[0]['type'];
	if($app_sub === 'dlc') $app_sub = 'app';
	mkdir('steam-images/dlcs-'.$appid);
	$cpd = copy('steam-images/'.$app_sub.'s-'.$appid.'/header.jpg', 'steam-images/dlcs-'.$appid.'/header.jpg');
	sa(var_dump($cpd) . ' - ' . $link);
}



return;
// use \Curl\Curl;

$headers = ['Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'Accept-Encoding: gzip, deflate',
    'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
    'Host: anistar.ru',
    'Connection: keep-alive',
    'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0'];

$curl = new \Curl\Curl();
// $curl->setHeaders($headers);
$curl->setOpt(CURLOPT_FOLLOWLOCATION, true);
$curl->setUserAgent('Mozilla/5.0 (Windows NT 6.3; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0');
$curl->setReferrer('https://www.example.com/url?url=https%3A%2F%2Fwww.example.com%2F');
$curl->setHeader('X-Requested-With', 'XMLHttpRequest');
$curl->get('http://www.plati.ru/api/search.ashx?query=Perimeter%20ii%20New%20Earth%20Steam&pagesize=500&response=json&rkey=1&jkey=3');

if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
    echo 'Response:' . "\n";
    var_dump($curl->response);
}


return;
$asd = json_decode('{"2":"big1.jpg","3":"big2.jpg","4":"big3.jpg","5":"big4.jpg","6":"header.jpg","7":"small1.jpg","8":"small2.jpg","9":"small3.jpg","10":"small4.jpg"}', true);

sa($asd);

sa(in_array('big1.jpg', $asd));


return;
function tif_to_png($src, $dest) // images/img.tif -> images/img.png
{
	$imagick = new Imagick($src);
	$imagick->setImageFormat('png');
	$imagick->mergeImageLayers(imagick::LAYERMETHOD_UNDEFINED);
	// $imagick->scaleImage(1200, 1200, true);
	$imagick->scaleImage(
		min($imagick->getImageWidth(),  1200),
		min($imagick->getImageHeight(), 1200),
		true
	);
	// $imagick->adaptiveResizeImage(1200, 1200, true);
	return $imagick->writeImage($dest);
}



return;
$itemid = '111541620810';

$price = get_item_price_from_ebay_results($itemid);

sa($price );



  $price2 = get_item_price_from_ebay_orders($itemid);

sa($price2);

return;
$games = arrayDB("SELECT * from games where steam_link <> '' AND ebay_id <> ''");

foreach ($games as $k => $game) {
  echo $game['name'];
  sa(get_item_price_from_ebay_results($game['ebay_id']));
}


return;
$itemid = '121738710904';
$option = [];
$option[] = @arrayDB("SELECT price1 as price,`time` FROM ebay_results WHERE itemid1 = '$itemid' order by id desc limit 1")[0];
$option[] = @arrayDB("SELECT price2 as price,`time` FROM ebay_results WHERE itemid2 = '$itemid' order by id desc limit 1")[0];
$option[] = @arrayDB("SELECT price3 as price,`time` FROM ebay_results WHERE itemid3 = '$itemid' order by id desc limit 1")[0];
$option[] = @arrayDB("SELECT price4 as price,`time` FROM ebay_results WHERE itemid4 = '$itemid' order by id desc limit 1")[0];
$option[] = @arrayDB("SELECT price5 as price,`time` FROM ebay_results WHERE itemid5 = '$itemid' order by id desc limit 1")[0];

sa($option);

$fresh = 0;
$price = 0;
foreach ($option as $key => $value) {
  sa((new DateTime($value['time']))->getTimestamp());
  if (isset($value['time']) && (new DateTime($value['time']))->getTimestamp() > $fresh) {
	$fresh = (new DateTime($value['time']))->getTimestamp();
	$price = $value['price'];
  }
}
sa($price);
sa($fresh);
sa(try_to_get_item_price($itemid));


return;
// phpinfo();
// $imagine = new Imagine\Gd\Imagine();

$path = __DIR__.'/../../pictures/';
$from = __DIR__.'/../../pictures/Gruppenbild_LunaLupis.tif';
$to = __DIR__.'/../../pictures/Gruppenbild_LunaLupis.png';

// $imagine->open($from)->save($to);


$dir = scandir($path . 'tif/');

// sa($dir);
$format = 'png';

// sa(get_defined_constants());

	$imagick = new Imagick($path . 'tif/Gruppenbild_LunaLupis.tif');
	$imagick->setImageFormat($format);
	$imagick->mergeImageLayers(imagick::LAYERMETHOD_UNDEFINED);
	// $imagick->scaleImage(1200, 1200, true);
	$imagick->scaleImage(
		min($imagick->getImageWidth(),  1200),
		min($imagick->getImageHeight(), 1200),
		true
	);
	// $imagick->adaptiveResizeImage(1200, 1200, true);
	var_dump($imagick->writeImage($path . $format.'/Gruppenbild_LunaLupis.' . $format));


for ($i=2; $i < count($dir); $i++) { 
  break;
  var_dump($i);
  var_dump($dir[$i]);
  echo "<br>";
	$image = new Imagick($path . 'tif/' . $dir[$i]);
	$image->setImageFormat($format);
	$image->mergeImageLayers(1);
	$new_name = str_replace('.tif', '.'.$format, $dir[$i]);
	var_dump($image->writeImage($path . $format.'/' . $new_name));
  echo "<hr>";
}


return;

  $ord_obj = new EbayOrders;

  $ord_arr = $ord_obj->getOrders(['NumberOfDays'=>3,'SortingOrder'=>'Ascending','PageNumber'=>'1']);

sa($ord_arr);

return;

$url = 'http://store.steampowered.com/app/375850/Island_Defense/';
  $game_id = preg_replace('/.*\/(\d+)\/.*/', '\1', $url);
  sa($game_id);
  $app_sub = preg_replace('/.*\/(\w+)\/\d+\/.*/', '\1', $url);
  sa($app_sub);

return;

  $Woo = new WooCommerceApi();
  $woo_item = $Woo->checkProductById('138');
  // $woo_item = $Woo->updateProductPrice('7428', 2.1);

  sa($woo_item);


return;
$res = arrayDB("SELECT item_id FROM ebay_games");

foreach ($res as $k => &$v) $v = $v['item_id'];

$res = array_flip($res);

sa($res);


$ids_arr = include(__DIR__.'/../../settings/ids_arr.php');

sa($ids_arr);


return;

$automaticArr = arrayDB("SELECT 
	ebay_orders.ExecutionMethod,
	ebay_orders.goods,
	ebay_orders.BuyerUserID,
	ebay_orders.BuyerEmail,
	ebay_order_items.title,
	ebay_order_items.shipped_time,
	ebay_automatic_log.*
FROM ebay_automatic_log
JOIN ebay_orders
ON ebay_automatic_log.order_id=ebay_orders.id
JOIN ebay_order_items
ON ebay_automatic_log.order_item_id=ebay_order_items.id
ORDER BY ebay_automatic_log.id DESC limit 0,30");

sa($automaticArr);


return;

$order = new GigOrder();
return;
$title = 'Lethal League PC spiel Steam Download Digital Link DE/EU/USA Key Code Gift';

sa(cut_steam_from_title($title));
sa(clean_ebay_title2($title));

return;
	$continue = false;
  $suitables = get_suitables2('112127391106');
  $suitable = ['item1_id' => '0']; // костыль
  if (count($suitables) > 1) {

	$continue = true;
  }elseif (count($suitables) < 1) {

	$continue = true;
  }else $suitable = $suitables[0];

  var_dump($continue);
  sa($suitable);

return;

$two_week_res = arrayDB("SELECT tt.*, ebay_games.title_clean, ebay_games.picture_hash FROM (select title,price,ebay_id,shipped_time,count(*) as count from ebay_order_items group by ebay_id) tt
JOIN ebay_games
ON tt.ebay_id = ebay_games.item_id
WHERE picture_hash <> '' AND shipped_time > NOW() - INTERVAL 14 DAY
order by count desc
limit 10");

sa($two_week_res);

return;
	$email_body = file_get_contents('http://info-rim.ru/mail2017/res2.html');

	$mail = get_a3_smtp_object();
	$mail->addAddress('nameaniq@gmail.com');
	$mail->addBCC('thenav@mail.ru');
	$mail->addBCC('store@gig-games.de');
	$mail->Subject = 'res2 test 7';
	$mail->Body    = $email_body;
	$mail->AltBody = strip_tags($email_body);

	$is_email_sent = $mail->send();
	var_dump($is_email_sent);


return;
sa(date('m/d/Y', time()-60*60*24));

return;
sa((new DateTime(str_replace(["{ts '","'}"], '', "{ts '2017-06-29 12:46:32'}")))->format('d-m-Y H:i:s'));


return;
?><style>
	  /* Always set the map height explicitly to define the size of the div
	   * element that contains the map. */
	  #map {
		height: 100%;
	  }
	  /* Optional: Makes the sample page fill the window. */
	  html, body {
		height: 100%;
		margin: 0;
		padding: 0;
	  }
	  .controls {
		margin-top: 10px;
		border: 1px solid transparent;
		border-radius: 2px 0 0 2px;
		box-sizing: border-box;
		-moz-box-sizing: border-box;
		height: 32px;
		outline: none;
		box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
	  }

	  #pac-input {
		background-color: #fff;
		font-family: Roboto;
		font-size: 15px;
		font-weight: 300;
		margin-left: 12px;
		padding: 0 11px 0 13px;
		text-overflow: ellipsis;
		width: 300px;
	  }

	  #pac-input:focus {
		border-color: #4d90fe;
	  }

	  .pac-container {
		font-family: Roboto;
	  }

	  #type-selector {
		color: #fff;
		background-color: #4d90fe;
		padding: 5px 11px 0px 11px;
	  }

	  #type-selector label {
		font-family: Roboto;
		font-size: 13px;
		font-weight: 300;
	  }
	</style>
  </head>
  <body>
	<input id="pac-input" class="controls" type="text"
		placeholder="Enter a location">
	<div id="type-selector" class="controls">
	  <input type="radio" name="type" id="changetype-all" checked="checked">
	  <label for="changetype-all">All</label>

	  <input type="radio" name="type" id="changetype-establishment">
	  <label for="changetype-establishment">Establishments</label>

	  <input type="radio" name="type" id="changetype-address">
	  <label for="changetype-address">Addresses</label>

	  <input type="radio" name="type" id="changetype-geocode">
	  <label for="changetype-geocode">Geocodes</label>
	</div>
	<div id="map"></div>

	<script>
	  // This example requires the Places library. Include the libraries=places
	  // parameter when you first load the API. For example:
	  // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

	  function initMap() {
		var map = new google.maps.Map(document.getElementById('map'), {
		  center: {lat: -33.8688, lng: 151.2195},
		  zoom: 13
		});
		var input = /** @type {!HTMLInputElement} */(
			document.getElementById('pac-input'));

		var types = document.getElementById('type-selector');
		map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
		map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

		var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.bindTo('bounds', map);

		var infowindow = new google.maps.InfoWindow();
		var marker = new google.maps.Marker({
		  map: map,
		  anchorPoint: new google.maps.Point(0, -29)
		});

		autocomplete.addListener('place_changed', function() {
		  infowindow.close();
		  marker.setVisible(false);
		  var place = autocomplete.getPlace();
		  if (!place.geometry) {
			// User entered the name of a Place that was not suggested and
			// pressed the Enter key, or the Place Details request failed.
			window.alert("No details available for input: '" + place.name + "'");
			return;
		  }

		  // If the place has a geometry, then present it on a map.
		  if (place.geometry.viewport) {
			map.fitBounds(place.geometry.viewport);
		  } else {
			map.setCenter(place.geometry.location);
			map.setZoom(17);  // Why 17? Because it looks good.
		  }
		  marker.setIcon(/** @type {google.maps.Icon} */({
			url: place.icon,
			size: new google.maps.Size(71, 71),
			origin: new google.maps.Point(0, 0),
			anchor: new google.maps.Point(17, 34),
			scaledSize: new google.maps.Size(35, 35)
		  }));
		  marker.setPosition(place.geometry.location);
		  marker.setVisible(true);

		  var address = '';
		  if (place.address_components) {
			address = [
			  (place.address_components[0] && place.address_components[0].short_name || ''),
			  (place.address_components[1] && place.address_components[1].short_name || ''),
			  (place.address_components[2] && place.address_components[2].short_name || '')
			].join(' ');
		  }

		  infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
		  infowindow.open(map, marker);
		});

		// Sets a listener on a radio button to change the filter type on Places
		// Autocomplete.
		function setupClickListener(id, types) {
		  var radioButton = document.getElementById(id);
		  radioButton.addEventListener('click', function() {
			autocomplete.setTypes(types);
		  });
		}

		setupClickListener('changetype-all', []);
		setupClickListener('changetype-address', ['address']);
		setupClickListener('changetype-establishment', ['establishment']);
		setupClickListener('changetype-geocode', ['geocode']);
	  }
	</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD563r49NBGTlbq5l8xtTYXytMbkCWyjC0&libraries=places&callback=initMap"
		async defer></script>

<img src="http://columbavet.info/wp-content/themes/times-pro-columbavet/optimized/image/Gruppe_Web1.jpg" alt="Gruppe_Web1">