<pre>
<?php
ini_set('max_execution_time', 3000);
set_time_limit(3000); // Указываем скрипту, чтобы не обрывал связь.


$first = csvToArr('csv/forte_in1.csv', ['delimetr' => ',','del_first' => 1]);
//print_r($first);
$first = array_map(function ($el){ return $el[0]; }, $first);
//print_r($first);

$second = csvToArr('csv/forte_in2.csv', ['delimetr' => ',','del_first' => 1]);
//$second = array_map(function ($el){ return $el[0]; }, $second);
//print_r($second);
$second2 = [];
foreach ($second as $key => $value) {
	$second2[$value[0]][$value[2]][$value[3]] = $value[5];
}
//print_r($second2);

class S{ public static $counter = 0; }

function savePic($path , $id, $n){

	if ($path !== false) {
		preg_match('(//(.+?)\.jpg)i', $path, $b);
		$path = 'http://'.$b[1].'.jpg';
		$name = $id.'-'.$n.'.jpg';
		if(copy($path, __DIR__.'/../pictures/'.$name)){
			S::$counter++;
			return $name;
		} else return '';

		// if(getimagesize($path)){
		// 	S::$counter++;
		// 	return $name;
		// } else return '';

	}else
		return '';
}

$third = [];
$third[0] = ['ID',	'картинка 1',	'картинка 2',	'картинка 3',	'картинка 4',	'картинка 5',	'картинка 6'];
foreach ($first as $fkey => $fval) {

	$fkey += 1;
	if(isset($second2[$fval])){
		$third[$fkey][0] = $fval;
		$third[$fkey][1] = savePic(isset($second2[$fval][1]) ? $second2[$fval][1][0] : false, $fval, 1);
		$third[$fkey][2] = savePic(isset($second2[$fval][2]) ? $second2[$fval][2][0] : false, $fval, 2);
		$third[$fkey][3] = savePic(isset($second2[$fval][3]) ? $second2[$fval][3][0] : false, $fval, 3);
		$third[$fkey][4] = savePic(isset($second2[$fval][4]) ? $second2[$fval][4][0] : false, $fval, 4);
		$third[$fkey][5] = savePic(isset($second2[$fval][5]) ? $second2[$fval][5][0] : false, $fval, 5);
		$third[$fkey][6] = savePic(isset($second2[$fval][6]) ? $second2[$fval][6][0] : false, $fval, 6);
	}else{
		$third[$fkey][0] = $fval;
		$third[$fkey][1] = '';
		$third[$fkey][2] = '';
		$third[$fkey][3] = '';
		$third[$fkey][4] = '';
		$third[$fkey][5] = '';
		$third[$fkey][6] = '';
	}

	//if($fkey == 10) break;
}
    $config = array(
        'delimetr' => ';',
        'encoding' => 'windows-1251',
        'keys_first_row' => false
        );
arrToCsv($third, 'csv/forte21sep.csv', $config);

?>

<?php
//var_dump(!!getimagesize('http://bilder.afterbuy.de/images/NPZZNW/etikett_colostrum_250g_1200x1200_72dpi.jpg'));
var_dump(S::$counter);
// print_r($first);
//print_r($second2);
print_r($third);

?>
</pre>