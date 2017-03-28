<?php





if (isset($_POST['mark_as_paid'])) {
	$OrderID = $_POST['ebay_order_id'];
	$status = $_POST['mark_as_paid'];
	echo json_encode(ajax_mark_as_paid($OrderID, $status));
}


if (isset($_POST['mark_as_shipped'])) {
	$OrderID = $_POST['ebay_order_id'];
	$status = $_POST['mark_as_shipped'];
	echo json_encode(ajax_mark_as_shipped($OrderID, $status));
}