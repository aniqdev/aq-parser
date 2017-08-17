<?php






$ebay_obj = new Ebay_shopping2();

$steam_id = '348540';

$urls = [
	'http://hot-body.net/img-generator/folders/v'.$steam_id.'/ramka.jpg',
	'http://hot-body.net/img-generator/folders/v'.$steam_id.'/1.jpg',
	'http://hot-body.net/img-generator/folders/v'.$steam_id.'/2.jpg',
	'http://hot-body.net/img-generator/folders/v'.$steam_id.'/3.jpg',
	'http://hot-body.net/img-generator/folders/v'.$steam_id.'/4.jpg',
];

sa($urls);

$add = $ebay_obj->updateItemPictureDetails('122580528510', $urls);

sa($add);



?>
