<?php 


$res = arrayDB("SELECT * FROM items WHERE scan = (select scan from items order by id desc limit 1)");

// sa($res);

$exrate_wmr = get_setting('exrate_wmr');
$exrate_wmz = get_setting('exrate_wmz');
$counter = ['WMR'=>0,'WMZ'=>0,'WME'=>0,'___'=>0];
foreach ($res as $key => &$item){
	$currency = '___';
	$min_price = 999999;
	if ($item['item1_price_eur'] > 0 && $item['item1_price_eur'] < $min_price) {
		$currency = 'WME';
		$min_price = $item['item1_price_eur'];
	}
	$item['item1_price_rur_in_eur'] = round($item['item1_price_rur'] / $exrate_wmr, 2);
	if ($item['item1_price_rur'] > 0 && $item['item1_price_rur_in_eur'] < $min_price) {
		$currency = 'WMR';
		$min_price = $item['item1_price_rur'] / $exrate_wmr;
	}
	$item['item1_price_usd_in_eur'] = round($item['item1_price_usd'] / $exrate_wmz, 2);
	if ($item['item1_price_usd'] > 0 && $item['item1_price_usd_in_eur'] < $min_price) {
		$currency = 'WMZ';
	}
	$item['currency'] = $currency;
	$counter[$currency]++;
}
?>
<!-- <script src="js/fixed-table.js"></script> -->
<div class="container" id="fixed_table_container"><br><br>
	<?= str_replace(['Array','(',')'], '', sa($counter,1)); ?>
	<table class="table">
		<thead>
			<tr>
				<th>#</th>
				<th>id</th>
				<th>name</th>
				<th>rur</th>
				<th>usd</th>
				<th>eur</th>
				<th>cheapest</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($res as $key => $item) {
			echo '<tr>';
				echo '<td>'.($key+1).'</td>';
				echo '<td>'.$item['item1_id'].'</td>';
				echo '<td>'.$item['item1_name'].'</td>';
				echo '<td>'.$item['item1_price_rur'].' ('.$item['item1_price_rur_in_eur'].')'.'</td>';
				echo '<td>'.$item['item1_price_usd'].' ('.$item['item1_price_usd_in_eur'].')'.'</td>';
				echo '<td>'.$item['item1_price_eur'].'</td>';
				echo '<td>'.$item['currency'].'</td>';
			echo '</tr>';
		}
		?>
		</tbody>
	</table>
</div>
<script>
	// var fixedTable = fixTable(document.getElementById('fixed_table_container'));
</script>