<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');
	$offset = (int)$_POST['offset'];

	// $where = "WHERE ebay_id <> ''";
	$where = "WHERE status = 'Active' and price <= 2";

	$count = arrayDB("SELECT count(*) FROM ebay_prices $where")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM ebay_prices $where LIMIT $offset , 1");

	$extra_field = 'extra_field';
	$water_mark = 'profile_id_updated1';

	if($res[0][$extra_field] === $water_mark){
		echo json_encode([
			'offset' => $offset,
			'count' => $count,
			'res' => $res[0],
			'resp' => 'kaufen_fixed',
			'ERRORS' => $_ERRORS,
		]);
		return;
	}
	//=============================================================================
	$ebay_id = $res[0]['item_id'];
	$ebay_prices_id = $res[0]['id'];

	$resp = EbayGigGames::updateItemShippingProfileID($ebay_id, '133946209010');

	unset($resp['Fees']);

	if (isset($resp['Ack']) && $resp['Ack'] !== 'Failure') {
		arrayDB("UPDATE ebay_prices SET $extra_field = '$water_mark' WHERE id = '$ebay_prices_id'");
	}
	//=============================================================================


	echo json_encode([
		'offset' => $offset,
		'count' => $count,
		'res' => $res[0],
		'resp' => $resp,
		'ERRORS' => $_ERRORS,
	]);
}


// sa($_ERRORS);

if($_POST) return;
?>
<style>
	
</style>

<h3>update-ebay-shipping-profile</h3>
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
function send_post(offset) {
	$.post('ajax.php' + window.location.search,
		{action:'iterate', offset:offset},
		function (data) {
			if (offset < data.count && offset < 3600) {
				if (data.resp.Ack) 	var add = data.resp.Ack;
				else var add = data.resp;
				it_ins_msg(offset + ' : ' + data.res.title + ' | ' + add);
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
	send_post(0);
});
</script>