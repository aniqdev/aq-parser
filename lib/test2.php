<pre>
<?php
ini_get('safe_mode') or set_time_limit(180); // Указываем скрипту, чтобы не обрывал связь.

// $res = Ebay_shopping2::findItemsAdvanced(0,'igx4u_com',1,200);

// $res = json_decode($res, true);

// foreach ($res['findItemsAdvancedResponse'][0] as $key => $value) {
// 	var_dump($key);
// }

// print_r($res);


$ebayObj = new Ebay_shopping2();

$res3 = $ebayObj->GetSellerItemsArray();

// $sql = 'TRUNCATE ebay_games;';
// foreach ($res3 as $id => $title) {
// 	$sql .= "INSERT INTO ebay_games (item_id,title) VALUES ('$id','"._esc($title)."');";
// }
// arrayDB($sql, true);

var_dump(count($res3));
print_r($res3);

// $res2 = $ebayObj->GetSellerListRequest(1, 200);

// $ids_arr = [];
// foreach ($res2['ItemArray']['Item'] as $key => $item) {
// 	$ids_arr[$item['ItemID']] = $item['Title'];
// }

// unset($res2['ItemArray']);
// print_r($res2);

// $pages = $res2['PaginationResult']['TotalNumberOfPages'];
// $entires = $res2['PaginationResult']['TotalNumberOfEntries'];
// echo 'Pages: ',$pages,'<br>';
// for ($i=2; $i <= $pages; $i++) { 
// 	$res2 = $ebayObj->GetSellerListRequest($i, 200);
// 	foreach ($res2['ItemArray']['Item'] as $key => $item) {
// 		$ids_arr[$item['ItemID']] = $item['Title'];
// 	}
// }

// echo 'Results hav: ',count($ids_arr),'<br>';
// echo 'Results must: ',$entires,'<br>';
// print_r($ids_arr);

var_dump(date('Y-m-d\TH:i:s.B\Z', time()-2592000*3));
var_dump(date('Y-m-d\TH:i:s.B\Z'));
var_dump(date('Y-m-d\TH:i:s.B\Z', time()+2592000));
var_dump(time());
?>
</pre>