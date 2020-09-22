<?php

$settings = get_settins_by_category('panels2017');

sa($settings);

for ($i=11; $i < 17; $i++) { 
	if (isset($settings['block'.$i.'_id']) && $settings['block'.$i.'_id']) {
		$block_res = Ebay_shopping2::getSingleItem($settings['block'.$i.'_id'],1);
		$block_price = $block_res['Item']['CurrentPrice']['Value'];
		$settings['block'.$i.'_price'] = $block_price;
	}
}

for ($i=21; $i < 27; $i++) { 
	if (isset($settings['block'.$i.'_id']) && $settings['block'.$i.'_id']) {
		$block_res = Ebay_shopping2::getSingleItem($settings['block'.$i.'_id'],1);
		$block_price = $block_res['Item']['CurrentPrice']['Value'];
		$settings['block'.$i.'_price'] = $block_price;
	}
}

set_settings_to_category('panels2017', $settings);



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
	$settings['block11_title'], $settings['block12_title'], $settings['block13_title'],
	$settings['block14_title'], $settings['block15_title'], $settings['block16_title'],
	number_format($settings['block11_price'],2), number_format($settings['block12_price'],2), number_format($settings['block13_price'],2),
	number_format($settings['block14_price'],2), number_format($settings['block15_price'],2), number_format($settings['block16_price'],2),
	pu($settings['block11_image']), pu($settings['block12_image']), pu($settings['block13_image']),
	pu($settings['block14_image']), pu($settings['block15_image']), pu($settings['block16_image']),
];

$proto_css = str_replace($replace_in, $replace_out, $proto_css);

$replace_in = [
	'{{TITLE21}}',	'{{TITLE22}}',	'{{TITLE23}}',
	'{{TITLE24}}',	'{{TITLE25}}',	'{{TITLE26}}',
	'{{PRICE21}}',	'{{PRICE22}}',	'{{PRICE23}}',
	'{{PRICE24}}',	'{{PRICE25}}',	'{{PRICE26}}',
	'{{IMAGE21}}',	'{{IMAGE22}}',	'{{IMAGE23}}',
	'{{IMAGE24}}',	'{{IMAGE25}}',	'{{IMAGE26}}',
];

$replace_out = [
	$settings['block21_title'], $settings['block22_title'], $settings['block23_title'],
	$settings['block24_title'], $settings['block25_title'], $settings['block26_title'],
	number_format($settings['block21_price'],2), number_format($settings['block22_price'],2), number_format($settings['block23_price'],2),
	number_format($settings['block24_price'],2), number_format($settings['block25_price'],2), number_format($settings['block26_price'],2),
	pu($settings['block21_image']), pu($settings['block22_image']), pu($settings['block23_image']),
	pu($settings['block24_image']), pu($settings['block25_image']), pu($settings['block26_image']),
];

$proto_css = str_replace($replace_in, $replace_out, $proto_css);

sa($proto_css);

file_put_contents(ROOT.'/css/extra-style-2017.css', $proto_css);

?>