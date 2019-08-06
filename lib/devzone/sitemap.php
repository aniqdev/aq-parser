<?php


// sa(arrayDB("SELECT count(*) FROM steam_de WHERE type = 'app'"));
echo '<a href="Files/sitemap-game-de.xml" download>right click to save (DE)</a><br>';
echo '<a href="Files/sitemap-game-en.xml" download>right click to save (EN)</a><br>';
echo '<a href="Files/sitemap-game-ru.xml" download>right click to save (RU)</a><br>';







$res = arrayDB("SELECT type,appid,title FROM steam_de WHERE type = 'app'");
sa(count($res));

$xml = '<?xml version="1.0" encoding="UTF-8"?>
		<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
	xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

foreach ($res as $val):
	$xml .= '<url>
		<loc>https://gig-games.de/game/?type='.$val['type'].'&amp;appid='.steam_to_gig($val['appid']).'&amp;title='.get_gig_game_url_title($val['title']).'</loc>
		<lastmod>2019-01-18T09:24:08Z</lastmod>
		<changefreq>monthly</changefreq>
		<priority>0.6</priority>
	</url>';
endforeach;

$xml .= '</urlset>';

file_put_contents(ROOT.'/Files/sitemap-game-de.xml', $xml);




$res = arrayDB("SELECT type,appid,title FROM steam_en WHERE type = 'app'");
sa(count($res));

$xml = '<?xml version="1.0" encoding="UTF-8"?>
		<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
	xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

foreach ($res as $val):
	$xml .= '<url>
		<loc>https://gig-games.de/en/game-en/?type='.$val['type'].'&amp;appid='.steam_to_gig($val['appid']).'&amp;title='.get_gig_game_url_title($val['title']).'</loc>
		<lastmod>2019-01-18T09:24:08Z</lastmod>
		<changefreq>monthly</changefreq>
		<priority>0.6</priority>
	</url>';
endforeach;

$xml .= '</urlset>';

file_put_contents(ROOT.'/Files/sitemap-game-en.xml', $xml);




$res = arrayDB("SELECT type,appid,title FROM steam_ru WHERE type = 'app'");
sa(count($res));

$xml = '<?xml version="1.0" encoding="UTF-8"?>
		<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
	xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

foreach ($res as $val):
	$xml .= '<url>
		<loc>https://gig-games.de/ru/game-ru/?type='.$val['type'].'&amp;appid='.steam_to_gig($val['appid']).'&amp;title='.get_gig_game_url_title($val['title']).'</loc>
		<lastmod>2019-01-18T09:24:08Z</lastmod>
		<changefreq>monthly</changefreq>
		<priority>0.6</priority>
	</url>';
endforeach;

$xml .= '</urlset>';

file_put_contents(ROOT.'/Files/sitemap-game-ru.xml', $xml);