<?php
header('Content-Type: application/json');

// пример http://parser.gig-games.de/steam-images-checker.php?app_id=375850&app_sub=app

$app_sub = @$_GET['app_sub'];
$app_id = @$_GET['app_id'];

$dir_path = __DIR__.'/steam-images/'.$app_sub.'s-'.$app_id;

if(!$app_id || !is_dir($dir_path)){
	echo json_encode([]);
	die;
}

$folder = scandir($dir_path);

unset($folder[0]);
unset($folder[1]);

echo json_encode($folder);


?>