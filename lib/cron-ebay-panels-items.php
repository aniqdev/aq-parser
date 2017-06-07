<?php


$one_week_res = arrayDB("SELECT tt.*, ebay_games.title_clean, ebay_games.picture_hash FROM (select title,price,ebay_id,shipped_time,count(*) as count from ebay_order_items group by ebay_id) tt
JOIN ebay_games
ON tt.ebay_id = ebay_games.item_id
WHERE picture_hash <> '' AND shipped_time > NOW() - INTERVAL 7 DAY
order by count desc
limit 10");

$two_week_res = arrayDB("SELECT tt.*, ebay_games.title_clean, ebay_games.picture_hash FROM (select title,price,ebay_id,shipped_time,count(*) as count from ebay_order_items group by ebay_id) tt
JOIN ebay_games
ON tt.ebay_id = ebay_games.item_id
WHERE picture_hash <> '' AND shipped_time > NOW() - INTERVAL 14 DAY
order by count desc
limit 10");

$one_month_res = arrayDB("SELECT tt.*, ebay_games.title_clean, ebay_games.picture_hash FROM (select title,price,ebay_id,shipped_time,count(*) as count from ebay_order_items group by ebay_id) tt
JOIN ebay_games
ON tt.ebay_id = ebay_games.item_id
WHERE picture_hash <> '' AND shipped_time > NOW() - INTERVAL 1 MONTH
order by count desc
limit 10");

$top_items = [];


foreach ($one_month_res as $key => $value) {
	if (!in_multi_array($value['ebay_id'], $top_items)) $top_items[] = $value;
	if (count($top_items) > 1) break;
}

foreach ($two_week_res as $key => $value) {
	if (!in_multi_array($value['ebay_id'], $top_items)) $top_items[] = $value;
	if (count($top_items) > 3) break;
}

foreach ($one_week_res as $key => $value) {
	if (!in_multi_array($value['ebay_id'], $top_items)) $top_items[] = $value;
	if (count($top_items) > 5) break;
}

if (count($top_items) < 6) die;

// sa($top_items);

function pu($hash)
{
	return 'http://i.ebayimg.com/thumbs/images/g/'.$hash.'/s-l225.jpg';
}

$proto_css = ROOT.'/lib/adds/extra-style-2017-prototype.css';

$proto_css = file_get_contents($proto_css);

$replace_in = [
	'{{TITLE11}}',	'{{TITLE12}}',	'{{TITLE13}}',
	'{{TITLE14}}',	'{{TITLE15}}',	'{{TITLE16}}',
	'{{PRICE11}}',	'{{PRICE12}}',	'{{PRICE13}}',
	'{{PRICE14}}',	'{{PRICE15}}',	'{{PRICE16}}',
	'{{IMAGE11}}',	'{{IMAGE12}}',	'{{IMAGE13}}',
	'{{IMAGE14}}',	'{{IMAGE15}}',	'{{IMAGE16}}',
];

$replace_out = [
	$top_items[0]['title_clean'], $top_items[1]['title_clean'], $top_items[2]['title_clean'],
	$top_items[3]['title_clean'], $top_items[4]['title_clean'], $top_items[5]['title_clean'],
	$top_items[0]['price'], $top_items[1]['price'], $top_items[2]['price'],
	$top_items[3]['price'], $top_items[4]['price'], $top_items[5]['price'],
	pu($top_items[0]['picture_hash']), pu($top_items[1]['picture_hash']), pu($top_items[2]['picture_hash']),
	pu($top_items[3]['picture_hash']), pu($top_items[4]['picture_hash']), pu($top_items[5]['picture_hash']),
];

$proto_css = str_replace($replace_in, $replace_out, $proto_css);


$top12 = arrayDB("SELECT tt.*, ebay_games.title_clean, ebay_games.picture_hash FROM (select title,price,ebay_id,count(*) as count from ebay_order_items group by ebay_id) tt
JOIN ebay_games
ON tt.ebay_id = ebay_games.item_id
WHERE picture_hash <> ''
order by count desc
limit 12");

$bottom_items = [];

foreach ($top12 as $key => $value) {
	if (!in_multi_array($value['ebay_id'], $bottom_items) && 
		!in_multi_array($value['ebay_id'], $top_items) &&
		!is_eve($value['ebay_id'])) $bottom_items[] = $value;
	if (count($bottom_items) > 5) break;
}

if (count($bottom_items) < 4) die;

$replace_in = [
	'{{TITLE21}}',	'{{TITLE22}}',	'{{TITLE23}}',
	'{{TITLE24}}',	'{{TITLE25}}',	'{{TITLE26}}',
	'{{PRICE21}}',	'{{PRICE22}}',	'{{PRICE23}}',
	'{{PRICE24}}',	'{{PRICE25}}',	'{{PRICE26}}',
	'{{IMAGE21}}',	'{{IMAGE22}}',	'{{IMAGE23}}',
	'{{IMAGE24}}',	'{{IMAGE25}}',	'{{IMAGE26}}',
];

$replace_out = [
	$bottom_items[0]['title_clean'], $bottom_items[1]['title_clean'], $bottom_items[2]['title_clean'],
	$bottom_items[3]['title_clean'], $bottom_items[4]['title_clean'], $bottom_items[5]['title_clean'],
	$bottom_items[0]['price'], $bottom_items[1]['price'], $bottom_items[2]['price'],
	$bottom_items[3]['price'], $bottom_items[4]['price'], $bottom_items[5]['price'],
	pu($bottom_items[0]['picture_hash']), pu($bottom_items[1]['picture_hash']), pu($bottom_items[2]['picture_hash']),
	pu($bottom_items[3]['picture_hash']), pu($bottom_items[4]['picture_hash']), pu($bottom_items[5]['picture_hash']),
];

$proto_css = str_replace($replace_in, $replace_out, $proto_css);

sa($proto_css);

file_put_contents(ROOT.'/css/extra-style-2017.css', $proto_css);

$settings = [
	'block11_id' => $top_items[0]['ebay_id'],
	'block11_title' => $top_items[0]['title_clean'],
	'block11_price' => $top_items[0]['price'],
	'block11_image' => $top_items[0]['picture_hash'],

	'block12_id' => $top_items[1]['ebay_id'],
	'block12_title' => $top_items[1]['title_clean'],
	'block12_price' => $top_items[1]['price'],
	'block12_image' => $top_items[1]['picture_hash'],

	'block13_id' => $top_items[2]['ebay_id'],
	'block13_title' => $top_items[2]['title_clean'],
	'block13_price' => $top_items[2]['price'],
	'block13_image' => $top_items[2]['picture_hash'],

	'block14_id' => $top_items[3]['ebay_id'],
	'block14_title' => $top_items[3]['title_clean'],
	'block14_price' => $top_items[3]['price'],
	'block14_image' => $top_items[3]['picture_hash'],

	'block15_id' => $top_items[4]['ebay_id'],
	'block15_title' => $top_items[4]['title_clean'],
	'block15_price' => $top_items[4]['price'],
	'block15_image' => $top_items[4]['picture_hash'],

	'block16_id' => $top_items[5]['ebay_id'],
	'block16_title' => $top_items[5]['title_clean'],
	'block16_price' => $top_items[5]['price'],
	'block16_image' => $top_items[5]['picture_hash'],


	'block21_id' => $bottom_items[0]['ebay_id'],
	'block21_title' => $bottom_items[0]['title_clean'],
	'block21_price' => $bottom_items[0]['price'],
	'block21_image' => $bottom_items[0]['picture_hash'],

	'block22_id' => $bottom_items[1]['ebay_id'],
	'block22_title' => $bottom_items[1]['title_clean'],
	'block22_price' => $bottom_items[1]['price'],
	'block22_image' => $bottom_items[1]['picture_hash'],

	'block23_id' => $bottom_items[2]['ebay_id'],
	'block23_title' => $bottom_items[2]['title_clean'],
	'block23_price' => $bottom_items[2]['price'],
	'block23_image' => $bottom_items[2]['picture_hash'],

	'block24_id' => $bottom_items[3]['ebay_id'],
	'block24_title' => $bottom_items[3]['title_clean'],
	'block24_price' => $bottom_items[3]['price'],
	'block24_image' => $bottom_items[3]['picture_hash'],

	'block25_id' => $bottom_items[4]['ebay_id'],
	'block25_title' => $bottom_items[4]['title_clean'],
	'block25_price' => $bottom_items[4]['price'],
	'block25_image' => $bottom_items[4]['picture_hash'],

	'block26_id' => $bottom_items[5]['ebay_id'],
	'block26_title' => $bottom_items[5]['title_clean'],
	'block26_price' => $bottom_items[5]['price'],
	'block26_image' => $bottom_items[5]['picture_hash'],
];

set_settings_to_category('panels2017', $settings);

?>