<?php

ini_get('safe_mode') or set_time_limit(180); // Указываем скрипту, чтобы не обрывал связь.

$ebay_games = arrayDB("SELECT * FROM ebay_games");

$steam_list = arrayDB("SELECT title, link FROM steam");



?>

<pre>
<?php

// print_r($ebay_games);
// print_r($steam_list);

?>
</pre>
<table class="ppp-table-collapse">
<?php

	$rest = array(); $j = 0;
	foreach ($ebay_games as $key1 => $val1) {
		if(stripos($val1['title'], 'steam') === false) continue;
		echo '<tr>';
		echo '<td>',(++$j),'</td>';
		echo '<td>',$val1['item_id'],'</td>';
		echo '<td><a href="','http://www.ebay.com/itm/',$val1['item_id'],'" target="_blank">','http://www.ebay.com/itm/',$val1['item_id'],'</a></td>';
		echo '<td>',$val1['title_clean'],'</td>';
		echo '<td>';
		$i = 0;
		$col2 = '';
		foreach ($steam_list as $key2 => $val2) {
			$var2 = similar_text(strtolower($val1['title_clean']), strtolower($val2['title']), $percentage);
			if($percentage > 75 && $percentage > $i){
				$i = $percentage;
				$col2 = $val2;
			} 
		}
		if ($col2 === '') {
			$rest[] = $val1['title_clean'];
		}
		echo @$col2['title'];
		echo '</td><td><a href="',@$col2['link'],'" target="_blank">',@$col2['link'],'</a></td></tr>';
	}

?>
</table>
<pre>
<?php

print_r($rest);
// print_r($ebay_games);
// print_r($steam_list);

?>