<?php

//$res = arrayDB("SELECT genres as g,tags as t FROM steam_de");
$res = arrayDB("SELECT games.id,title,genres,tags FROM steam_de
JOIN games
ON steam_de.link = games.steam_link
LIMIT 4500");

$final = [];
$index = 1;
foreach ($res as $v) {
	$g = explode(',', $v['genres']);
	foreach ($g as $gen) {
		if (isset($final[$gen])) {
			//$final[$gen] += 1;
		}else{
			$final[$gen] = $index++;
		}
	}
	$t = explode(',', $v['tags']);
	foreach ($t as $tag) {
		if (isset($final[$tag])) {
			//$final[$tag] += 1;
		}else{
			$final[$tag] = $index++;
		}
	}
}
sa(count($final));
//arsort($final);
//sa($final);

echo '<table class="ppp-table-collapse">';
foreach ($final as $fin => $num) {
	echo '<tr>';
	echo '<td>',$fin,'</td>';
	echo '<td>',$num,'</td>';
	echo '</tr>';
}
echo '</table><br><br>';

echo '<table class="ppp-table-collapse">';
foreach ($res as $v) {
	echo '<tr>';
	echo '<td>',$v['title'],'</td>';
		echo '<td>';
			foreach (explode(',', $v['genres']) as $k => $g) {
				if($k !== 0) echo ',';
				echo $final[$g];
			}
			foreach (explode(',', $v['tags']) as $t) {
				echo ',',$final[$t];
			}
		echo '</td>';
		echo '<td>';
			foreach (explode(',', $v['genres']) as $k => $g) {
				if($k !== 0) echo ',';
				echo $g;
			}
		echo '</td>';
		echo '<td>';
			foreach (explode(',', $v['tags']) as $k =>  $t) {
				if($k !== 0) echo ',';
				echo $t;
			}
		echo '</td>';
	echo '</tr>';
}
echo '</table>';
?>


