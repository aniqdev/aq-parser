<style>hr{border:1px solid blue;}</style>
<pre>
<?php
//require_once 'lib/simple_html_dom.php';

$t1 = readExcel('csv/art1.xlsx', 0);

var_dump($t1_len = count($t1));

$find = [
		// 'AB'=>'Hund',
		// 'AC'=>'Katze',
		// 'AD'=>'Pferd',
		// 'AE'=>'Nichts',
		'AF'=>'Fell',
		'AG'=>'Haut' ,
		'AH'=>'Hufe' ,
		'AI'=>'Magen' ,
		'AJ'=>'Darm' ,
		'AK'=>'Gelenk' ,
		'AL'=>'Sehne' ,
		'AM'=>'Knoche' ,
		'AN'=>'Zecken' ,
		'AO'=>'Milbe' ,
		'AP'=>'Parasit',
		'AQ'=>'Flöhe' ,
		'AR'=>'Ohre' ,
		'AR'=>'Wurm' ,
		'AS'=>'Fliege' ,
		'AT'=>'Geruch' ,
		'AU'=>'Immun' ,
		'AV'=>'Herz' ,
		'AW'=>'Zahn' ,
		'AX'=>'Skelett',
		'AY'=>'Augen' ,
		'AZ'=>'Stoffwechsel' ,
		'BA'=>'Krallen',
		'BB'=>'Maul'];

foreach ($find as $k => $w) {
	$t1[1][$k] = $w;
}

for ($i=2; $i <= $t1_len; $i++) { 

	foreach ($find as $k => $word) {
		if (stripos($t1[$i]['P'], $word) !== false) {
			$t1[$i][$k] = 1;
		}
	}
}



print_r($t1);

writeExcel('csv/art1.xlsx', $t1, 0);
?>
</pre>