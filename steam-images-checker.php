<?php


$app_sub = @$_GET['app_sub'];
$app_id = @$_GET['app_id'];

if(!$app_id){
	echo json_encode(['success' => false]);
	die;
}

$folder = scandir(__DIR__.'/steam-images/'.$app_sub.'s-'.$app_id);

unset($folder[0]);
unset($folder[1]);

echo json_encode($folder);


?>