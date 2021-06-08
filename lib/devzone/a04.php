<?php ini_get('safe_mode') or set_time_limit(1300);





function createPhoneNumber($n) {
    return "($n[0]$n[1]$n[2]) $n[3]$n[4]$n[5]-$n[6]$n[7]$n[8]$n[9]";
}

assertEquals2('(123) 456-7890', createPhoneNumber([1, 2, 3, 4, 5, 6, 7, 8, 9, 0]));
assertEquals2('(111) 111-1111', createPhoneNumber([1, 1, 1, 1, 1, 1, 1, 1, 1, 1]));


return;
function toWeirdCase($string) {
    $words = explode(' ', $string);
    foreach ($words as &$word) {
        $letters = str_split($word);
        foreach ($letters as $key => &$letter) {
            $letter = ($key % 2) ? strtolower($letter) : strtoupper($letter);
        }
        $word = implode('', $letters);
    }
    return implode(' ', $words);
}

function toWeirdCase_2($string) {
    return preg_replace_callback('/(\w)(.)?/', function ($match){ 
        return strtoupper($match[1]).strtolower(@$match[2]);
    }, $string);
}

assertEquals2('HeLlO WoRlD FoO BaR BaZ', toWeirdCase('Hello world foo bar baz'));
assertEquals2('WeLl I GuEsS YoU PaSsEd', toWeirdCase('wEll i GuesS you passed'));


function assertEquals2($aaa, $bbb)
{
   sa($aaa . '<br>' . $bbb);
}


return;
function arrayDiff($a, $b) {
    return array_values(array_filter($a, function($el) use ($b){
        return !in_array($el, $b);
    }));
}


assertEquals1([2], arrayDiff([1,2], [1]), "a was [1,2], b was [1], expected [2]");
assertEquals1([2,2], arrayDiff([1,2,2], [1]), "a was [1,2,2], b was [1], expected [2,2]");
assertEquals1([1], arrayDiff([1,2,2], [2]), "a was [1,2,2], b was [2], expected [1]");
assertEquals1([1,2,2], arrayDiff([1,2,2], []), "a was [1,2,2], b was [], expected [1,2,2]");
assertEquals1([], arrayDiff([], [1,2]), "a was [], b was [1,2], expected []");
assertEquals1([3], arrayDiff([1, 2, 3], [1,2]), "a was [1, 2, 3], b was [1,2], expected [3]");

function assertEquals1($arr1, $arr2, $comment)
{
    echo "<pre>";
    print_r($arr1);
    print_r($arr2);
    echo "$comment";
    echo "</pre>";
}



return;
function find_even_index($arr){
    $sum_left = 0;
    foreach ($arr as $key => $value) {

        $sum_right = 0;
        for ($i = $key + 1; $i < count($arr); $i++) { 
            $sum_right += $arr[$i];
        }
        if($sum_left === $sum_right) return $key;

        $sum_left += $value;
    }
    return -1;
}

function find_even_index_2($arr){
    $left_sum = 0;
    foreach ($arr as $key => $value) {
        $first_el = array_shift($arr);
        if($left_sum === array_sum($arr)) return $key;
        $left_sum += $first_el;
    }
    return -1;
}

function find_even_index_3($arr, $num = 0){
    foreach ($arr as $key => $value) if(array_sum($arr) - $num === $num += $value) return $key;
    return -1;
}

sa(3 . ' === ' . find_even_index_3([1,2,3,4,3,2,1])); 
sa(1 . ' === ' . find_even_index_3([1,100,50,-51,1,1]));
sa(-1 . ' === ' . find_even_index_3([1,2,3,4,5,6]));
sa(3 . ' === ' . find_even_index_3([20,10,30,10,10,15,35]));
sa(0 . ' === ' . find_even_index_3([20,10,-80,10,10,15,35]));
sa(6 . ' === ' . find_even_index_3([10,-80,10,10,15,35,20]));
sa(-1 . ' === ' . find_even_index_3(range(1,100)));
sa(0 . ' === ' . find_even_index_3([0,0,0,0,0]));
sa(3 . ' === ' . find_even_index_3([-1,-2,-3,-4,-3,-2,-1]));
sa(-1 . ' === ' . find_even_index_3(range(-100,-1)));


return;
function reverseWords($string)
{
    return preg_replace_callback('/\S/U', function($m)
    // return preg_replace_callback('/(^|\s|\b)(\S+)($|\s)/U', function($m)
    {
        return $m[0];
        // return $m[1].strrev($m[2]).$m[3];
    }, $string);
}

function reverseWords_2($str) {
    return implode(' ', array_reverse(explode(' ', strrev($str)))) ;
}

sa('dluow   ton  si  ataK  evah  modnar  a  .noitulos  stset  esac  yhW  diova  ti  syawla  sihT  eruS  !si  ?eb  ti  ot  !elur');
sa(reverseWords_2('would   not  is  Kata  have  random  a  solution.  tests  case  Why  avoid  it  always  This  Sure  is!  be?  it  to  rule!'));
echo "<hr>";
sa('elbuod  decaps  sdrow');
sa(reverseWords_2('double  spaced  words'));

echo "<hr>";
sa('ehT kciuq nworb xof spmuj revo eht yzal .god');
sa(reverseWords_2('The quick brown fox jumps over the lazy dog.'));




return;
function toCamelCase($str){
    $arr = str_split($str);
    $last_letter = '';
    foreach ($arr as &$letter) {
        if ($last_letter === '-' || $last_letter === '_') {
            $letter = strtoupper($letter);
        }
        $last_letter = $letter;
    }
    $str = implode('', $arr);
    return str_replace(['-','_'], '', $str);
}

function toCamelCase_2($str){
  return preg_replace_callback("/[_-](\w)/", function($matches)
    {
        return strtoupper($matches[1]); 
    }, $str);
}

sa(toCamelCase_2("the_stealth_warrior"));
sa(toCamelCase_2("The-Stealth-Warrior"));



return;
$string = 'Lorem ipsum dolor sit amet, consectetur adipisicing, elit. Consequatur dignissimos doloribus, distinctio nemo a facere non illo reiciendis perspiciatis fugit explicabo voluptas nesciunt aperiam suscipit nobis rerum esse laborum minus?';


function high($text) {
    $abc = range('a','z');

    $abc = array_flip($abc);

    $words = explode(' ', $text);

    $highest_word = '';
    $highest_score = 0;
    foreach ($words as $word) {
        $word_scores = 0;
        foreach (str_split($word) as $letter) {
            $word_scores += ($abc[$letter] + 1);
        }
        if ($word_scores > $highest_score) {
            $highest_score = $word_scores;
            $highest_word = $word;
        }
    }

    return $highest_word;
}

sa(high('man i need a taxi up to ubud'));
sa(high('what time are we climbing up the volcano'));
sa(high('take me to semynak'));
sa(high('aa b'));
sa(high('b aa'));
sa(high('bb d'));
sa(high('d bb'));





return;

$string = '123ff';

function duplicate_encode($string)
{
    $string = strtolower($string);

    $chars = count_chars($string, 1);

    $letters = str_split($string);

    $letters = array_map(function($el) use ($chars)
    {
        return $chars[ord($el)] > 1 ? ')' : '(';
    }, $letters);

    return implode('', $letters);
}

function duplicate_encode_2($word)
{
  $arr = array_map(function($el) use ($word)
  {
      return preg_match_all("/[$el]{1}/i", $word) > 1 ? ')' : '(';
  }, str_split($word));
  
  return implode('', $arr);
}

sa(duplicate_encode('din'));
sa(duplicate_encode('recede'));
sa(duplicate_encode('Success'));
sa(duplicate_encode('iiiiii'));
sa(duplicate_encode(' ( ( )'));
sa('<hr>');
sa(duplicate_encode_2('din'));
sa(duplicate_encode_2('recede'));
sa(duplicate_encode_2('Success'));
sa(duplicate_encode_2('iiiiii'));
sa(duplicate_encode_2(' ( ( )'));

return;
  $result4 = detect_pangram_3("The quick brown fox jumps over the lazy dog.");
  sa($result4);
  $result5 = detect_pangram_3("1L%r+f4G!e7w V z q6M h4d F3b+t O2n e K^g+c#S^i4i X7c-u P5d7j Y6a(a B");
  sa($result5);
  
  # Not pangrams:
  $result1 = detect_pangram_3("A pangram is a sentence that contains every single letter of the alphabet at least once.");
  sa( $result1 );
  $result2 = detect_pangram_3("5B!e i J x*p F h d!A:o q D y n6L%u9i.G9f2g4C a h+K!m+z:R t!j:B w s C");
  sa( $result2);


function detect_pangram($string) { 
  $string = strtolower($string);
  $string = preg_replace('/[^a-z]/', '', $string);
  $arr = str_split($string);
  $arr = array_flip($arr);
  return count($arr) === 26;
}

function detect_pangram_2($string)
{
    $abc = range('a', 'z');
    $string = strtolower($string);
    // $string = preg_replace('/[^a-z]/', '', $string);
    $arr = str_split($string);
    return !array_diff($abc, $arr);
    // sa($res);
}
function detect_pangram_3($s) { 
  return preg_match_all('/([a-z])(?!.*\1)/i', $s, $m) && isset($m[0][25]);
  sa($m);
}


return;
$file = csvToArr('./Files/wp_posts.csv',['delimetr' => ',']);

sa(count($file));
$shop_ids = array_column($file,0,2);
sa($shop_ids);



return;
$cdvet_feed = json_decode(file_get_contents('csv/cdvet_feed.json'), true);

// sa($cdvet_feed);

// $feed_new = csvToArr('https://www.cdvet.de/backend/export/index/productckeck?feedID=47&hash=a4dc5afc43b82eefd412334d8ed3239e', ['max_str' => 0,'encoding' => 'windows-1250', 'del_first' => true]);

$feed_new = file_get_contents('cdvet/cdvet-feed-3239e.json');

$feed_new = json_decode($feed_new, true);

sa($feed_new);

$feed_items = [];
foreach ($cdvet_feed as $key => $value) {
    unset($value[9]);
    $feed_items[$value[14]][] = $value;
}

sa($feed_items['https://www.cdvet.de/equigreen-micromineral']);



return;
$generator = new Picqer\Barcode\BarcodeGeneratorJPG();
// echo $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);
echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode('081231723897', $generator::TYPE_CODE_128)) . '">';





return;
$res = csvToArr('csv/emotions_marafon_2.csv', ['del_first' => true]);

draw_table_with_sql_results($res);




return;
$start_time = time();

$items = arrayDB("SELECT * from moda_list where flag = 'dataparsed1' limit 100");

$keys_arr = [
    'itemId',
    'title',
    'categoryId',
    'PictureURL',
    'QuantitySold',
    'HitCount',
    'currentPrice',
    'FeedbackScore', // seller FeedbackScore
    'ItemSpecifics',
    'VariationsPics',
    'post_id',
];

echo "<pre>";
$final_arr = [];
$handle = fopen(ROOT . '/Files/moda-arr.txt',"w");
foreach ($items as &$moda) {
    $moda_meta = get_moda_meta($moda['id']);
    if($moda_meta) $moda += $moda_meta;
    // sa($moda);
    $temp_arr = [];
    foreach ($keys_arr as $key) {
        $temp_arr[$key] = isset($moda[$key]) ? $moda[$key] : '';
    }
    $final_arr[] = $temp_arr;
    $bytes = fwrite($handle, json_encode($temp_arr).PHP_EOL);
    // var_export(PHP_EOL.$bytes.': ');
    print_r($temp_arr);
}
fclose($handle);
// $str_to_file = var_export($final_arr, true);
// $bytes_count = file_put_contents(ROOT . '/Files/moda-arr.eval.txt', 'return ' . $str_to_file . ';');


// var_dump($bytes_count);
echo "</pre>";
sa('Seconds: ' . (time() - $start_time));




return;
$url = 'https://touch.com.ua/zaryadnye-ustroystva/';


sa( rawurlencode(get_partner_link($url) ));




return;
$post_res = post_curl('https://gig-games.de', '$json_str', ['Content-Type: application/json; charset=utf-8']);

sa($post_res);



return;
function _draw_wp_cats_recur(&$arr, $parent_id)
{
    if (!$arr) return;

    if (isset($arr[$parent_id])) {
        echo '<ul>';
        foreach ($arr[$parent_id] as $val) {
            // href="https://modetoday.de/fashion_category/'.$val['slug'].'/"
            echo '<li><sp  target="_blank" title="'.$val['name'].'">'.$val['name'].' ('.$val['count'].')</sp>';
            if($val['term_id'] != $val['parent']) _draw_wp_cats_recur($arr, $val['term_id']);
            echo '</li>';
        }
        echo '</ul>';
    }
    
}
function _draw_wp_cats($parent_id = '0')
{
    $res = hundDB("SELECT DISTINCT t.*, tt.*
    FROM wp_terms AS t
    INNER JOIN wp_term_taxonomy AS tt
    ON t.term_id = tt.term_id
    WHERE tt.taxonomy = 'product_cat'");

    $parent_keys = [];
    foreach ($res as $val) {
        $parent_keys[$val['parent']][$val['term_id']] = $val;
    }

    // sa($parent_keys);

    _draw_wp_cats_recur($parent_keys, $parent_id);
}


return;
$res = unserialize('a:1:{s:13:"auslandisches";a:6:{s:4:"name";s:14:"Ausländisches";s:5:"value";s:9:"Ya | Nein";s:8:"position";i:1;s:10:"is_visible";i:1;s:12:"is_variation";i:1;s:11:"is_taxonomy";i:0;}}');

sa($res);

$arr = array (
  'pa_marke' => 
  array (
    'name' => 'pa_marke',
    'value' => '',
    'position' => 0,
    'is_visible' => 1,
    'is_variation' => 0,
    'is_taxonomy' => 1,
  ),
  'pa_produktart' => 
  array (
    'name' => 'pa_produktart',
    'value' => '',
    'position' => 1,
    'is_visible' => 1,
    'is_variation' => 0,
    'is_taxonomy' => 1,
  ),
  'pa_att-name' => 
  array (
    'name' => 'pa_att-name',
    'value' => '',
    'position' => 2,
    'is_visible' => 0,
    'is_variation' => 0,
    'is_taxonomy' => 1,
  ),
  'attribute' => array (
    'name' => 'Attribute',
    'value' => 'some value',
    'position' => 3,
    'is_visible' => 1,
    'is_variation' => 0,
    'is_taxonomy' => 0,
  ),
  'attribute' => array (
    'name' => 'pa_hundegrose',
    'value' => '',
    'position' => 4,
    'is_visible' => 1,
    'is_variation' => 0,
    'is_taxonomy' => 1,
  ),
);

sa(serialize($arr));

return;
draw_cats_recursion($CategoryParentID, 'CategoryName_DE');


function draw_cats_recursion($CategoryParentID, $cat_name_field = 'CategoryName')
{
    $res = arrayDB("SELECT * from moda_cats where CategoryParentID= '$CategoryParentID'");
    if ($res) {
        echo "<ul>";
        foreach ($res as $val) {
            if($val['CategoryID'] === '20749') echo "<li><mark title='".$val['CategoryID']."'>".$val[$cat_name_field].'</mark>';
            else echo "<li><span title='".$val['CategoryID']."'>".$val[$cat_name_field];
            if($val['CategoryID'] != $val['CategoryParentID']) draw_cats_recursion($val['CategoryID']);
            echo "</li>";
        }
        echo "</ul>";
    }
}




return;
    if(defined('DEV_MODE')) $post_uri = 'http://koeln-webstudio.loc/moda-sync.php';
    else $post_uri = 'https://modetoday.de/moda-sync.php?wpok';

    $post_resp = post_curl($post_uri, [
        'action' => 'update',
        'moda_id' => '9790',
    ]);

    sa($post_resp);




return;
$res = Ebay_shopping2::findItemsAdvanced_moda($categoryId = '169291', $page = 100);

$res = json_decode($res,1);


    $res = gml_clean_result($res);
sa($res);


return;
$resp = Ebay_shopping2::getSingleItem_moda('264696880829', $as_array = 1);

unset($resp['Item']['ItemSpecifics']);

sa($resp['Item']['QuantitySold']);
sa($resp);

return;
$moda_id = 18769;

gmp_make_post_request('update', $moda_id);



function gmp_make_post_request($action, $moda_id)
{
    if(defined('DEV_MODE')) $post_uri = 'http://koeln-webstudio.loc/moda-sync.php';
    else $post_uri = 'https://modetoday.de/moda-sync.php?wpok';

    $post_resp = post_curl($post_uri, [
        'action' => $action,
        'moda_id' => $moda_id,
    ]);

    sa($post_resp);

    return $post_resp['func_res'];
}


return;
$options = array('http' => array('method' => "GET", 'header' => "Accept-language: en-US\r\n" . "Cookie: Steam_Language=".get_language_by_table('steam_de')."; mature_content=1; birthtime=238921201; lastagecheckage=28-July-1977\r\n"));
$context = stream_context_create($options);
$link = 'http://store.steampowered.com/app/502410/';

$game_item = aqs_file_get_html($link, false, $context);

if ($title = $game_item->find('.apphub_AppName',0)) { // для app|dls
    $title = $title->innertext;

    $desc = $game_item->find('#game_area_description', 0);
    $desc = ($desc) ? $desc->innertext : '';
    $desc = strip_tags($desc, '<br><br/><br /><p><h2><strong><b><i><ul><li>');

}

sa($title);



return;
    if(defined('DEV_MODE')) $post_uri = 'http://koeln-webstudio.loc/moda-sync.php';
    else $post_uri = 'https://modetoday.de/moda-sync.php?wpok';

    $post_resp = post_curl($post_uri, [
        'action' => 'update',
        'moda_id' => 7304,
    ]);

    sa($post_resp);

return;
$a = [0=>2,1=>3,2=>4];

$b = [2=>'7',7=>'8',8=>'9'];

$a =  array_merge($a, $b);

sa($a);




return;
$items = arrayDB("SELECT *, UNIX_TIMESTAMP(endTime) as timest from moda_list where flag = 'dataparsed1' order by id desc limit 300");
?>
<style>
fg,fb{
    height: 10px;
    width: 10px;
    display: inline-block;
    background: lightgreen;
}
fb{
    background: lightcoral;
}
</style>
<div class="container"><br><br>
    <table class="table">
    <?php
    foreach ($items as $key => $item) {
        $mark = $item['timest'] > time() ? '<fg></fg>' : '<fb></fb>';
        echo "<tr>";
        echo "<td>{$item['itemId']}</td>";
        echo "<td><a href='https://www.ebay.de/itm/{$item['itemId']}' target='_blank'>{$item['title']}</a></td>";
        echo "<td>{$item['ListingType']}</td>";
        echo "<td>{$item['endTime']}</td>";
        echo "<td>{$mark}</td>";
        echo "</tr>";
    }
    ?>
    </table>
</div>
<?php


return;
    
    $resp = Ebay_shopping2::getSingleItem_moda($itemId = '382532184636', $as_array = 1);

    var_dump($itemId);
    
    sa($resp);




return;
    $items = arrayDB("SELECT * from moda_list where flag = 'dataparsed1' and ListingType = ''");

    foreach ($items as $moda) {
        $moda_id = $moda['id'];
        $ListingType = get_moda_meta($moda_id, $meta_key = 'ListingType');
        $ListingType = _esc($ListingType);
        arrayDB("UPDATE moda_list SET ListingType = '$ListingType' where id = $moda_id");
    }

sa(count($items));



return;
define('CRM_HOST', 'b24-1cbkwk.bitrix24.ru'); // Домен срм системы
define('CRM_PORT', '443'); 
define('CRM_PATH', '/crm/configs/import/lead.php'); 
define('CRM_LOGIN', 'thenav@mail.ru');  // логин
define('CRM_PASSWORD', 'kajmad'); // пароль
 
/********************************************************************************************/
$test = true;




if ($_SERVER['REQUEST_METHOD'] == 'POST' || $test){
 
    // получаем данные из полей и задаем название лида
     
    $postData = array(
        'TITLE' => 'Форма обратной связи', // сохраняем нашу метку и формируем заголовок лида
        'NAME' => '_NAME',   // сохраняем имя
        'PHONE_WORK' =>'_PHONE_WORK', // сохраняем телефон
        'EMAIL_WORK' => 'asd@asd.df', // сохраняем почту
        'UF_CRM_1443598721' => '_UF_CRM_1443598721', // сохраняем ИНН
    );
 
    // авторизация, проверка логина и пароля
    if (defined('CRM_AUTH'))
    {
        $postData['AUTH'] = CRM_AUTH;
    }
    else
    {
        $postData['LOGIN'] = CRM_LOGIN;
        $postData['PASSWORD'] = CRM_PASSWORD;
    }
 
    $fp = fsockopen("ssl://".CRM_HOST, CRM_PORT, $errno, $errstr, 30);
    if ($fp)
    {
        // формируем и шифруем строку с данными из формы
        $strPostData = '';
        foreach ($postData as $key => $value) $strPostData .= ($strPostData == '' ? '' : '&').$key.'='.urlencode($value);
            $str = "POST ".CRM_PATH." HTTP/1.0\r\n";
            $str .= "Host: ".CRM_HOST."\r\n";
            $str .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $str .= "Content-Length: ".strlen($strPostData)."\r\n";
            $str .= "Connection: close\r\n\r\n";
 
        $str .= $strPostData;

        sa($str);
 
        // отправляем запрос в срм систему
        fwrite($fp, $str );
        $result = '';
        while (!feof($fp))
        {
            $result .= fgets($fp, 128);
        }
        fclose($fp);
 
        $response = explode("\r\n\r\n", $result);
        $output = '<pre>'.print_r($response[1], 1).'</pre>';
    }
    else
    {
        echo 'Connection Failed! '.$errstr.' ('.$errno.')';
    }
}