<pre>
<?php

$t1 = readExcel('csv/sep1nov2.xlsx', 0);
$t2 = readExcel('csv/sep1nov2.xlsx', 1);

var_dump($t1_len = count($t1));
var_dump($t2_len = count($t2));
//print_r($t1);
//print_r($t2);

function check_id_in_T2($id , $t2){
	
	foreach ($t2 as $key => $value) {
		if ($value['A'] == $id || $value['B'] == $id ) {
			var_dump('A = '.$value['A'].' && B = '.$value['B'].'    id = '.$id);
			var_dump('TRUE');
			return $value['A'];
		}
	}
		var_dump('A = '.$value['A'].' && B = '.$value['B'].'    id = '.$id);
		var_dump('FALSE');
		return false;
}

$t2keys = [];
foreach ($t2 as $key => $v) {
	if($v['H'] == '0') $t2keys[$v['A']][] = $v['B'];
	else $t2keys[$v['A']][$v['H']-1] = $v['B'];
}
//print_r($t2keys);
$results = [];
for ($i = 4; $i <= $t1_len ; $i++) { 
	//break;
	var_dump('i = '.$i);
	$result = [];
	$L = $t1[$i]['L'];
	var_dump($L);
	$t1_id = $t1[$i]['A'];
	$check = check_id_in_T2($t1_id, $t2);
	var_dump($check);

	if ($L == 0 && $check === false) {

		var_dump('l=0');
		$t1[$i]['B'] = 'Standard Artikel';
		$t1[$i]['C'] = 'FALSCH';
		$t1[$i]['E'] = '0';

	}elseif ($L == 1){

		var_dump('l=1');
		$t1[$i]['B'] = 'Standard Artikel';
		$t1[$i]['C'] = 'WAHR';
		$t1[$i]['E'] = '1';
		if(isset($t2keys[$t1_id][0])) $t1[$i]['F'] = $t2keys[$t1_id][0];
		if(isset($t2keys[$t1_id][1])) $t1[$i]['G'] = $t2keys[$t1_id][1];
		if(isset($t2keys[$t1_id][2])) $t1[$i]['H'] = $t2keys[$t1_id][2];
		if(isset($t2keys[$t1_id][3])) $t1[$i]['I'] = $t2keys[$t1_id][3];
		if(isset($t2keys[$t1_id][4])) $t1[$i]['J'] = $t2keys[$t1_id][4];
		if(isset($t2keys[$t1_id][5])) $t1[$i]['K'] = $t2keys[$t1_id][5];

	}elseif ($L == 2){

		var_dump('l=2');
		$t1[$i]['B'] = 'Artikel mit Stückliste (Leistung)';
		$t1[$i]['C'] = 'FALSCH';
		$t1[$i]['E'] = '2';
		if(isset($t2keys[$t1_id][0])) $t1[$i]['F'] = $t2keys[$t1_id][0];
		if(isset($t2keys[$t1_id][1])) $t1[$i]['G'] = $t2keys[$t1_id][1];
		if(isset($t2keys[$t1_id][2])) $t1[$i]['H'] = $t2keys[$t1_id][2];
		if(isset($t2keys[$t1_id][3])) $t1[$i]['I'] = $t2keys[$t1_id][3];
		if(isset($t2keys[$t1_id][4])) $t1[$i]['J'] = $t2keys[$t1_id][4];
		if(isset($t2keys[$t1_id][5])) $t1[$i]['K'] = $t2keys[$t1_id][5];

	}elseif ($L == 0 && $check !== false) {

		var_dump('expression 4');
		$t1[$i]['B'] = 'Variantenartikel';
		$t1[$i]['C'] = 'FALSCH';
		$t1[$i]['D'] = $check;
		$t1[$i]['E'] = '0';

	}else{

		var_dump('expression 5 !!!');

	}
	echo "<hr>";

}
print_r($t1);

$cells = [];
$valls = [];
foreach ($t1 as $k1 => $row) {
	foreach ($row as $k2 => $col) {
		$cells[] = $k2.$k1;
		$valls[] = $col;
	}
}
// var_dump(count($cells));
// var_dump(count($valls));
// print_r($cells);
// print_r($valls);
writeCell('csv/sep1nov2_res.xlsx', $cells, $valls);
?>
</pre>