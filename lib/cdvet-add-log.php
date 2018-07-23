<?php


$res = arrayDB("SELECT * FROM cdvet_add_log ORDER BY id DESC LIMIT 200");


?>
<table class="orders-table">
<?php
foreach ($res as $val) {
	$data_json = json_decode($val['data_json'], true);
	$ebay_resp_json = json_decode($val['ebay_resp_json'], true);
	$errors_json = json_decode($val['errors_json'], true);
	echo '<tr>';
		// echo '<td>';
		// 	sa($data_json);
		// echo '</td>';
		echo '<td>';
			sa($ebay_resp_json);
		echo '</td>';
		echo '<td>';
			sa($errors_json);
		echo '</td>';
	echo '</tr>';
}

?>
</table>