<?php



$res = arrayDB("select * from games where extra_field = 'old_description'");

draw_table_with_sql_results($res);

?>