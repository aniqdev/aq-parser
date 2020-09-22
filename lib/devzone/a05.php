<div class="container">
<?php ini_get('safe_mode') or set_time_limit(300);





sa($res = Cdvet::GetSellerListRequest(1, 200));






return;
sa(base64url_encode(md5('http://store.steampowered.com/app/279740/')));
sa(base64_encode('http://store.steampowered.com/app/279740/'));

// MDg5MzcwNWZjNTMzNTMxYmVhNmFjNGIxMTdiYjRjYzc


return;
$res = arrayDB("SELECT title,type,appid,link,pics,updated_at from steam_de order by updated_at desc limit 300");
$res = arrayDB("select title,type,appid,link,pics,updated_at from steam_de where title = '112 Operator' order by updated_at desc limit 300");
foreach ($res as $key => $row) {
	$url = get_steam_images_url($row['type'], $row['appid'], '/header-80p.jpg');
	echo '<img src="'.$url.'" alt="">';
}

?>
</div>
<?php



return;
$copied = copy(is_dev('http://parser/moda-files/moda-arr.txt','http://parser.modetoday.de/moda-files/moda-arr.txt'), ROOT . '/moda-files/moda-arr.copy.txt');

if ($copied) {
	$handle = @fopen(ROOT . '/moda-files/moda-arr.copy.txt', "r");
	if ($handle) {
		$n = 0;
	    while (($str = fgets($handle)) !== false) {
	        if($n < 10) sa(json_decode($str,1));
	    	$n++;
	    }
	    if (!feof($handle)) {
	        echo "Ошибка: fgets() неожиданно потерпел неудачу\n";
	    }
	    fclose($handle);
	    sa($n);
	}
}