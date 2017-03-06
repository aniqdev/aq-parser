<pre><?php

$ebay_games = arrayDB("SELECT * FROM ebay_games");



//print_r($ebay_games);


?></pre>

<table class="ppp-table-collapse" style="font-size: .9em">
<?php

	$words_to_del = array(
		'-Region free-','Region free','Multilanguage',
		'Multilang','Regfree','ENGLISH','regfr','Regionfree');

foreach ($ebay_games as $k => $game) {
	$new_title = str_ireplace(['(PC)'], 'PC spiel', $game['title']);
	$new_title = str_ireplace($words_to_del, ' ', $new_title);
	$new_title = $new_title.' Download Digital Link DE/EU/USA Key Code Gift';
	$new_title = trim(preg_replace('/\s+/', ' ', $new_title));
	$new_title = str_ireplace('Â', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Gift', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Digital', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Code', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' spiel', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Download', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace('DE/', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Link', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Key', '', $new_title);
	echo   '<tr>
				<td title="',$game['item_id'],'"><a href="http://www.ebay.de/itm/',$game['item_id'],'" target="_blank">',$k+1,'</a></td>
				<td>',$game['title'],'</td>
				<td>',$new_title,'</td>
				<td>',strlen($new_title),'</td>
			</tr>';
}

?>
</table>