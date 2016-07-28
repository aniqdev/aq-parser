<pre><br><br>
<?php
function cmp($a, $b){
    if ($a['price'] == $b['price']) return 0;
    return ($a['price'] < $b['price']) ? 1 : -1;
}
// header("Content-type: application/json; charset=utf-8");
// header('X-Accel-Buffering: no');
// ob_end_flush();

// $strJSON = Ebay_shopping::findItemsAdvanced(0, 'economy-games', 1); 
// $strJSON = Ebay_shopping::getSingleItem('151861801776');
$strJSON = Ebay_shopping::findItemsAdvanced("Assassin's Creed Freedom Cry   Steam", 0);

$objJSON = json_decode($strJSON);
$itemArr = array();
$newArr = array();
if (isset($objJSON->findItemsAdvancedResponse[0]->searchResult[0]->item)) {
	$itemArr = $objJSON->findItemsAdvancedResponse[0]->searchResult[0]->item;
}
for ($i=0; $i < count($itemArr); $i++) {
	$newArr[$i]['itemid'] = $itemArr[$i]->itemId[0];
	$newArr[$i]['title']  = $itemArr[$i]->title[0];
	$newArr[$i]['price']  = $itemArr[$i]->sellingStatus[0]->convertedCurrentPrice[0]->__value__;
}

usort($newArr, "cmp");
 //echo "<pre>";
// $objJSON = json_decode($strJSON, true);
// $itemArr = array();
// if (isset($objJSON['findItemsAdvancedResponse'][0]['searchResult'][0]['item'])) {
// 	$itemArr = $objJSON['findItemsAdvancedResponse'][0]['searchResult'][0]['item'];
// }
// $newArr = array();
// foreach ($itemArr as $k => $v) {
// 	$newArr[$k]['title'] = $v['title'][0];
// 	$newArr[$k]['price'] = $v['sellingStatus'][0]['currentPrice'][0]['__value__'];
// 	$newArr[$k]['curre'] = $v['sellingStatus'][0]['currentPrice'][0]['@currencyId'];
// }



//print_r($newArr);
 echo($strJSON);
 print_r($newArr);
 //echo "</pre>";
?>
</pre>