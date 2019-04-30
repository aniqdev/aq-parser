<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');
	$offset = (int)$_POST['offset'];


	$where = "WHERE extra_field2 LIKE 'sub%'";
	$where = '';

	$count = arrayDB("SELECT count(*) FROM cdvet $where")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM cdvet $where LIMIT $offset , 1");
	//=============================================================================
	// sript below

	$extra_field = 'extra_field';
	$extra_field_mark = 'units_fixed';

	if(!$res || $res[0][$extra_field] === $extra_field_mark){
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

	$specs = parse_item_specifics($ebay_id);

	if (count($specs) < 3) {
		echo json_encode([
			'offset' => $offset,
			'count' => $count,
			'res' => $res[0],
			'resp' => 'NO Specs!',
			'$specs' => $specs,
			'ERRORS' => $_ERRORS,
		]);
		return;
	}

	$title = 'cdvet specs backup';
	$full_desc = _esc(implode('|', $specs));
	arrayDB("INSERT INTO ebay_data 
		(ebay_id,title,full_desc)
		VALUES
		('$ebay_id','$title','$full_desc')");

	//----------------------------------------------------------------------------

	unset($specs['Artikelzustand']);

	function do_units($shop_id, &$specs)
	{
		$cdvet_feed = json_decode(file_get_contents('csv/cdvet_feed.json'), true);
		$ustr = str_replace('.', ',', $cdvet_feed[$shop_id][8]) . $cdvet_feed[$shop_id][7];
		preg_match('/(\d*\.?\d+)\s?([^\s]+)/', str_replace(',', '.', $ustr), $unit_mathes);
		$units = Cdvet::get_units($unit_mathes);
		$specs['Maßeinheit'] = $units['UnitType']; // UnitType
		$specs['Anzahl der Einheiten'] = $units['UnitQuantity']; // UnitQuantity
	}
	do_units($shop_id, $specs);

	$specs['Herstellernummer'] = $shop_id;

	//----------------------------------------------------------------------------


	$resp = Cdvet::updateItemSpecifics($ebay_id, $specs);
	unset($resp['Fees']);

	if (isset($resp['Ack']) && $resp['Ack'] !== 'Failure') {
		arrayDB("UPDATE cdvet SET $extra_field = '$extra_field_mark' WHERE id = '$cdvet_id'");
	}


	//=============================================================================
	echo json_encode([
		'offset' => $offset,
		'count' => $count,
		'res' => $res[0],
		'resp' => $resp,
		'$specs' => $specs,
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