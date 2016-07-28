<form method="POST" class="compare-form">
	<textarea name="list1" id="list1" cols="30" rows="10" value="asd"><?php
//++++++++++++++++++++++++++++++++++++++++++++++++++
if (isset($_POST['list1'])) {
	echo ($_POST['list1']);
}
//++++++++++++++++++++++++++++++++++++++++++++++++++
?></textarea>
	<textarea name="list2" id="list2" cols="30" rows="10"><?php
//++++++++++++++++++++++++++++++++++++++++++++++++++
if (isset($_POST['list2'])) {
	echo ($_POST['list2']);
}
//++++++++++++++++++++++++++++++++++++++++++++++++++
?></textarea><br>
	<button type="submit">Go!</button>
</form>
<table class="ppp-table">
<?php

if ($_POST) {

	// разбили строку из textarea на массив
	$arr1 = explode(PHP_EOL, $_POST['list1']);
	$arr2 = explode(PHP_EOL, $_POST['list2']);

	// удалили элементы FALSE(пустые строки)
	$arr1 = array_filter($arr1);
	$arr2 = array_filter($arr2);

function array_watk_callback(&$value, $key){
	// echo "string = ";
	// var_dump($value);
	$words_to_del = array(
		'(PC)',' PC ','Steam','-Region free-','Region free','Multilanguage',
		'Multilang','Regfree','ENGLISH','--','Uplay');
    $value = str_ireplace( $words_to_del, ' ', $value);
    $value = trim(preg_replace('/\s+/', ' ', $value));
}

$boolarr2 = array_walk($arr2, 'array_watk_callback');

	$rest = array();
	foreach ($arr1 as $key1 => $val1) {
		echo '<tr>';
		echo '<td>',$val1,'</td>';
		echo '<td>';
		$i = 0;
		$col2 = '';
		foreach ($arr2 as $key2 => $val2) {
			$var2 = similar_text($val1, $val2, $percentage);
			if($percentage > 70 && $percentage > $i){
				$i = $percentage;
				$col2 = $val2;
			} 
		}
		if ($col2 === '') {
			$rest[] = $val1;
		}
		echo $col2;
		echo '</td></tr>';
	}

} // if ($_POST)

?>

</table>
<br>
<table class="ppp-table">
<?php
	foreach ($rest as $val3) {
		echo '<tr><td>',$val3,'</td></tr>';
	}
?>
</table>

<?php
// Открываем файл
$xls = PHPExcel_IOFactory::load(__DIR__.'/../csv/ass.xlsx');
// Устанавливаем индекс активного листа
$xls->setActiveSheetIndex(0);
// Получаем активный лист
$sheet = $xls->getActiveSheet();

?>

<pre>
<?php
$xcelArr = array();
// print_r($arr2);
echo '<table class="ppp-table">';
 
// Получили строки и обойдем их в цикле
$rowIterator = $sheet->getRowIterator();
foreach ($rowIterator as $r => $row) {
    // Получили ячейки текущей строки и обойдем их в цикле
    $cellIterator = $row->getCellIterator();
 
    //echo "<tr>";
         
    foreach ($cellIterator as $cell) {
    //    echo "<td>" . $cell->getCalculatedValue() . "</td>";
        $xcelArr[$r-1][] = $cell->getCalculatedValue();
    }
     
   // echo "</tr>";
}
echo "</table>";

$xcelArr2 = array();

foreach ($xcelArr as $keyq => $valueq) {
	$xcelArr2[md5($valueq[0])] = $valueq;
}

$xcelArr3 = array();
foreach ($rest as $keyw => $valuew) {
	if(!empty($xcelArr2[md5($valuew)])) $xcelArr3[] = $xcelArr2[md5($valuew)];
}

foreach ($xcelArr3 as $keye => &$valuee) {
	if (stripos($valuee[1], 'steam') !== false) {
		$valuee[0] = $valuee[0].' steam';
	}elseif(stripos($valuee[1], 'Другое') !== false){
		//$valuee[0] = $valuee[0].' steam';
	}elseif($valuee[1]){
		$valuee[0] = $valuee[0].' '.$valuee[1];	
	}else{
		$valuee[0] = $valuee[0].' steam';		
	}
}
?>
<br>
<table class="ppp-table">
<?php
	foreach ($xcelArr3 as $val4) {
		echo '<tr><td>',$val4[0],'</td></tr>';
	}
?>
</table>
<br>
<table class="ppp-table">
<?php
	foreach ($xcelArr3 as $val4) {
		echo '<tr><td>',$val4[0],'</td><td>',$val4[1],'</td><td>',$val4[2],'</td></tr>';
	}
?>
</table>
<?php
//print_r($xcelArr3);
print_r($_ERRORS);
?>
</pre>

<style>
/* .compare-form{
	text-align: center;
} */

.compare-form textarea{
	width: 40%;
}
</style>