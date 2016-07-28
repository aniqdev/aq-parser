<?php
if (isset($_GET['page'])):
$x = (int)$_GET['page'];
ini_get('safe_mode') or set_time_limit(1500); // Указываем скрипту, чтобы не обрывал связь.
include_once('simple_html_dom.php');
//include('PHPExcel.php');
require_once('array_DB.php');
$array = array();

// В следующей строчке Steam_Language=german, можно указывать другие языки вместо german
$options = array('http' => array('method' => "GET", 'header' => "Accept-language: de\r\n" . "Cookie: Steam_Language=german; birthtime=238921201; lastagecheckage=28-July-1977\r\n"));
$context = stream_context_create($options);

// Получаем общее кол-во страниц
if ($_GET['pages']) {
    $pages = $_GET['pages'];
}else{
    $data = file_get_html('http://store.steampowered.com/search/?sort_by=Released_DESC&page=1', false, $context);
    $pages = $data->find('.search_pagination_right a', count($data->find('.search_pagination_right a')) - 2)->innertext;
}
//  логика получения отметки mark(количество минуть начала эпохиUnix)
if ($_GET['mark']) {
    $mark = $_GET['mark'];
}else{
    $mark = (int)(time()/60);
}
// Запускаем парсинг... от 1 до последней страницы
// Начальная страница это $x = 1 (цифру можно изменить), конечная страница по умолчанию $pages (можно изменить на цифру). $x++ Вообще не трогаем.

    // Основная ссылка, с которой мы парсим игры
    $page = file_get_html('http://store.steampowered.com/search/?sort_by=Released_DESC&page=' . $x, false, $context);

    $titleArr = array();
    $priceArr = array();
    $ratingArr = array();
    $reviewsArr = array();
    foreach ($page->find('.search_result_row') as $key => $val) {

        $title = $page->find('.title', $key);
        ($title) ? $title = $title->innertext : $title = '';

        $link = $val->href;

        $sub = explode('/', trim($link))[3];

        if ($sub === 'sub') {
            $appid = $val->getAttribute('data-ds-packageid');
        }else{
            $appid = $val->getAttribute('data-ds-appid');
        }

        $year = trim($val->find('.search_released', 0)->innertext);
        $year = substr($year, strlen($year) - 4, 4);

        $price = strip_tags(preg_replace("'<span[^>]*?>.*?</span>'si", '', $val->find('.search_price', 0)->innertext));
        $searchInPrice = array('p&#1091;&#1073;.','&#36;','$');
        $price = str_replace("&nbsp;", " ", str_replace($searchInPrice, "", $price));
        $price = (float)str_replace(",", ".", $price);

        $sprice = $page->find('.search_price', $key);
        $strike = $sprice->find('strike',0);
        //var_dump(!!$strike);
        if(!!$strike) {
            $sprice = $strike->plaintext;
        }else{
            $sprice = $sprice->plaintext;
        } 
        $sprice = str_replace("&nbsp;", " ", $sprice);

        $revStr = $page->find('.search_reviewscore',$key);
        //echo $revStr;
        //($revStr) ? $revStr = $revStr->innertext : $revStr = '';
        $revStr = $revStr->find('span[data-store-tooltip]',0);
        ($revStr) ? $revStr = $revStr->attr['data-store-tooltip'] : $revStr = '';
        preg_match_all("/[\d]+/", $revStr, $matches);
        if (isset($matches[0][0])) {
            if (isset($matches[0][2])) {
                $matches[0][1] = $matches[0][1].$matches[0][2];
            }
            $rating = $matches[0][0];
            $reviews = $matches[0][1];
        }else{
            $rating = '';
            $reviews = '';
        }

        // $titleArr[] = $title;
        // $priceArr[] = $price;
        // $ratingArr[] = $rating;
        // $reviewsArr[] = $reviews;

        // echo '<hr><hr>'.mysql_escape_string(trim($appid));
        // echo '<hr>'.mysql_escape_string(trim($title));
        // echo '<hr>'.mysql_escape_string(trim($link));
        // echo '<hr>'.mysql_escape_string(trim($price));
        // echo '<hr>'.mysql_escape_string(trim($sprice));
        // echo '<hr>'.mysql_escape_string($rating);
        // echo '<hr>'.mysql_escape_string($reviews);
        // echo '<hr>'.mysql_escape_string($mark);

        $appid   = mysql_escape_string(trim($appid));
        $title   = mysql_escape_string(trim($title));
        $link    = mysql_escape_string(trim($link));
        $year    = mysql_escape_string(trim($year));
        $price   = mysql_escape_string(trim($price));
        $sprice  = mysql_escape_string(trim($sprice));
        $rating  = mysql_escape_string($rating);
        $reviews = mysql_escape_string($reviews);
        $appsub  = mysql_escape_string($sub);
        $mark    = mysql_escape_string($mark);

        arrayDB("INSERT INTO slist VALUES(null,'$appid','$title','$link','$year','$price','$sprice','$rating','$reviews','$appsub','$mark',null)");
    }

    // $jsonInfo = array('pages' => $pages,
    //                     'info' => array('title' => $titleArr,
    //                                     'price' => $priceArr,
    //                                     'rating'=> $ratingArr,
    //                                     'views' => $reviewsArr)
    //                     );

    echo json_encode(array('pages' => $pages, 'mark' => $mark, 'errors' => $_ERRORS));

else: ?>

<h3>парсим список игр и цены</h3>
<button id="get-slist">get-steam</button>
<span class="loading"></span>
<h3>Состояние процесса:</h3>
<ul id="message"><li></li></ul>

<?php
endif; // if($_GET['page'])
?>
