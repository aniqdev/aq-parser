<?php
ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.


if (isset($_POST['action']) && $_POST['action'] === 'edit') {
	$table = _esc($_POST['table']);
	$column = _esc($_POST['column']);
	$value = _esc($_POST['value']);
	$id = _esc($_POST['id']);
	$res = arrayDB("UPDATE `$table` SET `$column` = '$value' WHERE id = '$id'");
	echo json_encode(['status' => $res?'success':'error', 'report' => 'operation done', '$res' => $res, 'ERRORS' => $_ERRORS]);
	return;
}

$table_name = get_table_name(@$_POST['sql']);
?>
<style>
	table{
		border-collapse: collapse;
	}
	td,th{
	    border: 1px solid #777;
	    padding: 0 5px;
	}
	td{
		white-space: pre;
	}
</style>

<form action="index.php?action=sql" method="POST">
	<textarea id="js_sql" autofocus="autofocus" name="sql" cols="50" rows="10" placeholder="Введите запрос SQL. Будьте осторожны, вы можете повредить базу данных" style="width:100%;"><?php if (@$_POST['sql']) echo $_POST['sql'];?></textarea>
	<!-- <input autofocus="autofocus" name="sql" cols="50" rows="10" placeholder="Введите запрос SQL. Будьте осторожны, вы можете повредить базу данных" value="<?php if (@$_POST['sql']) echo $_POST['sql'];?>"> -->
	<br>
	<button name="send">Send</button>
	<button name="edit">Edit</button>
	<?php if (@$_POST['sql']) echo $_POST['sql'];?>
</form><br>
<script>
	  function sql_page_getCookie(name){
	    var re = new RegExp(name + "=([^;]+)");
	    var value = re.exec(document.cookie);
	    return (value != null) ? decodeURIComponent(value[1]) : null;
	  }

	  var today = new Date();
	  var expiry = new Date(today.getTime() + 30 * 24 * 3600 * 1000); // plus 30 days
	  function sql_page_setCookie(name, value){
	    document.cookie=name + "=" + encodeURIComponent(value) + "; path=/; expires=" + expiry.toGMTString();
	  }

	var sql_input = document.getElementById('js_sql');

	if(!sql_input.value && (sql_cookie = sql_page_getCookie('sql_input'))) sql_input.value = sql_cookie;

	sql_input.onkeyup = function(){
		sql_page_setCookie('sql_input', this.value);
	};
</script>
<h4>table: <?= $table_name;?></h4>
<input type="hidden" id="table_name" value="<?= $table_name;?>">
<?php

if (isset($_POST['edit']) && $table_name) {
	$res = arrayDB($_POST['sql']);
	if (is_array($res) && $res && isset($res[0]['id'])) {
		echo "<table><tr><th>№</th>";
		foreach ($res[0] as $key => $value) {
			echo "<th>",$key,"</th>";
		}
		echo "</tr>";
		foreach ($res as $kr => $row) {
			echo '<tr><td>',$kr+1,'</td>';
			foreach ($row as $kc => $col) {
				echo '<td contenteditable  clmn="',$kc,'" rid="',$row['id'],'">',htmlentities($col),'</td>';
			}
			echo '</tr>';
			if($kr > 30) break;
		}
	}else{
		sa($res);
	}
}elseif (isset($_POST['send']) || isset($_POST['edit'])) {
	if (!$_POST['sql']) {
		$res = 'Empty query!';
	}else{
		$res = arrayDB($_POST['sql']);
	}
	
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

<div id="report_screen" title="report screen">

</div>

<script>
function galert(type,text) {
	return ('<div class="alert alert-'+type+' alert-dismissible height-anim" role="alert">'+
  '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+text+'</div>');
}
$('[contenteditable]').on('blur', function() {
	// console.log();
	var table = $('#table_name').val();
	var column = $(this).attr('clmn');
	var value = $(this).text();
	var id = $(this).attr('rid');
	$.post('/ajax.php?action=sql',
		{action:'edit',table: table, column: column, value: value, id:id},
		function(data) {
			if (data.status && data.status === 'success') {
				$('#report_screen').append(galert('success','<b>Success!</b> '+data.report));
			}else{
				$('#report_screen').append(galert('danger','<b>Error!</b> '+data.report));
			}
		},'json');
})
</script>