<br>
<div class="ppp-form-group">
	<form method="POST" class="listForm">
		<input type="text" name="item" placeholder="Ведите id товара" style="width: 300px;">
		<input type="submit" name="addItem" value="Добавить строку">
	</form>
</div>
<div class="ppp-block">
<ol class="ppp-ol">
<?php


if (isset($_GET['delStr'])) {
	$delId = $_GET['delStr'];
	arrayDB("DELETE FROM blacklist WHERE id='$delId'");
	header("Location: http://".$_SERVER['HTTP_HOST'].'/index.php?action=blackl');
}
//============================================== 

	if (isset($_POST['addItem'])) {
		$itemName = mysql_escape_string(trim(strip_tags((int)$_POST['item'])));
		echo '<i>Вы добавили: "',$itemName,'" в конец списка</i>';
		arrayDB("INSERT INTO blacklist VALUES (NULL, '$itemName', 'item')");
	}

	$res = arrayDB("SELECT * FROM blacklist WHERE category='item'");

	// echo "<pre>";
	// print_r($res);
	// echo "</pre>";

	foreach ($res as $key => $value) {
		echo '<li>
				<a href="index.php?action=blackl&delStr=',$value['id'],'" class="delbutton">×</a>
				<span class="listitem" data-id="',$value['id'],'">',$value['item_id'],'</span>
			  </li>';
	}





?>
</ol>
</div>
<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
<br>
<div class="ppp-form-group">
	<form method="POST" class="listForm">
		<input type="text" name="seller" placeholder="Ведите id продавца" style="width: 300px;">
		<input type="submit" name="addSeller" value="Добавить строку">
	</form>
</div>
<div class="ppp-block">
<ol class="ppp-ol">
<?php


if (isset($_GET['delSell'])) {
	$delId = $_GET['delSell'];
	arrayDB("DELETE FROM blacklist WHERE id='$delId'");
	header("Location: http://".$_SERVER['HTTP_HOST'].'/index.php?action=blackl');
}
//============================================== 

	if (isset($_POST['addSeller'])) {
		$itemName = mysql_escape_string(trim(strip_tags((int)$_POST['seller'])));
		echo '<i>Вы добавили: "',$itemName,'" в конец списка</i>';
		arrayDB("INSERT INTO blacklist VALUES (NULL, '$itemName', 'seller')");
	}

	$res = arrayDB("SELECT * FROM blacklist WHERE category='seller'");

	// echo "<pre>";
	// print_r($res);
	// echo "</pre>";

	foreach ($res as $key => $value) {
		echo '<li>
				<a href="index.php?action=blackl&delStr=',$value['id'],'" class="delbutton">×</a>
				<span class="listitem" data-id="',$value['id'],'">',$value['item_id'],'</span>
			  </li>';
	}





?>
</ol>
</div>
