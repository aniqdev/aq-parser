<pre>
<?php


require_once ('PHPExcel.php');
//setlocale(LC_ALL, 'ru_RU.UTF-8');

?>
</pre>

<form action="index.php?action=csv" method="POST">
	<button name="all">Сгенерировать Exel полной базы</button>
	<button name="packs">Сгенерировать Exel паков</button>
	<button name="2015">Сгенерировать Exel 2015</button>
</form><br>

<pre>
<?php
	//var_dump($_POST);
	if (isset($_POST['all'])) {

		$list = arrayDB("SELECT * FROM steam");

		    $objPHPExcel = new PHPExcel();
		    $objPHPExcel->getActiveSheet()->fromArray($list, null, 'A1');
		    // Название страницы в Excel, по умолчанию Spiele
		    $objPHPExcel->getActiveSheet()->setTitle('Spiele');
		    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		    // Папка для сохранения файлов
		    $dir = 'Files';
		    // Префикс для файлов
		    $file_prefix = 'steam_game';

		    $objWriter->save($dir . '/' . $file_prefix . '.xlsx');

		echo '<a href="Files/steam_game.xlsx">Скачать Exel</a><br>';
	}
//==================================================================================
//==================================================================================
	if (isset($_POST['packs'])) {

		$list = arrayDB("SELECT title,price,`desc`,link,`release` FROM steam WHERE `year`=101");

		    $objPHPExcel = new PHPExcel();
		    $objPHPExcel->getActiveSheet()->fromArray($list, null, 'A1');
		    $objPHPExcel->getActiveSheet()->setTitle('Spiele');
		    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		    // Папка для сохранения файлов
		    $dir = 'Files';
		    // Префикс для файлов
		    $file_prefix = 'steam_game';

		    $objWriter->save($dir . '/' . $file_prefix . '.xlsx');

		echo '<a href="Files/steam_game.xlsx">Скачать Exel</a><br>';
	}
//==================================================================================
//==================================================================================
	if (isset($_POST['2015'])) {

		$list = arrayDB("SELECT title,link,price,rating from slist where mark=24392579 AND year=2015");

		    $objPHPExcel = new PHPExcel();
		    $objPHPExcel->getActiveSheet()->fromArray($list, null, 'A1');
		    $objPHPExcel->getActiveSheet()->setTitle('Spiele');
		    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		    // Папка для сохранения файлов
		    $dir = 'Files';
		    // Префикс для файлов
		    $file_prefix = 'steam_game2015';

		    $objWriter->save($dir . '/' . $file_prefix . '.xlsx');

		echo '<a href="Files/steam_game.xlsx">Скачать Exel</a><br>';
	}

?>
</pre>