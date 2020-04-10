<?php

$res = arrayDB("SELECT genres as g,tags as t FROM steam_de");

$final = [];
$exept = [
	'Action',
	'Abenteuer',
	'Gelegenheitsspiele',
	'Simulation',
	'Strategie',
	'RPG',
	'Sport',
];
$count = 0;
foreach ($res as $v) {
	$g = explode(',', $v['g']);
	foreach ($g as $gen) {
		if (in_array($gen, $exept)) {
			continue 2;
		}
	}
	$t = explode(',', $v['t']);
	foreach ($t as $tag) {
		if (in_array($tag, $exept)) {
			continue 2;
		}
	}
	$count += 1;
}

sa($count);

?>