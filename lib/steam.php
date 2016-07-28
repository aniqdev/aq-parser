<?php
if (isset($_GET['page'])):
header('Content-Type: text/html; charset=utf-8');
$page = (int)$_GET['page'];
ini_get('safe_mode') or set_time_limit(0); // Указываем скрипту, чтобы не обрывал связь.
include('simple_html_dom.php');
//include('PHPExcel.php');
require_once('array_DB.php');
$array = array();

// В следующей строчке Steam_Language=german, можно указывать другие языки вместо german
$options = array('http' => array('method' => "GET", 'header' => "Accept-language: de\r\n" . "Cookie: Steam_Language=russian; mature_content=1; birthtime=238921201; lastagecheckage=28-July-1977\r\n"));
$context = stream_context_create($options);

// Основная ссылка, с которой мы парсим игры
$data = file_get_html('http://store.steampowered.com/search/?sort_by=Released_DESC&category1=998&page=1', false, $context);
// Получаем общее кол-во страниц
$pages = $data->find('.search_pagination_right a', count($data->find('.search_pagination_right a')) - 2)->innertext;

// Запускаем парсинг... от 1 до последней страницы
// Начальная страница это $x = 1 (цифру можно изменить), конечная страница по умолчанию $pages (можно изменить на цифру). $x++ Вообще не трогаем.

for ($x = $page; $x <= $page; $x++) {
    $data_game = file_get_html('http://store.steampowered.com/search/?sort_by=Released_DESC&category1=998&page=' . $x, false, $context);
    
    foreach ($data_game->find('#search_result_container a[data-ds-appid]') as $a) {
   
        $game_item = file_get_html($a->href, false, $context);

        // пропускаем игру в случае ошибки
        if (!is_object($game_item)) continue;

        $languages = '';
        // $price = $game_item->find('.game_purchase_price', 0);
        // ($price) ? $price = $price->innertext : $price = '';
        $price = strip_tags(preg_replace("'<span[^>]*?>.*?</span>'si", '', $a->find('.search_price', 0)->innertext));

        $year = trim($a->find('.search_released', 0)->innertext);
        $year = substr($year, strlen($year) - 4, 4);
        foreach ($game_item->find('.game_language_options tr[style]') as $lang_item) {
            if (count($lang_item->find('img', 0)) > 0) {
                $languages .= ($languages !== '' ? ', ' : '') . trim($lang_item->find('td', 0)->plaintext);
            }
        }

        if ($title = $game_item->find('.apphub_AppName',0)) {
            $title = $title->innertext;

            $desc = $game_item->find('#game_area_description', 0);
            ($desc) ? $desc = $desc->innertext : $desc = '';
            $desc = strip_tags($desc, '<br><br/><br /><p><h2><strong><b><i><ul><li>');

        }elseif ($title = $game_item->find('.pageheader',0)) {
            $title = $title->innertext;

            $desc = '';
            foreach ($game_item->find('.tab_item_name') as $overlay) {
                $desc .= '"'.$overlay->plaintext.'" ';
            }

            $year = 101;
        }else{
            $title = '';
        }

        $genre = $game_item->find('.breadcrumbs a', 1);
        ($genre) ? $genre = str_replace("&nbsp;", " ", $genre->innertext) : $genre = '';

        $release = $a->find('.search_released', 0);
        ($release) ? $release = $release->innertext : $release = '';

        $searchInPrice = array('p&#1091;&#1073;.','&#36;','$');
        $price = trim(str_replace("&nbsp;", " ", str_replace($searchInPrice, "", $price)));
        $os_tab = $game_item->find('.sysreq_tab');
        $os = array();
        foreach ($os_tab as $key => $value) {
            $os[$key] = trim($value->innertext);
        }
        $sys_req = $game_item->find('.game_area_sys_req',0);
        ($sys_req) ? $sys_req = $sys_req->plaintext : $sys_req = '';

        $reviews = $game_item->find('div[data-store-tooltip]',0);
        ($reviews) ? $reviews = $reviews->attr['data-store-tooltip'] : $reviews = '';
        preg_match_all("/[\d]+/", $reviews, $matches);
        if (isset($matches[0][0])) {
            if (isset($matches[0][2])) {
                $matches[0][1] = $matches[0][1].$matches[0][2];
            }
            $rating = $matches[0][0];
            $reviewss = $matches[0][1];
        }else{
            $rating = '';
            $reviewss = '';
        }



        $link = $a->href;

        // echo "<br><h4>title-=-</h4>",$title;
        // echo "<br><h4>link-=-</h4>",$link;
        // echo "<br><h4>desc-=-</h4>",$desc;
        // echo "<br><h4>genre-=-</h4>",$genre;
        // echo "<br><h4>price-=-</h4>",$price;
        // echo "<br><h4>year-=-</h4>",$year;
        // echo "<br><h4>release-=-</h4>",$release;
        // echo "<br><h4>lang-=-</h4>",$languages;
        // echo "<br><h4>os-=-</h4>",implode(",", $os);
        // echo "<br><h4>sys_req-=-</h4>",$sys_req;
        // echo "<br><h4>rating-=-</h4>",$rating;
        // echo "<br><h4>reviewss-=-</h4>",$reviewss,'<hr><style>h4{display:inline}</style>';
        $title   = mysql_escape_string(trim($title));
        $link    = mysql_escape_string(trim($link));
        $desc    = mysql_escape_string(trim($desc));
        $genre   = mysql_escape_string(trim($genre));
        $price   = mysql_escape_string(trim($price));
        $year    = mysql_escape_string(trim($year));
        $release = mysql_escape_string(trim($release));
        $lang    = mysql_escape_string(trim($languages));
        $os      = mysql_escape_string(implode(",", $os));
        $sys_req = mysql_escape_string(trim($sys_req));
        $rating  = mysql_escape_string($rating);
        $reviewss= mysql_escape_string($reviewss);
        arrayDB("INSERT INTO steam VALUES (null,'$title','$link','$genre','$price','$year','$release','$lang','$desc','$os','$sys_req','$rating','$reviewss')");

    }//foreach по одной странице
echo json_encode( array(
    'pages' => $pages,
    'errors' => $_ERRORS
    ));
}//for по страницам

else: ?>
<h3>парсим игры Стим (подробно)</h3>
<button id="get-steam" class="get-steam-btn">get-steam</button>
<span class="loading"></span>
<h3>Состояние процесса:</h3>
<ul id="message" class="message"><li></li></ul>
<?php
endif; // if($_GET['page'])

?>