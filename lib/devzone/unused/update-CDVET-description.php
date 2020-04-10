<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');
	$offset = (int)$_POST['offset'];


	$where = "WHERE ebay_id <> ''";

	$count = arrayDB("SELECT count(*) FROM cdvet $where")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM cdvet $where LIMIT $offset , 1");
	//=============================================================================
	// sript below


	$extra_field_mark = 'desc_upadated4';

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
	$shop_id = $res[0]['shop_id'];

	$item_info = getSingleItem($ebay_id, ['as_array'=>true,'IncludeSelector'=>'Description']);

	$description = $item_info['Item']['Description'];

	$title = 'cdvet description backup';
	$full_desc = _esc($description);
	arrayDB("INSERT INTO ebay_data 
		(ebay_id,title,full_desc)
		VALUES
		('$ebay_id','$title','$full_desc')");

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

	if (substr_count($row['I'], '<div>') === 1) {

		$row['I'] = preg_replace('/http[^ <\r\n]+\//', '', $row['I']);

		$row['I'] = preg_replace('/ style="margin:\s?(\d{1,2}pt\s?){4};\s?line-height:.+?;\s?"/', '', $row['I']);
		$row['I'] = preg_replace('/ style="font-family:[^;]+?;\s?font-size:\s?\d\.?\dpt;\s?color:\s?#000000;\s?"/', '', $row['I']);
		$row['I'] = preg_replace('/font-family:[^;]+?;\s?font-size:\s?\d\.?\dpt;\s?color:\s?#000000;\s?/', '', $row['I']);
		$row['I'] = preg_replace('/font-family:[^;]+?;\s?font-size:\s?\d\.?\dpt;\s?/', '', $row['I']);
		
		$row['I'] = preg_replace('/<span>(.+?)<\/span>/', '${1}', $row['I']);
		$row['I'] = str_replace(['<div>','</div>'], '', $row['I']);
		if(stripos($row['I'], 'Zusammensetzung') !== false){
			preg_match('/(.*)(<p[^\/]+Zusammensetzung.*)/s', $row['I'], $zus_matches);
			
		}elseif(stripos($row['I'], 'Inhaltstoffe') !== false){
			preg_match('/(.*)(<p[^\/]+Inhaltstoffe.*)/s', $row['I'], $zus_matches);
		}
	}else{
		$row['I'] = strip_tags($row['I'], '<u><p><a><div><br><br/><b><strong>');
		preg_match('/(.*)(<div[^\/]+Zusammensetzung.*)/s', $row['I'], $zus_matches);
	}
	$desc_top = isset($zus_matches[1]) ? $zus_matches[1] : $row['I'];
	$desc_bot = isset($zus_matches[2]) ? $zus_matches[2] : '';

	$img_arr = explode('|', $cdvet_feed[$shop_id][16]);

	$img_arr = array_filter($img_arr, function ($url){
		return filter_var($url, FILTER_VALIDATE_URL) and (stripos($url, '.jpg') || stripos($url, '.png'));
	});

	if (count($img_arr) < 1) {
		echo json_encode(['resp' => 'There are no chosen pictures!',
					  'text_resp' => '<pre>There are no chosen pictures!</pre>',
					  'ERRORS' => $_ERRORS]);
		return;
	}

	$item = [
		'desc_title' => 'cdVet® ' . str_ireplace('cdvet', '', $row['C']),
		'chosen_desc_pics' => $img_arr,
		'desc_top' => $desc_top,
		'desc_bot' => $desc_bot,
	];

	$description = Cdvet::prepare_description($item);

	$description = str_replace('#buybtn', 'https://offer.ebay.de/ws/eBayISAPI.dll?BinConfirm&rev=2&fromPage=2047675&item='.$ebay_id.'&quantity=1&fb=1', $description);

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
function send_post(offset) {
	$.post('ajax.php' + window.location.search,
		{action:'iterate', offset:offset},
		function (data) {
			if (offset < data.count && offset < 1000) { // row limit
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
	send_post(0);
});
</script>