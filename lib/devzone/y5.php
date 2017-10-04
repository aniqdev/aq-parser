<?php ini_get('safe_mode') or set_time_limit(200); // Указываем скрипту, чтобы не обрывал связь.





$cd_arr = json_decode(file_get_contents('csv/eBayArtikel.json'), true);
$categories = json_decode(file_get_contents('csv/eBayArtikel_s2.json'), true);

foreach ($cd_arr as $key => $value) {
    $sorted_cats = cd_ebay_cat_sort($categories);
    $res = get_ebay_cat($value, $sorted_cats);

    echo ($key);
    sa($res);

    // if ($key > 100) {
    //     break;
    // }
}





return;
$str = '<div align="left">Entlastung der Entgiftungsorgane durch hohe Verf&uuml;gbarkeit von Calcium und Magnesium.</div>
<div align="left">&nbsp;</div>
<div align="left">Eine optimale Versorgung mit Mineralstoffen, Spurenelementen und Vitaminen unterst&uuml;tzt die physiologischen Organfunktionen und Stoffwechselvorg&auml;nge und ist Voraussetzung f&uuml;r ein gesundes Leben.</div>
<div align="left">&nbsp;</div>
<div align="left">Mineralerg&auml;nzungsfuttermittel f&uuml;r Schweine und Rinder</div>
<div align="left">&nbsp;</div>


<div align="left"><b><u>Zusammensetzung: </u></b>Algenkalk, Bierhefe, Malzkeime, Seealgenmehl, Traubenkerne extrahiert</div>
<div align="left">&nbsp;</div>
<div align="left"><b><u>Analytische Bestandteile und Gehalte</u></b>: Calcium 16,0%, Magnesium 1,00%, Phosphor &lt; 0,25%, Natrium 0,55%, salzs&auml;ureunl&ouml;sliche Asche 4,0%, Lysin 0,46%, Methionin 0,16%</div>
<div align="left">&nbsp;</div>
<div align="left"><b><u>F&uuml;tterungsempfehlung</u></b>: Rinder: 80g - 100g je nach Milchleistung und Mineralfuttergabe; Schweine: 3kg/t Babyferkel, 2kg/t Vormast, 1kg/t Endmast</div>
<div align="left"></div>';

$ddd = preg_match('/(.*)(<div[^\/]+Zusammensetzung.*)/s', $str, $matches);

var_dump($ddd);
sa($matches);


return;
sa(date('Y-m-d', time()-(60*60*24*30)));

return;
$csv = readExcel('csv/ebay art 18-09-2.xlsx');

sa($csv);

for ($i=2; $i < count($csv); $i++) { 
    
    break;

    $new_ebay_id = $csv[$i]['A'];

    $old_ebay_id = trim($csv[$i]['C']);

    if (!$old_ebay_id){
        continue;
    }

    arrayDB("UPDATE games SET ebay_id = '$new_ebay_id' WHERE old_ebay_id = '$old_ebay_id'");
}

for ($i=2; $i < count($csv); $i++) { 
    
    break;

    $new_ebay_id = $csv[$i]['A'];

    $title = _esc(trim($csv[$i]['B']));

    $old_ebay_id = arrayDB("SELECT item_id FROM ebay_games WHERE title = '$title'");

    if (!$old_ebay_id){
        sa($csv[$i]);
        continue;
    } 

    $old_ebay_id = $old_ebay_id[0]['item_id'];

    $csv[$i]['C'] = $old_ebay_id;

    arrayDB("UPDATE games SET ebay_id = '$new_ebay_id' WHERE old_ebay_id = '$old_ebay_id'");
}

// writeExcel('csv/ebay art 18-09-2.xlsx', $csv);


return;
$path = __DIR__.'/../../pictures/';
$from = __DIR__.'/../../pictures/Gruppenbild_LunaLupis.tif';
$to = __DIR__.'/../../pictures/Gruppenbild_LunaLupis.png';

// $imagine->open($from)->save($to);


$dir = scandir($path . 'tif/');

// sa($dir);
$format = 'png';

// sa(get_defined_constants());

    $imagick = new Imagick($path . 'tif/ArthroGreen_Classic.tif');
    $imagick->setImageFormat($format);
    $imagick->mergeImageLayers(imagick::LAYERMETHOD_UNDEFINED);
    // $imagick->scaleImage(1200, 1200, true);
    $imagick->scaleImage(
        min($imagick->getImageWidth(),  1200),
        min($imagick->getImageHeight(), 1200),
        true
    );
    // $imagick->adaptiveResizeImage(1200, 1200, true);
    var_dump($imagick->writeImage($path . $format.'/ArthroGreen_Classic.' . $format));


return;
$res = post_curl('http://hood.gig-games.de/api/listOrder', ['statusChange','startDate'=>'06/29/2017','endDate'=>'06/29/2017']);
if (isset($res['orderItems'])) $res = [$res];
sa($res);

$res = post_curl('http://hood.gig-games.de/api/listOrder', ['statusChange','startDate'=>'08/05/2017']);
if (isset($res['orderItems'])) $res = [$res];
sa($res);

return;

$res = post_curl('http://hood.gig-games.de/api/listOrder', ['statusChange','startDate'=> date('m/d/Y', time()-60*60*24)]);
if (isset($res['orderItems'])) $res = [$res];
sa($res);
return;

$txt = 'Key:SDF-G5YY4-YS45Y-S5YS4
Sdfsdf';
$key = get_steam_key_from_text($txt);
sa($key);

return;
$res = file_get_contents('https://graph.facebook.com/me?access_token=EAAGwIZBbQ34gBAKFDvZBqq9iQuo5PtC9zTd6X14zugEcAKTwZBBl2JXQTwAHgE1H1StEBLEpUcJzfBfUb1Yoxzip0gVXomyOE9c5okt5P92CSwjhZBVaNLBW1ghXWB6JAzA64BOlyoMF7by2ry9lkhEVU0sdbyCGx3xo04jW5g4NLRdD3KmXuLtykuM3qBYZD');
var_dump($res);
sa(json_decode($res,1));
return;

$query = "select * from `ebay_inv_messages_copy` limit 5,10";
$res = get_table_name($query);

sa($res);


return;

        $msg_email = html_entity_decode(html_entity_decode(html_entity_decode(get_messages_for_send_producr('DE', 'mail'))));
        $msg_ebay = html_entity_decode(get_messages_for_send_producr('DE', 'ebay'));

sa($msg_email);
sa($msg_ebay);

return;

        AutomaticBot::sendMessage(['text' => date('H:i:s').' there are NO any games suitable from the Games Table: '.'--']);
        return;

// sa(clean_ebay_title2('ArcaniA: Fall of Setarrif PC spiel Steam Download Link DE/EU/USA Key Code'));


// return;






//     $suitables = get_suitables($ebay_id = '111630067823');

// sa($suitables);

//     $suitables = get_suitables2($ebay_id = '111630067823');

// sa($suitables);

// return;




var_dump((int)[45,24] < 1);
var_dump((int)[]);


return;









$xml = '<?xml version="1.0" encoding="windows-1251"?>
<digiseller.response>
    <retval>0</retval>
    <retdesc></retdesc>
    <inv>
        <id>64823378</id>
        <name><![CDATA[GTA 5, CS GO, XCOM 2, ROCKET LEAGUE +  ПОДАРКИ ЗА ОТЗЫВ]]></name>
        <type_good>1</type_good>
        <wm_id>164322596678</wm_id>
        <link>https://www.oplata.info/info/buy.asp?id_i=64823378&uid=23CAE9F194D049DC81AF33F018A6761C</link>
        <wm_inv>693559165</wm_inv>
        <wm_purse>R939284726752</wm_purse>
        <uid>папа</uid>
    </inv>
</digiseller.response>';

$res = simplexml_load_string(str_replace(['&','windows-1251'], ['&amp;','utf-8'], $xml));

sa($res);






return;

$platiObj = new PlatiRuBuy();

$itemid = '1739981';

$res = $platiObj->getInvoice($itemid);

sa($res);







// AutomaticBot::sendMessage(['text' => date('H:i:s').'tesst']);



return;


$str = 'феврал';
// echo substr_replace($str,'я',-1);
sa($_SERVER);
var_dump(file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/ajax.php?action=cron-hood-messages'));

// $body = post_curl('http://hood.gig-games.de/api/getMessageText', ['messageId' => '8923934']);
// sa($body);


	// $res = post_curl('http://hood.gig-games.de/api/getOutboxMessages', ['lastInboxMessageId' => 0, 'lastOutboxMessageId' => 0]);
	// sa($res);

function round_price($price)
    {
        $array_round = array(
            1 => 0, 2 => 0, 3 => 5, 4 => 5, 5 => 5,
            6 => 5, 7 => 5, 8 => 9, 9 => 9, 0 => 0,
        );
        if ($price == '') return FALSE;
        $price = str_replace(",", ".", $price);

        if ($price * 0.01 < 0.05) {
            return $price - 0.05;
        }
        $price_new = $price * 0.99;

        $res = round($price_new, 2);
        $end_price = explode('.', $res);

        $rest = substr($end_price[1], -1);
        $replace = $array_round[$rest];
        if (strlen($end_price[1]) == 2) {
            $rest = substr_replace($end_price[1], $replace, -1);
            return $end_price[0] . '.' . $rest;
        }
        $price_new = strtr($rest, $array_round);
        $price_new = $end_price[0] . '.' . $end_price[1];
        return $price_new;
    }

function aqs_round_price($price='')
{
	if (!$price) return false;

    $round = array(
        1 => 0, 2 => 0, 3 => 5, 4 => 5, 5 => 5,
        6 => 5, 7 => 5, 8 => 9, 9 => 9, 0 => 0,
    );
	$price = round($price, 2);
	if ($price < 5) $price = $price - 0.05;
	else			$price = $price * 0.99;
	
	$price = number_format($price, 2);
	$price = substr_replace($price, $round[+$price[strlen($price)-1]],-1);
	return $price;
}
// var_dump(round(5.555,2));
// var_dump(number_format(5.555,2));

// sa([$n = '2.06',aqs_round_price($n),round_price($n)]);
// sa([$n = '4.9',aqs_round_price($n),round_price($n)]);
// sa([$n = '5.23',aqs_round_price($n),round_price($n)]);
// sa([$n = '5.3',aqs_round_price($n),round_price($n)]);
// sa([$n = '5.400',aqs_round_price($n),round_price($n)]);
// sa([$n = '5.51',aqs_round_price($n),round_price($n)]);
// sa([$n = '5.65',aqs_round_price($n),round_price($n)]);
// sa([$n = '5.76',aqs_round_price($n),round_price($n)]);
// sa([$n = '5.83',aqs_round_price($n),round_price($n)]);
// sa([$n = '5.999',aqs_round_price($n),round_price($n)]);
// $a = 5.1*100;
// var_dump($a);
// var_dump($a%5);

// sa(hoodItemSync(['122455219488']));

// function printArray($array) 
// { 
// 	$result = ""; 
// 	for($i=0;$i<count($array);$i++) 
// 	$result.=$array[$i]." "; 
// 	return $result."<br>"; 
// } 
// echo "Треугольник Паскаля<br><br>";
// $tri = array(1,array(1,1)); 
// echo $tri[0]."<br>"; 
// for($i=1;$i<7;$i++) 
// {
// 	$last = $tri[count($tri)-1];
// 	echo printArray($last); 
// 	$added = array();
// 	array_push($added,1); 
// 	for($j=0;$j<count($last)-1;$j++) 
// 	array_push($added, $last[$j]+$last[$j+1]); 
// 	array_push($added,1); 
// 	array_push($tri,$added); 
// }


$time_string = '14.06.17
um 17:08:07';

$format = hood_date_format($time_string);
var_dump($format);
echo "<hr>";

// var_dump(json_decode('[]',1));



// 	$res = post_curl('http://hood.gig-games.de/api/getInboxMessages', ['lastInboxMessageId' => '8972780']);
// var_dump($res);
// 	foreach ($res as $k => $msg) {
// 		var_dump($msg['idMessage']);
// 		echo "<br>";
// 	}
// 	sa($res);









// sa(hoodItemSync(['122478213637','112394797943','112334597943']));

// 	$myCurl = curl_init('http://hood.gig-games.de/api/import');
// 	curl_setopt_array($myCurl, [
// 	    CURLOPT_RETURNTRANSFER => true,
// 	    CURLOPT_POST => true,
// 	    CURLOPT_POSTFIELDS => http_build_query(['122455219488'])
// 	]);
// 	$response = curl_exec($myCurl);
// 	curl_close($myCurl);
// var_dump($response);

// sa(post_curl('http://hood.gig-games.de/api/getMessageText', ['messageId' => '8951628']));

// sa(post_curl('http://hood.gig-games.de/api/getInboxMessages', ['lastInboxMessageId' => '8950917']));


// $a = 0;

// switch($a) {
// case 0.01:
//         echo 'answer1';
// case $arr['1']:
//         echo 'answer2';
// case 0:
//         echo 'answer3';
// case 'true':
//         echo 'answer4'; continue;
// case NULL:
//         echo 'answer5'; break;
// default:
//         echo 'default';

// }
// echo '<br />';





// $a1 = "2abcde0";
// $a2 = true;

// settype($a1, integer);
// settype($a2, string);
// echo $a1, $a2;





// class Clazz { 
//     public $value; 
// } 
 
// $b = new Clazz; 
// $b->newValue = 1; 
 
// $a = $b; 
// $a->newValue = 2; 
 
// echo $b->newValue; 








// class c{ 
//     private $a = 42; 
//     function &a(){ 
//         return $this->a; 
//     } 
//     function print_a(){ 
//         echo $this->a; 
//     } 
// } 
// $c = new c; 
// $d = &$c->a(); 
// echo $d; 
// $d = 2; 
// $c->print_a();






// class People { 
//  public function greeting() { 
//    echo "A nice smile for you. "; 
//  } 
// } 
// trait MyParents { 
//   public function greeting() { 
//   parent::greeting(); 
//   $this->greeting2(); 
//   echo "A big hug for you. "; 
//  } 
// } 
// class MyMom extends People { 
//  use MyParents; 
//  public function greeting2() { 
//     echo "A big kiss for you. "; 
//  } 
// } 
// class MyDad extends People { 
//  use MyParents; 
//  public function greeting2() { 
//     echo "A strong handshake for you. "; 
//  } 
// } 
 
// $person = new MyMom(); 
// $person->greeting(); 





// (new CreateDesc2017(0))->run();





return;

	$res = getSingleItem('121966139435',['as_array'=>true,'IncludeSelector'=>'Description']);

	$full_desc = $res['Item']['Description'];

	$dom = str_get_html($full_desc);

	$images = [];

	if($img1 = @$dom->find('[src$="/1.jpg"]',0)->src) $images[] = $img1;
	if($img2 = @$dom->find('[src$="/2.jpg"]',0)->src) $images[] = $img2;
	if($img3 = @$dom->find('[src$="/3.jpg"]',0)->src) $images[] = $img3;

	sa($images);

?>