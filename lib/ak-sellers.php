<?php

if (isset($_GET['del'])) {
	$id = _esc($_GET['del']);
	arrayDB("DELETE FROM ak_sellers WHERE id='$id'");
}

$_GET['limit'] = @$_GET['limit'] ? $_GET['limit'] : 50; // типо насройка лимита по умолчанию
$limit = aqs_pagination('ak_sellers');

$res = arrayDB("SELECT * from ak_sellers LIMIT $limit");

?>
<div class="container">
	<h2>sellers</h2>
<table class="table table-condensed" id="js-del-deligator">

 		<tr>
 			<th>del</th>
 			<th>#</th>
 			<th>id</th>
 			<th>username</th>
 			<th>info</th>
		</tr>
		<?php foreach ($res as $kr => $row):
			echo '<tr><td><a href="?action=ak-sellers&del=',$row['id'],'" class="btn btn-danger btn-xs js-del">×</a></td><td>',($kr+1),'</td>';
				echo '<td>',$row['id'],'</td>';
				echo '<td>',$row['username'],'</td>';
				echo '<td>',$row['info'],'</td>';
			echo '</tr>';
		endforeach; ?>

</table>
</div>

<script>
$('#js-del-deligator').on('click', '.js-del', function() {
	if (!confirm("Уверен?")) return false;
});
</script>