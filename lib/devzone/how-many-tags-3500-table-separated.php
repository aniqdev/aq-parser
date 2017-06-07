<?php

//$res = arrayDB("SELECT genres as g,tags as t FROM steam_de");
$res = arrayDB("SELECT games.id,title,genres,tags FROM steam_de
JOIN games
ON steam_de.link = games.steam_link
LIMIT 4500");

//sa($res[3]);

$g_final = [];
$t_final = [];
$g_index = 1;
$t_index = 101;
foreach ($res as $v) {
	foreach (explode(',', $v['genres']) as $gen) {
		if (isset($g_final[$gen]) || $gen === 'Indie') {
			//$final[$gen] += 1;
		}else{
			$g_final[$gen] = $g_index++;
		}
	}
	foreach (explode(',', $v['tags']) as $tag) {
		if (isset($t_final[$tag]) || $tag === 'Indie') {
			//$final[$tag] += 1;
		}else{
			$t_final[$tag] = $t_index++;
		}
	}
}
// $g_final[''] = '';
// $t_final[''] = '';
sa(count($g_final));
sa(count($t_final));
//arsort($final);
//sa($final);

echo '<h3>Genres codes</h3><table class="ppp-table-collapse">';
foreach ($g_final as $fin => $num) {
	echo '<tr>';
	echo '<td>',$fin,'</td>';
	echo '<td>',$num,'</td>';
	echo '</tr>';
}
echo '</table><br><br>';

echo '<h3>Tags codes</h3><table class="ppp-table-collapse">';
foreach ($t_final as $fin => $num) {
	echo '<tr>';
	echo '<td>',$fin,'</td>';
	echo '<td>',$num,'</td>';
	echo '</tr>';
}
echo '</table><br><br>';

function g_numerate($el){	
	global $g_final;
	return $g_final[$el];
}
function t_numerate($el){	
	global $t_final;
	return $t_final[$el];
}
function indie_filter($el)
{
	if($el === 'Indie' || $el === '') return false;
	else return true;
}


echo '<table class="ppp-table-collapse">';
foreach ($res as $v) {
	$gens_arr = array_filter(explode(',', $v['genres']), 'indie_filter');
	$tags_arr = array_filter(explode(',', $v['tags']), 'indie_filter');
	$gens_arr = array_map("g_numerate", $gens_arr);
	$tags_arr = array_map("t_numerate", $tags_arr);
	echo '<tr>';
	echo '<td>',$v['title'],'</td>';
		echo '<td>';
			echo implode(',', $gens_arr);
			if($gens_arr && $tags_arr) echo ',';
			echo implode(',', $tags_arr);
		echo '</td>';
		echo '<td>';
			echo $v['genres'];
		echo '</td>';
		echo '<td>';
			echo $v['tags'];
		echo '</td>';
	echo '</tr>';
}
echo '</table>';
?>


