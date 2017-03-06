<?php




?>
<style>
	table{
		border-collapse: collapse;
	}
	td,th{
	    border: 1px solid #777;
	    padding: 0 5px;
	}
</style>

<form action="index.php?action=sql" method="POST">
	<textarea name="sql" cols="50" rows="10" placeholder="Введите запрос SQL. Будьте осторожны, вы можете повредить базу данных"><?php if (isset($_POST['send'])) echo $_POST['sql'];?></textarea>
	<br><button name="send">Отправить</button>
	<?php if (isset($_POST['send'])) echo $_POST['sql'];?>
</form><br>

<?php

if (isset($_POST['send'])) {
	$query = $_POST['sql'];
	$res = arrayDB($query);
	if (is_array($res) && $res) {
		echo "<table><tr><th>№</th>";
		foreach ($res[0] as $key => $value) {
			echo "<th>",$key,"</th>";
		}
		echo "</tr>";
		foreach ($res as $kr => $row) {
			echo '<tr><td>',$kr+1,'</td>';
			foreach ($row as $kc => $col) {
				echo '<td>',$col,'</td>';
			}
			echo '</tr>';
		}
	}else{
		print_r($res);
	}

}


?>