<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');
	$offset = (int)$_POST['offset'];


	$where = "WHERE ebay_id <> '' AND id > 943";

	// исправляем 2 позиции
	// $where = "WHERE ebay_id IN ('253286585358','253453548060')";

	$count = arrayDB("SELECT count(*) FROM cdvet $where")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM cdvet $where LIMIT $offset , 1");
	//=============================================================================
	// sript below

	$extra_field_mark = 'specs_upadated3';

	if(!$res || $res[0]['extra_field'] === $extra_field_mark){
		echo json_encode([
			'offset' => $offset,
			'count' => $count,
			'res' => $res[0],
			'resp' => $extra_field_mark,
			'check' => '',
			'ERRORS' => $_ERRORS,
		]);
		return;
	}

	$ebay_id = $res[0]['ebay_id'];
	$cdvet_id = $res[0]['id'];
	$shop_id = $res[0]['shop_id'];

	// $item_info = getSingleItem($ebay_id, ['as_array'=>true,'IncludeSelector'=>'Description']);

	// $description = $item_info['Item']['Description'];

	// $title = 'cdvet description backup';
	// $full_desc = _esc($description);
	// arrayDB("INSERT INTO ebay_data 
	// 	(ebay_id,title,full_desc)
	// 	VALUES
	// 	('$ebay_id','$title','$full_desc')");

	//----------------------------------------------------------------------------

	$cd_arr = json_decode(file_get_contents('csv/eBayArtikel.json'), true);
	$cd_arr = array_column($cd_arr, null, 'A');

	$cdvet_feed = json_decode(file_get_contents('csv/cdvet_feed.json'), true);

	if(isset($cd_arr[$shop_id])) $row = $cd_arr[$shop_id];
	else{
		echo json_encode(['resp' => 'There are no excel info!',
		  'text_resp' => '<pre>There are no excel info!</pre>',
		  'ERRORS' => $_ERRORS]);
		return;
	}
	if (substr_count($row['I'], '<div>') === 1) $check = '';
	else $check = ' | Check!!!';

	$categories = json_decode(file_get_contents('csv/eBayArtikel_s2.json'), true);

    $sorted_cats = Cdvet::cd_ebay_cat_sort($categories);
    $cat_ids = Cdvet::get_ebay_cat($row['L'], $sorted_cats);

	$ustr = str_replace('.', ',', $cdvet_feed[$row['A']][8]) . $cdvet_feed[$row['A']][7];
	preg_match('/(\d*\.?\d+)\s?([^\s]+)/', str_replace(',', '.', $ustr), $unit_mathes);
	$units = Cdvet::get_units($unit_mathes);

	$ItemSpecifics = [
			'EAN' => $row['K'],
			'Zusammensetzung' => insert_comas(Cdvet::get_zusammen($row['I'])),
			'Analytische Bestandteile und Gehalte' => insert_comas(Cdvet::get_gehalte($row['I'])),
			'Kurzbeschreibung' => $row['H'] ? insert_comas($row['H']) : '', // вставить запятые
			'Zweck' => Cdvet::get_zweck($cat_ids), // тут название категории
			'Formulierung' => '', // подумать как выделить
			'geeignet für' => Cdvet::get_geeignet($cat_ids), // "предназначен для" (Кошки, Собаки)
			'Herstellungsland und -region' => 'Deutschland',
			'Marke' => 'cdVet',
			'Maßeinheit' => $units['UnitType'], // UnitType
			'Anzahl der Einheiten' => $units['UnitQuantity'], // UnitQuantity
		];

	$ItemSpecifics = array_filter($ItemSpecifics);
	$ItemSpecifics = array_map(function ($el){
		return html_entity_decode($el);
	}, $ItemSpecifics);

	if(@$ItemSpecifics['Kurzbeschreibung'])
		$ItemSpecifics['Kurzbeschreibung'] = explode(',', $ItemSpecifics['Kurzbeschreibung']);

	if(@$ItemSpecifics['Zusammensetzung'])
		$ItemSpecifics['Zusammensetzung'] = explode(',', $ItemSpecifics['Zusammensetzung']);

	if(@$ItemSpecifics['Analytische Bestandteile und Gehalte'])
		$ItemSpecifics['Analytische Bestandteile und Gehalte'] = explode(',', $ItemSpecifics['Analytische Bestandteile und Gehalte']);

	$ItemSpecifics['Zweck'] = cut_text($ItemSpecifics['Zweck'], 65);
	$ItemSpecifics['geeignet für'] = explode(',', $ItemSpecifics['geeignet für']);

	//----------------------------------------------------------------------------


	$resp = Cdvet::updateItemSpecifics($ebay_id, $ItemSpecifics);
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
		'check' => $check,
		'ERRORS' => $_ERRORS,
	]);
}


// sa($_ERRORS);

if($_POST) return;
?>
<style>
	
</style>

<h3>update cdvet specs</h3>
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
				it_ins_msg(offset + ' : <a href="https://www.ebay.de/itm/'+data.resp.ItemID+'" target="_blank">' + data.res.title + '</a> | ' + add + data.check);
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