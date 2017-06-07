<pre><?php

$res = arrayDB("SELECT genres FROM steam_de");

$genres = [];
foreach ($res as $k => $v) {
	$genreses = explode(',', $v['genres']);
	foreach ($genreses as $val) {
		$genres[$val] = $val;
	}
}

$gens = []; $i = 1;
foreach ($genres as $value) {
	$gens[$i++] = $value;
}

var_dump(count($genres));

print_r($gens);




?></pre>

