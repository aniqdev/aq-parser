<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');

	$offset = (int)$_POST['offset'];
	$table = 'games';
	$where = "WHERE flag <> 'done_done' AND woo_id <> ''";

	if($offset == 0) arrayDB("UPDATE $table SET flag = 'done_done' WHERE flag = 'done_temp'");

	$count = arrayDB("SELECT count(*) FROM $table $where")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM $table $where LIMIT $offset , 1");


	//=============================================================================
	$id = $res[0]['id'];
	$flag = $res[0]['extrafield'];
	$woo_id = $res[0]['woo_id'];


	$data = [
		'stock_status' => 'outofstock', // instock / outofstock
	];

	$item = post_curl('https://hot-body.net/parser/ajax-controller.php', [
		'function' => 'ajax_hot_do_woocommerce_api_request',
		'method' => 'put',
		'endpoint' => "products/$woo_id",
		'data' => $data,
	]);

	if ($item['res']['stock_status'] === 'outofstock') {
		arrayDB("UPDATE $table SET flag = 'done_temp' WHERE id = '$id'");
		$resp = 'Good "'.$res[0]['name'].'"';
	}else{
		$resp = 'Bad "'.$res[0]['name'].'"';
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