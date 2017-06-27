<?php






$ebay_obj = new Ebay_shopping2();

$urls = [
	'http://hot-body.net/img-generator/folders/v418640/ramka.jpg',
	'http://hot-body.net/img-generator/folders/v418640/1.jpg',
	'http://hot-body.net/img-generator/folders/v418640/2.jpg',
	'http://hot-body.net/img-generator/folders/v418640/3.jpg',
];

$add = $ebay_obj->updateItemPictureDetails('122492214136', $urls);

sa($add);



?>
