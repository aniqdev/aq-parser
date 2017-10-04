<?php

$offset = _esc($_POST['offset']);

$count = arrayDB("SELECT count(*) FROM games WHERE steam_link <> '' AND old_ebay_id <> ''")[0]['count(*)'];

$game = arrayDB("SELECT * FROM games WHERE steam_link <> '' AND old_ebay_id <> '' LIMIT $offset , 1");

if ($game[0]['ebay_id']) {
	$added = ['success' => 0, 'resp' => 'item exists!'];
}else{
	$added = ajax_recovery_item($game[0]['old_ebay_id'], $game[0]['steam_link']);
}



echo json_encode([
		'status' => 'success',
		'offset' => $offset,
		'count' => $count,
		'added' => $added,
		'steam_link' => $game[0]['steam_link'],
		'errors' => $_ERRORS,
	]);

?>