<?php

$games = arrayDB("SELECT * FROM steam_de WHERE type = 'app' AND reg_price > 0");

function process($s)
{
	// $s = strip_tags($s);
	// $s = preg_replace('/[\x1B\x26\x6C\x74\x1F\x20\x2E\x1E\x6E\x64\x65\x0C\x5D]/', '', $s);
	// $s = preg_replace('/[\x{FFFF}-\x{FFFF}]+/u','',$s);
	// $s = str_replace(['™','’'], '', $s);
	// $s = str_replace('’', "'", $s);
 	
 	return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
	// return sanitizeXML($s);
	// return htmlentities($s);
}

$dom = new DOMDocument;
$elem_rss = $dom->createElement('rss');

$attr_version = $dom->createAttribute('version');
$attr_version->value = "2.0";

$attr_xmlnsg = $dom->createAttribute('xmlns:g');
$attr_xmlnsg->value = "http://base.google.com/ns/1.0";

$elem_rss->appendChild($attr_version);
$elem_rss->appendChild($attr_xmlnsg);



$elem_channel = $dom->createElement('channel');


$elem_title = $dom->createElement('title');
$elem_title->appendChild($dom->createTextNode('gig games'));
$elem_channel->appendChild($elem_title);

$elem_link = $dom->createElement('link');
$elem_link->appendChild($dom->createTextNode('https://gig-games.de'));
$elem_channel->appendChild($elem_link);

$elem_desc = $dom->createElement('description');
$elem_desc->appendChild($dom->createTextNode('Steam games'));
$elem_channel->appendChild($elem_desc);




foreach ($games as $key => $game) {
	if ($key > 10000) break;


	$elem_item = $dom->createElement('item');

	$elem_cild = $dom->createElement('g:id');
	$elem_cild->appendChild($dom->createTextNode($game['id']));
	$elem_item->appendChild($elem_cild);

	$elem_cild = $dom->createElement('g:title');
	$elem_cild->appendChild($dom->createTextNode($game['title']));
	$elem_item->appendChild($elem_cild);

	$elem_cild = $dom->createElement('g:description');
	$elem_cild->appendChild($dom->createTextNode($game['desc']));
	$elem_item->appendChild($elem_cild);

	$elem_cild = $dom->createElement('g:google_product_category');
	$elem_cild->appendChild($dom->createTextNode('1279'));
	$elem_item->appendChild($elem_cild);

	$elem_cild = $dom->createElement('g:link');
	$elem_cild->appendChild($dom->createTextNode(get_gig_game_link_2($game)));
	$elem_item->appendChild($elem_cild);

	$type  = ($game['type'] === 'dlc') ? 'app' : $game['type'];
	$img = 'https://parser.gig-games.de/steam-images/'.$type.'s-'.$game['appid'].'/header-80p.jpg';
	$elem_cild = $dom->createElement('g:image_link');
	$elem_cild->appendChild($dom->createTextNode($img));
	$elem_item->appendChild($elem_cild);

	$elem_cild = $dom->createElement('g:price');
	$elem_cild->appendChild($dom->createTextNode($game['reg_price'].' EUR'));
	$elem_item->appendChild($elem_cild);

	$elem_cild = $dom->createElement('g:condition');
	$elem_cild->appendChild($dom->createTextNode('new'));
	$elem_item->appendChild($elem_cild);

	$elem_cild = $dom->createElement('g:availability');
	$elem_cild->appendChild($dom->createTextNode('in stock'));
	$elem_item->appendChild($elem_cild);

	$elem_cild = $dom->createElement('g:adult');
	$elem_cild->appendChild($dom->createTextNode('no'));
	$elem_item->appendChild($elem_cild);

	$elem_cild = $dom->createElement('g:mpn');
	$elem_cild->appendChild($dom->createTextNode($game['id']));
	$elem_item->appendChild($elem_cild);

	$elem_channel->appendChild($elem_item);
}


$elem_rss->appendChild($elem_channel);

// $element->appendChild($dom->createTextNode('I am text with Ünicödé & HTML €ntities ©'));

$dom->appendChild($elem_rss);

file_put_contents(ROOT.'/ignore/game-feed.xml', $dom->saveXml());


$xml = '<?xml version="1.0"?>
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
	<channel>
		<title>gig games</title>
		<link>https://gig-games.de</link>
		<description>Steam games</description>';
foreach ($games as $key => $game) {
	break;
	$type  = ($game['type'] === 'dlc') ? 'app' : $game['type'];
	$img = 'https://parser.gig-games.de/steam-images/'.$type.'s-'.$game['appid'].'/header-80p.jpg';
	$xml .= '		<item>
			<g:id>'.$game['id'].'</g:id>
			<g:title>'.process($game['title']).'</g:title>
			<g:description><![CDATA[ '.process($game['desc']).' ]]></g:description>
			<g:google_product_category>1279</g:google_product_category>
			<g:link>'.process(get_gig_game_link_2($game)).'</g:link>
			<g:image_link>'.$img.'</g:image_link>
			<g:price>'.$game['reg_price'].' EUR</g:price>
			<g:condition>new</g:condition>
			<g:availability>in stock</g:availability>
			<g:adult>no</g:adult>
			<g:identifier_exists>no</g:identifier_exists>
			<g:shipping>
			  <g:service>ingame transfer</g:service>
			  <g:price>0.00 EUR</g:price>
			</g:shipping>
			<g:mpn>'.$game['id'].'</g:mpn>
		</item>';
}
$xml .= '	</channel>
</rss>';

// $xml = preg_replace('/[^\x20-\x7E]/','', $xml);
// $xml = preg_replace('/[[:^print:]]/', '', $xml);

// file_put_contents(ROOT.'/ignore/game-feed.xml', $xml);

?>
<div class="container">
	<br><br><br>
	<a href="/ignore/game-feed.xml">game-feed.xml</a> (<?= count($games); ?>)
</div>