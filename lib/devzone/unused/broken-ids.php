<?php
ini_get('safe_mode') or set_time_limit(1300); // Указываем скрипту, чтобы не обрывал связь.


$ids = arrayDB("SELECT id,name,ebay_id,extra_field from games");

foreach ($ids as $key => $string) {
	if (!$string['ebay_id']) {
		continue;
	}
	// $id = $string['id'];
	// if (!$string['extra_field']) {
	// 	$res = (new Ebay_shopping2)->getSingleItem($string['ebay_id']);
	// 	$res = _esc($res);
	// 	arrayDB("UPDATE games SET extra_field='$res' WHERE id='$id'");
	// }

	$itemArr = json_decode($string['extra_field'], true);
	echo '<i>',$itemArr['Ack'],'</i><br>';
	echo '<b>',$string['ebay_id'],'</b><br>';
	if ($itemArr['Ack'] !== 'Success') {
		echo '<a href="http://www.ebay.de/itm/',$string['ebay_id'],'" target="_blank">',$string['name'],'</a>';
		echo '<pre>',print_r($itemArr,true),'</pre>';
	}else{
		echo '<h3>',$itemArr['Item']['ListingStatus'],'</h3>';
		echo "<br>";
		echo '<a href="http://www.ebay.de/itm/',$string['ebay_id'],'" target="_blank">',$itemArr['Item']['Title'],'</a>';
		echo '<br><img src="',$itemArr['Item']['GalleryURL'],'">';
	}
	echo "<hr>";
	// if ($key > 100) {
	// 	break;
	// }
	$id = $string['id'];
	if ($itemArr['Ack'] !== 'Success' || $itemArr['Item']['ListingStatus'] === 'Completed') {
		arrayDB("UPDATE games SET ebay_id=null WHERE id='$id'");
	}
}




//$('#w1-3-_msg')



?>