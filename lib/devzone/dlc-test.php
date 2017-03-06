<pre>
<?php

$url0 = 'http://store.steampowered.com/search/?sort_by=Released_DESC&tags=-1&category1=998,996';
$url1 = 'http://store.steampowered.com/app/390960/?snr=321';
$url2 = 'http://store.steampowered.com/app/443730/';
$url3 = 'http://store.steampowered.com/sub/45320/';

// function is_dlc($url)
// {
// 	$dom = file_get_html($url);
// 	$tags_arr = [];
// 	$arr = ($re = $dom->find('.glance_tags',0))?$re->find('a.app_tag'):[];
// 	foreach ($arr as $mwqhg) $tags_arr[] = trim($mwqhg->plaintext);
// 	$tags = implode(',', $tags_arr);
// 	echo "<HR>";
// 	return $tags;
// 	//return !!$dom->find('.glance_details');
// }

// print_r(explode('/', $url1));
// echo "<HR>";

// var_dump('0000-00-00 00:00:00' == 0);

// var_dump(is_dlc($url1));
// var_dump(is_dlc($url2));
// var_dump(is_dlc($url3));


//$game_item = file_get_html('http://store.steampowered.com/app/443730/');
$game_item = file_get_html('http://store.steampowered.com/app/266840/');


$reviews = $game_item->find('div[data-store-tooltip]',0);

$overall_rating = ''; $overall_reviews = '';
if ($reviews2 = $game_item->find('div[data-store-tooltip]',1)) {
	
	$reviews = $reviews ? $reviews->attr['data-store-tooltip'] : '';
	$reviews = str_replace('30', '', $reviews);
	if (preg_match_all("/[\d]+/", $reviews, $matches)) {
	    if (isset($matches[0][2])) {
	        $matches[0][1] = $matches[0][1].$matches[0][2]; }
	    $recent_rating = $matches[0][0];
	    $recent_reviews = $matches[0][1];
	}
	
	$reviews2 = $reviews2 ? $reviews2->attr['data-store-tooltip'] : '';
	if (preg_match_all("/[\d]+/", $reviews2, $matches)) {
	    if (isset($matches[0][2])) {
	        $matches[0][1] = $matches[0][1].$matches[0][2]; }
	    $overall_rating = $matches[0][0];
	    $overall_reviews = $matches[0][1];
	}
}else{
	$reviews = $reviews ? $reviews->attr['data-store-tooltip'] : '';
	if (preg_match_all("/[\d]+/", $reviews, $matches)) {
	    if (isset($matches[0][2])) {
	        $matches[0][1] = $matches[0][1].$matches[0][2]; }
	    $overall_rating = $matches[0][0];
	    $overall_reviews = $matches[0][1];
	    $recent_rating = '';
	    $recent_reviews = '';
	}
}
	    var_dump($recent_rating);
	    var_dump($recent_reviews);
	    var_dump($overall_rating );
	    var_dump($overall_reviews);
//         $details_specs = [];
//         var_dump(count($game_item->find('img[src*=ratings]')));
//         foreach ($game_item->find('img[src*=ratings]') as $dfbhet) $details_specs[] = $dfbhet->src;
//         //$details_specs = implode(',', $details_specs);
//         print_r($details_specs);


// $apptags = $game_item->find('a.app_tag');
// $apptags = $game_item->find("a.[href*='genre']");


// $details_block = $game_item->find('.details_block',0);
// if($details_block) $genres_links = $details_block->find("a.[href*='genre']");
// else $genres_links = [];
// foreach ($genres_links as $wreds) $genres_arr[] = trim($wreds->plaintext);
// $genres_str = implode(',', $genres_arr);

	// var_dump($genres_str);


//var_dump(AutomaticBot::sendMessage(['text' => 'Статик2']));


// $packages = [];
// $game_wrappers = $game_item->find('.game_area_purchase_game_wrapper');
// foreach ($game_wrappers as $game_wrapper) {
// 	$texts = $game_wrapper->find('text');
// 	foreach ($texts as $text) {
// 		if ($text->plaintext === 'Package info') {
// 			$s = [];
// 			$s['price'] = trim($game_wrapper->find('.game_purchase_price',0)->plaintext);
// 			$s['title'] = trim($game_wrapper->find('h1',0)->plaintext);
// 			$s['link'] = trim($game_wrapper->find('a[href*=sub]',0)->href);
// 			$packages[] = $s;
// 			echo "<HR>";
// 		}
// 	}
// }
// print_r($packages);

//var_dump(clean_url_from_query('http://store.steampowered.com/app/575550/?snr=1_7_7_230_150_1'));

?>
</pre>