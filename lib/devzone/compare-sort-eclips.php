<style>hr{border:1px solid blue;}</style>
<pre>
<?php
//require_once 'lib/simple_html_dom.php';

$t1 = readExcel('csv/Amazon_SKU_eclips_res.xlsx', 0);
$t2 = readExcel('csv/Amazon_SKU_eclips_res.xlsx', 1);
$tinit = readExcel('csv/Amazon_SKU_eclips_init.xlsx', 1);

var_dump($tinit_len = count($tinit));
//print_r($tinit);

// $res1 = ['we']; $res2 = ['sd'];
// foreach ($t1 as $key => $value) {
// 	if (stripos($value['B'], 'afn') !== false) {
// 		$res1[] = $value;
// 	}else{
// 		$res2[] = $value;
// 	}
// }
// unset($res1[0]);
// unset($res2[0]);

foreach ($t1 as $k => $val) {
	$t1[$k]['D'] = '';
	foreach ($tinit as $key => $init) {
		if (trim($val['B']) === trim($init['B'])) {
			$t1[$k]['D'] = $init['A'];
			continue 2;
		}
	}
}

foreach ($t2 as $k => $val) {
	$t2[$k]['D'] = '';
	foreach ($tinit as $key => $init) {
		if (trim($val['B']) === trim($init['B'])) {
			$t2[$k]['D'] = $init['A'];
			continue 2;
		}
	}
}
print_r($t1);
print_r($t2);

var_dump(count($t1));
var_dump(count($t2));
// writeExcel('csv/Amazon_SKU_eclips_init_res.xlsx', $t1, 0);
// writeExcel('csv/Amazon_SKU_eclips_init_res.xlsx', $t2, 1);
?>
</pre>