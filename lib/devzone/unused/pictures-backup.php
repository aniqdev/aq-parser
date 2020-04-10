<pre><?php
ini_get('safe_mode') or set_time_limit(999); // Указываем скрипту, чтобы не обрывал связь.

function change_ebay_pictre_v2($img_path, $save_to, $orient){

	$imagine = new Imagine\Gd\Imagine();
	if ($orient === 'ver') {
		$ramka = $imagine->open('images/pictrure_v_ramoc.png');
	}elseif ($orient === 'hor') {
		$ramka = $imagine->open('images/pictrure_h_ramoc.png');
	}else{
		return;
	}
	$image = $imagine->open($img_path);
	//$image->resize(new Imagine\Image\Box(442, 215));
	$point = new Imagine\Image\Point(0, 0);
	$image->paste($ramka, $point);
	return $image->save($save_to, ['jpeg_quality' => 100]);

	//return '<img src="images/test2.jpg">';

} // change_ebay_pictre_v1()

$arr = arrayDB("SELECT item_id from ebay_games");

// foreach ($arr as $item) {

// 	echo "<hr>";
// 	var_dump($item['item_id']);
// 	$url_of_real_img = 'http://i.ebayimg.com/images/g/'.$item['picture_hash'].'/s-l500.jpg';

// 	$is_copied = copy($url_of_real_img, 'E:\xamp\htdocs\info-rim.ru\www/ebay-pictures/21jan_'.$item['item_id'].'.jpg');
// 	var_dump($is_copied);
// }

$i = 1;
// foreach ($arr as $k => $item){

// 	//if($k > 5) break;
// 	$item_id = $item['item_id'];
// 	$img_path_ver = 'E:\xamp\htdocs\info-rim.ru\www/ebay-pictures-vert/21jan_'.$item_id.'.jpg';
// 	if(file_exists($img_path_ver)) continue;
// 	$img_path_hor = 'E:\xamp\htdocs\info-rim.ru\www/ebay-pictures/21jan_'.$item_id.'.jpg';
// 	$save_to = 'E:\xamp\htdocs\info-rim.ru\www/ebay-pictures-new-hor/21jan_'.$item_id.'.jpg';
// 	if(!file_exists($img_path_hor)) continue;
// 	change_ebay_pictre_v2($img_path_hor, $save_to);
// 	var_dump($i++);
// 	if(!file_exists($save_to)) echo $item_id.'<hr>';

// }


$item_id = '122325326998';
$img_path_ver = 'E:\xamp\htdocs\info-rim.ru\www/ebay-pictures-vert/21jan_'.$item_id.'.jpg';
$img_path_hor = 'E:\xamp\htdocs\info-rim.ru\www/ebay-pictures/21jan_'.$item_id.'.jpg';
if (file_exists($img_path_ver)) {
	$img_path = $img_path_ver;
	$save_to = 'E:\xamp\htdocs\info-rim.ru\www/ebay-pictures-new/21jan_'.$item_id.'.jpg';
	$orient = 'ver';
}elseif (file_exists($img_path_hor)) {
	$img_path = $img_path_hor;
	$save_to = 'E:\xamp\htdocs\info-rim.ru\www/ebay-pictures-new-hor/21jan_'.$item_id.'.jpg';
	$orient = 'hor';
}else{
	die('нет сохраненного изображения');
}
change_ebay_pictre_v2($img_path_ver, $save_to, $orient);
if(!file_exists($save_to)) echo $item_id.'<hr>';



?></pre>