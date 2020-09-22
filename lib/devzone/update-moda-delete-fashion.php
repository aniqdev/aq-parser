<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');


	$table = 'moda_list';
	$extra_field = 'flag2';
	$extra_field_mark = 'updated4';

	$res = arrayDB("SELECT id,title FROM $table where post_id <> '0' and $extra_field <> '$extra_field_mark' LIMIT 1");
	//=============================================================================
	// sript below


	if(!$res){
		echo json_encode([
			'status' => 0,
			'message' => 'No Data!',
			'ERRORS' => $_ERRORS,
		]);
		return;
	}

	$moda_id = $res[0]['id'];

	if(is_dev()) $post_uri = 'http://koeln-webstudio.loc/moda-sync.php';
	else $post_uri = 'https://modetoday.de/moda-sync.php?wpok';

	$curl_resp = post_curl($post_uri, [
		'action' => 'delete',
		'moda_id' => $moda_id,
	]);

	if (isset($resp['Ack']) && $resp['Ack'] !== 'Failure') {
		arrayDB("UPDATE $table SET $extra_field = '$extra_field_mark' WHERE id = '$moda_id'");
	}


	//=============================================================================
	echo json_encode([
		'status' => 1,
		'curl_resp' => $curl_resp,
		'message' => $res[0]['title'],
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
function send_post() {
	$.post('ajax.php' + window.location.search,
		{action:'iterate'},
		function (data) {
			if (data.status) { // row limit
				it_ins_msg(data.message);
				send_post();
			}else{
				$('.loading').removeClass('inaction');
				it_ins_msg('Done!');
			}
		}, 'json');
}
$('.js-go-btn').on('click', function() {
	$(this).attr('disabled','true');
	send_post();
});
</script>