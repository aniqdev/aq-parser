<style>hr{border:1px solid blue;}</style>
<pre>
<?php
//require_once 'lib/simple_html_dom.php';

$t1 = readExcel('csv/sort10.xlsx', 0);
$t4 = readExcel('csv/sort10.xlsx', 1);
$t5 = readExcel('csv/sort10.xlsx', 2);


var_dump($t1_len = count($t1));
var_dump($t4_len = count($t4));
var_dump($t5_len = count($t5));

$t6 = [1 => $t4[1]];
for ($i=2; $i <= $t4_len; $i++) { 
	
	$x = true;
	for ($j=4; $j <= $t1_len; $j++) { 
		
		if (trim($t4[$i]['B']) === trim($t1[$j]['A'])) {
			
			$x = false;
			$t1[$j]['B'] = $t4[$i]['C'];
		}
	}
	if($x) $t6[] = $t4[$i];
}

$t7 = [1 => $t5[1]];
for ($i=2; $i <= $t5_len; $i++) { 
	
	$x = true;
	for ($j=4; $j <= $t1_len; $j++) { 
		
		if (trim($t5[$i]['B']) === trim($t1[$j]['A'])) {
			
			$x = false;
			$t1[$j]['C'] = $t5[$i]['C'];
		}
	}
	if($x) $t7[] = $t5[$i];
}

print_r($t6);
print_r($t7);
print_r($t1);

// writeExcel('csv/sort10res.xlsx', $t1, 0);
// writeExcel('csv/sort10res.xlsx', $t6, 3);
// writeExcel('csv/sort10res.xlsx', $t7, 4);
?>
</pre>