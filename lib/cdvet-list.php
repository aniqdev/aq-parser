<h2>cdVet ebay list</h2>
<?php

if (isset($_GET['del'])) {
	$id = (int)$_GET['del'];
	arrayDB("DELETE FROM cdvet WHERE id='$id'");
}

$cd_arr = arrayDB("SELECT * FROM cdvet");

?>
<div class="container">
<table class="ppp-table-collapse">
	<tr>
		<th>-</th>
		<th>#</th>
		<th>title</th>
		<th>ebay_id</th>
		<th>shop_id</th>
		<th>cat_id</th>
		<th>last update</th>
	</tr>
<?php

foreach ($cd_arr as $k => $item) {
	echo '<tr>';
	echo '<td><a href="?action=cdvet-list&del=',$item['id'],'" title="remove record">×</a></td>';
	echo '<td>',($k+1),'</td>';
	echo '<td>',$item['title'],'</td>';
	echo '<td>',$item['ebay_id'],'</td>';
	echo '<td>',$item['shop_id'],'</td>';
	echo '<td>',$item['cat_id'],'</td>';
	echo '<td>',$item['updated_at'],'</td>';
	echo '</tr>';
}

?>
</table>
</div>