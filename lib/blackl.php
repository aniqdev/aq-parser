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
		header('Location: ?action=blackl');
	}

	if (isset($_POST['addItem'])) {
		$itemName = _esc(trim(strip_tags((int)$_POST['item'])));
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
<!--|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
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
<pre>
<?php

?>
</pre>
<div class="ppp-block" id="addons-table">
<table class="ppp-table-collapse platitable">
<thead>
	<tr>
		<th>#</th>
		<th class="sort" data-sort="col1">parser name</th>
		<th class="sort" data-sort="col2">plati.ru name</th>
	</tr>
</thead>
<tbody class="list">
<?php
$res = arrayDB("SELECT * FROM blacklist WHERE category LIKE 'game_id=%'");
$games = arrayDB("SELECT * FROM games");

$garr = [];
foreach ($games as $k => $game) {
	$garr[$game['id']] = $game['name'];
}

foreach ($res as $key => $value) {
	$re_arr = [];
	$one = parse_str($value['category'], $re_arr);
	echo '<tr>
		<td><a href="index.php?action=blackl&delStr=',$value['id'],'" class="delbutton">×</a></td>
		<td class="col1">',$garr[$re_arr['game_id']],'</td>
		<td class="col2">',$re_arr['game_name'],'</td>
	  </tr>';
}

?>
</tbody>
</table>
</div>
<script>
	var options = {
	  valueNames: [ 'col1', 'col2' ],
	  page: 2000
	};

	var userList = new List('addons-table', options);



</script>