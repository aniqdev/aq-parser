<pre>
<?php

	die;

	$db = new DB;

	$count = arrayDB("SELECT count(*) as count FROM steam");
	$count = $count[0]['count'];

	var_dump($count);

	//$results = $db->get_results("SELECT * FROM steam LIMIT 1")[0];
	$fields = $db->list_fields("SELECT * FROM steam LIMIT 1");
	// $db->display($fields);

	$headers = [];
	foreach ($fields as $key => $field) {
		$headers[] = $field->name;
	}
	$db->display($headers);

	$i = 0;
	$fp = fopen(__DIR__.'/../Files/steam_gnom.csv', 'w');
	if(!$fp) die('Не удалось получить доступ к файлу');

	if (true) {

		$header_line = "id;Название игры;Ссылка;Жанр;Цена;Год;Релиз;Язык;Описание;ОС;Системные требования;Рейтинг;Обзоры;\r\n";
		//$header_line = iconv('UTF-8', 'Windows-1251', $header_line);
		$header_line = str_replace(';', ',', $header_line);
		fwrite($fp, $header_line);
	}else{
		fputcsv($fp, $headers, ',');
	}


	while ($i < $count) {

		$offers100 = arrayDB("SELECT * FROM steam LIMIT 100 OFFSET ".$i);
		$i = $i+100;

		foreach ($offers100 as $key => $item) {
			// foreach ($item as &$cell) {
			// 	$cell = @iconv('UTF-8', 'Windows-1251//IGNORE', $cell);
			// 	if($cell === false) continue 2;
			// }
			fputcsv($fp, $item, ',');
		}

	}
	fclose($fp);

?>
</pre>