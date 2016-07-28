<?php
header('Content-Type: text/html; charset=utf-8');
ini_get('safe_mode') or set_time_limit(5); // Указываем скрипту, чтобы не обрывал связь.
?>
<pre>
<?php
define('DOCROOT', 'E:\xamp\htdocs\parser\www\test.php');
define('ROOT', __DIR__);
require_once( 'lib/array_DB.php');
include_once('lib/simple_html_dom.php');
include_once('lib/functions.class/functions.class.php');

function steamChecker($request){
    
    if (stripos($request,'steam') !== false) {
        // закоментировать если не нужно удалять steam
        //$request = str_ireplace('steam', '', $request);
        $request = requestFilter($request);
        return $request;
    }else{
        return false;
    }
}

function steamCheckerBool($description){
    
    if (stripos($description,'steam') !== false) {
        return true;
    }else{
        return true;
    }
}

function getResultsFromApi($request, $blacklist, $blacksell){
    
        $k = 0;

        if($ww = steamChecker($request)) $request = $ww;
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
            $bool6 = true; 
            if($ww) $bool6 = steamCheckerBool($descLow);
            
//          echo "<br>itemID = ",$itemID;
// var_dump($bull4);
            if (($bool1 || $bool2) && $bool3 && $bool4 && $bool5 && $bool6) {

                $arrItem[$k] = array(); 
                $arrItem[$k][0] = $itemID;
                $arrItem[$k][1] = $name;
                $arrItem[$k][2] = $price;
                $arrItem[$k][3] = $description;
                $k++;
            }

        } // for i

        // var_dump($arrItem);

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

    $configJSON = file_get_contents(ROOT.'/settings/platiru_settings.json');
    $configArr = json_decode($configJSON, true);

    // print_r($changeArr);
    // print_r($configArr);

    $reqArr = array($request);
    foreach ($configArr as $pare){
        $reqArr = requestToArrChacker($pare, $reqArr);
        $reqArr = requestToArrChacker(array_reverse($pare), $reqArr); 
    }


    return $reqArr;

} // requestToArr2()


function strictFilter($request, $arrayIn){
    
    if($ww = steamChecker($request)) $request = $ww;
    //var_dump($request);
    $arrayOut = array();
    $requestArr = explode(' ', $request);
    foreach ($arrayIn as $game) {
        foreach ($requestArr as $reqWord) {
            $pos1 = stripos($game[1], $reqWord);
            if($pos1 === false) continue 2;
        }
        $arrayOut[] = $game;
    }
    // echo "requestArr = ";
    // print_r($requestArr);
    // print_r($arrayIn);
    // print_r($arrayOut);
    return $arrayOut;

}

function requestFilter($request){
    $request = str_ireplace([':',"'s","'",'!','.'], ' ', $request);
    $request = trim(preg_replace('/\s+/', ' ', $request));

    return $request;
}

//==========================================================================
//==========================================================================

    $blacklist = arrayDB("SELECT * FROM blacklist WHERE category='item'");
    $blacksell = arrayDB("SELECT * FROM blacklist WHERE category='seller'");

    $request = "Rock of Ages Steam  ";
    $request = requestFilter($request);
    $reqs = requestToArr2($request);

    $arrItem = array();
    $arrItem1 = array();
    $idsArr = array();
    foreach ($reqs as $req) {
        $reqEnc = urlencode($req);
        $arrItem2 = getResultsFromApi($reqEnc, $blacklist, $blacksell);
        // print_r($arrItem2);
        $arrItem2 = strictFilter($req, $arrItem2);
        // print_r($arrItem2);
        $arrItem1 = array_merge($arrItem1, $arrItem2);

    }
    // print_r($arrItem1);

    foreach ($arrItem1 as $v) {
        $idsArr[$v[0]] = $v;
    }

    foreach ($idsArr as $va) {
        $arrItem[] = $va;
    }

    usort ($arrItem, 'sortN');

    print_r($reqs);
    var_dump(count($idsArr));
    var_dump(count($arrItem));
    //print_r($idsArr);
    //print_r($arrItem);
        echo "<hr>";
    foreach ($arrItem as $key => $value) {
        echo "<hr># ";
        echo $key;
        echo "<br>";
        echo $value[1];
        echo "<br><b>";
        echo $value[2];
        echo "</b> p.<hr>";
    }
        echo "<hr>";


if ($_ERRORS) {
    var_dump('ERRORS =====>>>');
    print_r($_ERRORS);
}

    
