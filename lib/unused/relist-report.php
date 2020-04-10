<?php



$_GET['limit'] = @$_GET['limit'] ? $_GET['limit'] : 50; // типо насройка лимита по умолчанию
$limit = aqs_pagination('relist_report');

$res = arrayDB("SELECT * from relist_report ORDER BY id DESC LIMIT $limit");

foreach ($res as $value) {
	echo '<hr><a href="https://ebay.de/itm/'.$value['new_ebay_id'].'" target="_blank">'.$value['title'].'</a> <b>'.$value['plattform'].'</b>';
	sa(json_decode($value['response_json'],true));
}

?>