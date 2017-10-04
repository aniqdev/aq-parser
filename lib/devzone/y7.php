<?php ini_get('safe_mode') or set_time_limit(2000); // Указываем скрипту, чтобы не обрывал связь.

	$ebay_id = '122712181675';

	$res = getSingleItem($ebay_id, ['as_array'=>true,'IncludeSelector'=>'Description']);

	$description = $res['Item']['Description'];

	sa(strlen($description));
	sa(htmlspecialchars($description));

	$description = preg_replace('/<a href="http:\/\/koeln-webstudio.+?<\/a>/s',
			'<div href="http://koeln-webstudio.de/" title="koeln-webstudio" class="koeln-logo" target="_blank">
				<div class="gig-created">created by</div>
				<img src="http://hot-body.net/gig-less/images/visit_card.png" alt="koeln-webstudio">
				<div class="gig-entwick">Entwicklung<br> Marketing<br> Design</div>
			</div>', $description);

	$description = preg_replace('/([\.!])[^\.!]+?support@gig-games\.de.+?<\/p>/',
			'$1</p>', $description);


	$description = preg_replace('/<div class="col-sm-3"><div class="gig-slider-block.+?<\/div><br><\/div>/s',
			'', $description);
	$description = preg_replace('/gig-bottom-panel.+?<\/h2>/s',
			'gig-bottom-panel row">', $description);

	$description = preg_replace('/<div class="icol-xs-2"><div class="gig-slider-block.+?<\/div><\/div>/s',
			'', $description);

	sa(strlen($description));
	sa(htmlspecialchars($description));

	// $ebayObj = new Ebay_shopping2();

	// $res = $ebayObj->updateItemDescription($ebay_id, $description);
	// unset($res['Fees']);
	// sa($res);



return;
function change_contact(&$desc, $str, $l){
	$desc = preg_replace('/lng-'.$l.'(.+?)triple-support.+?gig-triple/s',
							 'lng-de${1}triple-support">Support 24H/7</div>
								<div class="triple-cont triple-mail"></div>
								<div class="triple-cont triple-new">'.$str.'</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="gig-triple', $desc);
}


	$res = getSingleItem('122712207245', ['as_array'=>true,'IncludeSelector'=>'Description']);

	$description = $res['Item']['Description'];

	sa(strlen($description));
	sa(htmlspecialchars($description));

	$description = preg_replace('/gig-quelle">(.+?)e:(.+?)<!--link-end-->/',
							 'gig-quelle">$1e: steampowered</a><!--link-end-->', $description);

	$description = preg_replace('/<a target="_blank" href="http:\/\/store.steampowered(.*)">/',
							 '<a>', $description);


	$description = preg_replace('/a\) Steam herunterladen(.+?)<br>b\)/s',
							 "a) Laden Sie das Steaminstallationdatei herunter.<br>\r\nb)", $description);

	$description = preg_replace('/a\) steam download(.+)<br>/',
							 'a) Download the Steam installation file.<br>', $description);

	$description = preg_replace('/a\) t(.+)<br>/',
							 "a) Téléchargez le fichier d'installation Steam.<br>", $description);

	$description = preg_replace('/a\) steamprogramme descarg(.+)<br>/',
							 'a) Descargue el archivo de instalación de Steam.<br>', $description);

	$description = preg_replace('/a\) steamprogramme scaricar(.+)<br>/',
							 'a) Scaricare il file di installazione di Steam.<br>', $description);


	$contact_de = 'Wenn Sie Fragen, Anregungen oder unsere Unterstützung bei der Aktivierung brauchen, können Sie uns jederzeit kontaktieren. In der Regel werden die Anfragen innerhalb eines Tages bearbeitet.';

	$contact_en = 'If you have any questions, suggestions or support, please do not hesitate to contact us. As a rule, the inquiries are processed within one day.';

	$contact_fr = "Si vous avez des questions, des suggestions ou des conseils, n'hésitez pas à nous contacter. En règle générale, les enquêtes sont traitées dans un jour.";

	$contact_es = 'Si tiene alguna pregunta, sugerencia o apoyo, no dude en ponerse en contacto con nosotros. Por regla general, las consultas se procesan en un día.';

	$contact_it = 'Se hai domande, suggerimenti o assistenza, non esitate a contattarci. Di norma, le richieste vengono elaborate entro un giorno.';

	change_contact($description, $contact_de, 'de');
	change_contact($description, $contact_en, 'en');
	change_contact($description, $contact_fr, 'fr');
	change_contact($description, $contact_es, 'es');
	change_contact($description, $contact_it, 'it');


	sa(strlen($description));
	sa(htmlspecialchars($description));


return;
// $jpegs = json_decode(file_get_contents('csv/jpegs.json'), true);

// sa($jpegs);

// return;
function get_filess($dir='.')
{
	$files = [];
	if ($handle = opendir($dir)){
		while(false !== ($item = readdir($handle))){
			$fpath = "$dir/$item";
			if (is_file($fpath)) {
				if (stripos($fpath, '.jpg') !== false) {
					$files[] = 'http://hot-body.net/Produktbilder_JPG/'.str_replace('E:\xamp\htdocs\parser\www\ignore\Produktbilder_NEU/', '', $fpath);
				}
			}elseif (is_dir($fpath) && ($item != ".") && ($item != "..")){
				$files = array_merge($files, get_filess($fpath));
			}
		}
		closedir($handle);
	}
	return $files; 
}

$arr = get_filess('E:\xamp\htdocs\parser\www\ignore\Produktbilder_NEU');

file_put_contents('csv/jpegs.json', json_encode($arr));

sa($arr);



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
function get_filesss($dir='.')
{
	$files = [];
	if ($handle = opendir($dir)){
		while(false !== ($item = readdir($handle))){
			$fpath = "$dir/$item";
			if (is_file($fpath)) {
				if (stripos($fpath, '.jpg') !== false) {
					$files[] = str_replace('E:\xamp\htdocs\parser\www\ignore\Produktbilder_NEU/', '', $fpath);
				}
			}elseif (is_dir($fpath) && ($item != ".") && ($item != "..")){
				$files = array_merge($files, get_filess($fpath));
			}
		}
		closedir($handle);
	}
	return $files; 
}

$arr = get_filesss('E:\xamp\htdocs\parser\www\ignore\Produktbilder_NEU');

// sa($arr);

$xcel = readExcel('csv/eBayArtikel.xlsx');

$res = [];
foreach ($xcel as $row => $cell) {
	$img = $cell['C'] . '_' . $cell['D'];
	$img = str_replace('http://www.cdvet.de/media/image/', '', $img);
	$perc1 = 0;
	$closest ='';
	foreach ($arr as $file) {
		similar_text($file, $img, $perc2);
		if ($perc1 < $perc2) {
			$perc1 = $perc2;
			$closest = $file;
		}
	}
	if ($perc1 > 50) {
		$res[$row][] = $perc1;
		$res[$row][] = $img;
		$res[$row][] = $closest;
	}
}

file_put_contents('csv/matches.json', json_encode($res));

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