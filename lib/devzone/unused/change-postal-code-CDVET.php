<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');
	$offset = (int)$_POST['offset'];


	$where = "WHERE ebay_id <> ''";

	$count = arrayDB("SELECT count(*) FROM cdvet $where")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM cdvet $where LIMIT $offset , 1");
	//=============================================================================
	// sript below


	$extra_field_mark = 'postal_updated3';

	if($res[0]['extra_field'] === $extra_field_mark){
		echo json_encode([
			'offset' => $offset,
			'count' => $count,
			'res' => $res[0],
			'resp' => $extra_field_mark,
			'ERRORS' => $_ERRORS,
		]);
		return;
	}

	$ebay_id = $res[0]['ebay_id'];
	$cdvet_id = $res[0]['id'];





	$resp = Cdvet::changePostalCode($ebay_id, '49584');
	unset($resp['Fees']);

	if (isset($resp['Ack']) && $resp['Ack'] !== 'Failure') {
		arrayDB("UPDATE cdvet SET extra_field = '$extra_field_mark' WHERE id = '$cdvet_id'");
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

<h3>delete-http-from-desc</h3>
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
			if (offset < data.count && offset < 1000) {
				if (data.resp && data.resp.Ack) 	var add = data.resp.Ack;
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