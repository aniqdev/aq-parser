<?php
ini_get('safe_mode') or set_time_limit(2500); // Указываем скрипту, чтобы не обрывал связь.
header('Content-Type: application/json');

$array = [];

$dev_mode = defined('DEV_MODE');

$table = $_POST['table'];

$whr_and = '';
// $whr_and = "appsub='dlc' AND";

$count = (int)arrayDB("SELECT count(*) as count FROM slist WHERE $whr_and scan = (select scan from slist order by id desc limit 1)")[0]['count'];
// В следующей строчке Steam_Language=german,russian,english,french,spanish,italian можно указывать другие языки
$options = array('http' => array('method' => "GET", 'header' => "Accept-language: en-US\r\n" . "Cookie: Steam_Language=".get_language_by_table($table)."; mature_content=1; birthtime=238921201; lastagecheckage=28-July-1977\r\n"));
$context = stream_context_create($options);

if(!isset($_GET['offset'])) die;
$offset = (int)$_GET['offset'];

save_steam_offset($table, $offset);

$query = "SELECT * FROM slist 
		  WHERE $whr_and scan = (select scan from slist order by id desc limit 1) 
		  LIMIT $offset,10";
$slist = arrayDB($query);
$affected = 0; 
$was_no_img = 0; 
$aggregator = [];
$aggregate = false; // использовать для отладки
foreach ($slist as $row) {

// ==> Ссылка на игру ($link)
    $link = _esc(clean_steam_url(trim($row['link'])));
    if($aggregate) $aggregator[$link]['title'] = $row['title'];
    $game_exists = arrayDB("SELECT id,notice,pics from $table where link = '$link'");

    // удалить после восстановления картинок
    // if (isset($game_exists[0])) {
    // 	$pics = $game_exists[0]['pics'];
    // 	if (strpos($pics, 'thumb') !== false) {
    // 		continue;
    // 	}
    // }

    // $dest = ROOT.'/steam-images/'.$row['appsub'].'s-'.$row['appid'];
    $dest = get_steam_images_dir_path($row['appsub'], $row['appid']);
    if($aggregate) $aggregator[$link]['dest'] = $dest;
    $img_exists = (file_exists($dest.'/header.jpg') && filesize($dest.'/header.jpg') > 30000);

    // удалить после восстановления картинок
    $img_exists = false;
    // после проверки убрать filesize оставить только 
    // $img_exists = file_exists($dest.'/header.jpg');
    if($aggregate) $aggregator[$link]['img_exists'] = $img_exists;
    if (!$img_exists && !$dev_mode) {
        $was_no_img++;
        $img_src = 'http://cdn.akamai.steamstatic.com/steam/'.$row['appsub'].'s/'.$row['appid'].'/header.jpg';
        @mkdir($dest, 0777, true);
        if(stripos($img_src, 'jpg') !== false) 
        	$copied = copy($img_src, $dest.'/header.jpg');
        // if (!$copied){
        //     $href = 'http://store.steampowered.com/'.$row['appsub'].'/'.$row['appid'].'/';
        //     copy(ROOT.'/images/noimage.png', $dest.'/header.jpg');
        // }

        $game_item = aqs_file_get_html($link, false, $context);
    	if (!is_object($game_item)) continue;

        $srcs = [];
        foreach ($game_item->find('a[href*=1920x1080]') as $kk => $img) {
            $src = $img->getAttribute('href');
            if($aggregate) $aggregator[$link]['img_srcs'][] = $src;
            // изменилась верстка в стиме
            // $src = parse_url( $src, PHP_URL_QUERY );
            // $src = str_replace('url=', '', $src);
            if(stripos($src, 'jpg') !== false)
            	copy($src, $dest.'/big'.($kk+1).'.jpg');

            $src = str_replace('1920x1080', '600x338', $src);
            if(stripos($src, 'jpg') !== false)
            	copy($src, $dest.'/small'.($kk+1).'.jpg');

            if($kk > 2) break;
        }
    }

    // if($game_exists) continue;

    if ($img_exists || $dev_mode) $game_item = aqs_file_get_html($link, false, $context);
    // пропускаем игру в случае ошибки
    if (!is_object($game_item)) continue;
    $affected++;
// ==> Тип товара ($appsub['app','sub','dlc'])
    $appsub = $row['appsub'];
    $main_game_title = '';
    $main_game_link = '';
    if ($appsub === 'app') {
        if($glance_details = $game_item->find('.glance_details' , 0)){
			$appsub = 'dlc';
			$main_game = $glance_details->find('a', 0);
            $main_game_title = $main_game->plaintext;
            $main_game_link = $main_game->href;
        }
    }
    $type = $appsub;
	$appid = $row['appid'];


// ==> Цена ($price)
    $old_price = $row['old_price'];
    $reg_price = $row['reg_price'];


// ==> Год ($year)
    $year = $row['year'];


// ==> purchase_note
    $notice = '';
    $purchase_note = $game_item->find('#purchase_note', 0);
    if($purchase_note) $notice = _esc(trim($purchase_note->plaintext));


// ==> game_area_details_specs ($details_specs)
    $details_specs = [];
    foreach ($game_item->find('.game_area_details_specs') as $dfbhet) $details_specs[] = $dfbhet->plaintext;
    $details_specs = implode(',', $details_specs);


// ==> Языки ($languages)
	$languages = []; // для игр
	foreach ($game_item->find('.game_language_options tr[class!=unsupported] .ellipsis') as $lang_item) {
		// sa(trim($lang_item->innertext));
	    $languages[] = trim($lang_item->innertext);
	}
	$languages = implode(',', $languages);

	if($appsub === 'sub'){ // для паков
	    $language_list = ($language_list = $game_item->find('.language_list', 0)) ? $language_list->innertext : '';
		$language_list = strip_tags(preg_replace(['"<b[^>]*>.*</b>"','/\s/'], '', $language_list));
		$languages = $language_list;
	} 
	if($aggregate) $aggregator[$link]['languages'] = $languages;

// ==> Название ($title)
    if ($title = $game_item->find('.apphub_AppName',0)) { // для app|dls
        $title = $title->innertext;

        $desc = $game_item->find('#game_area_description', 0);
        $desc = ($desc) ? $desc->innertext : '';
        $desc = strip_tags($desc, '<br><br/><br /><p><h2><strong><b><i><ul><li>');

    }elseif ($title = $game_item->find('.pageheader',0)) { // для sub|bundle
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
	$reviews = $game_item->find('.user_reviews_summary_row',0);
	if ($reviews2 = $game_item->find('.user_reviews_summary_row',1)) {
		
		$reviews = $reviews ? $reviews->attr['data-tooltip-html'] : '';
		$reviews = str_replace('30', '', $reviews);
		if (preg_match_all("/[\d]+/", $reviews, $matches)) {
		    if (isset($matches[0][2])) {
		        $matches[0][1] = $matches[0][1].$matches[0][2]; }
		    $recent_rating = $matches[0][0];
		    $recent_reviews = $matches[0][1];
		}
		
		$reviews2 = $reviews2 ? $reviews2->attr['data-tooltip-html'] : '';
		if (preg_match_all("/[\d]+/", $reviews2, $matches)) {
		    if (isset($matches[0][2])) {
		        $matches[0][1] = $matches[0][1].$matches[0][2]; }
		    $overall_rating = $matches[0][0];
		    $overall_reviews = $matches[0][1];
		}
	}else{
		$reviews = $reviews ? $reviews->attr['data-tooltip-html'] : '';
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

// ==> Список картинок ($pics)
    $path = get_steam_images_dir_path($type, $appid);
    $dir = @scandir($path);
    $pics = '';
    if ($dir) {
        $dir = array_slice($dir, 2);
        $pics = implode(',', $dir);
    }

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

        $set_list = "
            `appid` = '$appid', 
            `type` = '$type', 
            `title` = '$title',
            `link` = '$link',
            `pics` = '$pics',
            `genres` = '$genres',
            `notice` = '$notice',
            `developer` = '$developer',
            `publisher` = '$publisher',
            `reg_price` = '$reg_price',
            `old_price` = '$old_price',
            `year` = '$year',
            `release` = '$release',
            `specs` = '$specs',
            `lang` = '$lang',
            `desc` = '$desc',
            `os` = '$os',
            `sys_req` = '$sys_req',
            `r_rating` = '$r_rating',
            `r_reviews` = '$r_reviews',
            `o_rating` = '$o_rating',
            `o_reviews` = '$o_reviews',
            `tags` = '$tags',
            `usk_links` = '$usk_links',
            `usk_age` = '$usk_age',
            `main_game_title` = '$main_game_title',
            `main_game_link` = '$main_game_link',
            `includes` = '$includes'";

        if($game_exists){
            $steam_table_id = (int)$game_exists[0]['id'];
            arrayDB("UPDATE $table SET
            $set_list
            WHERE id = '$steam_table_id'");
        }else{
            arrayDB("INSERT INTO $table SET
            $set_list");
        }


}//foreach по одной странице
echo json_encode( array(
    'offset' => $offset,
    'table' => $table,
    'language' => get_language_by_table($table),
    'count' => $count,
    'affected' => $affected,
    'was_no_img' => $was_no_img,
    'aggregator' => $aggregator,
    'errors' => $_ERRORS
    ));

