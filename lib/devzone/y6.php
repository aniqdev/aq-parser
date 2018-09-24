<?php ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.




	$Ebay = new Ebay_shopping2();
	$response = $Ebay->removeFromSale('112570774808');

var_dump($response);




return;
$new_token = '45yh45y545y';

$res = EbayGigGames::setToken($new_token)->test();

sa($res);

return;
$ItemID = '112567976204';
$ShippingProfileID = '133946209010';

$res = EbayGigGames::updateItemShippingProfileID($ItemID, $ShippingProfileID);

sa($res);


return;
var_dump(strpos('Nach Erhalt des Artikels sollte Ihr Käufer innerhalb der folgenden Frist den Kauf widerrufen oder den Rückgabeprozess einleiten', 'Rück'));


return;
$res = parse_item_specifics($item_id='122716175719');

sa($res);

return;
$file = __DIR__.'/../adds/to-relist.json';

$completed_arr = json_decode(file_get_contents($file),1);

sa($completed_arr);

foreach ($completed_arr as $key => $ebay_id) {
	arrayDB("UPDATE games SET extra_field = 'to_relist2' WHERE ebay_id = '".$ebay_id."'");
}



return;
    $ebay_games = arrayDB("SELECT item_id,picture_hash FROM ebay_prices");
    // $pics_hashes = [];
    // foreach ($ebay_games as $getve) {
    //     $pics_hashes[$getve['item_id']] = $getve['picture_hash'];
    // }
    $pics_hashes = array_column($ebay_games, 'picture_hash', 'item_id');
    sa($pics_hashes);






return;
$res = json_decode(ebay_reparse_one('123324130896', $reparse_one = false), true);

sa($res);








return;
$res = (new Ebay_shopping2())->GetSellerListRequest(1, 200);

sa($res);
return;
	$excel = readExcel('csv/eBayArtikel21-02-2018.xlsx', 1); // новый файл

sa($excel);


return;
$res = get_text_template('mail', 'DE');

$res = str_replace('<!-- facebook_paragraph -->', get_facebook_paragraph('112630283155','test2'), $res);

echo $res;




return;
$item_id = '122712205303';

$res = EbayGigGames::RelistItemRequest($item_id);

unset($res['Fees']);
sa($res);


return;
$start = time();



var_dump(!!@fopen('https://www.cdvet.de/media/image/1d/a0/5b/oster-backmischung-leckerli-180g_590_1_1280x1280.png','r'));
echo "<hr>";
var_dump(!!@fopen('https://www.cdvet.de/media/image/ba/86/ce/magenschutz-200g_591_1_1280x1280.png','r'));




sa(time()-$start);


return;
$feed_new = csvToArr('http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129', ['max_str' => 0,'encoding' => 'windows-1250']);

foreach ($feed_new as $key => $val) {

	$shop_id =   _esc(trim($val[0]));
	$UnitQuantity =  _esc(trim($val[8]));
	$UnitType =  _esc(trim($val[7]));
	arrayDB("UPDATE cdvet_feed 
			SET UnitQuantity = '$UnitQuantity', UnitType = '$UnitType'
			WHERE shop_id = '$shop_id'");
}

return;
$res = arrayDB("select * from cdvet_cats LIMIT 10");

$res1 = array_column($res, 'cat_name', 'shop_category');

sa($res1);

$res2 = array_column($res, 'cat_name');

sa($res2);

$res3 = array_column($res, null, 'shop_category');

sa($res3);


return;
$start = time();
$ebay_item_arr = Cdvet::GetSellerList();

sa((time()-$start)/60);
sa($ebay_item_arr);



return;
$title=urlencode('gig-games');
$url=urlencode('https://stores.ebay.de/gig-games');
$url=urlencode('http://bit.ly/FBshareArticle');
$url=urlencode('https://gig-games.de/facebook.html');
$summary=urlencode('Computerspiele zu Hammerpreisen.');
$image=urlencode('https://gig-games.de/images/gig-games-facebook.jpg');
 
?>


<button onClick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php echo $title;?>&amp;p[summary]=<?php echo $summary;?>&amp;p[url]=<?php echo $url; ?>&amp;p[images][0]=<?php echo $image;?>','sharer','toolbar=0,status=0,width=700,height=400');">Share</button>





<?php
return;
?>
<div class="page-header">
  <h1>Share Dialog</h1>
</div>

<p>Click the button below to trigger a Share Dialog</p>

<div id="shareBtn" class="btn btn-success clearfix">Share</div>

<p style="margin-top: 50px">
  <hr />
  <a class="btn btn-small"  href="https://developers.facebook.com/docs/sharing/reference/share-dialog">Share Dialog Documentation</a>
</p>

<script>
		window.fbAsyncInit = function() {
    FB.init({
      appId      : '159640361375710',
      xfbml      : true,
      version    : 'v2.12'
    });
    FB.AppEvents.logPageView();
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
  
document.getElementById('shareBtn').onclick = function() {
  FB.ui({
    method: 'share',
    display: 'popup',
    href: 'https://gig-games.de/facebook.html',
  }, function(response){});
}
</script>



<?php


return;


?><html>
<head>
    <title>Your Website Title</title>
    <meta property="fb:app_id" 		  content="159640361375710"/>
	<meta property="og:url"           content="https://stores.ebay.de/gig-games">
	<meta property="og:type"          content="website">
	<meta property="og:title"         content="gig-games">
	<meta property="og:description"   content="Computerspiele zu Hammerpreisen">
	<meta property="og:image"         content="https://gig-games.de/images/gig-games-facebook.jpg">
</head>
<body>

	<div id="fb-root"></div>

<script>
	window.fbAsyncInit = function() {
    FB.init({
      appId      : '159640361375710',
      xfbml      : true,
      version    : 'v2.12'
    });
    FB.AppEvents.logPageView();
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

 //  (function(d, s, id) {
	//   var js, fjs = d.getElementsByTagName(s)[0];
	//   if (d.getElementById(id)) return;
	//   js = d.createElement(s); js.id = id;
	//   js.src = 'https://connect.facebook.net/de_DE/sdk.js#xfbml=1&version=v2.12&appId=159640361375710';
	//   fjs.parentNode.insertBefore(js, fjs);
	// }(document, 'script', 'facebook-jssdk'));
</script>

	<div class="fb-share-button" data-href="https://gig-games.de/facebook.html" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fstores.ebay.de%2Fgig-games&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Teilen</a></div>

</head>
<body>

<?php ini_get('safe_mode') or set_time_limit(1000); // Указываем скрипту, чтобы не обрывал связь.









return;
$res = arrayDB("SELECT * FROM ebay_trusted_users");

foreach ($res as $key => $value) {
	$user_id = _esc($value['user_id']);
	$is_trusted = $value['is_trusted'];

	$check = arrayDB("SELECT id FROM ebay_users WHERE user_id = '$user_id'");

	if($check) arrayDB("UPDATE ebay_users SET is_trusted = '$is_trusted' WHERE user_id = '$user_id'");
	else arrayDB("INSERT INTO ebay_users (user_id,is_trusted) VALUES ('$user_id', $is_trusted)");
}








return;
$orders_7days = arrayDB("SELECT awaiting_orders.id,UserID, ItemID, TransactionID, EndTime, ShippingAddress as Country, PaidTime, ShippedTime, 7days_sent, 14days_sent
	from awaiting_orders
	left join ebay_orders
	on awaiting_orders.OrderLineItemID = ebay_orders.order_id
	WHERE 
	7days_sent = 0 AND
	ShippedTime < NOW() - INTERVAL 7 DAY AND
	ShippedTime > NOW() - INTERVAL 8 DAY AND
	CommentType = 'Positive'");

$orders_7days = array_map(function($el)
{
	$el['Country'] = @json_decode($el['Country'],true)['Country'];
	return $el;
}, $orders_7days);

draw_table_with_sql_results($orders_7days, 1);



return;
$feedback_7days  = get_text_template('feedback_7days', 'DE');
sa($feedback_7days);

$feedback_14days  = get_text_template('feedback_14days', 'DE');
sa($feedback_14days);





return;
$orders_8days = arrayDB("SELECT awaiting_orders.id,UserID,ItemID,TransactionID,EndTime,ShippingAddress,PaidTime,ShippedTime,7days_sent,14days_sent from awaiting_orders
	left join ebay_orders
	on awaiting_orders.OrderLineItemID = ebay_orders.order_id
	WHERE 
	PaidTime < NOW() - INTERVAL 7 DAY AND
	-- PaidTime > NOW() - INTERVAL 8 DAY AND
	ShippedTime > 0 AND
	CommentType = 'Positive'
	ORDER BY ShippedTime ASC");

$orders_8days = array_map(function($el)
{	
	$el['Country'] = json_decode($el['ShippingAddress'],true)['Country'];
	unset($el['ShippingAddress']);
	return $el;
}, $orders_8days);

draw_table_with_sql_results($orders_8days, 1);


return;
$imagine = new Imagine\Gd\Imagine();
$point = new Imagine\Image\Point(0, 0);

$feed_new = csvToArr('http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129', ['max_str' => 0,'encoding' => 'windows-1250']);

foreach ($feed_new as $k => $row) {

	// if($k < 95 || $k >= 105) continue;

	$img_arr = explode('|', $row[16]);

	$img_arr = array_filter($img_arr, function ($url){
		return filter_var($url, FILTER_VALIDATE_URL) and (stripos($url, '.jpg') || stripos($url, '.png'));
	});

	sa($img_arr);
	if(!$img_arr) continue;

	@mkdir('cdvet-images/'.$row[0]);

	foreach ($img_arr as $i => $img_url) {

		try {
			$image = $imagine->open($img_url);
		} catch (Exception $e) {
		    sa( 'Выброшено исключение: '.  $e->getMessage());
		    continue;
		}

		$width = $image->getSize()->getWidth();
		$height = $image->getSize()->getHeight();

		$size  = new Imagine\Image\Box($width, $height);

		$background = $imagine->create($size);

		$background->paste($image, $point);

		$background->save('cdvet-images/'.$row[0].'/img-'.($i+1).'.jpg', ['jpeg_quality' => 100]);
	}



	// if(defined('DEV_MODE') && $k > 5) break;
}







return;
	$ebay_obj = new Ebay_shopping2();
	$msgs_arr = $ebay_obj->GetMessages('inbox', 100);

	sa($msgs_arr);


return;
	$cd_arr = json_decode(file_get_contents('csv/eBayArtikel.json'), true);
$cd_arr = array_column($cd_arr, null, 'A');
sa($cd_arr);

return;
$res = (new Ebay_shopping2())->test2('96453894500');

sa($res);


return;
	$ebay_id = '112630294338';

	$item_info = getSingleItem($ebay_id, ['as_array'=>true,'IncludeSelector'=>'Description']);

	$description = $item_info['Item']['Description'];

	$title = 'description backup';
	$full_desc = _esc($description);
	arrayDB("INSERT INTO ebay_data 
		(ebay_id,title,full_desc)
		VALUES
		('$ebay_id','$title','$full_desc')");

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


	$ebayObj = new Ebay_shopping2();

	if(strlen($description) > 50000){
		$resp = $ebayObj->updateItemDescription($ebay_id, $description);
		unset($resp['Fees']);
	}else{
		sa('else');
	}


sa($resp);














return;
$steam_price_to = _esc(@$_POST['steam_price'][1]);
var_dump($_POST['steam_price'][1]);
var_dump($steam_price_to);

return;
$ebayObj = new Ebay_shopping2();

$res = $ebayObj->GetSellerListRequest(1, 200);

sa($res);

return;
$tables_arr = [
	'steam_de',
	'steam_en',
	'steam_fr',
	'steam_es',
	'steam_it',
];

foreach ($tables_arr as $steam_table) {

	$games_arr = arrayDB("SELECT genres,tags,year,developer,publisher,specs,lang,os 
		FROM $steam_table
		WHERE ebay_id <> '' AND ebay_price > 0 AND advantage > 0 AND instock = 'yes'");
	if(!$games_arr) continue;
	update_filter_values($games_arr, $steam_table);
}





function update_filter_values(&$games_arr, $steam_table){
	$insert_query = '';
	// ==============================================================================
	// Сохраняем значения developers
	$developer_res = [];
	foreach ($games_arr as $val) {
		if($val['developer']) @$developer_res[$val['developer']] += 1;
	}
	sa(count($developer_res));
	sa($developer_res);
	foreach ($developer_res as $developer => $count) {
		$developer = _esc($developer);
		$insert_query .= "('$steam_table','developer','$developer','$count'),".PHP_EOL;
	}



	// ==============================================================================
	// Сохраняем значения publishers
	$publisher_res = [];
	foreach ($games_arr as $val) {
		if($val['publisher']) @$publisher_res[$val['publisher']] += 1;
	}
	sa(count($publisher_res));
	sa($publisher_res);
	foreach ($publisher_res as $publisher => $count) {
		$publisher = _esc($publisher);
		$insert_query .= "('$steam_table','publisher','$publisher','$count'),".PHP_EOL;
	}
}





return;
// $opts = array(
//   'http'=>array(
//     'method'=>"GET",
//     'header'=>"Accept-language: en\r\n" .
//               "Cookie: world=1090\r\n"
//   )
// );

// $context = stream_context_create($opts);

// $link = 'http://l2on.net/?c=market&a=item&id=129';

// $page = file_get_contents($link, false, $context);

// echo iconv('windows-1251', 'utf-8', $page);

return;
$filter_data = arrayDB("SELECT * FROM filter_values");

$to_json_arr = [];
foreach ($filter_data as $v) {
	// $to_json_arr[$v['name']][$v['value']] = $v['count'];
	$to_json_arr[$v['name']][] = ['v' => $v['value'], 'c' => $v['count']];
}

sa($to_json_arr);


return;
	$mail = get_store_smtp_object();
	$mail->addAddress('thenav@mail.ru');
	$mail->Subject = 'test message title';
	$mail->Body    = 'test message body';

var_dump($mail->send());



return;
$res = Ebay_shopping2::getSingleItem('253202707133', JSON_OBJECT_AS_ARRAY);


sa($res);




return;
// $res = Cdvet::removeFromSale('253202711811');

// unset($res['Fees']);
// sa($res);

// return;
$items_arr = [];

$items_arr[] = [
	'ItemID' => '253201322474',
	'StartPrice' => '35.95', // 35.95
	'Quantity' => '3',
];

$items_arr[] = [
	'ItemID' => '253202681426',
	'StartPrice' => '50.95', // 50.95
	'Quantity' => '3',
];

$items_arr[] = [
	'ItemID' => '253202711811',
	'StartPrice' => '32.95', // 32.95
	'Quantity' => '3',
];

sa($items_arr);

$res = Cdvet::reviseInventoryStatus($items_arr);

sa($res);

return;
$str = 'cdVet® Fit-Crock Sensitive Mini - Getreidefrei - 250 ml';

preg_match('/(\d*\.?\d+)\s?([^\s]+)/', str_replace(',', '.', $str), $unit_mathes);

$units = Cdvet::get_units($unit_mathes);


sa($units);

return;
$res = (new Ebay_shopping2)->GetCategorySpecifics('134754');

sa($res);



return;
function get_urls_of_real_img($item_id){
	$res = Ebay_shopping2::getSingleItem($item_id, JSON_OBJECT_AS_ARRAY);

	if($res['Ack'] === 'Failure'){ echo 'Failure'; return 0; }

	$PicturesURL = $res['Item']['PictureURL'];

	//sa($PicturesURL);

	$url_of_real_img = [];

	foreach ($PicturesURL as $key => $value) {
		$found = preg_match('#/[^s]/(.+)/#', $value, $matches);
		if(!$found) continue;
		$url_of_real_img[] = 'http://i.ebayimg.com/images/g/'.$matches[1].'/s-l1600.jpg';
	}

	return $url_of_real_img;
}

function do_action($ebay_id, $hor_vert)
{
	$urls_of_real_img = get_urls_of_real_img($ebay_id);
	unset($urls_of_real_img[0]);

	$otras_path = 'E:/xamp/htdocs/info-rim.ru/www/ebay-april-otras/';
	@mkdir($otras_path.$ebay_id);
	foreach ($urls_of_real_img as $key => $value) {
		copy($value, $otras_path.$ebay_id.'/april_'.($key+1).'.jpg');
	}



	$urls_of_real_img[0] = 'http://hot-body.net/april-pics/ebay-april-'.$hor_vert.'/april_'.$ebay_id.'.jpg';

	// $ebayObj = new Ebay_shopping2();
	// $res = $ebayObj->updateItemPictureDetails($ebay_id, $urls_of_real_img);
	// unset($res['Fees']);
	// sa($res);
	// echo '<a href="http://www.ebay.de/itm/'.$ebay_id.'">'.$ebay_id.'</a><hr>';
}


$folder_vert = 'E:/xamp/htdocs/info-rim.ru/www/ebay-pictures-new/';
$files_vert = scandir($folder_vert);

// for ($i=2; $i < count($files_vert); $i++) { 
// 	//echo $folder_vert,$files_vert[$i],'<br>';
// 	if($i < 400) continue;
// 	$ebay_id = preg_replace('/21jan_(\d+).jpg/', '$1', $files_vert[$i]);
// 	do_action($ebay_id, 'vert');
// 	echo $ebay_id,'<hr>';

// 	//if($i > 400) break;
// }



$folder_hor = 'E:/xamp/htdocs/info-rim.ru/www/ebay-pictures-new-hor/';
$files_hor = scandir($folder_hor);

for ($i=2; $i < count($files_hor); $i++) { 
	//echo $folder_hor,$files_hor[$i],'<br>';

	$ebay_id = preg_replace('/21jan_(\d+).jpg/', '$1', $files_hor[$i]);
	do_action($ebay_id, 'hor');
	echo $ebay_id,'<hr>';

	//break;
}



?>