<?php ini_get('safe_mode') or set_time_limit(120);

$num = isset($_GET['num']) ? (int)$_GET['num'] : 50;

$file = ROOT.'/lib/adds/cron-moda-update-status.txt';

$status = file_get_contents($file);


if ($status !== 'in-progress') {
	$start_time = time();
	file_put_contents($file, 'in-progress');
	for ($i=0; $i < $num; $i++) { 
		gmp_update_oldest_record_report();
		if((time() - $start_time) > 50) break;
	}
	file_put_contents($file, 'in-peace');
}

sa(@$i+1);




function gmp_update_oldest_record_report()
{
	global $_ERRORS;

	$data = [
		'res' => '',
		'resp' => '',
		'update_query' => '',
		'key_value_list' => '',
		'cron_status' => '',
	];

	$time_start = microtime(1);

	$data = gmp_update_oldest_record($data);

	// what an option?
	$action = 'error';
	$report = 'error';
	if ($data['cron_status'] === 'good' && $data['res']['post_id']) {
		$action = 'update';
		$is_done = gmp_make_post_request($action, $data['res']['id']);
		if($is_done) $report = 'updated';
	}
	if ($data['cron_status'] === 'good' && !$data['res']['post_id']) {
		$action = 'insert';
		$is_done = gmp_make_post_request($action, $data['res']['id']);
		if($is_done) $report = 'inserted';
	}
	if ($data['cron_status'] === 'remove' && $data['res']['post_id']) {
		$action = 'delete';
		$is_done = gmp_make_post_request($action, $data['res']['id']);
		if($is_done){
			arrayDB("DELETE FROM moda_list WHERE id = '{$data['moda_id']}';");
			$report = 'deleted';
		}
	}
	if ($data['cron_status'] === 'remove' && !$data['res']['post_id']) {
		arrayDB("DELETE FROM moda_list WHERE id = '{$data['moda_id']}'");
		$action = 'no-id';
		$report = 'no-id';
	}
	if ($data['cron_status'] === 'expired') {
		$action = 'expired';
		$report = 'expired';
	}

	if(isset($_GET['dump'])) sa($data);

	$ack = $data['resp']['Ack'];

	if ($ack === 'Failure') $errors = _esc(json_encode(array_merge($_ERRORS, $data['resp']['Errors'])));
	else $errors = _esc(json_encode($_ERRORS));

	$endTime = @$data['resp']['Item']['EndTime'] ? $data['resp']['Item']['EndTime'] : 0;

	arrayDB("INSERT INTO moda_cron_update SET
							moda_id = '{$data['moda_id']}',
							Ack = '$ack',
							endTime = '$endTime',
							errors = '$errors',
							action = '$action',
							report = '$report',
							time_spent = '".round(microtime(1) - $time_start, 3)."',
							cron_status = '{$data['cron_status']}',
							created_at = NOW()
				");
}




function gmp_update_oldest_record($data)
{
	global $_ERRORS;

	if(!$res = arrayDB("SELECT * from moda_list order by updated_at limit 1")) return;
	$data['res'] = $res[0];

	$resp = Ebay_shopping2::getSingleItem_moda($res[0]['itemId'], $as_array = 1);
	unset($resp['Item']['ReturnPolicy']);
	$data['resp'] = $resp;
	unset($data['resp']['Item']['ItemSpecifics']);
	unset($data['resp']['Item']['BusinessSellerDetails']);
	unset($data['resp']['Item']['Variations']);
	unset($data['resp']['Item']['ExcludeShipToLocation']);

	$extra_field = 'flag';
	$extra_field_mark = 'dataparsed1';

	$moda_id = $res[0]['id'];
	$data['moda_id'] = $moda_id;

	// =========== cron_status ========================
	$cron_status = gmp_get_cron_status($res, $resp);
	$data['cron_status'] = $cron_status;

	if ($resp['Ack'] !== 'Success' || !isset($resp['Item']['ItemID'])) {
		arrayDB("UPDATE moda_list SET $extra_field = 'skipped',
								cron_status = 'failure',
								updated_at = NOW()
					 WHERE id = '$moda_id'");
		return $data;
	}

	// =============== update moda data =============================
	$key_value_list = gmp_save_moda_meta($moda_id, $resp);
	
	unset($key_value_list['Description']);
	$data['key_value_list'] = $key_value_list;

	$resp['Item']['ListingType'] = _esc($resp['Item']['ListingType']);
	$resp['Item']['Title'] = _esc($resp['Item']['Title']);

	$update_query = "UPDATE moda_list SET $extra_field = '$extra_field_mark',
								ListingType = '{$resp['Item']['ListingType']}',
								title = '{$resp['Item']['Title']}',
								currentPrice = '{$resp['Item']['ConvertedCurrentPrice']['Value']}',
								startTime = '{$resp['Item']['StartTime']}',
								endTime = '{$resp['Item']['EndTime']}',
								cron_status = '$cron_status',
								updated_at = NOW()
						 WHERE id = '$moda_id'";
	arrayDB($update_query);
	$data['update_query'] = $update_query;

	return $data;
}













function gmp_make_post_request($action, $moda_id)
{
	if(defined('DEV_MODE')) $post_uri = 'http://koeln-webstudio.loc/moda-sync.php';
	else $post_uri = 'https://modetoday.de/moda-sync.php?wpok';

	$post_resp = post_curl($post_uri, [
		'action' => $action,
		'moda_id' => $moda_id,
	]);

	sa($post_resp);

	return $post_resp['func_res'];
}







function gmp_get_cron_status(&$res, &$resp)
{
	$cron_status = 'good';
	if ($res[0]['cron_status'] === 'failure' || $res[0]['globalId'] !== 'EBAY-DE') { // old_cron_status
		$cron_status = 'remove';
	}else{	
		if (@$resp['Ack'] !== 'Success') {
			$cron_status = 'failure';
			if ($resp['Errors'][0]['ShortMessage'] === 'Invalid item ID.') {
				$cron_status = 'remove';
			}
		}else{
			$end_time_stamp = date_timestamp_get(date_create($resp['Item']['EndTime']));
			$now = time();
			if ($end_time_stamp > 1 && $end_time_stamp < $now) {
				$cron_status = 'expired';
			}
			if ($end_time_stamp > 1 && $end_time_stamp < ($now - 60*60*24)) {
				$cron_status = 'remove';
			}
		}
	}
	return $cron_status;
}





function gmp_save_moda_meta($moda_id, &$resp)
{
	
	set_moda_meta($moda_id, @$key_value_list = [
		'ListingType' => $resp['Item']['ListingType'],
		'ListingStatus' => $resp['Item']['ListingStatus'],
		'GalleryURL' => $resp['Item']['GalleryURL'],
		'PictureURL' => gmp_get_picture_hashes($resp['Item']['PictureURL']),
		'PrimaryCategoryIDPath' => $resp['Item']['PrimaryCategoryIDPath'],
		'Seller.UserID' => $resp['Item']['Seller']['UserID'],
		'Seller.FeedbackRatingStar' => $resp['Item']['Seller']['FeedbackRatingStar'],
		'Seller.FeedbackScore' => $resp['Item']['Seller']['FeedbackScore'],
		'Seller.PositiveFeedbackPercent' => $resp['Item']['Seller']['PositiveFeedbackPercent'],
		'Seller.TopRatedSeller' => $resp['Item']['Seller']['TopRatedSeller'],
		'Seller.CurrentPrice.Value' => $resp['Item']['CurrentPrice']['Value'],
		'Seller.CurrentPrice.CurrencyID' => $resp['Item']['CurrentPrice']['CurrencyID'],
		'Seller.ConvertedCurrentPrice.Value' => $resp['Item']['ConvertedCurrentPrice']['Value'],
		'Seller.ConvertedCurrentPrice.CurrencyID' => $resp['Item']['ConvertedCurrentPrice']['CurrencyID'],
		'QuantitySold' => $resp['Item']['QuantitySold'],
		'ItemSpecifics' => json_encode(gmp_remove_Ruck($resp['Item']['ItemSpecifics']['NameValueList'])),
		'HitCount' => $resp['Item']['HitCount'],
		'Storefront.StoreURL' => $resp['Item']['Storefront']['StoreURL'],
		'Storefront.StoreName' => $resp['Item']['Storefront']['StoreName'],
		'Address.Street1' => $resp['Item']['BusinessSellerDetails']['Address']['Street1'],
		'Address.Street2' => $resp['Item']['BusinessSellerDetails']['Address']['Street2'],
		'Address.CityName' => $resp['Item']['BusinessSellerDetails']['Address']['CityName'],
		'Address.StateOrProvince' => $resp['Item']['BusinessSellerDetails']['Address']['StateOrProvince'],
		'Address.CountryName' => $resp['Item']['BusinessSellerDetails']['Address']['CountryName'],
		'Address.Phone' => $resp['Item']['BusinessSellerDetails']['Address']['Phone'],
		'Address.PostalCode' => $resp['Item']['BusinessSellerDetails']['Address']['PostalCode'],
		'Address.CompanyName' => $resp['Item']['BusinessSellerDetails']['Address']['CompanyName'],
		'Address.FirstName' => $resp['Item']['BusinessSellerDetails']['Address']['FirstName'],
		'Address.LastName' => $resp['Item']['BusinessSellerDetails']['Address']['LastName'],
		'Email' => $resp['Item']['BusinessSellerDetails']['Email'],
		'Description' => $resp['Item']['Description'],
		'Variations' => json_encode($resp['Item']['Variations']['VariationSpecificsSet']['NameValueList']),
		'VariationsPics' => json_encode($resp['Item']['Variations']['Pictures']),
	]);

	return $key_value_list;
}