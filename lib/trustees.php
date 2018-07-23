<?php

if (isset($_GET['del'])) {
	$plati_id = _esc($_GET['del']);
	arrayDB("DELETE FROM gig_trustee_items
			WHERE plati_id='$plati_id'");
}

$res = arrayDB("SELECT plati_id,name,counter from gig_trustee_items");

if(defined('DEV_MODE')) sa($res);

?>
<div class="container">
<table class="table table-condensed" id="js-del-deligator">

 		<tr><th>del</th><th>№</th>
		<?php foreach ($res[0] as $key => $value):
			echo '<th>',$key,'</th>';
		endforeach; ?>
		</tr>
		<?php foreach ($res as $kr => $row):
			echo '<tr><td><a href="?action=trustees&del=',$row['plati_id'],'" class="btn btn-danger btn-xs js-del">×</a></td><td>',($kr+1),'</td>';
				echo '<td><a target="_blank" href="https://www.plati.ru/itm/',$row['plati_id'],'">',$row['plati_id'],'</a></td>';
				echo '<td>',$row['name'],'</td>';
				echo '<td>',$row['counter'],'</td>';
			echo '</tr>';
		endforeach; ?>

</table>
</div>

<script>
$('#js-del-deligator').on('click', '.js-del', function() {
	if (!confirm("Уверен?")) return false;
});
</script>