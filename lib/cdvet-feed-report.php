<?php

$res = arrayDB("SELECT *,DATE_FORMAT(created_at, '%d-%m-%Y') as datef FROM cdvet_feed_log ORDER BY id DESC LIMIT 1000");


?>
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css?t=08-11-17_11:16:25">
<div class="container">
	<h2>cdvet feed report</h2>
	<p>(Changes in the new feed compared to the previous one)</p>
	<table class="table">
		<tr>
			<th>#</th>
			<th>action</th>
			<th>ids</th>
			<th>date</th>
		</tr>
	<?php
foreach ($res as $key => $value) {
	echo '<tr>';
	echo '<td>',($key+1),'</td>';
	echo '<td>',$value['dir'],'</td>';
	echo '<td>',$value['ids'],'</td>';
	echo '<td>',$value['datef'],'</td>';
	echo '</tr>';
}
	?>
	</table>
</div>