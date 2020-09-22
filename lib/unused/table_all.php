<div id="platitable" class="platitable">
<div class="ppp">
	<form method="POST">
		<input class="search" placeholder="Search">&nbsp;<i>Количество столбцов: </i>
		<a href="/index.php?action=table_all&lim=8">8</a>
		<a href="/index.php?action=table_all&lim=10">10</a>
		<a href="/index.php?action=table_all&lim=12">12</a>
		<button name="excel">Excel</button>
		<?php if (isset($_POST['excel'])) : ?>
		<a href="Files/platiru_table.xlsx">Скачать Exel</a>
		<?php endif;?>
	</form>
</div>
<br>
<div class="ppp">
<table class="ppp-table-collapse">
<?php	

	isset($_GET['lim']) ? $lim = (int)$_GET['lim'] : $lim = 5;

	$scans = arrayDB("SELECT `date`,hash FROM scans ORDER BY id DESC LIMIT $lim");
	$games = arrayDB("SELECT id,name FROM games");
	$res = arrayDB("SELECT game_id, item1_price, scan FROM items");

	$all = array();
	foreach ($res as $key => $val) {
		$id = $val['game_id'];
		$scan = $val['scan'];
		$price = $val['item1_price'];
		$all[$id][$scan] = $price;
	}

	// echo "<pre>";
	// print_r($all);
	// echo "</pre>";

echo "<thead><tr><th class='sort' data-sort='row1'>Наименование</th>";

foreach ($scans as $sc) {
	echo "<th>",$sc['date'],"</th>";
}
echo "</tr></thead>";
echo '<tbody class="list">';
foreach ($games as $g) {
	echo "<tr><td class='row1'>",$g['name'],"</td>";
	$toExcel[$g['id']]['name'] = $g['name'];
	foreach ($scans as $s) {
		echo "<td>";
		isset($all[$g['id']][$s['hash']]) ? $p = $all[$g['id']][$s['hash']] : $p = 'NaN';
		$toExcel[$g['id']][$s['hash']] = $p;
		echo $p;
		echo "</td>";
	}
	echo "</tr>";
}

	// echo "<pre>";
	// print_r($toExcel);
	// echo "</pre>";
// =================================== Excel ===============================
	if (isset($_POST['excel'])) {
		include('PHPExcel.php');

		    $objPHPExcel = new PHPExcel();
		    $objPHPExcel->getActiveSheet()->fromArray($toExcel, null, 'A1');
		    $objPHPExcel->getActiveSheet()->setTitle('Table');
		    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		    // Папка для сохранения файлов
		    $dir = 'Files';
		    // Префикс для файлов
		    $file_prefix = 'platiru_table';

		    $objWriter->save($dir . '/' . $file_prefix . '.xlsx');
	}

?>
	</tbody>
</table>
</div> <!-- ppp-block -->
</div> <!-- platitable -->
<script>
	var options = {
	  valueNames: ['row1'],
	  page: 1500
	};
	var userList = new List('platitable', options);
</script>