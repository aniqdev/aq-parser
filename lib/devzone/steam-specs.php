<pre><?php

$res = arrayDB("SELECT specs FROM steam limit 2000,8000");

$genres = [];
foreach ($res as $k => $v) {
	$genreses = explode(',', $v['specs']);
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
//print_r(array_flip($gens));




?></pre>

