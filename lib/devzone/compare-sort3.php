<style>
	hr{
		border: 1px solid blue;
	}
</style>
<pre>
<?php
require_once 'lib/simple_html_dom.php';

$t1 = readExcel('csv/sep4.xlsx', 0);

var_dump($t1_len = count($t1));
//print_r($t1);


foreach ($t1 as &$val) {
	
	$dom = str_get_html($val['B']);
	$dom = preg_replace('/(%0d%0a\s|\s%0d%0a)/', "%0d%0a", $dom->plaintext);
	$dom = preg_replace('/(%0d%0a){3,}/', "%0d%0a%0d%0a", $dom);
	$dom = str_replace('%0d%0a', PHP_EOL, $dom);
	$val['C'] = html_entity_decode($dom);
}


$cells = [];
$valls = [];
foreach ($t1 as $k1 => $row) {
	foreach ($row as $k2 => $col) {
		$cells[] = $k2.$k1;
		$valls[] = $col;
	}
}
var_dump(count($cells));
var_dump(count($valls));
writeCell('csv/sep4res.xlsx', $cells, $valls);
// print_r($cells);
// print_r($valls);
?>
</pre>