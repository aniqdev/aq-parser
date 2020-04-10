<?php

if (isset($_POST['game_id']) && isset($_POST['steam_link'])) {
	$game_id = _esc($_POST['game_id']);
	$steam_link = _esc($_POST['steam_link']);
	$_POST['good'] = arrayDB("UPDATE games SET steam_link = '$steam_link' WHERE id = '$game_id'");
	echo json_encode($_POST);
	die;
}

?>
<style>
	td button{
		height: 19px;
    	line-height: 15px;
	}
</style>

<table class="ppp-table-collapse" id="js-deligator">
	<tr>
		<th>#</th>
		<th>title</th>
		<th>add</th>
		<th>suggestion</th>
	</tr>
<?php

$res = arrayDB("SELECT * FROM games WHERE steam_link is null order by name LIMIT 800");

$mmax = 10;

foreach ($res as $key => $game) {
	if (!$game['steam_link']) {
		$g_name = _esc(trim(str_ireplace('steam', '', $game['name'])));
		$matches = arrayDB("SELECT title,link FROM steam_de 
				WHERE MATCH (title) AGAINST ('$g_name') LIMIT $mmax");

		$game_id = _esc($game['id']);
		$match = @$matches[0];
		$steam_link = _esc($match['link']);
		if (trim(strtolower(str_replace([':','™','®'], '', $g_name))) === trim(strtolower(str_replace([':','™','®'], '', $match['title'])))) {
			arrayDB("UPDATE games SET steam_link = '$steam_link' WHERE id = '$game_id'");
		}
		$g_name = trim(str_ireplace('steam', '', $game['name']));
		echo '<tr class="',$game['id'],'">';
		echo '<td>',$key+1,'</td>';
		if($game['ebay_id']) echo '<td><a target="_blank" href ="http://www.ebay.de/itm/',$game['ebay_id'],'">',$g_name,'</a></td>';
		else echo '<td>',$g_name,'</td>';
		echo '<td>';
		foreach ($matches as $key => $match) { if($key > $mmax) break;
			echo '<button type="button" class="add-link" name="',$match['link'],'" id="',$game['id'],'">Add!</button><br>';
		}
		echo '</td><td>';
		foreach ($matches as $key => $match) { if($key > $mmax) break;
			echo '<a target="_blank" href="',$match['link'],'">',$match['title'],'</a><br>';
		}
		echo '</td></tr>';
		
	}
}


?>
</table>

<script>
	$('#js-deligator').on('click', '.add-link', function (e) {
		var game_id = this.id;
		var steam_link = this.name;
		$.post('ajax.php?action=devzone/add-steam-links',
			{game_id:game_id,steam_link:steam_link},
			function (data) {
				if (data.good === true) {
					$("tr."+game_id).remove();

				}
		},'json');
	})
</script>
