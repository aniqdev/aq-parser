<div class="containerr">
<?php ini_get('safe_mode') or set_time_limit(300);


$url = 'https://cdvet-parser.gig-games.de/b2b/input-last.json';
$json = file_get_contents($url);
$cdvet_feed = json_decode($json, 1);

// $hund_count = 0;
// foreach ($cdvet_feed as $key => $variants) {
// 	if(stripos($variants[0]['description_clean'], 'hund') !== false || stripos($variants[0]['name'], 'hund') !== false) $hund_count++;
// }

// sa(count($cdvet_feed));
// sa(($hund_count));
// sa($cdvet_feed);

$file = csvToArr('./Files/wp_posts.csv',['delimetr' => ',']);

// sa(count($file));
$shop_ids = array_column($file,0,2);

$with_hund_count = 0;
$woo_count = 0;
?>
<div class="container-fluid" style="max-width: 1400px;">
	<table class="ppp-table">
		<tr>
			<th>shop_id</th>
			<th>category</th>
			<th>title</th>
			<th title="with ''hund'' in description">hund</th>
			<th>on hundefutter</th>
		</tr>
		<?php foreach ($cdvet_feed as $key => $variants) {
			$with_hund = '';
			if(stripos($variants[0]['description_clean'], 'hund') !== false){
				// $with_hund = '<i class="glyphicon glyphicon-ok"></i>';
				$with_hund = 'with_hund';
				$with_hund_count++;
			}
			$in_woo = '';
			if (isset($shop_ids[$variants[0]['id']])) {
				$in_woo = 'in_woo['.$shop_ids[$variants[0]['id']].']';
				$woo_count++;
			}
		?>
		<tr>
			<td><?= $variants[0]['id'] ?></td>
			<td><?= $variants[0]['category'] ?></td>
			<td><a href="<?= $variants[0]['url'] ?>" target="_blank"><?= $variants[0]['name'] ?></a></td>
			<td><?= $with_hund ?></td>
			<td><?= $in_woo ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td></td>
			<td></td>
			<td><?= count($cdvet_feed) ?></td>
			<td><?= $with_hund_count ?></td>
			<td><?= $woo_count ?></td>
		</tr>
	</table>
</div>