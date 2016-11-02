<style>hr{border:1px solid blue;}</style>
<pre>
<?php
require_once 'lib/simple_html_dom.php';

$t1 = readExcel('csv/sep5.xlsx', 0);
$t2 = readExcel('csv/sep5.xlsx', 1);
$t3 = readExcel('csv/sep5.xlsx', 2);

var_dump($t1_len = count($t1));
var_dump($t2_len = count($t2));
var_dump($t3_len = count($t3));
//print_r($t3);

$t1_inverted = [];

for ($i=4; $i <= $t1_len; $i++) { 
	$t1_inverted[$t1[$i]['A']] = true;
}

$t2_res =[];
for ($i=2; $i <= $t2_len; $i++) { 
	
	if(!isset($t1_inverted[$t2[$i]['B']])) $t2_res[] = $t2[$i];
}

$t3_res =[];
for ($i=2; $i <= $t3_len; $i++) { 
	
	if(!isset($t1_inverted[$t3[$i]['B']])) $t3_res[] = $t3[$i];
}

print_r($t2_res);
print_r($t3_res);

var_dump(count($t2_res));
var_dump(count($t3_res));

$cells = [];
$valls = [];
foreach ($t3_res as $k1 => $row) {
	foreach ($row as $k2 => $col) {
		$cells[] = $k2.($k1+2);
		$valls[] = $col;
	}
}
var_dump(count($cells));
var_dump(count($valls));
writeCell('csv/sep5res3.xlsx', $cells, $valls);
// print_r($cells);
// print_r($valls);
?>
</pre>