<style>hr{border:1px solid blue;}</style>
<pre>
<?php
//require_once 'lib/simple_html_dom.php';

$t1 = readExcel('csv/Amazon_SKU_2.xlsx', 0);
$t2 = readExcel('csv/Amazon_SKU_2.xlsx', 1);
$t3 = readExcel('csv/Amazon_SKU_2.xlsx', 2);

var_dump($t1_len = count($t1));
var_dump($t2_len = count($t2));
var_dump($t3_len = count($t3));
//print_r($t1);
//print_r($t2);


	
foreach ($t2 as $t2k => $t2row) {

	if($t2k == '1') continue;
	$isTrue = false;
	for ($i=2; $i <= $t1_len; $i++) {
		$b1 = trim($t2row['A']) === trim($t1[$i]['B']);
		$b2 = trim($t2row['A']) === trim($t1[$i]['C']);
		$b3 = trim($t2row['A']) === trim($t1[$i]['D']);
		$b4 = trim($t2row['A']) === trim($t1[$i]['E']);
		if ($b1 || $b2 || $b3 || $b4) {
			$isTrue = true;
			if (isset($t2row['D'])) {
				$t1[$i]['F'] = trim($t2row['A']);
				$t1[$i]['G'] = trim($t2row['B']);
				$t1[$i]['H'] = trim($t2row['C']);
			}else{
				$t1[$i]['I'] = trim($t2row['A']);
				$t1[$i]['J'] = trim($t2row['B']);
				$t1[$i]['K'] = trim($t2row['C']);
			}
		}
		// if ($t2k == '18' && $i == '985') {
		// 	echo "string";
		// 	var_dump($b1 || $b2 || $b3 || $b4);
		// }
	}
	if(!$isTrue) $t3[] = $t2row;
	
}
var_dump(count($t3));
//print_r($t3);

// writeExcel('csv/Amazon_SKU_2res3.xlsx', $t1, 0);
// writeExcel('csv/Amazon_SKU_2res3.xlsx', $t3, 2);
?>
</pre>