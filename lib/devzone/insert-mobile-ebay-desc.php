<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');
	$offset = (int)$_POST['offset'];

	$where = "WHERE ebay_id <> '' and steam_link <> ''";

	$count = arrayDB("SELECT count(*) FROM games $where")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM games $where LIMIT $offset , 1");

	$ebay_id = $res[0]['ebay_id'];
	$games_id = $res[0]['id'];
	$steam_link = $res[0]['steam_link'];
	$watermark = 'mobile_desc1';

	if($res[0]['extra_field'] === $watermark){
		echo json_encode([
			'offset' => $offset,
			'count' => $count,
			'res' => $res[0],
			'resp' => $watermark,
			'ebay_id' => $ebay_id,
			'ERRORS' => $_ERRORS,
		]);
		return;
	}
	//=============================================================================

	$item_info = getSingleItem($ebay_id, ['as_array'=>true,'IncludeSelector'=>'Description']);

	$description = $item_info['Item']['Description'];

	$title = 'description backup';
	$full_desc = _esc($description);
	arrayDB("INSERT INTO ebay_data 
		(ebay_id,title,full_desc)
		VALUES
		('$ebay_id','$title','$full_desc')");

	$steam_desc = arrayDB("SELECT `desc` FROM steam_de WHERE link = '$steam_link'");
	$steam_desc = $steam_desc[0]['desc'];

	if (!$steam_desc) {
		arrayDB("UPDATE games SET extra_field = 'no_steam_desc' WHERE id = '$games_id'");
		echo json_encode([
			'offset' => $offset,
			'count' => $count,
			'res' => $res[0],
			'resp' => 'no steam desc',
			'ebay_id' => $ebay_id,
			'$steam_link' => $steam_link,
			'ERRORS' => $_ERRORS,
		]);
		return;
	}

	$steam_desc = trim(preg_replace('/<h2>.+?<\/h2>/', '', $steam_desc, 1));
	$steam_desc = strip_tags($steam_desc, '<ol><ul><li><br><br/>');

	$char_limit = 700;
	if (strlen($steam_desc) > $char_limit) {
		$steam_desc = strip_tags($steam_desc);
		if (strlen($steam_desc) > $char_limit) {
			$steam_desc = substr($steam_desc, 0, $char_limit);
		}
	}

	$description = '<div vocab="https://schema.org/" typeof="Product" style="display:none;">
<span property="description">'.$steam_desc.'</span>
</div>
'.$description;

	$ebayObj = new Ebay_shopping2();

	if(strlen($description) > 50000){
		$resp = $ebayObj->updateItemDescription($ebay_id, $description);
		unset($resp['Fees']);
	}else {
		$resp = 'old description!';
		arrayDB("UPDATE games SET extra_field = 'old_description' WHERE id = '$games_id'");
	}

	if (isset($resp['Ack']) && $resp['Ack'] !== 'Failure') {
		arrayDB("UPDATE games SET extra_field = '$watermark' WHERE id = '$games_id'");
	}
	//=============================================================================

	echo json_encode([
		'offset' => $offset,
		'count' => $count,
		'ebay_id' => $ebay_id,
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

<h3>Remove forms</h3>
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
			if (offset < data.count && offset < 6000) {
				if (data.resp && data.resp.Ack) 	var add = data.resp.Ack;
				else var add = data.resp;
				it_ins_msg(offset + ' : <a href="https://www.ebay.de/itm/'+data.ebay_id+'" target="_blank">' + data.res.name + '</a> | ' + add);
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
	send_post(2740);
});
</script>