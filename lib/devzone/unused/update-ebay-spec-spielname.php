<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');
	$offset = (int)$_POST['offset'];

	// $where = "WHERE ebay_id <> ''";
	$where = "WHERE status = 'Active'";

	$count = arrayDB("SELECT count(*) FROM ebay_prices $where")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM ebay_prices $where LIMIT $offset , 1");

	$extra_field = 'extra_field';
	$water_mark = 'Spielnamed1';

	if($res[0][$extra_field] === $water_mark){
		echo json_encode([
			'offset' => $offset,
			'count' => $count,
			'res' => $res[0],
			'resp' => $water_mark,
			'ERRORS' => $_ERRORS,
		]);
		return;
	}
	//=============================================================================
	$ebay_id = $res[0]['item_id'];
	$ebay_prices_id = $res[0]['id'];

	$game_name = $res[0]['title_clean'];

	$specs = get_item_specifics($ebay_id, true);

	$specs['Spielname'] = $game_name;

	if ($specs) {
		$resp = EbayGigGames::setTokenByName('gig-games')
			->updateItemSpecifics($ebay_id, $specs);
	}

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

<h3>update-ebay-spec-spielname</h3>
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
var from = 3000;
var to = 7000
function send_post(offset) {
	$.post('ajax.php' + window.location.search,
		{action:'iterate', offset:offset},
		function (data) {
			if (offset < data.count && offset < to) {
				if (data.resp.Ack) 	var add = data.resp.Ack;
				else var add = data.resp;
				it_ins_msg(offset + ' : <a href="https://www.ebay.de/itm/'+data.resp.ItemID+'" target="_blank">' + data.res.title + '</a> | ' + add);
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
	send_post(from);
});
</script>