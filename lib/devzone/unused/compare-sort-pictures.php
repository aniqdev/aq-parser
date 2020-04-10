<style>hr{border:1px solid blue;}</style>
<pre>
<?php
ini_get('safe_mode') or set_time_limit(60*150); // Указываем скрипту, чтобы не обрывал связь.
//require_once 'lib/simple_html_dom.php';

$t1 = readExcel('csv/bilder.xlsx', 0);
$t2 = readExcel('csv/bilder.xlsx', 1);
//print_r($t1);

$t2new = []; $falsed = [];
foreach ($t2 as $k => $val) {
	
	$istrue = copy($val['C'], 'pictures/'.$val['A'].'-'.$val['B'].'.jpg');
	if($istrue) $t2new[$val['A']][$val['B']] = $val['C'];
	else $falsed[] = $val;
}
//print_r($t2new);

foreach ($t1 as $key => $value) {
	if($key == '1') continue;

	$id = trim($value['A']);
	if (isset($t2new[$id][1])) $t1[$key]['B'] = $id.'-1.jpg';
	if (isset($t2new[$id][2])) $t1[$key]['C'] = $id.'-2.jpg';
	if (isset($t2new[$id][3])) $t1[$key]['D'] = $id.'-3.jpg';
	if (isset($t2new[$id][4])) $t1[$key]['E'] = $id.'-4.jpg';
	if (isset($t2new[$id][5])) $t1[$key]['F'] = $id.'-5.jpg';
	if (isset($t2new[$id][6])) $t1[$key]['G'] = $id.'-6.jpg';

}
print_r($falsed);
print_r($t1);

var_dump(count($t1));
var_dump(count($t2));
writeExcel('csv/bilder_res.xlsx', $t1, 0);
?>
</pre>