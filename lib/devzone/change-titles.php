<pre><?php
ini_get('safe_mode') or set_time_limit(1300); // Указываем скрипту, чтобы не обрывал связь.

$ebay_games = arrayDB("SELECT * FROM ebay_games");



//print_r($ebay_games);


?></pre>

<table class="ppp-table-collapse" style="font-size: .9em">
<?php

	$words_to_del = array(
		'-Region free-','Region free','Multilanguage',
		'Multilang','Regfree','ENGLISH','regfr','Regionfree');

$ebayObj = new Ebay_shopping2();
$fales = [];
foreach ($ebay_games as $k => $game) {

	$skip = false;
	if(stripos($game['title'], 'EU/USA') !== false) $skip = true;
	if(stripos($game['title'], ' ISK') !== false) $skip = true;

	$new_title = str_ireplace(['(PC)'], 'PC spiel', $game['title']);
	$new_title = str_ireplace($words_to_del, ' ', $new_title);
	$new_title = $new_title.' Download Digital Link DE/EU/USA Key Code Gift Game';
	$new_title = trim(preg_replace('/\s+/', ' ', $new_title));
	$new_title = str_ireplace('Â', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Game', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Gift', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Digital', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Code', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' spiel', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Download', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace('DE/', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Link', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Key', '', $new_title);

	$res['Ack'] = 'skipped';
	if(!$skip) $res = $ebayObj->updateItemTitle($game['item_id'], $new_title);

	echo   '<tr>
				<td title="',$game['item_id'],'"><a href="http://www.ebay.de/itm/',$game['item_id'],'" target="_blank">',$k+1,'</a></td>
				<td>',$game['title'],'</td>
				<td>',$skip ? $game['title'] : $new_title,'</td>
				<td>',strlen($new_title),'</td>
				<td>',$res['Ack'],'</td>
			</tr>';

	if ($res['Ack'] !== 'Success' && $res['Ack'] !== 'skipped') $fales[] = $res;
	if($k > 1500) break;
}

?>
</table>
<pre>
	<?php
		print_r($fales);
	?>
</pre>