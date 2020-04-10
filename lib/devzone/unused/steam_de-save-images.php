<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');
	$offset = (int)$_POST['offset'];

$table_langs = [
    'steam_de' => 'german',
    'steam_en' => 'english',
    'steam_fr' => 'french',
    'steam_es' => 'spanish',
    'steam_it' => 'italian',
];
$table = 'steam_de';

// В следующей строчке Steam_Language=german,russian,english,french,spanish,italian можно указывать другие языки
$options = array('http' => array('method' => "GET", 'header' => "Accept-language: en-US\r\n" . "Cookie: Steam_Language=".$table_langs[$table]."; mature_content=1; birthtime=238921201; lastagecheckage=28-July-1977\r\n"));
$context = stream_context_create($options);
	$count = arrayDB("SELECT count(*) FROM steam_de")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM steam_de LIMIT $offset , 1");
	//=============================================================================
	// sript below


	$row = $res[0];
	$row['appsub'] = ($row['type']==='dlc') ? 'app' : $row['type'];
// ==> Ссылка на игру ($link)
    $link = _esc(clean_steam_url(trim($row['link'])));
    $aggregator[$link]['title'] = $row['title'];

    $dest = ROOT.'/steam-images/'.$row['appsub'].'s-'.$row['appid'];
    $aggregator[$link]['dest'] = $dest;
    $img_exists = file_exists($dest);
    $aggregator[$link]['img_exists'] = $img_exists;
    $was_no_img = false;
    if (!$img_exists && !defined('DEV_MODE')) {
        $was_no_img = true;
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
            $aggregator[$link]['img_srcs'][] = $src;
            // изменилась верстка в стиме
            // $src = parse_url( $src, PHP_URL_QUERY );
            // $src = str_replace('url=', '', $src);
            copy($src, $dest.'/big'.($kk+1).'.jpg');

            $src = str_replace('1920x1080', '600x338', $src);
            copy($src, $dest.'/small'.($kk+1).'.jpg');

            if($kk > 2) break;
        }
    }


	
	//=============================================================================
	echo json_encode([
		'offset' => $offset,
		'count' => $count,
		'res' => $res[0],
		'was_no_img' => $was_no_img,
		'aggregator' => $aggregator,
		'ERRORS' => $_ERRORS,
	]);
}


// sa($_ERRORS);

if($_POST) return;
?>
<style>
	
</style>

<h3>prototype iterator</h3>
<form id="js_go_form" class="go-form">
    <button name="aaa" value="bbb" type="button" class="js-go-btn">Go!</button>
</form><br><br><br>
<span class="loading"></span>
<h3>Состояние процесса:</h3>
<ul id="message" class="message"><li></li></ul>

<script>
function it_ins_msg(msg) {
	$( "#message li:first" ).before( "<li>"+msg+"</li>" );
	if($('#message li').length > 100) {
		$('#message li:last').remove();
	}
}
function send_post(offset) {
	$.post('ajax.php' + window.location.search,
		{action:'iterate', offset:offset},
		function (data) {
			if (offset < data.count) {
				it_ins_msg(offset + ' : ' + data.res.title);
				send_post(offset+1);
			}else{
				$('.loading').removeClass('inaction');
				it_ins_msg('Done!');
				it_ins_msg( "или что-то пошло не так" );
			}
		}, 'json');
}
$('.js-go-btn').on('click', function() {
	$(this).attr('disabled','true');
	send_post(7000);
});
</script>