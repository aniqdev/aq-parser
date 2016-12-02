<style>hr{border:1px solid blue;}</style>
<pre>
<?php
//require_once 'lib/simple_html_dom.php';

$t1 = readExcel('csv/sort14nov.xlsx', 0);
$t2 = readExcel('csv/sort14nov.xlsx', 1);

var_dump($t1_len = count($t1));
var_dump($t2_len = count($t2));

foreach ($t1 as $k1 => &$val1) {
	foreach ($t2 as $k2 => $val2) {
		if (trim($val1['A']) === trim($val2['E'])) {
			if (stripos($val2['A'], 'afn') !== false) {
				$val1['E'] = $val2['A'];
				$val1['F'] = $val2['B'];
				$val1['G'] = $val2['C'];
			}else{
				$val1['B'] = $val2['A'];
				$val1['C'] = $val2['B'];
				$val1['D'] = $val2['C'];
			}
		}
	}
}
print_r($t1);

var_dump(count($t1));
var_dump(count($t2));
writeExcel('csv/sort14nov_res.xlsx', $t1, 0);
?>
</pre>