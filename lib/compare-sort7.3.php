<style>hr{border:1px solid blue;}</style>
<pre>
<?php
//require_once 'lib/simple_html_dom.php';

$t1 = csvToArr('csv/amazonSKUs.csv');

var_dump($t1_len = count($t1));
//print_r($t1);

$t2 = [];
foreach ($t1 as $k => $val) {
	if(isset($val[0])) $t2[$k+1]['A'] = $val[0];
	if(isset($val[1])) $t2[$k+1]['B'] = $val[1];
	if(isset($val[2])) $t2[$k+1]['C'] = $val[2];
	if(isset($val[3])) $t2[$k+1]['D'] = $val[3];
}
print_r($t2);

// $j = 2;
// foreach ($t1keys as $k => $val) {
// 	$t2[$j]['A'] = $k;
// 	if(isset($val[0])) $t2[$j]['B'] = $val[0]['B'];
// 	if(isset($val[1])) $t2[$j]['C'] = $val[1]['B'];
// 	if(isset($val[2])) $t2[$j]['D'] = $val[2]['B'];
// 	if(isset($val[3])) $t2[$j]['E'] = $val[3]['B'];
// 	if(isset($val[4])) $t2[$j]['F'] = $val[4]['B'];
// 	if(isset($val[5])) $t2[$j]['G'] = $val[5]['B'];
// 	$j++;
// }


$cells = [];
$valls = [];
foreach ($t2 as $k1 => $row) {
	foreach ($row as $k2 => $col) {
		$cells[] = $k2.($k1);
		$valls[] = $col;
	}
}
var_dump(count($cells));
var_dump(count($valls));
writeCell('csv/amazonSKUs.xlsx', $cells, $valls);
// print_r($cells);
// print_r($valls);
?>
</pre>