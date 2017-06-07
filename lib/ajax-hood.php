<?php






if ($_POST && isset($_POST['hood_get_price'])) {
	$url = 'http://hood.gig-games.de/api/getItemPrice';
	$res = post_curl($url, $_POST);
	echo json_encode($res);
	$ebay_id = $_POST['ebay_id'];
	if (isset($_POST['hood_check']) &&
		$res['status'] === 'success' &&
		$ebay_id) {
			$hood_id = $_POST['hood_id'];
			arrayDB("UPDATE games SET hood_id = '$hood_id' WHERE ebay_id = '$ebay_id'");
	}
}



if ($_POST && isset($_POST['hood_change_price'])) {
	$url = 'http://hood.gig-games.de/api/changeItemPriceQuantity';
	$res = post_curl($url, $_POST);
	echo json_encode($res);	
}




?>