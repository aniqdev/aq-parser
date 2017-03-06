<style>hr{border:1px solid blue;}</style>
<pre>
<?php
//require_once 'lib/simple_html_dom.php';

$t1 = readExcel('csv/sort5.xlsx', 0);
$t2 = readExcel('csv/sort5.xlsx', 1);

var_dump($t1_len = count($t1));
var_dump($t2_len = count($t2));
//print_r($t2);

// var_dump($t1[832]['A'] === $t2[9483]['A']);
// var_dump($t2[9483]['B'] == '136');


for ($i=4; $i <= $t1_len; $i++) { 

	foreach ($t2 as $k => $val) {
		if ($t1[$i]['A'] === $val['A'] && $val['B'] == '136') {
			$t1[$i]['B'] = $val['C'];
		}
		if ($t1[$i]['A'] === $val['A'] && $val['B'] == '137') {
			$t1[$i]['C'] = $val['C'];
		}
		if ($t1[$i]['A'] === $val['A'] && $val['B'] == '138') {
			$t1[$i]['D'] = $val['C'];
		}
	}
}
print_r($t1);

$cells = [];
$valls = [];
foreach ($t1 as $k1 => $row) {
	foreach ($row as $k2 => $col) {
		$cells[] = $k2.($k1);
		$valls[] = $col;
	}
}
var_dump(count($cells));
var_dump(count($valls));
writeCell('csv/sort5res.xlsx', $cells, $valls);
// print_r($cells);
// print_r($valls);
?>
</pre>