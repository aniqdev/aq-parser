<style>hr{border:1px solid blue;}</style>
<pre>
<?php
//require_once 'lib/simple_html_dom.php';

$t1 = readExcel('csv/sort11nov.xlsx', 0);
$t2 = readExcel('csv/sort11nov.xlsx', 1);

var_dump($t1_len = count($t1));
var_dump($t2_len = count($t2));
//print_r($t1);

$excel_keys = [];
foreach ($t1[1] as $key => $value) {
	$excel_keys[] = $key;
}
print_r($excel_keys);

for ($i=2; $i <= $t1_len; $i++) { 
	
	for ($j=1; $j <= $t2_len; $j++) { 
		
		if (strpos(trim($t2[$j]['A']),trim($t1[$i]['A'])) !== false) {
			
			$ex = explode('|', trim($t2[$j]['B']));
			foreach ($ex as $k => $val) {
				$t1[$i][$excel_keys[$k+2]] = $val;
			}
			break;
		}
	}
}
print_r($t1);

writeExcel('csv/sort11nov_res.xlsx', $t1, 0);
?>
</pre>