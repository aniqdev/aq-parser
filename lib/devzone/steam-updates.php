<?php



$res_de = arrayDB("SELECT updated_at,count(*) from steam_de group by day(updated_at) order by updated_at");
$res_en = arrayDB("SELECT updated_at,count(*) from steam_en group by day(updated_at) order by updated_at");
$res_fr = arrayDB("SELECT updated_at,count(*) from steam_fr group by day(updated_at) order by updated_at");
$res_es = arrayDB("SELECT updated_at,count(*) from steam_es group by day(updated_at) order by updated_at");
$res_it = arrayDB("SELECT updated_at,count(*) from steam_it group by day(updated_at) order by updated_at");
$res_ru = arrayDB("SELECT updated_at,count(*) from steam_ru group by day(updated_at) order by updated_at");



?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-2"><h3>de</h3><br><?php draw_table_with_sql_results($res_de, 1); ?></div>
		<div class="col-sm-2"><h3>en</h3><br><?php draw_table_with_sql_results($res_en, 1); ?></div>
		<div class="col-sm-2"><h3>fr</h3><br><?php draw_table_with_sql_results($res_fr, 1); ?></div>
		<div class="col-sm-2"><h3>es</h3><br><?php draw_table_with_sql_results($res_es, 1); ?></div>
		<div class="col-sm-2"><h3>it</h3><br><?php draw_table_with_sql_results($res_it, 1); ?></div>
		<div class="col-sm-2"><h3>ru</h3><br><?php draw_table_with_sql_results($res_ru, 1); ?></div>
	</div>
</div>