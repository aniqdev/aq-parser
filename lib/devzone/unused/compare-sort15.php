<style>hr{border:1px solid blue;}</style>
<pre>
<?php

$t1 = readExcel('csv/sort15.xlsx', 0);
$t2 = readExcel('csv/sort15.xlsx', 1);

var_dump($t1_len = count($t1));
var_dump($t2_len = count($t2));

$t1_new = ['asd',$t1[1]];
foreach ($t1 as $k1 => $val1) {
	foreach ($t2 as $k2 => $val2) {
		if (trim($val1['A']) === trim($val2['A'])) {
			var_dump($k1);
			continue 2;
		}
	}
		$t1_new[] = $val1;
}
unset($t1_new[0]);
//print_r($t1_new);

var_dump(count($t1_new));
var_dump(count($t2));
writeExcel('csv/sort15_res.xlsx', $t1_new, 0);
?>
</pre>