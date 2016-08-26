<?php
ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.
//include('simple_html_dom.php');
//include('PHPExcel.php');
ini_set("display_errors",1);
error_reporting(E_ALL);
require_once('array_DB.php');
require_once('simple_html_dom.php');
if(!defined('ROOT')) header('Content-Type: text/html; charset=utf-8');

function cmp($a, $b){
    if ($a['price'] == $b['price']) return 0;
    return ($a['price'] < $b['price']) ? -1 : 1;
}

if (isset($_POST['ebay_getprices'])) {

	$games = arrayDB("SELECT id,name FROM games");
	
	// определяем начало цикла
	$start = $_POST['start'];
	// определяем конец цикла
	$num = count($games);
	if ($_POST['end'] < $num) $end = $_POST['end'];
	else $end = $num;

	// определяем скан маркер
	if (isset($_POST['scan']) && $_POST['scan'] != '0') $scan = $_POST['scan'];
	else $scan = time(); // он же $mark

	$options = array('http' => array('method' => "GET", 'header' => "Accept-language: ru\r\n" . "Cookie: lucky9=3194744\r\n"));
	$context = stream_context_create($options);

	$gamesArr = array();
	// Основная ссылка, с которой мы парсим игры
	for ($j = $start; $j <= $end; $j++) {
		$game = $games[$j-1]['name'];
		
		$strJSON = Ebay_shopping::findItemsAdvanced($game, 0, 1 , 1249);
		
		$objJSON = json_decode($strJSON);
		$itemArr = array();
		$newArr = array();
		if (isset($objJSON->findItemsAdvancedResponse[0]->searchResult[0]->item)) {
			$itemArr = $objJSON->findItemsAdvancedResponse[0]->searchResult[0]->item;
		}
		for ($i=0; $i < count($itemArr); $i++) {
			$newArr[$i]['itemid'] = $itemArr[$i]->itemId[0];
			$newArr[$i]['title']  = $itemArr[$i]->title[0];
			$newArr[$i]['price']  = $itemArr[$i]->sellingStatus[0]->convertedCurrentPrice[0]->__value__;
		}

		usort($newArr, "cmp");

		$title1 = 0; $itemid1 = 0; $price1 = 0;
		$title2 = 0; $itemid2 = 0; $price2 = 0;
		$title3 = 0; $itemid3 = 0; $price3 = 0;
		$title4 = 0; $itemid4 = 0; $price4 = 0;
		$title5 = 0; $itemid5 = 0; $price5 = 0;

		$game_id = $games[$j-1]['id'];
		if (isset($newArr[0])) {
			$itemid1 = _esc($newArr[0]['itemid']);
			$title1  = _esc($newArr[0]['title']);
			$price1  = _esc($newArr[0]['price']);
		}

		if (isset($newArr[1])) {
			$itemid2 = _esc($newArr[1]['itemid']);
			$title2  = _esc($newArr[1]['title']);
			$price2  = _esc($newArr[1]['price']);
		}

		if (isset($newArr[2])) {
			$itemid3 = _esc($newArr[2]['itemid']);
			$title3  = _esc($newArr[2]['title']);
			$price3  = _esc($newArr[2]['price']);
		}

		if (isset($newArr[3])) {
			$itemid4 = _esc($newArr[3]['itemid']);
			$title4  = _esc($newArr[3]['title']);
			$price4  = _esc($newArr[3]['price']);
		}

		if (isset($newArr[4])) {
			$itemid5 = _esc($newArr[4]['itemid']);
			$title5  = _esc($newArr[4]['title']);
			$price5  = _esc($newArr[4]['price']);
		}

		$query = "INSERT INTO ebay_results VALUES(null,'$game_id','$itemid1','$title1','$price1',
																 '$itemid2','$title2','$price2',
																 '$itemid3','$title3','$price3',
																 '$itemid4','$title4','$price4',
																 '$itemid5','$title5','$price5',
																 '$scan',null)";
		arrayDB($query);
		// $gamesArr[]['game_name'] = $game;
		// $gamesArr[count($gamesArr)-1]['game_list'] = $newArr;
	} // for ($j; $j <= $end; $j++)

	$answer = [ 'start'=> $start,
				'end'  => $end,
				'scan' => $scan,
				'num'  => $num,
				//'query'  => $query,
				//'games'=> $gamesArr,
				'errors' => $_ERRORS
				];
	echo json_encode($answer);
// endif (isset($_POST['ebay_getprices']))
}elseif(isset($_POST['ebay_getpurhis'])){
//======================================================================================
// создание контекста для открытия потока
	$options = array('http' => array('method' => "GET", 'header' => "Accept-language: de\r\n" . "Cookie: lucky9=3194744\r\n"));
	$context = stream_context_create($options);

isset($_POST['itemid']) ? $itemid = $_POST['itemid'] : $itemid = 0;
// Основная ссылка, с которой мы парсим игры
$as = file_get_html('http://offer.ebay.de/ws/eBayISAPI.dll?ViewBidsLogin&item='.$itemid, false, $context);

if (count($as->find('.BHbidSecBorderGrey')) > 0) {
	//var_dump($as->find('.BHbidSecBorderGrey'));
	$item1 = $as->find('.BHbidSecBorderGrey')[0]->find('.contentValueFont');
}elseif(count($as->find('.contentValueFont')) > 0){
	$item1 = $as->find('.contentValueFont');
}else $item1 = array();

$innertimes = array();
$timestamps = array();
$quantitys  = array();
$purprices  = array();
$curencies  = array();
$purchday   = array();
foreach ($item1 as $key => $value) {
	if ($key%3===2){
		array_push($innertimes, $value->innertext);
		$rest = substr(trim($value->innertext), 0, 18); // 09.10.15 08:03:54
		$rest = trim(str_replace('.', '-', $rest)); // 09-10-15 08:03:54
		$timest = DateTime::createFromFormat('d-m-y H:i:s', $rest)->getTimestamp(); // создаем объект Date
		array_push($timestamps, $timest);
		array_push($purchday, intval($timest/60/60/24));
	}elseif($key%3===1){
		array_push($quantitys, (int)trim($value->innertext));
	}elseif($key%3===0){
		$pprice = str_replace(',', '.', $value->innertext);
		$pprice = htmlentities($pprice,ENT_NOQUOTES,'UTF-8');
		$pprice = str_replace('&nbsp;', ' ', $pprice);
		preg_match('/(\D+(?!=\d))\D*(\d+.\d+)/', $pprice, $return );
		$curenc = trim($return[1]);
		$pprice = trim($return[2]);
		array_push($purprices, (float)$pprice);
		array_push($curencies, $curenc);
	}
}

if (count($timestamps)>0) $weekSells = 'более 99 продаж';
else $weekSells = 'Unbekannter Artikel';
$weekAgo = time()-604800;
foreach ($timestamps as $key => $value) {
	if ($value < $weekAgo) {
		$weekSells = $key;
		break;
	}
}

$resTable = array();
foreach ($timestamps as $t => $times) {
	$resTable[$t] = array('price'   => $purprices[$t],
						  'curency' => $curencies[$t],
						  'amount'  => $quantitys[$t],
						  'purday'  => $purchday[$t],
						  'time'    => $innertimes[$t],
						  'times'   => $times );
}

//$resTable = array_reverse($resTable);
$dayArr = array();
foreach ($resTable as $key => $value) {
	if (isset($dayArr[$value['purday']])) 
		 $dayArr[$value['purday']] += $value['amount'];
	else $dayArr[$value['purday']] = $value['amount'];
}

$dayArr = array_reverse($dayArr, true);
$dayArrCopy = $resTable;

if (count($resTable) > 1) {
	$_first = array_pop($dayArrCopy)["purday"];
	$_last = array_shift($dayArrCopy)["purday"];
}elseif (count($resTable) == 1) {
	$_first = array_pop($dayArrCopy)["purday"];
	$_last = $_first;
}else{
	$_first = 1;
	$_last = 0;
}

$dayArr2 = array();
for ($i=$_first; $i <= $_last; $i++) { 
	if (isset($dayArr[$i])) $dayArr2[$i] = $dayArr[$i];
	else $dayArr2[$i] = 0;
}

$sendData = array(  'weekSells' => $weekSells,
					'resTable' => $resTable,
					'dayArr' => $dayArr2,
					'errors' => $_ERRORS,
					'first' => $_first,
					'last' => $_last );

echo json_encode($sendData);
//=======================================================================

	
// endif (isset($_POST['ebay_getpurhis']))
}else{
?>

	<h3>парсим цены Ebay.com</h3>
	<button id="ebay_getprices" class="bttn">Спарсить цены</button>
	<button id="ebay_getpurhis" class="bttn">Спарсить продажи</button>
	<span class="loading2"></span>
	<h3>Состояние процесса:</h3>
	<ul id="message2"><li></li></ul>

<?php
} // if (isset($_POST['ebay_getprices']))
?>