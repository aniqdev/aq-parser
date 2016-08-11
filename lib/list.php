<br>
<div class="ppp-form-group">
	<form method="POST" class="listForm">
		<input type="text" name="item" placeholder="Ведите наименование товара" style="width: 300px;">
		<input type="submit" name="addItem" value="Добавить строку">
	</form>
	или
	<form method="POST" class="listForm" enctype="multipart/form-data">
		<input type="file" name="myFile">
		<input type="submit" name="addFile" value="Добавить таблицу">
	</form>
	<form method="POST" class="ppp-right delall">
		<input type="password" name="pswd" class="delall" maxlength="6" size="6">
		<input type="submit" name="delAll" class="delall" value="Очистить список!">
	</form>
</div>
<div class="ppp-block">
<ol class="ppp-ol" id="have-data-table" data-table="games">
<?php


if (isset($_GET['delStr'])) {
	$delId = (int)$_GET['delStr'];
	arrayDB("DELETE FROM games WHERE id='$delId'");
}
if (isset($_POST['delAll']) && $_POST['pswd'] === 'koeln') {
	$multiQuery = "DELETE FROM games";
	arrayDB($multiQuery);
	sleep(3);
	$multiQuery = "DELETE FROM scans";
	arrayDB($multiQuery);
	sleep(1);
}
//==============================================  files start
function moveFile(){
	$uploads_dir = 'csv';
	if ($_FILES["myFile"]["error"] == UPLOAD_ERR_OK) {
		$tmp_name = $_FILES["myFile"]["tmp_name"];
		$name = $_FILES["myFile"]["name"];
		$extention = explode('.', $name);
		$name = $extention[0];
		$extention = strtolower(array_pop($extention));
		$md5name = md5(microtime().rand(0,9999));
		$newName = $md5name . '.' . $extention;
		move_uploaded_file($tmp_name, "$uploads_dir/$newName");

		//получаем массив игр из CSV файла
		$reqs = array();
		if (($handle = fopen("$uploads_dir/$newName", "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 100, ",")) !== FALSE) {
				$num = count($data);
				for ($c=0; $c < $num; $c++) {
					array_push($reqs, $data[$c]);
				}
			}
			fclose($handle);

			// echo "<pre>";
			// print_r($reqs);
			// echo "</pre>";
		} else return('Ошибка при чтении CSV');
		$sql = '';
		foreach ($reqs as $key => $value) {
			$value = _esc(trim(strip_tags($value)));
			$sql .=  "INSERT INTO games (name) VALUES ('$value');";
		}
		// 	echo "<pre>";
		// 	var_dump($sql);
		// 	echo "</pre>";
		arrayDB($sql,true);
		return ("Файлы добавлены!");
	} else return ("Ошибка при загрузке файла!");
}

if ($_FILES) {
	moveFile();
	sleep(1);
}
//============================================= / files end

	if (isset($_POST['addItem'])) {
		$itemName = _esc(trim(strip_tags($_POST['item'])));
		echo '<i>Вы добавили: "',$itemName,'" в конец списка</i>';
		arrayDB("INSERT INTO games (name) VALUES ('$itemName')");
	}

	$res = arrayDB('SELECT * FROM games');

	// echo "<pre>";
	// print_r($res);
	// echo "</pre>";

	foreach ($res as $key => $value) {
		echo '<li>
				<a href="index.php?action=list&delStr=',$value['id'],'" class="delbutton">×</a>
				<input type="checkbox" class="chedit" id="chedit',$value['id'],'">
				<label for="chedit',$value['id'],'" class="checkEdit"></label>
				<span class="listitem" data-id="',$value['id'],'">',$value['name'],'</span>
			  </li>';
	}





?>
</ol>
</div>
