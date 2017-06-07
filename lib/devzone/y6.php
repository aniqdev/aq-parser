<?php
ini_get('safe_mode') or set_time_limit(4000); // Указываем скрипту, чтобы не обрывал связь.

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