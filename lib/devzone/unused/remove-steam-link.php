<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');
	$offset = (int)$_POST['offset'];

	$where = "WHERE ebay_id <> ''";

	$count = arrayDB("SELECT count(*) FROM games $where")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM games $where LIMIT $offset , 1");

	if($res[0]['extra_field'] === 'steam_link_removed'){
		echo json_encode([
			'offset' => $offset,
			'count' => $count,
			'res' => $res[0],
			'resp' => 'steam_link_removed',
			'ERRORS' => $_ERRORS,
		]);
		return;
	}

	//=============================================================================
	$ebay_id = $res[0]['ebay_id'];
	$games_id = $res[0]['id'];

	$steam_de = arrayDB("SELECT steam_de.*,steam.usk_links as pegi_links,steam.usk_age as pegi_age 
						FROM steam_de
						LEFT JOIN steam
						ON steam_de.link = steam.link
						WHERE steam_de.link = (select steam_link from games where ebay_id = '$ebay_id' limit 1) LIMIT 1");
	if ($steam_de) {
		$steam_de = $steam_de[0];
	}else{
		echo json_encode([
			'offset' => $offset,
			'count' => $count,
			'res' => $res[0],
			'resp' => 'no steam_de',
			'ERRORS' => $_ERRORS,
		]);
		return ['success' => 0, 'resp' => 'No steam info!'];
	}

	$specifics = build_item_specifics_array($steam_de);

	$ebayObj = new Ebay_shopping2();
	$resp = $ebayObj->UpdateCategorySpecifics($ebay_id, $specifics);

	if (isset($resp['Ack']) && $resp['Ack'] !== 'Failure') {
		arrayDB("UPDATE games SET extra_field = 'steam_link_removed' WHERE id = '$games_id'");
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

<h3>remove steam links</h3>
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
	$.post('ajax.php?action=devzone/remove-steam-link',
		{action:'iterate', offset:offset},
		function (data) {
			if (offset < data.count && offset < 20000) {
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