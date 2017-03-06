<?php

include_once('simple_html_dom.php');

function imagine_steam($header_path){

	$imagine = new Imagine\Gd\Imagine();
	$ramka = $imagine->open('images/ramka.jpg');
	$header = $imagine->open($header_path);
	$header->resize(new Imagine\Image\Box(442, 215));
	$point = new Imagine\Image\Point(29, 138);
	$ramka->paste($header, $point);
	$ramka->save('images/test1.jpg');

	return '<img src="images/test1.jpg">';

} // imagine_steam()

function imagine_3d($header_path){
var_dump($header_path);
	$imagine = new Imagine\Gd\Imagine();
	$ramka = $imagine->open('images/3d.png');
	$header = $imagine->open($header_path);
	$header->resize(new Imagine\Image\Box(220, 103));
	$point = new Imagine\Image\Point(0, 0);
	$ramka->paste($header, $point);
	$ramka->save('images/test3d.png');

	return '<img src="images/test3d.png">';

} // imagine_3d()

function get_steam_game_data($url){

	// В следующей строчке Steam_Language=german, можно указывать другие языки вместо german
	$options = array('http' => array('method' => "GET", 'header' => "Accept-language: de\r\n" . "Cookie: Steam_Language=german; mature_content=1; birthtime=238921201; lastagecheckage=28-July-1977\r\n"));
	$context = stream_context_create($options);
	$dom = file_get_html($url, false, $context);
	$steam_title = $dom->find('.apphub_AppName',0)->plaintext;
	$steam_lang_unsupported = @$dom->find('.unsupported',0)->plaintext;
	$steam_lang_de = stripos($steam_lang_unsupported, 'Deutsch');
	$de = ''; if($steam_lang_de === false) $de = ', DE';
	$steam_description = $dom->find('#game_area_description',0)->innertext;
	$steam_description = str_replace('<h2>Über dieses Spiel</h2>','', $steam_description);
	$srcs = array();
	foreach ($dom->find('a[href*=1920x1080]') as $img) {
		$src = $img->getAttribute('href');
		$src = parse_url( $src, PHP_URL_QUERY );
		$src = str_replace(['url=','1920x1080'], ['','600x338'], $src);
		//echo '<img src="',$src,'">';
		$srcs[] = $src;
	}
	$desc_str = file_get_contents('lib/adds/responsive.html');
	$search = array(
		'{{TITLE}}',	'{{DE}}',	'{{DESCRIPTON}}',
		'{{IMG1}}',		'{{IMG2}}',		'{{IMG3}}',
		'{{IMG3D}}'
		);
	$replace = array(
		$steam_title,	$de,    $steam_description,
		$srcs[0],		$srcs[1],		$srcs[2],
		'images/test3d.png'
		);
	$desc_str = str_replace($search, $replace, $desc_str);
	file_put_contents('lib/adds/opisanie_ebay.html', $desc_str);

	return $steam_title;

} // get_steam_game_data()

$url = ''; $steam_title = ''; $frame = '';
if (isset($_GET['url']) && $_GET['url']) {
	$url = trim($_GET['url']);
	$game_id = preg_replace('/.*\/(\d+)\/.*/', '\1', $url);
	$url = preg_replace('/\?.+/', '', $url);
	$header_path = 'http://cdn.akamai.steamstatic.com/steam/apps/'.$game_id.'/header.jpg';
	if (@exif_imagetype($header_path) === 2) {
		$msg = imagine_steam($header_path);
		$png3d = imagine_3d($header_path);
		$steam_title = get_steam_game_data($url);
		$frame = '<iframe src="lib/adds/opisanie_ebay.html" class="imagine-frame" frameborder="0"></iframe>';
	}else{
		$msg = 'Картинка отсутствует!';
	}
}else{
	$msg = 'Вставить ссылку на игру стим в поле выше!<br><b>Пример: </b><i>http://store.steampowered.com/app/379720/</i>';
}




?>
<form method="GET" class="imagine-form text-center">
    <input type="hidden" name="action" value="imagine">
    <input type="text" name="url" placeholder="insert steam link" value="<?php echo $url; ?>">
    <input type="submit" value="Go!">
</form>
<br>
<h1 class="text-center">
	<?php echo $steam_title; ?>
</h1>
<br>
<div class="text-center">
	<?php echo $msg; ?>
</div>
<br>
	<?php echo $frame; ?>
<style>
.imagine-frame{
	background: rgba(231, 231, 231, 0.7) url(http://hot-body.net/gig-less/images/cropped-bg-hexa.jpg);
    display: block;
	margin: auto;
    width: 1280px;
    height: 3000px;
}
</style>