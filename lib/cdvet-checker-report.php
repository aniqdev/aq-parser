<?php

$res = arrayDB("SELECT *,DATE_FORMAT(created_at, '%d-%m-%Y') as datef FROM cdvet_checker_log ORDER BY id DESC LIMIT 1000");


?>
<div class="container">
	<h3>cdvet checker report</h3>
	<table class="table">
		<tr>
			<th>#</th>
			<th>ebay_id</th>
			<th>shop_id</th>
			<th>ebay_title</th>
			<th>message</th>
			<th>date</th>
		</tr>
	<?php
foreach ($res as $key => $value) {
	echo '<tr>';
	echo '<td>',($key+1),'</td>';
	echo '<td>',$value['ebay_id'],'</td>';
	echo '<td>',$value['shop_id'],'</td>';
	echo '<td>',$value['ebay_title'],'</td>';
	echo '<td>',$value['msg'],'</td>';
	echo '<td>',$value['datef'],'</td>';
	echo '</tr>';
}
	?>
	</table>
</div>