<form method="GET" align="center" class="search-charts">
    <input type="hidden" name="action" value="imagine-m">
    <input type="text" name="img" placeholder="insert steam link" value="">
    <input type="submit" value="Go!">
</form>
<?php

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

$imagine = new Imagine\Gd\Imagine();

$ramka = $imagine->open('images/3d.png');

$header = $imagine->open($header_path);
$header->resize(new Imagine\Image\Box(220, 103));

$point = new Imagine\Image\Point(0, 0);
$ramka->paste($header, $point);

$ramka->save('images/test3d.png');

return '<img src="images/test3d.png">';

} // imagine_3d()

if (isset($_GET['img'])) {
	$img = $_GET['img'];
	$game_id = preg_replace('/.*\/(\d+)\/.*/', '\1', $img);
	$header_path = 'http://cdn.akamai.steamstatic.com/steam/apps/'.$game_id.'/header.jpg';
	if (@exif_imagetype($header_path) === 2) {
		$msg = imagine_steam($header_path);
	}else{
		$msg = 'Картинка отсутствует!';
	}
}else{
	$msg = 'Вставить ссылку на игру стим в поле выше!<br><b>Пример: </b><i>http://store.steampowered.com/app/379720/</i>';
}




?>
<br>
<div class="text-center">
	<?php echo $msg; ?>
</div>
