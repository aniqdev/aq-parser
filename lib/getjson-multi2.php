<?php
ini_set('max_execution_time', 300);
ini_set("display_errors",1);
error_reporting(E_ALL);
set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.
require_once(__DIR__.'/../vendor/autoload.php');
require_once('array_DB.php');

// для функции uasort()
function sortN($a,$b){ return $a['price']-$b['price'];}

function getResultsFromApi($result, $blacklist, $blacksell){
	
		$k = 0;
		//print_r($result);
		//$result = json_decode($result);
		$iQ = $result->total;
		if ($iQ > 500) $iQ = 500;

		$arrItem = array();
		for($i = 0; $i < $iQ; $i++){
			
			$itemID      = $result->items[$i]->id;
			$sellID      = $result->items[$i]->seller_id;
		    $name        = $result->items[$i]->name;
		    $price       = $result->items[$i]->price_rur;
		    $description = $result->items[$i]->description;

   			$nameLow = mb_convert_case($name, MB_CASE_LOWER, "UTF-8");
   			$descLow = mb_convert_case($description, MB_CASE_LOWER, "UTF-8");

			$bool1 = (stripos($nameLow,'free') !== false || stripos($nameLow,'row') !== false || stripos($nameLow,'bundle') !== false);
			$bool2 = (stripos($descLow,'free') !== false || stripos($descLow,'row') !== false || stripos($descLow,'bundle') !== false);
			$bool3 = (stripos($nameLow,'ccount') === false && stripos($nameLow,'ккаунт') === false && stripos($nameLow,'cis') === false && stripos($descLow,'украина') === false);

			$bool4 = BlackListFilter($blacklist,$itemID);
			$bool5 = BlackListFilter($blacksell,$sellID);

			if (($bool1 || $bool2) && $bool3 && $bool4 && $bool5) {

				$arrItem[$k] = array();	
	    		$arrItem[$k]['itemID'] = $itemID;
			    $arrItem[$k]['name'] = $name;
			    $arrItem[$k]['price'] = $price;
			    $arrItem[$k]['sellID'] = $sellID;
			    $k++;
			}

		} // for i
		return $arrItem;

} // getResultsFromApi()


function requestToArrChacker($pare, $reqArr){

    $k = 0;
    foreach ($reqArr as $nameOld) {
        $nameNew1 = str_ireplace($pare[0], $pare[1], $nameOld);
        if(!in_array($nameNew1, $reqArr)) $reqArr[] = $nameNew1;
        $k++; if($k == 64) break;
    }
    return $reqArr;
}


function requestToArr($request){

    $configJSON = file_get_contents(__DIR__.'/../settings/platiru_settings.json');
	$configArr = json_decode($configJSON, true);

    $reqArr = array($request);
    foreach ($configArr as $pare){
        $reqArr = requestToArrChacker($pare, $reqArr);
        $reqArr = requestToArrChacker(array_reverse($pare), $reqArr); 
    }


    return $reqArr;

} // requestToArr()

function strictFilter($request, $arrayIn){
    
    $arrayOut = array();
    $requestArr = explode(' ', $request);
    foreach ($arrayIn as $game) {
        foreach ($requestArr as $reqWord) {
            $pos1 = stripos($game['name'], $reqWord);
            if($pos1 === false) continue 2;
        }
        $arrayOut[] = $game;
    }
    return $arrayOut;

} // strictFilter()

function requestFilter($request){
    $request = str_ireplace([':',"'s","'",'!','.'], ' ', $request);
    return trim(preg_replace('/\s+/', ' ', $request));
}

function savePaltiRuToBase($arrItem, $game_id, $scan){

	$item1_id = 0; $item1_name = 'No results'; $item1_price = 0; $item1_desc = 'No results';
	$item2_id = 0; $item2_name = 'No results'; $item2_price = 0; $item2_desc = 'No results';
	$item3_id = 0; $item3_name = 'No results'; $item3_price = 0; $item3_desc = 'No results';

	usort ($arrItem, 'sortN');
	if (isset($arrItem[0])) {
		$item1_id    = _esc(trim(strip_tags($arrItem[0]['itemID'])));
		$item1_name  = _esc(trim(strip_tags($arrItem[0]['name'])));
		$item1_price = _esc(trim(strip_tags($arrItem[0]['price'])));
		$item1_desc  = _esc(trim(strip_tags($arrItem[0]['sellID'])));
	}
	if (isset($arrItem[1])) {
		$item2_id    = _esc(trim(strip_tags($arrItem[1]['itemID'])));
	 	$item2_name  = _esc(trim(strip_tags($arrItem[1]['name'])));
		$item2_price = _esc(trim(strip_tags($arrItem[1]['price'])));
		$item2_desc  = _esc(trim(strip_tags($arrItem[1]['sellID'])));
	}
	if (isset($arrItem[2])) {
		$item3_id    = _esc(trim(strip_tags($arrItem[2]['itemID'])));
	 	$item3_name  = _esc(trim(strip_tags($arrItem[2]['name'])));
		$item3_price = _esc(trim(strip_tags($arrItem[2]['price'])));
		$item3_desc  = _esc(trim(strip_tags($arrItem[2]['sellID'])));
	}

	arrayDB("INSERT INTO items VALUES(null,'$game_id','$item1_id','$item1_name','$item1_price','$item1_desc',
									'$item2_id','$item2_name','$item2_price','$item2_desc',
									'$item3_id','$item3_name','$item3_price','$item3_desc','$scan',null)");
}

//=========================================================================
//=========================================================================
use \Curl\MultiCurl;

// Requests in parallel with callback functions.
$multi_curl = new MultiCurl();

$multi_curl->success(function($instance) {
    $_GET['results'][] = $instance->response;

    $urlArr = parse_url($instance->url);
	parse_str($urlArr['query'], $queryArr);
	$_GET['rkey'][] = $queryArr['rkey'];
});
$multi_curl->error(function($instance) {
    $_ERRORS[] = $instance->errorMessage;
});

if (isset($_POST['getjson'])) {
	// получаем массив игр из Базы Данных
	$reqs = arrayDB('SELECT * FROM games');

	
	
	if (isset($_POST['scan']) && $_POST['scan'] != '0') $scan = $_POST['scan'];
	else {
		$scan = time();
		$scandate = date('d-m-y H:i:s');
		arrayDB("INSERT INTO scans VALUES(null,'$scandate','$scan')");
	}


	$start = $_POST['start'];
	$j = $_POST['start'];
	$num = count($reqs);
	if ($_POST['end'] < $num) $end = $_POST['end'];
	else $end = $num;

	$blacklistM = arrayDB("SELECT * FROM blacklist WHERE category='item'");
	$blacksell = arrayDB("SELECT * FROM blacklist WHERE category='seller'");
	
	//$game_list = array();
	for ($j; $j <= $end; $j++) {
		
		$game_id = $reqs[$j-1]['id'];
		$blackaddons = arrayDB("SELECT * FROM blacklist WHERE category LIKE 'game_id=$game_id%'");
		$blacklist = array_merge($blacklistM, $blackaddons);
		$request = $reqs[$j-1]['name'];
	    $request = requestFilter($request);
		if(isset($game_list)) $game_list[]['name'] = $request;

	    $requests = requestToArr($request);

	    $arrItem = array();
	    $arrItem1 = array();
	    $idsArr = array();

	    $_GET['results'] = [];$_GET['rkey'] = [];
	    foreach ($requests as $k => $req) {
	        $reqEnc = rawurlencode($req);
	        $url = "http://www.plati.ru/api/search.ashx?query={$reqEnc}&pagesize=500&response=json&rkey={$k}";
	        $multi_curl->addGet($url);
	    }
	    $multi_curl->start();

	    foreach ($_GET['results'] as $k => $result) {
	    	$n = $_GET['rkey'][$k];
	    	$req = $requests[$n];
	        $arrItem2 = getResultsFromApi($result, $blacklist, $blacksell);
	        $arrItem2 = strictFilter($req, $arrItem2);
	        $arrItem1 = array_merge($arrItem1, $arrItem2);
	    }

	    foreach ($arrItem1 as $v) {
	        $idsArr[$v['itemID']] = $v;
	    }

	    foreach ($idsArr as $va) {
	        $arrItem[] = $va;
	    }

			//print_r($arrItem);

		if(isset($game_list)) $game_list[count($game_list)-1]['results'] = $arrItem;

		savePaltiRuToBase($arrItem, $game_id, $scan);

	} // for j
	if(!isset($game_list)) $game_list = 'disabled';
	
	$answer = [ 'start'=> $start,
				'end'  => $end,
				'scan' => $scan,
				'num'  => $num,
				'games' => $game_list,
				'errors' => $_ERRORS,
				];
	echo json_encode($answer);
}