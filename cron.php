<?php
// header('Content-Type: application/json');
require_once 'vendor/autoload.php';
require_once 'lib/PHPExcel.php';
require_once 'lib/simple_html_dom.php';
require_once 'lib/array_DB.php';
define('ROOT', __DIR__);

if (isset($_GET['action'])) {
	$toFile = 'lib/'.$_GET['action'].'.php';
	if (file_exists($toFile)) include_once($toFile);
	else echo '{"error":"404"}';
} else {
	echo '{"error":"404"}';
}
