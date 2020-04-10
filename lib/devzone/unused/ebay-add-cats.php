<?php

$where = '';
if (!defined('DEV_MODE')) {
	$where = "WHERE is_on_ebay='yes'";
}

$steam_arr = arrayDB("SELECT title,link,genres,tags from steam_de $where");

$gens = [
	'Action' => [
		'Tactical' => [],
		'Shooter (1st Person)' =>[],
		'Shooter (3st Person)' =>[],
		'SciFi' => [],
		'Überleben' => [],
		'andere' => [],
	],
	'Abenteuer' => [
		'Entscheidungsfreiheit' => [],
		'Puzzle' => [],
		'Dating-Simulation' => [],
		'Visual Novel' => [],
		'Erotik' => [],
		'andere' => [],
	],
	'Echtzeit-Strategie' => [
		'Brettspiel' => [],
		'Göttersimulation' => [],
		'Wirtschaftssimulation' => [],
		'SciFi' => [],
		'Taktik' => [],
		'Wargame' => [],
		'Globalstrategie' => [],
		'Tower Defense' => [],
		'Mittelalter' => [],
	],
	'Rundenstrategie' => [
		'Brettspiel' => [],
		'Göttersimulation' => [],
		'Wirtschaftssimulation' => [],
		'SciFi' => [],
		'Taktik' => [],
		'Wargame' => [],
		'Globalstrategie' => [],
		'Tower Defense' => [],
		'Mittelalter' => [],
	],
	'Strategie' => [
		'andere' => [],
	],
	'Rennspiele' => [
		'Sport' => [],
		'Management' => [],
	],
	'RPG' => [
		'Hack and Slash' => [],
		'JRPG' => [],
		'andere' => [],
	],
];

foreach ($steam_arr as $key => $game) {

	if (stripos($game['genres'], 'Action') !== false) {

		if (stripos($game['tags'], 'Taktik') !== false) {
			$gens['Action']['Tactical'][] = $game;
		}
		
		elseif (stripos($game['tags'], 'FPS') !== false) {
			$gens['Action']['Shooter (1st Person)'][] = $game;
		}
		
		elseif (stripos($game['tags'], 'Third-Person Shooter') !== false ||
				stripos($game['tags'], '3rd-Person') !== false) {
			$gens['Action']['Shooter (3st Person)'][] = $game;
		}
		
		elseif (stripos($game['tags'], 'SciFi')) {
			$gens['Action']['SciFi'][] = $game;
		}
		
		elseif (stripos($game['tags'], 'Überleben') !== false || stripos($game['tags'], 'Horror') !== false) {
			$gens['Action']['Überleben'][] = $game;
		}
		
		else {
			$gens['Action']['andere'][] = $game;
		}
	}

	if (stripos($game['genres'], 'Abenteuer') !== false) {

		if (stripos($game['tags'], 'Entscheidungsfreiheit') !== false) {
			$gens['Abenteuer']['Entscheidungsfreiheit'][] = $game;
		}
		
		elseif (stripos($game['tags'], 'Puzzle') !== false) {
			$gens['Abenteuer']['Puzzle'][] = $game;
		}
		
		elseif (stripos($game['tags'], 'Dating-Simulation') !== false) {
			$gens['Abenteuer']['Dating-Simulation'][] = $game;
		}
		
		elseif (stripos($game['tags'], 'Visual Novel') !== false) {
			$gens['Abenteuer']['Visual Novel'][] = $game;
		}
		
		elseif (stripos($game['tags'], 'Erotik') !== false) {
			$gens['Abenteuer']['Erotik'][] = $game;
		}
		
		else {
			$gens['Abenteuer']['andere'][] = $game;
		}

	}elseif (stripos($game['genres'], 'Indie') !== false) {

		if (stripos($game['tags'], 'Entscheidungsfreiheit') !== false) {
			$gens['Abenteuer']['Entscheidungsfreiheit'][] = $game;
		}
	}

	if (stripos($game['genres'], 'Strategie') !== false) {
		
		if (stripos($game['tags'], 'Echtzeit-Strategie') !== false) {
			
			if (stripos($game['tags'], 'Brettspiel') !== false){
				$gens['Echtzeit-Strategie']['Brettspiel'][] = $game;
			}

			elseif (stripos($game['tags'], 'Göttersimulation') !== false){
				$gens['Echtzeit-Strategie']['Göttersimulation'][] = $game;
			}

			elseif (stripos($game['tags'], 'Wirtschaftssimulation') !== false ||
					stripos($game['tags'], 'Management') !== false){
				$gens['Echtzeit-Strategie']['Wirtschaftssimulation'][] = $game;
			}

			elseif (stripos($game['tags'], 'SciFi') !== false ||
					stripos($game['tags'], 'Futuristisch') !== false){
				$gens['Echtzeit-Strategie']['SciFi'][] = $game;
			}

			elseif (stripos($game['tags'], 'Taktik') !== false){
				$gens['Echtzeit-Strategie']['Taktik'][] = $game;
			}

			elseif (stripos($game['tags'], 'Wargame') !== false ||
					stripos($game['tags'], 'Krieg') !== false ||
					stripos($game['tags'], 'Geschichte') !== false){
				$gens['Echtzeit-Strategie']['Wargame'][] = $game;
			}

			elseif (stripos($game['tags'], 'Globalstrategie') !== false){
				$gens['Echtzeit-Strategie']['Globalstrategie'][] = $game;
			}

			elseif (stripos($game['tags'], 'Tower Defense') !== false){
				$gens['Echtzeit-Strategie']['Tower Defense'][] = $game;
			}

			elseif (stripos($game['tags'], 'Mittelalter') !== false){
				$gens['Echtzeit-Strategie']['Mittelalter'][] = $game;
			}
		}elseif (stripos($game['tags'], 'Rundenstrategie') !== false ||
				stripos($game['tags'], 'Rundenbasiert') !== false) {
			
			if (stripos($game['tags'], 'Brettspiel') !== false){
				$gens['Rundenstrategie']['Brettspiel'][] = $game;
			}

			elseif (stripos($game['tags'], 'Göttersimulation') !== false){
				$gens['Rundenstrategie']['Göttersimulation'][] = $game;
			}

			elseif (stripos($game['tags'], 'Wirtschaftssimulation') !== false ||
					stripos($game['tags'], 'Management') !== false){
				$gens['Rundenstrategie']['Wirtschaftssimulation'][] = $game;
			}

			elseif (stripos($game['tags'], 'SciFi') !== false ||
					stripos($game['tags'], 'Futuristisch') !== false){
				$gens['Rundenstrategie']['SciFi'][] = $game;
			}

			elseif (stripos($game['tags'], 'Taktik') !== false){
				$gens['Rundenstrategie']['Taktik'][] = $game;
			}

			elseif (stripos($game['tags'], 'Wargame') !== false ||
					stripos($game['tags'], 'Krieg') !== false ||
					stripos($game['tags'], 'Geschichte') !== false){
				$gens['Rundenstrategie']['Wargame'][] = $game;
			}

			elseif (stripos($game['tags'], 'Globalstrategie') !== false){
				$gens['Rundenstrategie']['Globalstrategie'][] = $game;
			}

			elseif (stripos($game['tags'], 'Tower Defense') !== false){
				$gens['Rundenstrategie']['Tower Defense'][] = $game;
			}

			elseif (stripos($game['tags'], 'Mittelalter') !== false){
				$gens['Rundenstrategie']['Mittelalter'][] = $game;
			}
		}else{
			$gens['Strategie']['andere'][] = $game;
		}
	}

	if (stripos($game['genres'], 'RPG') !== false){
		
		if (stripos($game['tags'], 'Hack and Slash') !== false){
			$gens['RPG']['Hack and Slash'][] = $game;
		}
		
		if (stripos($game['tags'], 'JRPG') !== false){
			$gens['RPG']['JRPG'][] = $game;
		}

		else{
			$gens['RPG']['andere'][] = $game;
		}
	}
}

// $gens_count = [];

// foreach ($gens as $key => $value) {
// 	$gens_count[$key] = [];
// 	foreach ($value as $k => $val) {
// 		$gens_count[$key][$k] = count($val);
// 	}
// }

// sa($gens_count);
?>
<table class="ppp-table-collapse">
	<tr>
		<th>#</th>
		<th>title</th>
		<th>genre</th>
		<th>category</th>
		<th>steam genres</th>
		<th>steam tags</th>
		<th>link</th>
	</tr>
<?php
//sa($gens);
$i = 1;
// foreach ($gens as $key => $value) {
// 	foreach ($value as $k => $tag) {
// 		foreach ($tag as $game) {
// 			echo '<tr>';
// 			echo '<td>',$i++,'</td>';
// 			echo '<td>',$game['title'],'</td>';
// 			echo '<td>',$key,'</td>';
// 			echo '<td>',$k,'</td>';
// 			echo '<td>',$game['genres'],'</td>';
// 			echo '<td>',$game['tags'],'</td>';
// 			echo '<td><a href="',$game['link'],'" target="_blank">',$game['link'],'</a></td>';
// 			echo '</tr>';
// 		}
// 	}
// }

?>
</table>