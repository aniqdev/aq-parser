<?php


$feed_new = csvToArr('https://www.cdvet.de/backend/export/index/productckeck?feedID=47&hash=a4dc5afc43b82eefd412334d8ed3239e', ['max_str' => 0,'encoding' => 'windows-1250']);

// $feed_new = csvToArr('http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129', ['max_str' => 0,'encoding' => 'windows-1250']);

// draw_table_with_sql_results($feed_new, $first_row_thead = true);

$feed_new = array_column($feed_new, null, 0);

sa(count($feed_new));
sa($feed_new);

?>