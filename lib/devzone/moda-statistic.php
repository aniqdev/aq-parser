<?php ini_get('safe_mode') or set_time_limit(1300);




$limit = 500;

$res1 = arrayDB("SELECT tt1.moda_id,tt2.meta_value as HitCount,tt1.meta_value as QuantitySold, ROUND(tt2.meta_value / tt1.meta_value, 2) as `Hit/Sold`
				from
				(select * from moda_list_meta WHERE meta_key = 'QuantitySold' AND meta_value > 3000 limit $limit) as tt1
				join
				(select * from moda_list_meta WHERE meta_key = 'HitCount') as tt2
				on tt1.moda_id = tt2.moda_id
				order by CAST(HitCount AS unsigned) desc");


$res2 = arrayDB("SELECT tt1.moda_id,tt2.meta_value as HitCount,tt1.meta_value as QuantitySold, ROUND(tt2.meta_value / tt1.meta_value, 2) as `Hit/Sold`
				from
				(select * from moda_list_meta WHERE meta_key = 'QuantitySold') as tt1
				join
				(select * from moda_list_meta WHERE meta_key = 'HitCount' AND meta_value > 50000 limit $limit) as tt2
				on tt1.moda_id = tt2.moda_id
				order by CAST(QuantitySold AS unsigned) desc");


?>

<div class="container">
	<h2>Moda statistic</h2>
	<div class="row">
		<div class="col-sm-6">
			<?php draw_table_with_sql_results($res1, 1); ?>
		</div>
		<div class="col-sm-6">
			<?php draw_table_with_sql_results($res2, 1); ?>
		</div>
	</div>
</div>

<?php









