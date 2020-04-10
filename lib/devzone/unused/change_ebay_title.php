<pre><?php

	$ebayObj = new Ebay_shopping2();

	//$res = $ebayObj->GetSellerItemsArray();

	//$res = $ebayObj->GetSellerListRequest();

	$item_id = '111985295023';

	$new_title = "Grotesque Tactics 2 - Dungeons and Donuts PC Steam Link EU/USA Key";

	$res = $ebayObj->updateItemTitle($item_id, $new_title);

	//$res = $ebayObj->updateQuantity($item_id, 3);

	print_r($res);

	//var_dump(date_shorter(add_one_hour($ebay_send_resp->Timestamp)));


?></pre>