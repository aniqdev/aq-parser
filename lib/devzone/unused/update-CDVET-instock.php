<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');
	$offset = (int)$_POST['offset'];


	$where = "WHERE ebay_id <> ''";

	$count = arrayDB("SELECT count(*)
from cdvet_feed
left join (select shop_id,ebay_id,ebay_title from cdvet_checker_log group by ebay_id) tt
on cdvet_feed.shop_id = tt.shop_id
where instock = 'outofstock'")[0]['count(*)'];

	$res = arrayDB("SELECT title,cdvet_feed.shop_id,ebay_id,ebay_title,instock
from cdvet_feed
left join (select shop_id,ebay_id,ebay_title from cdvet_checker_log group by ebay_id) tt
on cdvet_feed.shop_id = tt.shop_id
where instock = 'outofstock' LIMIT $offset , 1");
	//=============================================================================
	// sript below


	$ebay_id = $res[0]['ebay_id'];


	$resp = Cdvet::removeFromSale($ebay_id);
	unset($resp['Fees']);


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

<h3>remove outOfStock from sale</h3>
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
				if (data.resp && data.resp.Ack) var add = data.resp.Ack;
				else var add = data.resp;
				it_ins_msg(offset + ' : <a href="https://www.ebay.de/itm/'+data.res.ebay_id+'" target="_blank">' + data.res.title + '</a> | ' + add);
				send_post(offset+1);
			}else{
				$('.loading').removeClass('inaction');
				it_ins_msg('Done!');
				it_ins_msg( "или что-то пошло не так" );
			}
			$('#message a').pickText('Parasitenabwehr');
		}, 'json');
}
$('.js-go-btn').on('click', function(){
	$(this).attr('disabled','true');
	send_post(0);
});
</script>