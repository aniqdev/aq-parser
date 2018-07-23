<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');
	$offset = (int)$_POST['offset'];

	$where = "WHERE ebay_id <> ''";

	$count = arrayDB("SELECT count(*) FROM games $where")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM games $where LIMIT $offset , 1");

	if($res[0]['extra_field2'] === 'kaufen_fixed'){
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
	$ebay_id = $res[0]['ebay_id'];
	$games_id = $res[0]['id'];

	$item_info = getSingleItem($ebay_id, ['as_array'=>true,'IncludeSelector'=>'Description']);

	$description = $item_info['Item']['Description'];

	$title = 'description backup';
	$full_desc = _esc($description);
	arrayDB("INSERT INTO ebay_data 
		(ebay_id,title,full_desc)
		VALUES
		('$ebay_id','$title','$full_desc')");


	$description = preg_replace('/offer\.ebay\.de.+?fb=1/s',
		'offer.ebay.de/ws/eBayISAPI.dll?BinConfirm&fromPage=2047675&item='.$ebay_id.'&fb=1', $description);


	$ebayObj = new Ebay_shopping2();

	$resp = $ebayObj->updateItemDescription($ebay_id, $description);
	unset($resp['Fees']);

	if (isset($resp['Ack']) && $resp['Ack'] !== 'Failure') {
		arrayDB("UPDATE games SET extra_field2 = 'kaufen_fixed' WHERE id = '$games_id'");
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

<h3>Fix kaufen button</h3>
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
			if (offset < data.count && offset < 15000) {
				if (data.resp.Ack) 	var add = data.resp.Ack;
				else var add = data.resp;
				it_ins_msg(offset + ' : ' + data.res.name + ' | ' + add);
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