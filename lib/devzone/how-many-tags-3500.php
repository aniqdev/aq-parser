<?php

//$res = arrayDB("SELECT genres as g,tags as t FROM steam_de");
$res = arrayDB("SELECT genres as g,tags as t FROM steam_de
JOIN games
ON steam_de.link = games.steam_link
LIMIT 4500");

$final = [];
foreach ($res as $v) {
	$g = explode(',', $v['g']);
	foreach ($g as $gen) {
		if (isset($final[$gen])) {
			$final[$gen] += 1;
		}else{
			$final[$gen] = 1;
		}
	}
	$t = explode(',', $v['t']);
	foreach ($t as $tag) {
		if (isset($final[$tag])) {
			$final[$tag] += 1;
		}else{
			$final[$tag] = 1;
		}
	}
}
sa(count($final));
arsort($final);
sa($final);

?>