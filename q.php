<?php
header('Content-Type: application/json');
require_once 'vendor/autoload.php';
require_once 'lib/PHPExcel.php';
require_once 'lib/simple_html_dom.php';
require_once 'lib/array_DB.php';
define('ROOT', __DIR__);

$q = _esc($_GET['term']);

$res = arrayDB("SELECT title,link FROM steam WHERE title LIKE '%$q%' LIMIT 10");


$ret = ['query'=>$_GET['term']];

foreach ($res as $val) {
	$suggestions[] = $val['title'];
	$data[] = $val['link'];
}
$ret['suggestions'] = $suggestions;
$ret['data'] = $data;



// $ret = [];
// foreach ($res as $val) {
// 	$ret[] = $val['title'];
// }

echo json_encode($ret);

?>