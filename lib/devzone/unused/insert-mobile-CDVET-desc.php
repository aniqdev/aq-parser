<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');
	$offset = (int)$_POST['offset'];


	$where = "";

	$count = arrayDB("SELECT count(*) FROM cdvet $where")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM cdvet $where LIMIT $offset , 1");
	//=============================================================================
	// sript below


	$extra_field_mark = 'mobile_desced1';

	if($res[0]['extra_field'] === $extra_field_mark || !$res){
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
	$shop_id = $res[0]['shop_id'];

	$item_info = getSingleItem($ebay_id, ['as_array'=>true,'IncludeSelector'=>'Description']);

	$description = $item_info['Item']['Description'];

	if (!$description) {
		echo json_encode([
			'offset' => $offset,
			'count' => $count,
			'res' => $res[0],
			'resp' => 'NO Description!',
			'ERRORS' => $_ERRORS,
		]);
		return;
	}

	$title = 'cdvet description backup';
	$full_desc = _esc($description);
	arrayDB("INSERT INTO ebay_data 
		(ebay_id,title,full_desc)
		VALUES
		('$ebay_id','$title','$full_desc')");

	//----------------------------------------------------------------------------

	$top_desc = trim(str_get_html($description)->find('.cv-desc-top', 0)->innertext);

	$top_desc = str_replace(['</div>','</p>','&nbsp;'], ['</div><br>','</p><br>',' '], $top_desc);

	$top_desc = strip_tags($top_desc, '<ol><ul><li><br><br/>');

	$top_desc = trim(preg_replace('/<br>$/', '', $top_desc));
	$top_desc = trim(preg_replace('/<br>$/', '', $top_desc));

	$top_desc = str_replace(['<br>','<br/>'], '<br>', $top_desc);

	$top_desc = str_replace("\r\n", ' ', $top_desc);

	$top_desc = preg_replace('/\s{2,}/', ' ', $top_desc);

	$top_desc = str_replace(['<br><br>','<br> <br>'], '<br>', $top_desc);

	str_replace(['<br>','<br/>'], '<br>', $top_desc, $br_count);
	// каждый <br> стоит 50 символов
	$char_limit = 750 - ($br_count * 5);
	// $char_limit = 750;
	if (strlen($top_desc) > $char_limit) {
		$top_desc = strip_tags($top_desc);
		if (strlen($top_desc) > $char_limit) {
			$top_desc = substr($top_desc, 0, $char_limit);
		}
	}

	if (!$top_desc) {
		echo json_encode([
			'offset' => $offset,
			'count' => $count,
			'res' => $res[0],
			'resp' => 'NO top_desc!',
			'ERRORS' => $_ERRORS,
		]);
		return;
	}

	$description = '
<!-- mobile_desc -->
<div vocab="https://schema.org/" typeof="Product" style="display:none;">
	<span property="description">'.$top_desc.'</span>
</div>
<!-- /mobile_desc -->
'.$description;

	//----------------------------------------------------------------------------


	$resp = Cdvet::updateItemDescription($ebay_id, $description);
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
		'$top_desc' => $top_desc,
		'ERRORS' => $_ERRORS,
	]);
}


// sa($_ERRORS);

if($_POST) return;
?>
<style>
	
</style>

<h3>update cdvet description</h3>
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
var row_limit = 1000; // row limit
function send_post(offset) {
	$.post('ajax.php' + window.location.search,
		{action:'iterate', offset:offset},
		function (data) {
			if (offset < data.count && offset < row_limit) { // row limit
				if (data.resp && data.resp.Ack) 	var add = data.resp.Ack;
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
	send_post(first_row);
});
</script>