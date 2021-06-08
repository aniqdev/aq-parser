<div class="containerr">
<?php ini_get('safe_mode') or set_time_limit(300);


$n = 0;
for ($i=0; $i < 3000; $i++) { 
	if (random_int(1, 10) == 1) {
		$n++;
	}
}
sa($n);

return;
$url = 'https://cdvet-parser.gig-games.de/b2b/input.json';
$json = file_get_contents($url);
$cdvet_feed = json_decode($json, 1);

$feed_items = [];
foreach ($cdvet_feed as $key => $variants) {
	foreach($variants as $feed_item){
		$feed_items[] = $feed_item;
	}
}

sa(count($feed_items));

$feed_items = array_column($feed_items, null,'id');
sa($feed_items);




return;
$feed_new = csvToArr('https://www.cdvet.de/backend/export/index/productckeck?feedID=47&hash=a4dc5afc43b82eefd412334d8ed3239e', ['max_str' => 0,'encoding' => 'windows-1250', 'del_first' => true]);


$cdvet_cats = [];

foreach ($feed_new as $key => $string) {
	// echo "<div style='border-top: 1px solid #797979;'></div>";
	// echo $string[10];
	@$cdvet_cats[$string[10]] += 1;
}
// $cdvet_cats = array_unique($cdvet_cats);

xa($cdvet_cats);
// sa($feed_new);


