<?php
ini_set('max_execution_time', 300);
ini_set("display_errors",1);
error_reporting(E_ALL);
set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.


use \Curl\MultiCurl;

// Requests in parallel with callback functions.
$multi_curl = new MultiCurl();

$multi_curl->success(function($instance) {

    $urlArr = parse_url($instance->url);
	parse_str($urlArr['query'], $queryArr);
	$rkey = $queryArr['rkey'];
	$jkey = $queryArr['jkey'];

    $_GET['results'][$jkey][$rkey] = $instance->response;

});
$multi_curl->error(function($instance) {
	global $_ERRORS;
    $_ERRORS[] = $instance->errorMessage;
});

if (isset($_POST['getjson'])) {
	// получаем массив игр из Базы Данных

$where = get_steam_miracle_where();
	if ($_POST['getjson'] > 0) {
		$id = (int)$_POST['getjson'];
		$reqs = arrayDB("SELECT id,title as name FROM steam_de WHERE id='$id'");
	}elseif ($_POST['getjson'] === 'interval') {
		$reqs = arrayDB("SELECT steam_de.id, steam_de.title as name
					FROM steam_de LEFT JOIN steam_items ON steam_de.id=steam_items.game_id
					WHERE $where ORDER BY o_reviews DESC LIMIT 200");
	}else{
		$reqs = arrayDB('SELECT id,title as name FROM steam_de');
	}
	
	if (isset($_POST['scan']) && $_POST['scan'] != '0') $scan = $_POST['scan'];
	else $scan = time();

	$j = (int)$_POST['start'];
	$end = (int)$_POST['end'];

	$num = count($reqs);
	if ($_POST['end'] > $num) $end = $num;

	$blacklistM = arrayDB("SELECT * FROM steam_blacklist WHERE category='item'");
	$blacksell = arrayDB("SELECT * FROM blacklist WHERE category='seller'");

	//$game_list = array();

//--------------------------------------------------------------------------------------------------
	$_GET['results'] = [];
	for ($j; $j <= $end; $j++) {

		$request = $reqs[$j-1]['name'].' steam';
	    $request = _requestFilter($request);
	    $requests = _requestToArr($request);
		if(isset($game_list)) $game_list[]['name'] = $request;

	    $_GET['results'][$j] = [];
	    foreach ($requests as $k => $req) {
	        $reqEnc = rawurlencode($req);
	        $url = "http://www.plati.io/api/search.ashx?query={$reqEnc}&pagesize=500&response=json&rkey={$k}&jkey={$j}";
	        $multi_curl->addGet($url);
	    }
	}
	$multi_curl->start();
//--------------------------------------------------------------------------------------------------
	$j = (int)$_POST['start'];
	for ($j; $j <= $end; $j++) {
		if(!$_GET['results'][$j]){
			$_ERRORS[] = $_GET['results'][$j];
			continue;
		}
		$game_id = $reqs[$j-1]['id'];
		$blackaddons = arrayDB("SELECT * FROM steam_blacklist WHERE category LIKE 'game_id=$game_id%'");
		$blacklist = array_merge($blacklistM, $blackaddons);

		$request = $reqs[$j-1]['name'];
	    $request = _requestFilter($request);
	    $requests = _requestToArr($request);

	    $arrItem1 = array();
	    //print_r($_GET['results']);
	    foreach ($_GET['results'][$j] as $k => $result) {
	    	if (!isset($requests[$k])) {
	    		//sa($requests);
	    	}
	        $arrItem2 = _getResultsFromApi($result, $blacklist, $blacksell);
	        $arrItem2 = _strictFilter($requests[$k], $arrItem2);
	        $arrItem1 = array_merge($arrItem1, $arrItem2);
	    }

	    $idsArr = array();
	    foreach ($arrItem1 as $v) {
	        $idsArr[$v['itemID']] = $v;
	    }

	    $arrItem = array();
	    foreach ($idsArr as $va) {
	        $arrItem[] = $va;
	    }

		if(isset($game_list)) $game_list[count($game_list)-1]['results'] = $arrItem;

		_savePaltiRuToBase($arrItem, $game_id, $scan, 'steam_items');
	} // for j
//--------------------------------------------------------------------------------------------------
	if(!isset($game_list)) $game_list = 'disabled';
	
	$answer = [ 'start'=> $_POST['start'],
				'end'  => $end,
				'scan' => $scan,
				'num'  => $num,
				'games' => $game_list,
				'errors' => $_ERRORS,
				];
	echo json_encode($answer);
}