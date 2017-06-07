<?php
ini_get('safe_mode') or set_time_limit(850); // Указываем скрипту, чтобы не обрывал связь.


$games = arrayDB("SELECT item_id,title from ebay_games");
$scan = time();

foreach ($games as $key => $game) {
// break;
	$ebay_id = $game['item_id'];
	$title = $game['title'];
	$check = arrayDB("SELECT id FROM ebay_data WHERE images = '' AND ebay_id = '$ebay_id'");
	if($key < 0 || $key > 4000 || !$check) continue;

	$check2 = arrayDB("SELECT * FROM games WHERE ebay_id = '$ebay_id'");
	if($check2 && $check2['extra_field'] === 'desc2017may') continue;

	echo '<hr><b>'.$key.'</b><br> <a href="http://www.ebay.de/itm/'.$ebay_id.'" target="_blank">'.$title.'</a><br>';

	$res = getSingleItem($ebay_id,['as_array'=>true,'IncludeSelector'=>'Description']);
	if($res['Ack'] !== 'Success') continue;

	$full_desc = $res['Item']['Description'];

	//sa($res['Item']['Title']);

	$dom = str_get_html($full_desc);
	if(!$dom){
		echo '<h3>no dom. continued!</h3>';
		continue;
	} 

	$game_data = [];
	$images = [];

	$game_data['title'] = $res['Item']['Title'];
	$game_data['desc_title'] = ($tit = @$dom->find('.gig-tittle',0)) ? $tit->find('h2',0)->plaintext: '';
	$game_data['gig-img3d'] = @$dom->find('.gig-img3d',0)->src;

	if($img1 = @$dom->find('.gig-img1',0)->src) $images[] = $img1;
	elseif($img1 = @$dom->find('[src$="/1.jpg"]',0)->src) $images[] = $img1;

	if($img2 = @$dom->find('.gig-img2',0)->src) $images[] = $img2;
	elseif($img1 = @$dom->find('[src$="/2.jpg"]',0)->src) $images[] = $img1;

	if($img3 = @$dom->find('.gig-img3',0)->src) $images[] = $img3;
	elseif($img1 = @$dom->find('[src$="/3.jpg"]',0)->src) $images[] = $img1;

	$game_data['gig-about'] = @$dom->find('.gig-about',0)->innertext;

	$ebay_id = _esc($ebay_id);
	$title = _esc($game_data['title']);
	$desc_title = _esc($game_data['desc_title']);
	$img3d = _esc($game_data['gig-img3d']);
	$images = _esc(implode(',', $images));
	$full_desc = _esc($full_desc);
	$game_desc = _esc($game_data['gig-about']);
 
	arrayDB("INSERT INTO ebay_data 
		(ebay_id,
		title,
		desc_title,
		img3d,
		images,
		full_desc,
		game_desc,
		scan)
		VALUES
		('$ebay_id',
		'$title',
		'$desc_title',
		'$img3d',
		'$images',
		'$full_desc',
		'$game_desc',
		'$scan')");
}




?>