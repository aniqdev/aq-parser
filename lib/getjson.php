<?php
ini_set('max_execution_time', 300);
set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.
require_once('array_DB.php');

// для функции uasort()
function sortN($a,$b){ return $a['price']-$b['price'];}

function getResultsFromApi($request, $blacklist, $blacksell){
	
		$k = 0;

		$arrItem = array();
		// получаем результаты запросов в JSON
		$opts = array('http'=>array('header' => "User-Agent:MyAgent/1.0\r\n"));
		$context = stream_context_create($opts);
		$url = 'http://www.plati.ru/api/search.ashx?query='.$request.'&pagesize=500&response=json';
		$result = file_get_contents($url,false,$context);
		$result = json_decode($result);
		$iQ = $result->total;
		if ($iQ > 500) $iQ = 500;
		
			
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
// 			echo "<br>itemID = ",$itemID;
// var_dump($bull4);
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

function requestToArr($request=''){
	
    $a = [' 10 ',' 9 ',' 8 ',' 7 ',' 6 ',' 5 ',' 4 ',' 3 ',' 2 ',' 1 ',];
    $b = [' x ',' ix ',' viii ',' vii ',' vi ',' v ',' iv ',' iii ',' ii ',' i '];

    $c = [' Edition',' DLC',' Add-on',' Pack',' Bundle'];
    $d = [];

    $e = [' goty'];
    $f = [' game of the year edition'];

    $retArr = array($request);

    $result1 = str_ireplace($a, $b, $request);
    $result2 = str_ireplace($b, $a, $request);
    $result3 = str_ireplace($c, $d, $request);
    $result4 = str_ireplace($e, $f, $request);
    $result5 = str_ireplace($f, $e, $request);

    if($request != $result1) $retArr[] = $result1;
    if($request != $result2) $retArr[] = $result2;
    if($request != $result3) $retArr[] = $result3;
    if($request != $result4) $retArr[] = $result4;
    if($request != $result5) $retArr[] = $result5;

    return $retArr;

} // requestToArr()

function requestToArrChacker($pare, $reqArr){

    $k = 0;
    foreach ($reqArr as $nameOld) {
        $nameNew1 = str_ireplace($pare[0], $pare[1], $nameOld);
        if(!in_array($nameNew1, $reqArr)) $reqArr[] = $nameNew1;
        $k++; if($k == 64) break;
    }
    return $reqArr;
}

function requestToArr2($request){
    $changeArr = [
        [' 10 ', ' x '],
        [' 9 ', ' ix '],
        [' 8 ', ' viii '],
        [' 7 ', ' vii '],
        [' 6 ', ' vi '],
        [' 5 ', ' v '],
        [' 4 ', ' iv '],
        [' 3 ', ' iii '],
        [' 2 ', ' ii '],
        [' 1 ', ' i '],
        [' goty', ' game of the year edition'],
        [' Edition', ''],
        [' DLC', ''],
        [' Add-on', ''],
        [' Pack', ''],
        [' Bundle', '']
    ];

    $configJSON = file_get_contents(__DIR__.'/../settings/platiru_settings.json');
	$configArr = json_decode($configJSON, true);

    $reqArr = array($request);
    foreach ($configArr as $pare){
        $reqArr = requestToArrChacker($pare, $reqArr);
        $reqArr = requestToArrChacker(array_reverse($pare), $reqArr); 
    }


    return $reqArr;

} // requestToArr2()

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
    $request = trim(preg_replace('/\s+/', ' ', $request));

    return $request;
}

//=========================================================================
//=========================================================================

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
		$arrItem = array();

		$item1_id    = 0;
		$item1_name  = 'No results';
		$item1_price = 0;
		$item1_desc  = 'No results';
		$item2_id    = 0;
		$item2_name  = 'No results';
		$item2_price = 0;
		$item2_desc  = 'No results';
		$item3_id    = 0;
		$item3_name  = 'No results';
		$item3_price = 0;
		$item3_desc  = 'No results';

		//============================================================
		//========= function getResultsFromApi()
    $requests = requestToArr2($request);

    $arrItem = array();
    $arrItem1 = array();
    $idsArr = array();
    foreach ($requests as $req) {
        $reqEnc = urlencode($req);
        $arrItem2 = getResultsFromApi($reqEnc, $blacklist, $blacksell);
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

		usort ($arrItem, 'sortN');
		if(isset($game_list)) $game_list[count($game_list)-1]['results'] = $arrItem;

		$game_id         = $reqs[$j-1]['id'];
		if (isset($arrItem[0])) {
			$item1_id    = mysql_escape_string(trim(strip_tags($arrItem[0]['itemID'])));
			$item1_name  = mysql_escape_string(trim(strip_tags($arrItem[0]['name'])));
			$item1_price = mysql_escape_string(trim(strip_tags($arrItem[0]['price'])));
			$item1_desc  = mysql_escape_string(trim(strip_tags($arrItem[0]['sellID'])));
		}
		if (isset($arrItem[1])) {
			$item2_id    = mysql_escape_string(trim(strip_tags($arrItem[1]['itemID'])));
		 	$item2_name  = mysql_escape_string(trim(strip_tags($arrItem[1]['name'])));
			$item2_price = mysql_escape_string(trim(strip_tags($arrItem[1]['price'])));
			$item2_desc  = mysql_escape_string(trim(strip_tags($arrItem[1]['sellID'])));
		}
		if (isset($arrItem[2])) {
			$item3_id    = mysql_escape_string(trim(strip_tags($arrItem[2]['itemID'])));
		 	$item3_name  = mysql_escape_string(trim(strip_tags($arrItem[2]['name'])));
			$item3_price = mysql_escape_string(trim(strip_tags($arrItem[2]['price'])));
			$item3_desc  = mysql_escape_string(trim(strip_tags($arrItem[2]['sellID'])));
		}

		arrayDB("INSERT INTO items VALUES(null,'$game_id','$item1_id','$item1_name','$item1_price','$item1_desc',
										'$item2_id','$item2_name','$item2_price','$item2_desc',
										'$item3_id','$item3_name','$item3_price','$item3_desc','$scan',null)");

	} // for j
	if(!isset($game_list)) $game_list = 'disabled';
	
	$answer = [ 'start'=> $start,
				'end'  => $end,
				'scan' => $scan,
				'num'  => $num,
				'games' => $game_list,
				'errors' => $_ERRORS
				];
	echo json_encode($answer);
}elseif(isset($_POST['setGigGamesIds'])){
	//setGigGamesIdsToFile();
	echo '{"setGigGamesIdsToFile" : "done", "errors" : '.json_encode($_ERRORS).'}';
}else{
?>
<div class="row h518">
	<div class="col-sm-6">
		<h3>парсим цены Plati.ru</h3>
		<button id="getjson" class="getjson-btn">Спарсить</button>
		<span class="loading loading1"></span>
		<h3>Состояние процесса:</h3>
		<ul id="message1" class="message"><li></li></ul>
	</div>
	<div class="col-sm-6">
		<h3>парсим цены Ebay.com</h3>
		<button id="ebay_getprices" class="getjson-btn">Спарсить</button>
		<span class="loading loading2"></span>
		<h3>Состояние процесса:</h3>
		<ul id="message2" class="message"><li></li></ul>
	</div>
</div>

<div class="add-shadow"></div>
<div class="loader hide"></div>
<?php
} // if (isset($_POST['getjson']))


?>