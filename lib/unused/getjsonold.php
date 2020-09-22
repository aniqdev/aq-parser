<?php
echo 'ini_get: ',ini_get('max_execution_time');
echo "<br>";
echo 'ini_set: ',ini_set('max_execution_time', 300);
echo "<br>";
echo 'ini_get: ',ini_get('max_execution_time');
echo "<br>";
echo 'set_time_limit: ',set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.
echo "<br>";
echo 'ini_get: ',ini_get('max_execution_time');

if (isset($_POST['getjson'])) {
	// получаем массив игр из Базы Данных
	$reqs = arrayDB('SELECT * FROM games');

	$scandate = date('d-m-y H:i:s');
	$scan = md5(microtime().rand(0,9999));
	arrayDB("INSERT INTO scans VALUES(null,'$scandate','$scan')");

	// получаем результаты запросов в JSON
	$opts = array('http'=>array('header' => "User-Agent:MyAgent/1.0\r\n"));
	$context = stream_context_create($opts);

	$num = count($reqs);
	echo "Обработано запросов: ",$num;
	for ($j=0; $j < $num; $j++) {

			sleep(round(rand(1,50)/10, 3));


		$item1_id    = 0;
		$item1_name  = 'No results';
		$item1_price = 0;
		$item1_desc  = 'No results';
		$item2_id    = 0;
		$item2_name  = 'No results';
		$item2_price = 0;
		$item2_desc  = 'No results';
		$item3_id    = 0;
		$item3_name  = 'No results';
		$item3_price = 0;
		$item3_desc  = 'No results';
		$arrItem = array();
		
		$arrMatch = array();
		$request = urlencode($reqs[$j]['name']);
		$url = 'http://www.plati.ru/api/search.ashx?query='.$request.'&pagesize=500&response=json';
		$result = file_get_contents($url,false,$context);
		$result = json_decode($result);
		$iQ = $result->total;
		if ($iQ > 500) $iQ = 500;

		for($i = 0; $i < $iQ; $i++){
			$arrItem[$i] = array();

			$itemID = $result->items[$i]->id;
    		$arrItem[$i][0] = mysql_escape_string(trim(strip_tags($itemID)));
    
		    $name = $result->items[$i]->name;
		    $arrItem[$i][1] = mysql_escape_string(trim(strip_tags($name)));

		    $price = $result->items[$i]->price_usd;
		    $arrItem[$i][2] = mysql_escape_string(trim(strip_tags($price)));
		    
		    $description = $result->items[$i]->description;
		    $arrItem[$i][3] = mysql_escape_string(trim(strip_tags($description)));
		} // for i
		
		usort ($arrItem, 'sortN');

		$game_id         = $reqs[$j]['id'];
		if (isset($arrItem[0])) {
			$item1_id    = $arrItem[0][0];
		 	$item1_name  = $arrItem[0][1];
			$item1_price = $arrItem[0][2];
			$item1_desc  = $arrItem[0][3];
		}
		if (isset($arrItem[1])) {
			$item2_id    = $arrItem[1][0];
		 	$item2_name  = $arrItem[1][1];
			$item2_price = $arrItem[1][2];
			$item2_desc  = $arrItem[1][3];
		}
		if (isset($arrItem[2])) {
			$item3_id    = $arrItem[2][0];
		 	$item3_name  = $arrItem[2][1];
			$item3_price = $arrItem[2][2];
			$item3_desc  = $arrItem[2][3];
		}

		arrayDB("INSERT INTO items VALUES(null,'$game_id','$item1_id','$item1_name','$item1_price','$item1_desc',
										'$item2_id','$item2_name','$item2_price','$item2_desc',
										'$item3_id','$item3_name','$item3_price','$item3_desc','$scan',null)");

	} // for j
	echo "<p>Парсинг завершен, можете перейти на страницу Table для просмотра результатов</p>";
}else{
	?>
<form action="index.php?action=getjson" method="POST">
	<input type="submit" name="getjson" value="Спарсить">
</form>
<?php
} // if (isset($_POST['getjson']))


?>