<?php

if (isset($_GET['del'])) {
	$id = _esc($_GET['del']);
	arrayDB("DELETE FROM ak_keys WHERE id='$id'");
}

$_GET['limit'] = @$_GET['limit'] ? $_GET['limit'] : 50; // типо насройка лимита по умолчанию
$limit = aqs_pagination('ak_keys');

$res = arrayDB("SELECT * from ak_keys LIMIT $limit");

?>
<div class="container">
	<h2>Warehouse</h2>
<table class="table table-condensed" id="js-del-deligator">

 		<tr>
 			<th>del</th>
 			<th>#</th>
 			<th>id</th>
 			<th>key</th>
 			<th>ebay_id</th>
 			<th>game_name</th>
 			<th>seller</th>
 			<th>price</th>
 			<th>status</th>
		</tr>
		<?php foreach ($res as $kr => $row):
			echo '<tr title="',$row['updated_at'],'"><td><a href="?action=ak-keys&del=',$row['id'],'" class="btn btn-danger btn-xs js-del">×</a></td><td>',($kr+1),'</td>';
				echo '<td>',$row['id'],'</td>';
				echo '<td>',$row['steam_key'],'</td>';
				echo '<td>',$row['ebay_id'],'</td>';
				echo '<td>',$row['game_name'],'</td>';
				echo '<td>',$row['seller'],'</td>';
				echo '<td>',$row['price'],'</td>';
				echo '<td>',$row['status'],'</td>';
			echo '</tr>';
		endforeach; ?>

</table>
</div>

<script>
$('#js-del-deligator').on('click', '.js-del', function() {
	if (!confirm("Уверен?")) return false;
});
</script>