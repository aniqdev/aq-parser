<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');
	$offset = (int)$_POST['offset'];


	// $where = "WHERE extra_field2 LIKE 'sub%'";
	$extra_field = 'extra_field';
	$extra_field_mark = 'set_vat3';
	$where = "WHERE $extra_field <> '$extra_field_mark'";

	$count = arrayDB("SELECT count(*) FROM cdvet $where")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM cdvet $where LIMIT 1");
	//=============================================================================
	// sript below


	if(!$res || $res[0][$extra_field] === $extra_field_mark){
		echo json_encode([
			'offset' => $offset,
			'count' => $count,
			'res' => $res[0],
			'resp' => $extra_field_mark,
			'itm_link' => 'https://www.ebay.de/itm/'.$ebay_id,
			'continue' => 0,
			'ERRORS' => $_ERRORS,
		]);
		return;
	}

	$cdvet_id = $res[0]['id'];
	$ebay_id = $res[0]['ebay_id'];
	$shop_id = $res[0]['shop_id'];

	$resp = Ebay_shopping2::getSingleItem_test($ebay_id, true);

	if (isset($resp['Ack']) && $resp['Ack'] !== 'Failure') {
		$vat_percent = (int)$resp['Item']['BusinessSellerDetails']['VATDetails']['VATPercent'];
		if ($vat_percent == 16) {
			$respons = Cdvet::updateItemVATPercent($ebay_id, 19); // new_vat_percent
		}
		if ($vat_percent == 5) {
			$respons = Cdvet::updateItemVATPercent($ebay_id, 7); // new_vat_percent
		}
		// if($vat_percent) arrayDB("UPDATE cdvet SET `vat` = '$vat_percent' WHERE id = '$cdvet_id'");
	}
	arrayDB("UPDATE cdvet SET $extra_field = '$extra_field_mark' WHERE id = '$cdvet_id'");


	//=============================================================================
	echo json_encode([
		'offset' => $offset,
		'count' => $count,
		'res' => $res[0],
		'resp' => $resp,
		'respons' => $respons,
		'itm_link' => 'https://www.ebay.de/itm/'.$ebay_id,
		'continue' => 1,
		'ERRORS' => $_ERRORS,
	]);
}


// sa($_ERRORS);

if($_POST) return;
?>
<style>
	
</style>

<h3>update cdVet Specifics</h3>
<form id="js_go_form" class="go-form">
    <button name="aaa" value="bbb" type="button" class="js-go-btn">Go!</button>
</form><br><br><br>
<span class="loading"></span>
<h3>Состояние процесса:</h3>
<ul id="message" class="message"><li></li></ul>

<script>
function it_ins_msg(msg) {
	$( "#message li:first" ).before( "<li>"+msg+"</li>" );
	if($('#message li').length > 100) {
		$('#message li:last').remove();
	}
}
var first_row = 0; // first row
var row_limit = 10000; // row limit
function send_post(offset) {
	$.post('ajax.php' + window.location.search,
		{action:'iterate', offset:offset},
		function (data) {
			if (data.continue) { // row limit
				if (data.resp && data.resp.Ack) 	var add = data.resp.Ack;
				else var add = data.resp;
				it_ins_msg(offset + ' : <a href="'+data.itm_link+'" target="_blank">' + data.res.title + '</a> | ' + add);
				send_post(offset+1);
			}else{
				$('.loading').removeClass('inaction');
				it_ins_msg('Done!');
				it_ins_msg( "или что-то пошло не так" );
			}
		}, 'json');
}
$('.js-go-btn').on('click', function() {
	$(this).attr('disabled','true');
	send_post(first_row);
});
</script>