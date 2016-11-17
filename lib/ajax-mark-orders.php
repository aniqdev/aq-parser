<?php


function ajax_mark_as_paid(){
	

	$ebayObj = new EbayOrders();
	$OrderID = $_POST['ebay_order_id'];
	$status = $_POST['mark_as_paid'];
	if($OrderID > 0){}else return false;
	// MarkAsShipped($OrderID, $status = 'true')
	$ret = $ebayObj->MarkAsPaid($OrderID, $status);
	if ($ret['Ack'] == 'Success') {
		if ($status === 'true') {
			arrayDB("UPDATE ebay_orders SET PaidTime=CURRENT_TIMESTAMP WHERE order_id='$OrderID'");
		}else{
			arrayDB("UPDATE ebay_orders SET PaidTime=0 WHERE order_id='$OrderID'");
		}
	}
	return $ret;
}

function ajax_mark_as_shipped(){
	

	$ebayObj = new EbayOrders();
	$OrderID = $_POST['ebay_order_id'];
	$status = $_POST['mark_as_shipped'];
	if($OrderID > 0){}else return false;
	// MarkAsShipped($OrderID, $status = 'true')
	$ret = $ebayObj->MarkAsShipped($OrderID, $status);
	if ($ret['Ack'] == 'Success') {
		if ($status === 'true') {
			arrayDB("UPDATE ebay_orders SET ShippedTime=CURRENT_TIMESTAMP WHERE order_id='$OrderID'");
		}else{
			arrayDB("UPDATE ebay_orders SET ShippedTime=0 WHERE order_id='$OrderID'");
		}
	}
	return $ret;
}


if (isset($_POST['mark_as_paid'])) {
	echo json_encode(ajax_mark_as_paid());
}


if (isset($_POST['mark_as_shipped'])) {
	echo json_encode(ajax_mark_as_shipped());
}