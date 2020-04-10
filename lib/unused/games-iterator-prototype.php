<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');
	$offset = (int)$_POST['offset'];

	$count = arrayDB("SELECT count(*) FROM games")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM games LIMIT $offset , 1");
	//=============================================================================
	// sript below






	
	//=============================================================================
	echo json_encode([
		'offset' => $offset,
		'count' => $count,
		'res' => $res[0],
		'ERRORS' => $_ERRORS,
	]);
}


// sa($_ERRORS);

if($_POST) return;
?>
<style>
	
</style>

<h3>prototype iterator</h3>
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
			if (offset < data.count) {
				it_ins_msg(offset + ' : ' + data.res.name);
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