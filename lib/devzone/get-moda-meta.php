<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate-meta') {
	header('Content-Type: application/json');

	if (@$_POST['btn'] === 'restart') {
		arrayDB("UPDATE moda_list set flag = ''");
	}
	if (@$_POST['btn'] === 'pause') {
		echo json_encode([
			'keep_going' => 0,
			'ERRORS' => $_ERRORS,
		]);
		return;
	}

	$offset = (int)$_POST['offset'];

	$table = 'moda_list';

	$extra_field = 'flag';
	$extra_field_mark = 'dataparsed1';

	$where = "WHERE $extra_field = ''";
	// $where = '';

	$count = arrayDB("SELECT count(*) FROM $table $where")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM $table $where LIMIT $offset , 1");
	//=============================================================================
	// sript below


	if(!$res){
		echo json_encode([
			'keep_going' => 0,
			'ERRORS' => $_ERRORS,
		]);
		return;
	}

	$itemId = $res[0]['itemId'];

	$resp = Ebay_shopping2::getSingleItem_moda($itemId, $as_array = 1);

	if ($resp['Ack'] !== 'Success' || !isset($resp['Item']['ItemID'])) {
		echo json_encode([
			'keep_going' => 1,
			'moda_id' => $moda_id,
			'itm_link' => 'https://www.ebay.de/itm/'.$itemId,
			'res' => $res[0],
			'$resp' => $resp,
			'ERRORS' => $_ERRORS,
		]);
		arrayDB("UPDATE $table SET $extra_field = 'skipped' WHERE id = '{$res[0]['id']}'");
		return;
	}

	$moda_id = $res[0]['id'];

	set_moda_meta($moda_id, $key_value_list = [
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

	$ListingType = _esc($resp['Item']['ListingType']);

	arrayDB("UPDATE $table SET $extra_field = '$extra_field_mark',
								ListingType = '$ListingType' WHERE id = '$moda_id'");


	//=============================================================================

	unset($key_value_list['Description']);

	echo json_encode([
		'keep_going' => 1,
		'moda_id' => $moda_id,
		'offset' => $offset,
		'count' => $count,
		'res' => $res[0],
		'resp' => $resp,
		'key_value_list' => $key_value_list,
		'itm_link' => 'https://www.ebay.de/itm/'.$itemId,
		'ERRORS' => $_ERRORS,
	]);
}


// sa($_ERRORS);

if($_POST) return;
?>
<style>
	
</style>

<div class="" id="<?= js_alpha_dash(__FILE__); ?>">
    <h3><?= script_title(__FILE__); ?></h3>
	<form id="js_go_form" class="go-form">
	    <button name="aaa" value="continue" type="button" class="js-go-btn"><i class="glyphicon glyphicon-play"></i> Continue!</button>
	    <button name="aaa" value="restart" type="button" class="js-go-btn"><i class="glyphicon glyphicon-refresh"></i> Restart!</button>
	    <button name="aaa" value="pause" type="button" class="js-go-btn js-pause-btn"><i class="glyphicon glyphicon-pause"></i> Pause!</button>
	</form><br><br><br>
	<span class="loading"></span>
	<h3>Состояние процесса:</h3>
	<ul id="message" class="message"><li></li></ul>
</div>

<script>
(function() {
var js_alpha_dash = '<?= js_alpha_dash(__FILE__); ?>'
function it_ins_msg(msg) {
	$( '#'+js_alpha_dash+" #message li:first" ).before( "<li>"+msg+"</li>" );
	if($('#'+js_alpha_dash+' #message li').length > 100) {
		$('#'+js_alpha_dash+' #message li:last').remove();
	}
}
var first_row = 0; // first row
var row_limit = 1000; // row limit
var pause = false
function send_post(offset, btn) {
	$.post('ajax.php' + window.location.search,
		{action:'iterate-meta', offset:offset, btn:btn},
		function (data) {
			if (data.keep_going !== 1 || pause) {
				$('#'+js_alpha_dash+' .js-pause-btn').attr('disabled', false);
				$('#'+js_alpha_dash+' .loading').removeClass('inaction');
				it_ins_msg('Done! (или что-то пошло не так)');
			}else{
				if (data.resp && data.resp.Ack) var add = data.resp.Ack;
				else var add = data.resp;
				it_ins_msg(data.moda_id + ' : <a href="'+data.itm_link+'" target="_blank">' + data.res.title + '</a> | ' + add);
				send_post(offset);
			}
		}, 'json');
}
$('#'+js_alpha_dash+' .js-go-btn').on('click', function() {
	$('#'+js_alpha_dash+' .js-go-btn').attr('disabled','true');
	$('#'+js_alpha_dash+' .js-pause-btn').attr('disabled', false);
	var btn = $(this).val()
	if(btn === 'pause'){
		pause = true
		return false
	}
	send_post(first_row, btn);
});
}())
</script>

<template>
	ListingType // боать товары только FixedPriceItem
	GalleryURL
	PictureURL
	PrimaryCategoryID и SecondaryCategoryID
	PrimaryCategoryIDPath и SecondaryCategoryIDPath
    [Seller] => Array
    (
        [UserID] => islandpearl2010
        [FeedbackRatingStar] => YellowShooting
        [FeedbackScore] => 13270
        [PositiveFeedbackPercent] => 99.5
        [TopRatedSeller] => 1
    )
    [ConvertedCurrentPrice] => Array
        (
            [Value] => 1
            [CurrencyID] => EUR
        )

    [CurrentPrice] => Array
        (
            [Value] => 1
            [CurrencyID] => EUR
        )
    ListingStatus забирать только Active
    QuantitySold
    ItemSpecifics кроме Rück...
    HitCount
    PrimaryCategoryIDPath
	[StoreURL] => https://stores.ebay.de/id=1037067816
	[StoreName] => sjocieville
    [BusinessSellerDetails] => Array
    (
        [Address] => Array
            (
                [Street1] => dal as agoho
                [Street2] => camiguin
                [CityName] => mambajao
                [StateOrProvince] => default
                [CountryName] => Philippines
                [Phone] => 9495484361
                [PostalCode] => 9100
                [CompanyName] => turbo-direct
                [FirstName] => jocieville
                [LastName] => samporna
            )

        [Email] => sjocieville@yahoo.com
        [LegalInvoice] => 
    )
    Description
</template>





<?php



function gmp_get_picture_hashes($pic_url_arr)
{
	$pic_hashes = [];
	if ($pic_url_arr) {
		foreach ($pic_url_arr as $pic_url) {
			if (preg_match('#/[^s]/(.+)/#', $pic_url, $matches)) {
				$pic_hashes[] = $matches[1];
			}
		}
	}
	return implode(',', $pic_hashes);
}

function gmp_remove_Ruck($ItemSpecifics)
{
	if(is_array($ItemSpecifics) && isset($ItemSpecifics[0])) {
		return array_values(array_filter($ItemSpecifics, function($value)
		{
			return (stripos($value['Name'], 'Rück') === false) ? true : false;
		}));
	}else{
		return $ItemSpecifics;
	}
}
