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

if ($_POST && isset($_POST['hood_change_price_no_id'])) {
	$url = 'http://hood.gig-games.de/api/changeItemPriceQuantity';
	$ebay_id = $_POST['ebayId'];
	$hoodId = arrayDB("SELECT hood_id FROM games WHERE ebay_id = '$ebay_id'");
	if ($hoodId) $hoodId = $hoodId[0]['hood_id'];
	else echo json_encode(['status' => 'error', 'error' => 'no hood id']);	
	$res = post_curl($url, ['hoodId'=>$hoodId,'newPrice'=>$_POST['newPrice']]);
	echo json_encode($res);	
}



if ($_POST && isset($_POST['hood_remove'])) {
	$url = 'http://hood.gig-games.de/api/removeFromSale';
	$res = post_curl($url, $_POST);
	echo json_encode($res);	
}

if ($_POST && isset($_POST['hood_remove_no_id'])) {
	$url = 'http://hood.gig-games.de/api/removeFromSale';
	$ebay_id = $_POST['ebayId'];
	$hoodId = arrayDB("SELECT hood_id FROM games WHERE ebay_id = '$ebay_id'");
	if ($hoodId) $hoodId = $hoodId[0]['hood_id'];
	else echo json_encode(['status' => 'error', 'error' => 'no hood id']);	
	$res = post_curl($url, ['hoodId'=>$hoodId]);
	echo json_encode($res);	
}




?>