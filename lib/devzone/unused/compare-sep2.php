<pre>
<?php

$t1 = readExcel('csv/sep2.xlsx', 0);
$t2 = readExcel('csv/sep2.xlsx', 1);

var_dump($t1_len = count($t1));
var_dump($t2_len = count($t2));
//var_dump($t1);
//var_dump($t2);
// var_dump($t1[286]['A'] === $t2[217]['C']);
// var_dump('0' == $t2[217]['D']);
for ($i = 4; $i <= $t1_len ; $i++) { 
	// break;
	foreach ($t2 as $k => $val) {
		if($t1[$i]['A'] === $val['C'] && '0' == $val['D']){
			$t1[$i]['B'] = $val['A'];
			$t1[$i]['C'] = $val['B'];
		}
		if($t1[$i]['A'] === $val['C'] && '8347' ==  $val['D']){
			$t1[$i]['D'] = $val['A'];
			$t1[$i]['E'] = $val['B'];
		}
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
print_r($cells);
print_r($valls);
writeCell('csv/sep2res.xlsx', $cells, $valls);
?>
</pre>