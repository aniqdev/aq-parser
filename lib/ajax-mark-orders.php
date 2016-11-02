<?php


function ajax_mark_as_shipped(){
	

	$ebayObj = new EbayOrders();

	$OrderID = $_POST['ebay_order_id'];

	// MarkAsShipped($OrderID, $status = 'true')
	$ret = $ebayObj->MarkAsShipped($OrderID);

	if ($ret['Ack'] == 'Success') {
		arrayDB("UPDATE ebay_orders SET ShippedTime=CURRENT_TIMESTAMP WHERE order_id='$OrderID'");
	}

	return $ret;
}


if (isset($_POST['mark_as_shipped'])) {
	
	$ans = ajax_mark_as_shipped();

	echo json_encode($ans);
}