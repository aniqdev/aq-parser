<?php ini_get('safe_mode') or set_time_limit(1300);



$res = arrayDB("select title,ebay_id,shop_id as cdvet_id,vat from cdvet where vat in(5,16) order by vat");



?>
<div class="container">
	<br>
	<?php draw_table_with_sql_results($res); ?>
</div>
