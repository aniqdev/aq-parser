<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');

	$offset = (int)$_POST['offset'];
	$table = 'eve_corporations';
	$where = "WHERE flag <> 'done1'";

	if($offset == 0) arrayDB("UPDATE $table SET flag = 'done1' WHERE flag = 'done2'");

	$count = arrayDB("SELECT count(*) FROM $table $where")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM $table $where LIMIT $offset , 1");


	//=============================================================================
	$id = $res[0]['id'];
	$corporation_id = $res[0]['corporation_id'];
	$flag = $res[0]['flag'];

	if ($flag === 'done1') {

		$resp = 'Done ('.$res[0]['name'].')';
		
	}else{

		$url = "https://esi.evetech.net/latest/corporations/$corporation_id/";

		$corp_info_json = file_get_contents($url);

		$corp_info = json_decode($corp_info_json, true);

		foreach ($corp_info as &$val) {
			$val = _esc($val);
		}

		arrayDB("UPDATE $table SET
				name = '$corp_info[name]',
				ceo_id = '$corp_info[ceo_id]',
				creator_id = '$corp_info[creator_id]',
				description = '$corp_info[description]',
				home_station_id = '$corp_info[home_station_id]',
				member_count = '$corp_info[member_count]',
				shares = '$corp_info[shares]',
				tax_rate = '$corp_info[tax_rate]',
				ticker = '$corp_info[ticker]',
				url = '$corp_info[url]',
				war_eligible = '$corp_info[war_eligible]',
				date_founded = '$corp_info[date_founded]',
				flag = 'done2'
			WHERE id = '$id'");

		$resp = 'Saved "'.$corp_info['name'].'"';

	}

	//=============================================================================


	echo json_encode([
		'offset' => $offset,
		'count' => $count,
		'res' => $res[0],
		'resp' => $resp,
		// '$corp_info_json' => $corp_info_json,
		// '$corp_info' => $corp_info,
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