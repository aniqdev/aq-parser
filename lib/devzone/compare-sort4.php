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

function checkT($t1A,$t23){
	
	foreach ($t23 as $k => $val) {
		if (trim($t1A) === trim($val['B'])) {
			return $val;
		}
	}
	return false;
}

for ($i=4; $i <= $t1_len; $i++) { 
	
	$check = checkT($t1[$i]['A'], $t2);
	if($check){
		$t1[$i]['B'] = $check['D'];
		$t1[$i]['C'] = $check['C'];
	}
	
	$check = checkT($t1[$i]['A'], $t3);
	if($check){
		$t1[$i]['D'] = $check['D'];
		$t1[$i]['E'] = $check['C'];
	}
}
print_r($t1);

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
writeCell('csv/sep5res.xlsx', $cells, $valls);
// print_r($cells);
// print_r($valls);
?>
</pre>