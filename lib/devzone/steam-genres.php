<pre><?php

$res = arrayDB("SELECT genres FROM steam limit 2000,10000");

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

