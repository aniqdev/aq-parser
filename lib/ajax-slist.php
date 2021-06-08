<?php
ini_get('safe_mode') or set_time_limit(1500); // Указываем скрипту, чтобы не обрывал связь.

$x = (int)$_GET['page'];
$array = [];

// В следующей строчке Steam_Language=german, можно указывать другие языки вместо german
$options = array('http' => array('method' => "GET", 'header' => "Accept-language: de\r\n" . "Cookie: Steam_Language=german; birthtime=238921201; lastagecheckage=28-July-1977\r\n"));
$context = stream_context_create($options);

// Получаем общее кол-во страниц
if ($_GET['pages']) {
    $pages = $_GET['pages'];
}else{
    $doc = file_get_contents('http://store.steampowered.com/search/?sort_by=Name_ASC&category1=21,994,996,998&page=1', false, $context);
    //var_dump($doc);
    $data = str_get_html($doc);
    $pages = $data->find('.search_pagination_right a', count($data->find('.search_pagination_right a')) - 2)->innertext;
}
//  логика получения отметки scan(количество секунд с начала эпохи Unix)
if ($_GET['scan']) {
    $scan = $_GET['scan'];
}else{
    $scan = time();
}
// Запускаем парсинг... от 1 до последней страницы
// Начальная страница это $x = 1 (цифру можно изменить), конечная страница по умолчанию $pages (можно изменить на цифру). $x++ Вообще не трогаем.

    // Основная ссылка, с которой мы парсим игры
    $doc = file_get_contents('http://store.steampowered.com/search/?sort_by=Name_ASC&category1=21,994,996,998&page=' . $x, false, $context);
    //var_dump($doc);
    $page = str_get_html($doc);
    $aggregator = [];
    
    if(is_object($page)) foreach ($page->find('a[data-ds-appid]') as $game_block) {

        // ==> Название
        $title = ($title = $game_block->find('.title', 0)) ? $title->innertext : '';

        // ==> Ссылка
        $link = clean_url_from_query($game_block->href);
        $link = clean_steam_url($link);

        // ==> Тип продукта
        $appsub = explode('/', $link)[3];

        if ($appsub === 'sub') {
            $appid = $game_block->getAttribute('data-ds-packageid');
        }else{
            $appid = $game_block->getAttribute('data-ds-appid');
        }

        // ==> Год выпуска
        $year = trim($game_block->find('.search_released', 0)->innertext);
        $year = substr($year, strlen($year) - 4, 4);

        // ==> Дата релиза ($release)
        $release = $game_block->find('.search_released', 0);
        $release = $release ? $release->innertext : '';

        // ==> Цена
        $price_block = $game_block->find('.search_price', 0);
        $price_text = $price_block->innertext;
        if (trim($price_text)) {
            $reg_price = strip_tags(preg_replace("'<span[^>]*>.*</span>'si", '', $price_text));
            preg_match("'<span[^>]*>(.*)</span>'si", $price_block->innertext, $old_price);

            $searchInPrice = array('p&#1091;&#1073;.','&#36;','$',"&nbsp;",);
            $reg_price = trim(str_replace($searchInPrice, '', $reg_price));
            $old_price = trim(str_replace($searchInPrice, '', strip_tags(@$old_price[1])));
            $reg_price = str_replace(',', '.', $reg_price);
            $old_price = str_replace(',', '.', $old_price);
        }else{
            $reg_price = '-1';
            $old_price = '-1';
        }

        // ==> Отзывы
        $revStr = $game_block->find('span[data-tooltip-html]',0);
        ($revStr) ? $revStr = $revStr->attr['data-tooltip-html'] : $revStr = '';
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

        $aggregator[$appsub.'-'.$appid]['title'] = $title;
        $aggregator[$appsub.'-'.$appid]['reg_price'] = $reg_price;
        $aggregator[$appsub.'-'.$appid]['rating'] = $rating;
        $aggregator[$appsub.'-'.$appid]['reviews'] = $reviews;


        $appid     = _esc(trim($appid));
        $title     = _esc(trim($title));
        $link      = _esc(trim($link));
        $year      = _esc(trim($year));
        $release   = _esc(trim($release));
        $reg_price = (float)$reg_price;
        $old_price = (float)$old_price;
        $rating    = _esc($rating);
        $reviews   = _esc($reviews);
        $appsub    = _esc($appsub);
        $scan      = _esc($scan);

        arrayDB("INSERT INTO slist VALUES(null,
            '$appid',
            '$title',
            '$link',
            '$year',
            '$release',
            '$reg_price',
            '$old_price',
            '$rating',
            '$reviews',
            '$appsub',
            '$scan',
            null)");

        arrayDB("UPDATE steam_de 
                    SET reg_price = '$reg_price',
                        old_price = '$old_price', 
                        o_rating = '$rating',
                        o_reviews = '$reviews' 
                    WHERE link = '$link'");

    } // end foreach

    echo json_encode(array('pages' => $pages, 'scan' => $scan, 'aggregator'=>$aggregator, 'errors' => $_ERRORS));
