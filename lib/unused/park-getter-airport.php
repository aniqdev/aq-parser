<?php
if (!isset($_GET['park']) && !isset($_REQUEST['_/parser/lib/park-getter-airport_php'])) die('Oops!');
	header('Content-Type: text/html; charset=utf-8');
//ini_get('safe_mode') or set_time_limit(0); // Указываем скрипту, чтобы не обрывал связь.
//include('simple_html_dom.php');
//include('PHPExcel.php');
require_once('array_DB.php');
require_once('simple_html_dom.php');


$scan = (int)time();
$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded'
        //'content' => $postdata
    )
);
$context  = stream_context_create($opts);
$result = file_get_html('http://www.koeln-bonn-airport.de/parken-anreise/parken.html', false, $context);

$resArr = array();

for ($i=0; $i <= 3; $i++):
$resArr[$i] = array();
$resArr[$i]['capacity'] = $result->find('.col3_layer')[$i]->plaintext;
$resArr[$i]['free'] = $result->find('.col4_layer')[$i]->plaintext;
$resArr[$i]['diff'] = $resArr[$i]['capacity'] - $resArr[$i]['free'];

endfor;
	
	$resStr = json_encode($resArr);
	arrayDB("INSERT INTO park_airport (results,scan) VALUES('$resStr','$scan')");

	$res = arrayDB("SELECT results FROM park_airport ORDER BY id DESC LIMIT 2");

echo "<pre>";
//print_r($resArr);
print_r(json_decode($res[0]['results'])[0]);
print_r(json_decode($res[1]['results'])[1]);
echo "</pre>";