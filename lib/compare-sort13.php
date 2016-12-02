<style>hr{border:1px solid blue;}</style>
<pre>
<?php
//require_once 'lib/simple_html_dom.php';

$t1 = readExcel('csv/amazon_check_sort13.xlsx', 0);
$t2 = readExcel('csv/amazon_check_sort13.xlsx', 1);


foreach ($t1 as $k1 => $val1) {
	foreach ($t2 as $k2 => &$val2) {
		if (trim($val2['A']) === trim($val1['B'])) {
			$val2['E'] = $val1['A'];
		}
	}
}

// print_r($t1);
print_r($t2);

var_dump(count($t1));
var_dump(count($t2));
// writeExcel('csv/amazon_check_sort13_res.xlsx', $t1, 0);
writeExcel('csv/amazon_check_sort13_res.xlsx', $t2, 1);
?>
</pre>