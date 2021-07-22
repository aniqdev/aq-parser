<?php ini_set('max_execution_time', 300);

$_GET['similar_products'] = json_decode(file_get_contents('csv/similar_products_18-06.json'), true);
// sa($_GET['similar_products']);
// return;
function is_similar_items($item1, $item2)
{
	if($item1 === $item2) return false;
	if(!isset($_GET['similar_products'][$item1])) return false;
	if (in_array($item2, $_GET['similar_products'][$item1])) {
		return true;
	}
	return false;
}

// sa($similar_products);

// $artikles = readExcel('csv/Artikel.xlsx');

// sa($artikles);

$orders = readExcel('csv/orders .xlsx');

// sa($orders);

$orders_keys = [];
foreach ($orders as $key => $order){
	if($order['A']) $orders_keys[$order['A']][] = $order['B'];
}
// sa(count($orders_keys));
// sa($orders_keys);

// $orders_items_count = [];
// foreach ($orders_keys as $order => $items){
// 	$orders_items_count[$order] = count($items);
// }
// arsort($orders_items_count);
// sa($orders_items_count);
// return;


?>
<div class="container">
<pre>Таблица содержит заказы зодержащие совпадения из Artike.xlsx
Всего заказов: <?= count($orders_keys) ?>

Заказов с совпадениями: 544</pre>
	<table class="ppp-table">
		<tr>
			<th>заказ</th>
			<th>товары</th>
			<th>кол-во совпадений</th>
			<th>совпадения</th>
		</tr>
		<?php $key = 0; $affected = 0;
		foreach ($orders_keys as $order => $items) {
			// if($key > 1000) break; $key++;
			$similar_count = 0;
			$similar_pairs = [];
			foreach ($items as $item1) {
				foreach ($items as $item2) {
					if (is_similar_items($item1, $item2)) {
						$similar_count++;
						$similar_pairs[] = "$item1 ($item2)";
					}
				}
			}
			if(!$similar_count) continue;
			$affected++;
			echo '<tr>';
			echo '<td>';
			echo $order;
			echo '</td>';
			echo '<td>';
			echo implode('<br>', $items);
			echo '</td>';
			echo '<td>';
			echo $similar_count;
			echo '</td>';
			echo '<td>';
			echo implode('<br>', $similar_pairs);
			echo '</td>';
			echo '</tr>';
		}
		?>
	</table>
	<?php //sa('Total: ' . $affected); ?>
</div>
<?php
// $similar_products = [];
// foreach ($artikles as $key => $row) {
// 	if ($key != 1 && $row['B']) {
// 		$similar_products[$row['A']] = explode('|', $row['B']);
// 	}
// }
// sa($similar_products);
// file_put_contents('csv/similar_products_18-06.json', json_encode($similar_products, 128));

