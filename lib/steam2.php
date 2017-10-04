<?php
if (isset($_GET['offset'])):
header('Content-Type: text/html; charset=utf-8');
ini_get('safe_mode') or set_time_limit(2500); // Указываем скрипту, чтобы не обрывал связь.
include_once('simple_html_dom.php');
//include('PHPExcel.php');
require_once('array_DB.php');
$array = array();

$table_langs = [
    'steam_de' => 'german',
    'steam_en' => 'english',
    'steam_fr' => 'french',
    'steam_es' => 'spanish',
    'steam_it' => 'italian',
];
$table = $_POST['table'];

// $whr_and = "appsub='sub' AND";
$whr_and = '';

$count = (int)arrayDB("SELECT count(*) as count FROM slist WHERE $whr_and scan = (select scan from slist order by id desc limit 1)")[0]['count'];
// В следующей строчке Steam_Language=german,russian,english,french,spanish,italian можно указывать другие языки
$options = array('http' => array('method' => "GET", 'header' => "Accept-language: en-US\r\n" . "Cookie: Steam_Language=".$table_langs[$table]."; mature_content=1; birthtime=238921201; lastagecheckage=28-July-1977\r\n"));
$context = stream_context_create($options);

if(!isset($_GET['offset'])) die;
$offset = (int)$_GET['offset'];
//if(!$offset) arrayDB("TRUNCATE steam");

$query = "SELECT * FROM slist 
		  WHERE $whr_and scan = (select scan from slist order by id desc limit 1) 
		  LIMIT $offset,30";
$slist = arrayDB($query);
$affected = 0;
foreach ($slist as $row) {

// ==> Ссылка на игру ($link)
    $link = _esc(clean_steam_url(trim($row['link'])));
    $game_exists = arrayDB("SELECT id from $table where link = '$link'");

    $dest = ROOT.'/steam-images/'.$row['appsub'].'s-'.$row['appid'];
    $img_exists = file_exists($dest);
    if (!$img_exists && !defined('DEV_MODE')) {
        $img_src = 'http://cdn.akamai.steamstatic.com/steam/'.$row['appsub'].'s/'.$row['appid'].'/header.jpg';
        @mkdir($dest, 0777, true);
        $copied = copy($img_src, $dest.'/header.jpg');
        if (!$copied){
            $href = 'http://store.steampowered.com/'.$row['appsub'].'/'.$row['appid'].'/';
            copy(ROOT.'/images/noimage.png', $dest.'/header.jpg');
        }

        $game_item = file_get_html($link, false, $context);

        $srcs = [];
        foreach ($game_item->find('a[href*=1920x1080]') as $kk => $img) {
            $src = $img->getAttribute('href');
            $src = parse_url( $src, PHP_URL_QUERY );

            $src = str_replace('url=', '', $src);
            copy($src, $dest.'/big'.($kk+1).'.jpg');

            $src = str_replace('1920x1080', '600x338', $src);
            copy($src, $dest.'/small'.($kk+1).'.jpg');

            if($kk > 2) break;
        }
    }

    if($game_exists) continue;
    if ($img_exists || defined('DEV_MODE')) $game_item = file_get_html($link, false, $context);
    // пропускаем игру в случае ошибки
    if (!is_object($game_item)) continue;
    $affected++;
// ==> Тип товара ($appsub['app','sub','dlc'])
    $appsub = $row['appsub'];
    $main_game_title = '';
    $main_game_link = '';
    if ($appsub === 'app') {
    	$glance_details = $game_item->find('.glance_details');
        if($glance_details){
			$appsub = 'dlc';
			$main_game = $glance_details[0]->find('a', 0);
            $main_game_title = $main_game->plaintext;
            $main_game_link = $main_game->href;
        }
    }
	$appid = $row['appid'];


// ==> Цена ($price)
    $old_price = $row['old_price'];
    $reg_price = $row['reg_price'];


// ==> Год ($year)
    $year = $row['year'];


// ==> purchase_note
    $notice = '';
    $purchase_note = $game_item->find('#purchase_note', 0);
    if($purchase_note) $notice = $purchase_note->plaintext;


// ==> game_area_details_specs ($details_specs)
    $details_specs = [];
    foreach ($game_item->find('.game_area_details_specs') as $dfbhet) $details_specs[] = $dfbhet->plaintext;
    $details_specs = implode(',', $details_specs);


// ==> Языки ($languages)
    $languages = []; // для игр
    foreach ($game_item->find('.game_language_options tr[style]') as $lang_item) {
        if (count($lang_item->find('img', 0)) > 0) {
            $languages[] = trim($lang_item->find('td', 0)->plaintext);
        }
    }
    $languages = implode(',', $languages);

    // для паков
    $language_list = ($language_list = $game_item->find('.language_list', 0)) ? $language_list->innertext : '';
	$language_list = strip_tags(preg_replace(['"<b[^>]*>.*</b>"','/\s/'], '', $language_list));
	if($appsub === 'sub') $languages = $language_list;

// ==> Название ($title)
    if ($title = $game_item->find('.apphub_AppName',0)) {
        $title = $title->innertext;

        $desc = $game_item->find('#game_area_description', 0);
        $desc = ($desc) ? $desc->innertext : '';
        $desc = strip_tags($desc, '<br><br/><br /><p><h2><strong><b><i><ul><li>');

    }elseif ($title = $game_item->find('.pageheader',0)) {
        $title = $title->innertext;

        $desc = [];
        foreach ($game_item->find('.tab_item_name') as $overlay) {
            $desc[] = $overlay->plaintext;
        }
        $desc = implode('<br>', $desc);

    }else{
        $title = '';
        $desc = '';
    }


// ==> Жанры ($genres)
    $genres = []; $genres_links = [];
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
    $release = $row['release'];


// ==> Операционная система ($os)
    $os = [];
    if ($appsub === 'sub') {
		$os_list = ($os_list = $game_item->find('.tab_item_details', 0)) ? $os_list->find('.platform_img') : [];
		foreach ($os_list as $span) $os[] = str_ireplace('platform_img ', '', $span->class);
    }else{
		$os_list = ($os_list = $game_item->find('.game_area_purchase_platform', 0)) ? $os_list->find('.platform_img') : [];
		foreach ($os_list as $span) $os[] = str_ireplace('platform_img ', '', $span->class);
    }
    $os = implode(",", $os);
    $os = str_ireplace(',hmd_separator', '', $os);


// ==> Системные требования ($sys_req)
    $sys_req = $game_item->find('.game_area_sys_req',0);
    ($sys_req) ? $sys_req = $sys_req->plaintext : $sys_req = '';


// ==> Обзоры/рейтинг ($reviews, $rating)
	$recent_rating = ''; $recent_reviews = '';
	$overall_rating = ''; $overall_reviews = '';
	$reviews = $game_item->find('div[data-store-tooltip]',0);
	if ($reviews2 = $game_item->find('div[data-store-tooltip]',1)) {
		
		$reviews = $reviews ? $reviews->attr['data-store-tooltip'] : '';
		$reviews = str_replace('30', '', $reviews);
		if (preg_match_all("/[\d]+/", $reviews, $matches)) {
		    if (isset($matches[0][2])) {
		        $matches[0][1] = $matches[0][1].$matches[0][2]; }
		    $recent_rating = $matches[0][0];
		    $recent_reviews = $matches[0][1];
		}
		
		$reviews2 = $reviews2 ? $reviews2->attr['data-store-tooltip'] : '';
		if (preg_match_all("/[\d]+/", $reviews2, $matches)) {
		    if (isset($matches[0][2])) {
		        $matches[0][1] = $matches[0][1].$matches[0][2]; }
		    $overall_rating = $matches[0][0];
		    $overall_reviews = $matches[0][1];
		}
	}else{
		$reviews = $reviews ? $reviews->attr['data-store-tooltip'] : '';
		if (preg_match_all("/[\d]+/", $reviews, $matches)) {
		    if (isset($matches[0][2])) {
		        $matches[0][1] = $matches[0][1].$matches[0][2]; }
		    $overall_rating = $matches[0][0];
		    $overall_reviews = $matches[0][1];
		    $recent_rating = '';
		    $recent_reviews = '';
		}
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


// ==> Включаемые в пак игры ($includes)
$includes = []; 
$overlay = $game_item->find('.tab_item_overlay');
foreach ($overlay as $overlay) $includes[] = preg_replace('/\D/', '', $overlay->href);
$includes = implode(',', $includes);

// ==> Паки в которых состоит игра ($bundles)
        $packages = [];
        // $game_wrappers = $game_item->find('.game_area_purchase_game_wrapper');
        // foreach ($game_wrappers as $game_wrapper) {
        //     $texts = $game_wrapper->find('text');
        //     foreach ($texts as $text) {
        //         $tt = $text->plaintext;
        //         if ($tt === 'Package info' || $tt === 'Paketinformationen') {
        //             $s = [];
        //             $s['price'] = trim($game_wrapper->find('.game_purchase_price',0)->plaintext);
        //             $s['title'] = trim($game_wrapper->find('h1',0)->plaintext);
        //             $s['link'] = trim($game_wrapper->find('a[href*=sub]',0)->href);
        //             $packages[] = $s;
        //         }
        //     }
        // }
        // $packages = ($packages) ? json_encode($packages) : null;

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
        $title    = _esc(trim(html_entity_decode($title)));
        $appid    = _esc($appid);
        $type     = _esc($appsub);
        $link     = $link;
        $desc     = _esc(trim(html_entity_decode($desc)));
        $genres   = _esc($genres);
        $notice   = _esc(trim($notice));
        $developer= _esc($developer);
        $publisher= _esc($publisher);
        $reg_price= _esc($reg_price);
        $old_price= _esc($old_price);
        $year     = _esc(trim($year));
        $release  = _esc(trim($release));
        $specs    = _esc(trim($details_specs));
        $lang     = _esc(trim($languages));
        $os       = _esc($os);
        $sys_req  = _esc(trim($sys_req));
        $r_rating   = _esc(trim($recent_rating));
        $r_reviews = _esc(trim($recent_reviews));
        $o_rating   = _esc(trim($overall_rating));
        $o_reviews = _esc(trim($overall_reviews));
        $tags     = _esc($tags);
        $usk_links= _esc($usk_links);
        $usk_age  = _esc($usk_age);
        $main_game_title  = _esc(trim($main_game_title));
        $main_game_link  = _esc(trim($main_game_link));
        $includes  = _esc($includes);

        arrayDB("INSERT INTO $table (
            `appid`,
            `type`,
            `title`,
            `link`,
            `genres`,
            `notice`,
            `developer`,
            `publisher`,
            `reg_price`,
            `old_price`,
            `year`,
            `release`,
            `specs`,
            `lang`,
            `desc`,
            `os`,
            `sys_req`,
            `r_rating`,
            `r_reviews`,
            `o_rating`,
            `o_reviews`,
            `tags`,
            `usk_links`,
            `usk_age`,
            `main_game_title`,
            `main_game_link`,
            `includes`)
            VALUES (
            '$appid', 
            '$type', 
            '$title',
            '$link',
            '$genres',
            '$notice',
            '$developer',
            '$publisher',
            '$reg_price',
            '$old_price',
            '$year',
            '$release',
            '$specs',
            '$lang',
            '$desc',
            '$os',
            '$sys_req',
            '$r_rating',
            '$r_reviews',
            '$o_rating',
            '$o_reviews',
            '$tags',
            '$usk_links',
            '$usk_age',
            '$main_game_title',
            '$main_game_link',
            '$includes')");

}//foreach по одной странице
echo json_encode( array(
    'offset' => $offset,
    'table' => $table,
    'language' => $table_langs[$table],
    'count' => $count,
    'affected' => $affected,
    'errors' => $_ERRORS
    ));


else: ?>
<h3>парсим игры Стим (подробно)</h3>
<button id="get-steam" class="get-steam-btn">get-steam</button>
<button id="get-steam2" class="get-steam-btn">get-steam2</button>
<span class="loading"></span>
<h3>Состояние процесса:</h3>
<ul id="message" class="message"><li></li></ul>
<?php
endif; // if($_GET['page'])

?>