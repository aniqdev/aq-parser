<pre>
<?php

$t1 = readExcel('csv/sep3.xlsx', 0);
$t2 = readExcel('csv/sep3.xlsx', 1);

var_dump($t1_len = count($t1));
var_dump($t2_len = count($t2));
//print_r($t1);
//print_r($t2);

$res = [];
for ($i = 2; $i <= $t2_len ; $i++) { 
	// break;
	foreach ($t1 as $k => $val) {
		if(trim($t2[$i]['A']) === trim($val['B'])) continue 2;
	}
	$res[] = $t2[$i];
}
print_r($res);

$cells = [];
$valls = [];
foreach ($res as $k1 => $row) {
	foreach ($row as $k2 => $col) {
		$cells[] = $k2.($k1+2);
		$valls[] = $col;
	}
}
var_dump(count($cells));
var_dump(count($valls));
print_r($cells);
print_r($valls);
writeCell('csv/sep3res.xlsx', $cells, $valls);
?>
</pre>