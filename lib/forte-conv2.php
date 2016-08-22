<?php
ini_set('max_execution_time', 300);
set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.


$first = csvToArr('csv/forte_in1.csv', ['delimetr' => ',','del_first' => 1]);
//$first = array_map(function ($el){ return $el[0]; }, $first);

class S{ public static $counter = 0; }

$third = [];
$third[0] = ['BaseProdukt',	'KinderProdukt1',	'KinderProdukt2',	'KinderProdukt3',	'KinderProdukt4',	'KinderProdukt5',	'KinderProdukt6'];


$second = [];
foreach ($first as $k => $val) {
	$second[$val[0]][] = $val[1];
}


foreach ($second as $key => $value) {
	$p = [];
	$p[0] = $key;
	$p = array_merge($p,$value);
	$third[] = $p;
}

    $config = array(
        'delimetr' => ';',
        'encoding' => 'windows-1251',
        'keys_first_row' => false
        );
arrToCsv($third, 'csv/forte.csv', $config);

?>
<pre>
<?php
var_dump(S::$counter);
// print_r($first);
 // print_r($second);
 print_r($third);

?>
</pre>