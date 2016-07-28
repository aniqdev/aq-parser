<?php
	$scans = arrayDB('SELECT DISTINCT scan FROM ebay_results ORDER BY scan DESC');
		// echo "<br><pre>\n";
		// print_r($scans);
		// echo '</pre>';
?>
<form method="POST">
	<select name="scan">
	<?php
foreach ($scans as $key => $value) {
	$d = date('d.m.Y H:i', $value['scan']*60);
	echo '<option value="',$value['scan'],'">',count($scans)-$key,' От ',$d,'</option>';
}
	?>
	</select>
	<button type="submit">go</button>
</form>
<?php

if (isset($_POST['scan'])) {
	include(__DIR__.'/PHPExcel.php');
	$scan = $_POST['scan'];
	$list = arrayDB("SELECT ebay_list.name,  ebay_results.price1, ebay_results.price2,
							ebay_results.price3, ebay_results.price4, ebay_results.price5
						FROM ebay_list INNER JOIN ebay_results ON ebay_list.id=ebay_results.game_id
						WHERE ebay_results.scan='$scan'");

	for ($i=0; $i < count($list); $i++) { 
		$list[$i]['link'] = "http://www.ebay.de/sch/PC-Videospiele-/1249/i.html?_sop=2&LH_BIN=1&_from=R40&_nkw=".$list[$i]['name'];
	}

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getActiveSheet()->fromArray($list, null, 'A1');
    // Название страницы в Excel, по умолчанию Spiele
    $objPHPExcel->getActiveSheet()->setTitle('Spiele');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

    // Папка для сохранения файлов
    $dir = 'Files';
    // Префикс для файлов
    $file_prefix = 'ebay_list';

    $objWriter->save($dir . '/' . $file_prefix . '.xlsx');

	echo '<a href="Files/ebay_list.xlsx">Скачать Exel</a><br>';
}

?>