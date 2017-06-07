<?php
ini_get('safe_mode') or set_time_limit(850); // Указываем скрипту, чтобы не обрывал связь.

if ($_POST) {
	$ans = hoodItemSync([$_POST['ebay_id']]);
	echo json_encode(['ans'=>$ans, 
					'date' => date('Y-m-d H:i:s'),
					'_POST'=>$_POST, 
					'_ERRORS'=>$_ERRORS]);
	die;
}

?>

<div class="container">
	<h2>Hood import</h2>
	<button class="btn btn-default" id="update-ebay-games">update ebay games</button><br><br>
	<div id="form_here" class="hs-console">Загрузка...</div><br>
</div>

<?php


$ebay_games = arrayDB("SELECT item_id,title_clean,last_update 
from ebay_games
left join hood_last_update
on ebay_games.item_id = hood_last_update.ebay_id
order by ebay_games.id");


?>

<div class="container">
	<table id="hs_table" class="ppp-table-collapse">
		<?php foreach ($ebay_games as $key => $game) { ?>
			<tr id="<?= $game['item_id'];?>" 
				title="<?= $game['title_clean'];?>" 
				class="hs-tr">
				<td><?= ++$key;?></td>
				<td><?= $game['item_id'];?></td>
				<td><?= $game['title_clean'];?></td>
				<td class="timestamp"><?= $game['last_update'];?></td>
			</tr>
		<?php } ?>
	</table>
</div>

<script src="js/react.min.js"></script>
<script src="js/react-dom.min.js"></script>
<script src="js/babel-core.min.js"></script>

<script src="js/hood-sync.jsx" type="text/babel"></script>






