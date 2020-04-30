<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');

	if (isset($_POST['quantity']) && $_POST['quantity'] > 0) {
		$quantity = (int)$_POST['quantity'];
	}else{
		$quantity = 3;
	}

	$extra_field = 'extra_field';
	$extra_field_mark = 'qtt_updated';

	if ($_POST['effect'] === 'restart') {
		arrayDB("UPDATE cdvet SET $extra_field = ''");
	}

	$res = arrayDB("SELECT * FROM cdvet WHERE $extra_field <> '$extra_field_mark' LIMIT 1");
	//=============================================================================
	// sript below

	if ($res) {
		$cdvet_id = $res[0]['id'];
		$ebay_id = $res[0]['ebay_id'];

		$resp = Cdvet::changeQuantity($ebay_id, $quantity);

		unset($resp['Fees']);

		$api_error = '';
		if ($resp['Ack'] !== 'Success' && isset($resp['Errors']['LongMessage'])) {
			$api_error = '<br> - '.$resp['Errors']['LongMessage'];
		}

		arrayDB("UPDATE cdvet SET $extra_field = '$extra_field_mark' WHERE id = '$cdvet_id'");

		$progress_data = arrayDB("SELECT count(*) as updated,(select count(*) from cdvet) as total from cdvet where $extra_field = '$extra_field_mark' group by $extra_field");

		if ($progress_data) {
			$total = $progress_data[0]['total'];
			$updated = $progress_data[0]['updated'];

			$done_perc = round($updated/$total*100, 1);
			$progress_html = "[ $updated / $total ] ( {$done_perc}% )";

			$keep_going = ($updated < $total) ? 1 : 0;

		}else{
			$progress_html = '[] (0%)';
		}

		$ack = isset($resp['Ack']) ? $resp['Ack'] : 'Fail!';
		$itm_link = 'https://www.ebay.de/itm/' . $ebay_id;
	}else{
		$keep_going = 0;
		$progress_html = '[] (100%)';
	}
	//=============================================================================
	echo json_encode([
		'res' => @$res[0],
		'resp' => @$resp,
		'keep_going' => $keep_going,
		'progress' => $progress_html,
		'msg' => '<a href="' . $itm_link . '" target="_blank">' . $res[0]['title'] . '</a> | ' . $ack . $api_error,
		'ERRORS' => $_ERRORS,
	]);
}


// sa($_ERRORS);

if($_POST) return;
?>
<style>
	
</style>

<div class="container">
	<h3>update cdVet Quantity</h3>
	<form id="js_go_form" class="go-form">
	    <button name="update" value="continue" type="button" class="btn btn-default js-go-btn"><i class="glyphicon glyphicon-play"></i> continue</button>
	    <button name="update" value="restart" type="button" class="btn btn-default js-go-btn"><i class="glyphicon glyphicon-refresh"></i> restart</button>
	    <button name="update" value="pause" disabled type="button" class="btn btn-default js-go-btn js-pause-btn"><i class="glyphicon glyphicon-pause"></i> pause</button> | 
	    <input type="number" class="form-control" value="3" style="display: inline-block; width: 60px;" id="quantity_inp">
	</form><br>
	<h3>Состояние процесса: <span id="progress"></span></h3>
<ul id="message" class="message list-unstyled"><li></li></ul>

</div>
<script>
function it_ins_msg(msg) {
	$( "#message li:first" ).before( "<li>"+msg+"</li>" );
	if($('#message li').length > 1500) {
		$('#message li:last').remove();
	}
}

var pause = false

$('.js-go-btn').on('click', function() {
	$('.js-go-btn').attr('disabled', true);
	$('.js-pause-btn').attr('disabled', false);
	pause = false
	var act = this.name
	var effect = this.value

	var quantity = parseInt($('#quantity_inp').val())

	var send = {action:'iterate', act:act, effect:effect, quantity:quantity}

	if(effect === 'pause'){
		pause = true
		$('.js-pause-btn').attr('disabled', true);
	}
	else send_post(send);
});

function send_post(send) {
	$.post('ajax.php' + window.location.search, send,
		function (data) {
			if(data.progress) $('#progress').html(data.progress)
			if (data.keep_going && !pause) { // row limit
				it_ins_msg(data.msg);
				send.effect = 'continue'
				send_post(send);
			}else{
				it_ins_msg('Done!');
				$('.js-go-btn').attr('disabled', false);
				$('.js-pause-btn').attr('disabled', true);
			}
		}, 'json');
}
</script>