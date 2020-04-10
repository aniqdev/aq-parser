<?php
ini_get('safe_mode') or set_time_limit(3000); // Указываем скрипту, чтобы не обрывал связь.

$games = arrayDB("SELECT appid,type,title,link,reg_price FROM steam_de ORDER BY id LIMIT 25000,5001");
// var_dump(ROOT);
// return;
?>

<style>
img{
	width: 200px;
	height: 93px;
}
</style>

<div class="container" id="">

<div id="m-tooltip"></div>
	<?php foreach ($games as $k => $g) {
		break;
		// if($k < 1000 || $k>1000) continue;
		$app_sub = ($g['type'] === 'sub')?'sub':'app';
		$img_src = 'http://cdn.akamai.steamstatic.com/steam/'.$app_sub.'s/'.$g['appid'].'/header.jpg';
		// echo '<img src="',$img_src,'">';
		$dest = ROOT.'/steam-images/'.$app_sub.'s-'.$g['appid'].'.jpg';
		$copied = copy($img_src, $dest);
		if (!$copied){
			$href = 'http://store.steampowered.com/'.$app_sub.'/'.$g['appid'].'/';
			echo '<a href="'.$href.'" target="_blank">'.$g['title'].'</a><br>';
			copy('https://mysteamgames.co/templates/mysteamgames/images/noimage.png', $dest);
		} 
	} ?>
</div>
