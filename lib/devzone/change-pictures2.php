<pre><?php
ini_get('safe_mode') or set_time_limit(2500); // Указываем скрипту, чтобы не обрывал связь.

function get_url_of_real_img($item_id){
	$res = Ebay_shopping2::getSingleItem($item_id, JSON_OBJECT_AS_ARRAY);

	if($res['Ack'] === 'Failure'){ echo 'Failure'; return 0; }

	$PictureURL = $res['Item']['PictureURL'][0];

	$found = preg_match('#/[^s]/(.+)/#', $PictureURL, $matches);

	if(!$found) return 0;

	$url_of_real_img = 'http://i.ebayimg.com/images/g/'.$matches[1].'/s-l500.jpg';

	return $url_of_real_img;
}



?></pre>


<?

function change_ebay_pictre_v1($img_path){

	$imagine = new Imagine\Gd\Imagine();
	$ramka = $imagine->open('images/pictrure_v_ramo.png');
	$image = $imagine->open($img_path);
	//$image->resize(new Imagine\Image\Box(442, 215));
	$point = new Imagine\Image\Point(0, 0);
	$image->paste($ramka, $point);
	$image->save('images/test2.jpg', ['jpeg_quality' => 100]);

	return '<img src="images/test2.jpg">';

} // change_ebay_pictre_v1()


$item_id = '121717310159';

$steam_link = 'http://store.steampowered.com/app/249650/';

$img_path = get_url_of_real_img($item_id);

//echo change_ebay_pictre_v1($img_path);


function get_steam_images($steam_link = ''){

	if(!$steam_link) return [];
	$options = array('http' => array('method' => "GET", 'header' => "Accept-language: de\r\n" . "Cookie: Steam_Language=german; mature_content=1; birthtime=238921201; lastagecheckage=28-July-1977\r\n"));
	$context = stream_context_create($options);
	$dom = file_get_html($steam_link, false, $context);

	$srcs = [];
	foreach ($dom->find('a[href*=1920x1080]') as $k => $img) {
		$src = $img->getAttribute('href');
		$src = parse_url( $src, PHP_URL_QUERY );
		$src = str_replace(['url=','!1920x1080'], ['','600x338'], $src);
		//echo '<img src="',$src,'">';
		$srcs[] = $src;
		if($k > 3) break;
	}
	return $srcs;
}


function get_alt_steam_link($steam_link=''){
	
	if(!$steam_link) return [];
	$includes = arrayDB("SELECT includes from steam where link = '$steam_link'");
	if(!isset($includes[0]['includes'])) return [];
	$app_id = explode(',', $includes[0]['includes'])[0];
	if(!$app_id) return [];
	$app = arrayDB("SELECT link from steam where link = 'http://store.steampowered.com/app/".$app_id."/'");
	if(!$app) return [];;
	$steam_link = $app[0]['link'];
	return get_steam_images($steam_link);
}

//print_r($srcs = get_steam_images($steam_link));
// foreach ($srcs as $src) {
// 	echo '<img src="',$src,'"><br>';
// }

$ebayObj = new Ebay_shopping2();
// $res = $ebayObj->updateItemPictureDetails($item_id, ['http://i.ebayimg.com/images/g/kEQAAOSwPcVVuexY/s-l500.jpg']);
// unset($res['Fees']);
// print_r($res);


$excel_list = readExcel('csv/ebay_steam1.xlsx', 0);
//print_r($excel_list);
foreach ($excel_list as $row => $col) {
	break;
	//if(!in_array($row, [6,7])) continue;
	if($row < 901 || $row > 1200) continue;
	echo '<hr><b>row = ',$row,'</b><br>';
	$steam_link = $col['F'];
	if(!$steam_link) {echo "<i>! в excel нет ссылки</i>";}
	echo '<div>===> <a href="',$col['C'],'" target="_blank">',$col['D'],'</a></div>';
	echo '<div>===> <a href="',$col['F'],'" target="_blank">',$col['F'],'</a></div>';

	$item_id = $col['B'];

	$main_img_file = 'E:\xamp\htdocs\info-rim.ru\www/ebay-pictures/21jan_'.$item_id.'.jpg';
	$main_img_url = 'http://hot-body.net/ebay-images/ebay-pictures/21jan_'.$item_id.'.jpg';

	if(!file_exists($main_img_file)) {echo "<i>! нет старого файла: </i>".$main_img_file; continue;}

	$new_img_file_hor = 'E:\xamp\htdocs\info-rim.ru\www/ebay-pictures-new-hor/21jan_'.$item_id.'.jpg';
	$new_img_file_ver = 'E:\xamp\htdocs\info-rim.ru\www/ebay-pictures-new/21jan_'.$item_id.'.jpg';

	if(file_exists($new_img_file_hor)){
		$new_img_url = 'http://hot-body.net/ebay-images/ebay-pictures-new-hor/21jan_'.$item_id.'.jpg';
	}elseif (file_exists($new_img_file_ver)) {
		$new_img_url = 'http://hot-body.net/ebay-images/ebay-pictures-new/21jan_'.$item_id.'.jpg';
	}else{echo "<i>! нет нового файла: </i>".$main_img_file; continue;}

	$srcs = get_steam_images($steam_link);

	if(!$srcs) {
		$srcs = get_alt_steam_link($steam_link);
	}

	if(!$srcs) {echo "<i>! нет картинок в стим! $steam_link </i>";}

	$output = '<pre>'.print_r($srcs,1).'</pre>';

	array_unshift($srcs, $main_img_url);

	array_unshift($srcs, $new_img_url);

	$res = $ebayObj->updateItemPictureDetails($item_id, $srcs);
	if(!isset($res['Ack']) || $res['Ack'] !== 'Success'){
		$res = $ebayObj->updateItemPictureDetails($item_id, $srcs);
	}
	if(!isset($res['Ack']) || $res['Ack'] !== 'Success'){
		$res = $ebayObj->updateItemPictureDetails($item_id, $srcs);
	}
	if (!isset($res['Ack']) || $res['Ack'] !== 'Success') {
		$file = 'E:\xamp\htdocs\info-rim.ru\www\Fails.txt';
		file_put_contents($file, $item_id.PHP_EOL, FILE_APPEND | LOCK_EX);
	}
	unset($res['Fees']);
	echo $output.'<pre>'.print_r($res,1).'</pre>';
}


?>