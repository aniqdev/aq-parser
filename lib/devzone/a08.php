<?php 







$hotel_dom = file_get_html('https://agent.tui.ru/Tours/Europe/Russia/anapa/Pionerskiy-prospert/beton-brut-all-inclusive');

$links = $hotel_dom->find('.ad-thumb-list a');

sa(count($links));
foreach ($links as $key => $link) {
	if($key >= 4) break;
	$img_src = preg_replace('/\?.+/', '', $link->href);
	$img_src = 'https://agent.tui.ru/' . $img_src;
	echo '<img src="'.$img_src.'" width="320">';
	sa($img_src);
}










return;
$json = file_get_contents('https://apigate.tui.ru/api/content/hotel/europe-turkey-alanya-mahmutlar-armas__prestige/');

sa(json_decode($json, 1));

return;
?>
<div class="containerr">
<?php ini_get('safe_mode') or set_time_limit(300);





$url = './csv/cdvet-products-09.12.20.json';
$json = file_get_contents($url);
$cdvet_products = json_decode($json, 1);

// $feed_items = [];
// foreach ($cdvet_feed as $key => $variants) {
// 	foreach($variants as $feed_item){
// 		$feed_items[] = $feed_item;
// 	}
// }

sa(count($cdvet_products));

// $cdvet_products = array_column($cdvet_products,'F','A'); // taxes
sa($cdvet_products);

?>
</div>