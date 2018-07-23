<?php
ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.


// для js зщые запроса
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
	$table = _esc($_POST['table']);
	$column = _esc($_POST['column']);
	$value = _esc($_POST['value']);
	$id = _esc($_POST['id']);
	$res = arrayDB("UPDATE `$table` SET `$column` = '$value' WHERE id = '$id'");
	echo json_encode(['status' => $res?'success':'error', 'report' => 'operation done', '$res' => $res, 'ERRORS' => $_ERRORS]);
	return;
}

$table_name = 'cdvet_cats';
?>
<style>
	table{
		border-collapse: collapse;
	}
	td,th{
	    border: 1px solid #777;
	    padding: 0 5px;
	}
	td[contenteditable]{
		white-space: pre;
		background: #232323;
	}
</style>

<h2>cdVet catigories editor</h2>
<h4>table: <?= $table_name;?></h4>
<input type="hidden" id="table_name" value="<?= $table_name;?>">
<?php
	$res = arrayDB("SELECT * FROM cdvet_cats");
	if (is_array($res) && $res && isset($res[0]['id'])) {
		echo "<table style='margin:auto'><tr><th>№</th>";
		foreach ($res[0] as $key => $value) {
			echo "<th>",$key,"</th>";
		}
		echo "</tr>";
		foreach ($res as $kr => $row) {
			echo '<tr><td>',$kr+1,'</td>';
			foreach ($row as $kc => $col) {
				if($kc === 'id' || $kc === 'section'){
					echo '<td>',htmlentities($col),'</td>';
					continue;
				} 
				echo '<td contenteditable  clmn="',$kc,'" rid="',$row['id'],'">',htmlentities($col),'</td>';
			}
			echo '</tr>';
			// if($kr > 10) break; // ограничение количества выводимых строк
		}
	}else{
		sa($res);
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
	$.post('/ajax.php'+location.search,
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