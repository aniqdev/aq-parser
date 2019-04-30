<?php
if (!isset($_GET['show'])) {
	sa(arrayDB("SELECT count(*) FROM steam_de WHERE type = 'app'"));
	echo '<a href="/a.php?action=devzone/sitemap&show=show">right click to save</a>';
	return;
}
header("Content-Type: application/xml");
header("Expires: Thu, 19 Feb 2020 13:24:18 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0,pre-check=0");
header("Cache-Control: max-age=0");
header("Pragma: no-cache");


$res = arrayDB("SELECT type,appid,title FROM steam_de WHERE type = 'app'");

?><?= '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
	xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
<?php
foreach ($res as $val): ?>
	<url>
		<loc><?= 'https://gig-games.de/game/?type='.$val['type'].'&amp;appid='.steam_to_gig($val['appid']).'&amp;title='.get_gig_game_url_title($val['title']);?></loc>
		<lastmod>2019-01-18T09:24:08Z</lastmod>
		<changefreq>monthly</changefreq>
		<priority>0.6</priority>
	</url>
<?php endforeach; ?>

</urlset>