<?php

if (isset($_GET['del'])) {
	$id = _esc($_GET['del']);
	arrayDB("DELETE FROM ebay_black_white_list WHERE id='$id'");
}

$_GET['limit'] = @$_GET['limit'] ? $_GET['limit'] : 500; // типо насройка лимита по умолчанию
$limit = aqs_pagination('ebay_black_white_list');

$res = arrayDB("SELECT * from ebay_black_white_list LIMIT $limit");

?>
<div class="container">
	<h2>Ebay white-black list</h2>
<table class="table table-condensed" id="js-del-deligator">

 		<tr><th>del</th><th>№</th>
		<?php foreach ($res[0] as $key => $value):
			echo '<th>',$key,'</th>';
		endforeach; ?>
		</tr>
		<?php foreach ($res as $kr => $row):
			echo '<tr><td><a href="?action=white-list&del=',$row['id'],'" class="btn btn-danger btn-xs js-del">×</a></td><td>',($kr+1+@$_GET['offset']),'</td>';
				echo '<td>',$row['id'],'</td>';
				echo '<td>',$row['game_id'],'</td>';
				echo '<td>',$row['ebay_id'],'</td>';
				echo '<td>',$row['title'],'</td>';
				echo '<td>',$row['category'],'</td>';
			echo '</tr>';
		endforeach; ?>

</table>
</div>

<script>
$('#js-del-deligator').on('click', '.js-del', function() {
	if (!confirm("Уверен?")) return false;
});
</script>