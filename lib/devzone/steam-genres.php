<?php

$res = arrayDB("SELECT genres FROM steam_de");

$genres = [];
foreach ($res as $k => $v) {
	$genreses = explode(',', $v['genres']);
	foreach ($genreses as $val) {
		@$genres[$val] = ++$genres[$val];
	}
}

var_dump(count($genres));

array_multisort($genres, SORT_DESC);

sa($genres);



// $gens = []; $i = 1;
// foreach ($genres as $value) {
// 	$gens[$i++] = $value;
// }

// var_dump(count($genres));

// print_r($gens);






