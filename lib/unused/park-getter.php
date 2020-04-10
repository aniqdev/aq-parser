<?php
if (!isset($_GET['park']) && !isset($_REQUEST['_/parser/lib/park-getter_php'])) die('Oops!');
	header('Content-Type: text/html; charset=utf-8');
ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.
//include('simple_html_dom.php');
//include('PHPExcel.php');
require_once('array_DB.php');
require_once('simple_html_dom.php');
include_once 'adds/park_variables.php';

function cmp($a, $b){
    if ($a['price'] == $b['price']) return 0;
    return ($a['price'] < $b['price']) ? -1 : 1;
}

$scan = (int)(time()/60);
$scans = arrayDB('SELECT DISTINCT scan FROM park_results ORDER BY scan DESC');

function art($art, $scan, $scans, $parken){
	$mailString = '';
	if(isset($scans[0])){
		$scan2 = $scans[0]['scan'];
		$resLast = arrayDB("SELECT * FROM park_results WHERE scan='$scan2' AND art='$art'");
	}
	for ($i=1; $i <= 28; $i++):

	$postdata = http_build_query(
	    array(
	        'action' => 'parkplaetze_anzeigen',
	        'dauer' => $i,
			'art' => $art,
			'sort' => '0'
	    )
	);

	$opts = array('http' =>
	    array(
	        'method'  => 'POST',
	        'header'  => 'Content-type: application/x-www-form-urlencoded',
	        'content' => $postdata
	    )
	);

	$context  = stream_context_create($opts);

	$result = file_get_html('https://www.parkplatzvergleich.de/parken-flughafen-koeln/', false, $context);
	//$result = str_get_html($result);
	$result = $result->find('#tabelle')[0];
	$resArray = array();

	foreach ($result->find('#name div a') as $key1 => $value1) {
		$resArray[$key1] = array();
		$resArray[$key1]['name'] = str_replace ("ö" ,'oe' , $value1->innertext);
	}

	foreach ($result->find('#preis') as $key2 => $value2) {
		preg_match('/\d+.\d+/', $value2->plaintext, $matches);
		if ($key2 > 0) {
			$resArray[$key2-1]['price'] = (float)$matches[0];
		}
	}
	$resArray = array_merge ($resArray, $parken[$i]);
		// echo "<pre>";
		// print_r($resArray);
		// echo "</pre>";

		usort($resArray, "cmp");

		// echo "<pre><hr>";
		// var_dump($resArray);
		// echo "</pre>";

		$hashed = array();
		$hashed2 = array();
		$hashNames = array();
		$resArray2 = json_decode($resLast[$i-1]['results'], true);
		foreach ($resArray as $k => $value) {
			//var_dump($value);
			$j = md5($value['name']);
			$hashed[$j] = $value['price'];
			$hashNames[$j] = $value['name'];
			$j = md5($resArray2[$k]['name']);
			$hashed2[$j] = $resArray2[$k]['price'];
		}
		// echo "<pre>";
		// 	print_r($hashed);
		// 	print_r($hashed2);
		// 	print_r($hashNames);
		// echo "</pre>";

		foreach ($hashed as $k => $value) {
			if ($value != $hashed2[$k]) {
				$nameD = $hashNames[$k];
				$priceD = $value - $hashed2[$k];
				$mailString .= "Tage $i | Товар <b>$nameD</b> изменился на <i>$priceD EUR</i><br>\r\n";
			}
		}
		$resStr = json_encode($resArray);
		//echo $resStr;
		arrayDB("INSERT INTO park_results (results,tage,scan,art) VALUES('$resStr','$i','$scan','$art')");

	echo $i,"-";
	endfor;

	$to = 'thenav@mail.ru,';
	$to .= 'konstantin@gig-games.de';
	$subject = 'Nachricht message';
	$name = 'Aniq';
	$email = 'info@parkplatz.de';
	if($mailString){
		mail($to, $subject, $mailString,
	                 "From: ".$name." <".$email.">\r\n"
	                ."Reply-To: ".$email."\r\n"
	                ."Content-type: text/html; charset=utf-8 \r\n"
	                ."X-Mailer: PHP/" . phpversion());
	}
} //art()

$arts = array(0,1,3,4,6);
foreach ($arts as $art) {
	echo 'XELJ = ',$art;
	art($art, $scan, $scans, $parken);
}

// echo "<pre>";
// print_r($resArray);
// echo "</pre>";