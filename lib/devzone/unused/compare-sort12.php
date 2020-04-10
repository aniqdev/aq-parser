<style>hr{border:1px solid blue;}</style>
<pre>
<?php
//require_once 'lib/simple_html_dom.php';

$t1 = readExcel('csv/sort12_themengebiet.xlsx', 0);

var_dump($t1_len = count($t1));
//print_r($t1);


for ($i=1; $i <= $t1_len; $i++) { 

	$ex = explode(';', $t1[$i]['B']);
	array_walk($ex, function (&$value){ $value = str_replace('/ ', '/', $value); });
	$ex = array_unique($ex);
	//var_dump($ex);
	$t1[$i]['B'] = implode(';', $ex);
}
print_r($t1);

writeExcel('csv/sort12_themengebiet_res.xlsx', $t1, 0);
?>
</pre>