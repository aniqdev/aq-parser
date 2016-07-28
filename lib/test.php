<?php
$seller = 'gig-games';
$ebay_obj = new Ebay_shopping2();
$result_arr = $ebay_obj->getSellerInfo($seller);

if ($result_arr['status'] === 'OK') {
	$aq_page1_msg = 'Seller <b>'.$seller.'</b> was found<br>'.
	'It has: <b>'.$result_arr['totalPages'].'</b> pages<br>'.
	'and <b>'.$result_arr['totalEntries'].'</b> items for sale.';

	$cssClass1 = 'updated';

}elseif($result_arr['status'] === 'error'){
	$aq_page1_msg = $result_arr['errorMsg'];
	$cssClass1 = 'error';
}
//echo $aq_page1_msg;
?>
<pre>
	<?php
	$ids_arr = array();
	for ($i=1; $i <= $result_arr['totalPages']; $i++) { 
		$items = $ebay_obj->getProductsBySeller($seller, $i);

		foreach ($items['items'] as $item) {
			$ids_arr[] = $item['itemId'];
		}
	}
	$_SESSION['ids_arr'] = $ids_arr;
	// print_r($ids_arr) ?>
</pre>