<style>hr{border:1px solid blue;}</style>
<pre>
<?php
//require_once 'lib/simple_html_dom.php';

$t1 = readExcel('csv/Amazon_SKU_3.xlsx', 0);
//$t2 = readExcel('csv/Amazon_SKU_3.xlsx', 1);
$t3 = readExcel('csv/Amazon_SKU_3.xlsx', 2);
$t4 = readExcel('csv/Amazon_SKU_3.xlsx', 3);

var_dump($t1_len = count($t1));
//var_dump($t2_len = count($t2));
var_dump($t3_len = count($t3));
var_dump($t4_len = count($t4));
//print_r($t1);
//print_r($t4);

$t1_keys = [];
foreach ($t1 as $key => $val) {
	$t1_keys[$val['A']] = $key;
}
//print_r($t1_keys);

$t3_left = [];
$t3_left[1] = $t3[1];
for ($i=2; $i <= $t3_len; $i++) { 
	
	$x = true;
	for ($j=5; $j <= $t4_len; $j++) { 
		
		if(!is_numeric($t4[$j]['E'])) continue;

		if (trim($t3[$i]['B']) === trim($t4[$j]['B'])) {
			
			$x = false;
			if (isset($t1_keys[$t4[$j]['E']]) && isset($t4[$j]['D'])) {
				
				$t1[$t1_keys[$t4[$j]['E']]]['F'] = $t4[$j]['A'];
				$t1[$t1_keys[$t4[$j]['E']]]['G'] = $t4[$j]['B'];
				$t1[$t1_keys[$t4[$j]['E']]]['H'] = $t4[$j]['C'];

			}elseif (isset($t1_keys[$t4[$j]['E']]) && !isset($t4[$j]['D'])) {
				
				$t1[$t1_keys[$t4[$j]['E']]]['I'] = $t4[$j]['A'];
				$t1[$t1_keys[$t4[$j]['E']]]['J'] = $t4[$j]['B'];
				$t1[$t1_keys[$t4[$j]['E']]]['K'] = $t4[$j]['C'];

			}else{

			}
		}
	}
	if($x) $t3_left[] = $t3[$i];
}

//print_r($t1);
//unset($t3_left[0]);
print_r($t3_left);

//writeExcel('csv/Amazon_SKU_3res.xlsx', $t1, 0);
writeExcel('csv/Amazon_SKU_3left.xlsx', $t3_left, 0);
?>
</pre>