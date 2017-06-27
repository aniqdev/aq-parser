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
$options = array('http' => array('method' => "GET", 'header' => "Accept-language: en-US\r\n" . "Cookie: Steam_Language=german; mature_content=1; birthtime=238921201; lastagecheckage=28-July-1977\r\n"));
$context = stream_context_create($options);

// Основная ссылка, с которой мы парсим игры
$data = file_get_html('http://store.steampowered.com/search/?sort_by=Name_ASC&category1=21,994,996,998&page=1', false, $context);
// Получаем общее кол-во страниц
$pages = $data->find('.search_pagination_right a', count($data->find('.search_pagination_right a')) - 2)->innertext;

// Запускаем парсинг... от 1 до последней страницы
// Начальная страница это $x = 1 (цифру можно изменить), конечная страница по умолчанию $pages (можно изменить на цифру). $x++ Вообще не трогаем.

if ($page === 1) {
    arrayDB("TRUNCATE steam");
}

for ($x = $page; $x <= $page; $x++) {
    $data_game = file_get_html('http://store.steampowered.com/search/?sort_by=Name_ASC&category1=21,994,996,998&page=' . $x, false, $context);
    //$data_game = file_get_html('http://store.steampowered.com/search/?sort_by=Relevance&category1=998,21&page=' . $x, false, $context);
    
    foreach ($data_game->find('#search_result_container a[data-ds-appid]') as $a) {
   
// ==> Ссылка на игру ($link)
        $link = $a->href;

        $game_item = file_get_html($link, false, $context);

        // пропускаем игру в случае ошибки
        if (!is_object($game_item)) continue;

        // $price = $game_item->find('.game_purchase_price', 0);
        // ($price) ? $price = $price->innertext : $price = '';


// ==> Цена ($price)
        $price_block = $a->find('.search_price', 0);
        $disc_price = strip_tags(preg_replace("'<span[^>]*>.*</span>'si", '', $price_block->innertext));
        preg_match("'<span[^>]*>(.*)</span>'si", $price_block->innertext, $reg_price);

        $searchInPrice = array('p&#1091;&#1073;.','&#36;','$',"&nbsp;",);
        $disc_price = trim(str_replace($searchInPrice, '', $disc_price));
        $reg_price = trim(str_replace($searchInPrice, '', strip_tags(@$reg_price[1])));


// ==> Год ($year)
        $year = trim($a->find('.search_released', 0)->innertext);
        $year = substr($year, strlen($year) - 4, 4);


// ==> game_area_details_specs ($details_specs)
        $details_specs = [];
        foreach ($game_item->find('.game_area_details_specs') as $dfbhet) $details_specs[] = $dfbhet->plaintext;
        $details_specs = implode(',', $details_specs);


// ==> Языки ($languages)
        $languages = [];
        foreach ($game_item->find('.game_language_options tr[style]') as $lang_item) {
            if (count($lang_item->find('img', 0)) > 0) {
                $languages[] = trim($lang_item->find('td', 0)->plaintext);
            }
        }
        $languages = implode(',', $languages);


// ==> Название ($title)
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


// ==> Жанры ($genres)
        $genres = [];
        $details_block = $game_item->find('.game_details', 0);
        if($details_block) $genres_links = $details_block->find("a[href*='genre']");
        foreach ($genres_links as $wreds) $genres[] = trim($wreds->plaintext);
        $genres = implode(',', $genres);


// ==> Developer ($developer)
        $developer = '';
        if($details_block) $developer = $details_block->find("a[href*='developer']", 0);
        if($developer) $developer = trim($developer->plaintext);


// ==> Publisher ($publisher)
        $publisher = '';
        if($details_block) $publisher = $details_block->find("a[href*='publisher']", 0);
        if($publisher) $publisher = trim($publisher->plaintext);


// ==> Дата релиза ($release)
        $release = $a->find('.search_released', 0);
        $release = $release ? $release->innertext : '';


// ==> Операционная система ($os)
        $os = [];
        $os_tab = $game_item->find('.sysreq_tab');
        foreach ($os_tab as $bwrfv) $os[] = trim($bwrfv->innertext);
        $os = implode(",", $os);


// ==> Системные требования ($sys_req)
        $sys_req = $game_item->find('.game_area_sys_req',0);
        ($sys_req) ? $sys_req = $sys_req->plaintext : $sys_req = '';


// ==> Обзоры/рейтинг ($reviews, $rating)
        $reviews = $game_item->find('div[data-store-tooltip]',0);
        $reviews = $reviews ? $reviews->attr['data-store-tooltip'] : '';
        $rating = ''; $reviewss = '';
        if (preg_match_all("/[\d]+/", $reviews, $matches)) {
            if (isset($matches[0][2])) {
                $matches[0][1] = $matches[0][1].$matches[0][2]; }
            $rating = $matches[0][0];
            $reviewss = $matches[0][1];
        }


// ==> Тип товара ($appsub['app','sub','dlc'])
        $appsub = explode('/', trim($link))[3];
        if ($appsub === 'sub') {
            $appid = $a->getAttribute('data-ds-packageid');
        }else{
            $appid = $a->getAttribute('data-ds-appid');
            if($game_item->find('.glance_details')) $appsub = 'dlc';
        }


// ==> Теги ($tags)
        $tags_arr = [];
        $arr = ($re = $game_item->find('.glance_tags',0))?$re->find('a.app_tag'):[];
        foreach ($arr as $mwqhg) $tags_arr[] = trim($mwqhg->plaintext);
        $tags = implode(',', $tags_arr);


// ==> Возрастные ограничения ($usks)
        $usk_links = [];
        foreach ($game_item->find('img[src*=ratings]') as $uu) $usk_links[] = $uu->src;
        $usk_age = preg_replace("/[\D]+/", '', @$usk_links[0]);
        $usk_links = implode(',', $usk_links);



// ==> Паки в которых состоит игра ($bundles)
        $packages = [];
        $game_wrappers = $game_item->find('.game_area_purchase_game_wrapper');
        foreach ($game_wrappers as $game_wrapper) {
            $texts = $game_wrapper->find('text');
            foreach ($texts as $text) {
                $tt = $text->plaintext;
                if ($tt === 'Package info' || $tt === 'Paketinformationen') {
                    $s = [];
                    $s['price'] = trim($game_wrapper->find('.game_purchase_price',0)->plaintext);
                    $s['title'] = trim($game_wrapper->find('h1',0)->plaintext);
                    $s['link'] = trim($game_wrapper->find('a[href*=sub]',0)->href);
                    $packages[] = $s;
                }
            }
        }
        $packages = ($packages) ? json_encode($packages) : null;

        // echo "<br><h4>title-=-</h4>",$title;
        // echo "<br><h4>link-=-</h4>",$link;
        // echo "<br><h4>desc-=-</h4>",$desc;
        // echo "<br><h4>genres-=-</h4>",$genres;
        // echo "<br><h4>price-=-</h4>",$price;
        // echo "<br><h4>year-=-</h4>",$year;
        // echo "<br><h4>release-=-</h4>",$release;
        // echo "<br><h4>lang-=-</h4>",$languages;
        // echo "<br><h4>os-=-</h4>",implode(",", $os);
        // echo "<br><h4>sys_req-=-</h4>",$sys_req;
        // echo "<br><h4>rating-=-</h4>",$rating;
        // echo "<br><h4>reviewss-=-</h4>",$reviewss,'<hr><style>h4{display:inline}</style>';
        $title    = _esc(trim($title));
        $appid    = _esc($appid);
        $type     = _esc($appsub);
        $link     = _esc(clean_url_from_query(trim($link)));
        $desc     = _esc(trim($desc));
        $genres   = _esc($genres);
        $developer= _esc($developer);
        $publisher= _esc($publisher);
        $reg_price= _esc($reg_price);
        $disc_price= _esc($disc_price);
        $year     = _esc(trim($year));
        $release  = _esc(trim($release));
        $specs    = _esc(trim($details_specs));
        $lang     = _esc(trim($languages));
        $os       = _esc($os);
        $sys_req  = _esc(trim($sys_req));
        $rating   = _esc($rating);
        $reviewss = _esc($reviewss);
        $tags     = _esc($tags);
        $usk_links= _esc($usk_links);
        $usk_age  = _esc(trim($usk_age));
        $packages = _esc($packages);

        arrayDB("INSERT INTO steam VALUES (null, 
            '$appid', 
            '$type', 
            '$title',
            '$link',
            '$genres',
            '$developer',
            '$publisher',
            '$reg_price',
            '$disc_price',
            '$year',
            '$release',
            '$specs',
            '$lang',
            '$desc',
            '$os',
            '$sys_req',
            '$rating',
            '$reviewss',
            '$tags',
            '$usk_links',
            '$usk_age',
            '$packages')");

    }//foreach по одной странице
echo json_encode( array(
    'pages' => $pages,
    'errors' => $_ERRORS
    ));
}//for по страницам

else: ?>
<h3>парсим игры Стим (подробно)</h3>
<form id="parse_steam" class="parse-steam-form">
    <button name="steam2" value="steam_de" type="button" class="js-get-steam2 get-steam-btn">Steam DE</button>
    <button name="steam2" value="steam_en" type="button" class="js-get-steam2 get-steam-btn">Steam EN</button>
    <button name="steam2" value="steam_fr" type="button" class="js-get-steam2 get-steam-btn">Steam FR</button>
    <button name="steam2" value="steam_es" type="button" class="js-get-steam2 get-steam-btn">Steam ES</button>
    <button name="steam2" value="steam_it" type="button" class="js-get-steam2 get-steam-btn">Steam IT</button>
</form><br><br><br>
<span class="loading"></span>
<h3>Состояние процесса:</h3>
<ul id="message" class="message"><li></li></ul>
<?php
endif; // if($_GET['page'])

?>