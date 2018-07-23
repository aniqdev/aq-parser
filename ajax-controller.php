<?php
header('Access-Control-Allow-Origin: *');
require_once 'vendor/autoload.php';
require_once 'lib/PHPExcel.php';
require_once 'lib/simple_html_dom.php';
require_once 'lib/array_DB.php';
define('ROOT', __DIR__);

if (isset($_POST['function']) && $_POST['function'] && function_exists($_POST['function'])) {
	echo $_POST['function']();
}else{
	echo '{"error":"404"}';
}