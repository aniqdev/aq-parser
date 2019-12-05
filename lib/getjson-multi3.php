<?php
ini_set('max_execution_time', 300);
ini_set("display_errors",1);
error_reporting(E_ALL);
set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.

//=========================================================================
//=========================================================================

// Requests in parallel with callback functions.
$multi_curl = new \Curl\MultiCurl();
$multi_curl->setOpt(CURLOPT_FOLLOWLOCATION, true);

$multi_curl->success(function($instance) {

    $urlArr = parse_url($instance->url);
	parse_str($urlArr['query'], $queryArr);
	$rkey = $queryArr['rkey'];
	$jkey = $queryArr['jkey'];

    $_GET['results'][$jkey][$rkey] = $instance->response;
    // sa($instance->response);

});
$multi_curl->error(function($instance) {
	global $_ERRORS;
	$_ERRORS[] = 'THAT WAS multi_curl ERROR!!!';
    $_ERRORS[] = $instance->errorMessage;
});

if (isset($_POST['getjson'])) {
	// получаем массив игр из Базы Данных
	$reqs = arrayDB('SELECT * FROM games');


	if ($_POST['getjson'] > 0) {
		$operation = 'update';
		$game_id = (int)$_POST['getjson'];
		$reqs = arrayDB("SELECT * FROM games WHERE id = '$game_id'");
	}else{
		$operation = 'insert';
		$reqs = arrayDB('SELECT * FROM games');
	}

	
	
	if (isset($_POST['scan']) && $_POST['scan'] != '0') $scan = $_POST['scan'];
	else {
		$scan = time();
		// delete_old_records('items','date'); // depricated
		delete_old_records_2('items');
		// deprecated
		// $scandate = date('d-m-y H:i:s');
		// arrayDB("INSERT INTO scans VALUES(null,'$scandate','$scan')");
	}


	$start = $_POST['start'];
	$j = $_POST['start'];
	$num = count($reqs);
	if ($_POST['end'] < $num) $end = $_POST['end'];
	else $end = $num;

	$blacklistM = arrayDB("SELECT * FROM blacklist WHERE category='item'");
	$blacksell = arrayDB("SELECT * FROM blacklist WHERE category='seller'");

	//$game_list = array();

//--------------------------------------------------------------------------------------------------
	$_GET['results'] = [];
	for ($j; $j <= $end; $j++) {

		$request = $reqs[$j-1]['name'];
	    $request = _requestFilter($request);
	    $requests = _requestToArr($request);
		if(isset($game_list)) $game_list[]['name'] = $request;

	    $_GET['results'][$j] = [];
	    foreach ($requests as $k => $req) {
	        $reqEnc = rawurlencode($req);
	        $url = "http://www.plati.ru/api/search.ashx?query={$reqEnc}&pagesize=500&response=json&rkey={$k}&jkey={$j}";
	        if(@$_POST['show_url']) echo "<br>",$url,'<hr>';
	        $multi_curl->addGet($url);
	    }
	}
	$multi_curl->start();
//--------------------------------------------------------------------------------------------------
	$j = $_POST['start'];
	for ($j; $j <= $end; $j++) {
		if(!$_GET['results'][$j]){
			$_ERRORS[] = $_GET['results'][$j];
			continue;
		}
		$game_id = $reqs[$j-1]['id'];
		$blackaddons = arrayDB("SELECT * FROM blacklist WHERE category LIKE 'game_id=$game_id%'");
		$blacklist = array_merge($blacklistM, $blackaddons);

		$request = $reqs[$j-1]['name'];
	    $request = _requestFilter($request);
	    $requests = _requestToArr($request);

	    $arrItem1 = array();
	    //print_r($_GET['results']);
	    foreach ($_GET['results'][$j] as $k => $result) {
	        $arrItem2 = _getResultsFromApi($result, $blacklist, $blacksell);
	        $arrItem2 = _strictFilter($requests[$k], $arrItem2);
	        $arrItem1 = array_merge($arrItem1, $arrItem2);
	    }

	    $idsArr = [];
	    foreach ($arrItem1 as $v) {
	        $idsArr[$v['itemID']] = $v;
	    }

	    $arrItem = [];
	    foreach ($idsArr as $va) {
	        $arrItem[] = $va;
	    }

		if(isset($game_list)) $game_list[count($game_list)-1]['results'] = $arrItem;

		$sql_query = _savePaltiRuToBase($arrItem, $game_id, $scan, 'items', $operation);
	} // for j
//--------------------------------------------------------------------------------------------------
	if(!isset($game_list)) $game_list = 'disabled';
	
	$answer = [ 'start'=> $start,
				'end'  => $end,
				'scan' => $scan,
				'num'  => $num,
				'games' => $game_list,
				'errors' => $_ERRORS,
				//'$sql_query' => $sql_query,
				];
	echo json_encode($answer);
}