<style>hr{border:1px solid blue;}</style>
<pre>
<?php
//require_once 'lib/simple_html_dom.php';

$t1 = readExcel('csv/AmazonPrice.xlsx', 0);
$t2 = readExcel('csv/AmazonPrice.xlsx', 1);


var_dump($t1_len = count($t1));
var_dump($t2_len = count($t2));
//print_r($t2);


for ($i=1; $i <= $t1_len; $i++) { 
	
	for ($j=1; $j <= $t2_len; $j++) { 
		
		if (trim($t1[$i]['A']) === trim($t2[$j]['A'])) {
			
			$t1[$i]['B'] = $t2[$j]['B'];
		}
	}
}
print_r($t1);


writeExcel('csv/AmazonPrice_res.xlsx', $t1, 0);
?>
</pre>