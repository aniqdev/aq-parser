<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');

	$offset = (int)$_POST['offset'];
	$table = 'games';
	$where = "WHERE flag <> 'done_done' AND steam_link <> ''";

	if($offset == 0) arrayDB("UPDATE $table SET flag = 'done_done' WHERE flag = 'done_temp'");

	$count = arrayDB("SELECT count(*) FROM $table $where")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM $table $where LIMIT $offset , 1");


	//=============================================================================
	$id = $res[0]['id'];
	$flag = $res[0]['extrafield'];
	$steam_link = $res[0]['steam_link'];

	$game = arrayDB("SELECT * FROM steam_de WHERE link = '$steam_link' LIMIT 1");

	if ($game) {
		$game = $game[0];

		$plati_ru = arrayDB("SELECT * FROM items WHERE game_id = '$id' ORDER BY id DESC LIMIT 1");

		$exrate = arrayDB("SELECT value FROM aq_settings WHERE name='exrate'");
		$dataex = $exrate[0]['value']; // 67

		if ((float)$plati_ru[0]['item1_price'] > 0) {
			$regular_price = formula($plati_ru[0]['item1_price'], $dataex);
		}else{
			$regular_price = '99';
		}

		$data = [
			'name' => $game['title'],
			'type' => 'simple',
			'regular_price' => $regular_price,
			'description' => $game['desc'],
			'short_description' => $game['specs'],
			'categories' => [['id'=>82]],
			// 'images' => [
			// 	['src' => $img_src]
			// ],
			// 'stock_status' => 'outofstock',
		];

		set_img_path($data, $game);

		if (!$plati_ru || (float)$plati_ru[0]['item1_price'] == 0) {
			$data['stock_status'] = 'outofstock';
		}
		$ret = post_curl('https://hot-body.net/parser/ajax-controller.php', [
			'function' => 'ajax_hot_do_woocommerce_api_request',
			'method' => 'post',
			'endpoint' => 'products',
			'data' => $data,
		]);
		if (isset($ret['res']['id'])) {
			$woo_id = _esc($ret['res']['id']);
			arrayDB("UPDATE $table SET flag = 'done_temp', woo_id = '$woo_id' WHERE id = '$id'");
		}
		$resp = 'Created "'.$game['title'].'"';
	}else{
		arrayDB("UPDATE $table SET flag = 'No steam_de' WHERE id = '$id'");
		$resp = 'No item on steam_de "'.$res[0]['name'].'"';
	}





	//=============================================================================


	echo json_encode([
		'offset' => $offset,
		'count' => $count,
		'res' => $res[0],
		'resp' => $resp,
		'$ret' => @$ret,
		'ERRORS' => $_ERRORS,
	]);
}


// sa($_ERRORS);

if($_POST) return;
?>
<style>
	
</style>

<div class="container">
	<h3><?= script_title(__FILE__, '.php'); ?></h3>
	<form id="js_go_form" class="go-form">
	    <button name="aaa" value="bbb" type="button" class="js-go-btn">Go!</button>
	</form><br><br><br>
	<span class="loading"></span>
	<h3>Состояние процесса:</h3>
	<ul id="message" class="message"><li></li></ul>
</div>

<script>
function it_ins_msg(msg) {
	$( "#message li:first" ).before( "<li>"+msg+"</li>" );
	if($('#message li').length > 100) {
		$('#message li:last').remove();
	}
}
var from = 0;
var to = 200000;
function send_post(offset) {
	$.post('ajax.php' + window.location.search,
		{action:'iterate', offset:offset},
		function (data) {
			if (offset < data.count && offset < to) {
				it_ins_msg(offset + ' : ' + data.resp);
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






<?php


function set_img_path(&$data, &$game)
{
	if (file_exists(get_steam_images_dir_path($game['type'], $game['appid']).'/header-80p.jpg')) {
		$img_src = get_steam_images_dir_url($game['type'], $game['appid']).'/header-80p.jpg';
		$data['images'] = [['src' => $img_src]];
	}elseif (file_exists(get_steam_images_dir_path($game['type'], $game['appid']).'/header.jpg')) {
		$img_src = get_steam_images_dir_url($game['type'], $game['appid']).'/header-80p.jpg';
		$data['images'] = [['src' => $img_src]];
	}else{

	}
}