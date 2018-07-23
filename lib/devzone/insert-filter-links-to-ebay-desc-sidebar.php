<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');
	$offset = (int)$_POST['offset'];

	$where = "WHERE ebay_id <> ''";

	$count = arrayDB("SELECT count(*) FROM games $where")[0]['count(*)'];

	$res = arrayDB("SELECT * FROM games $where LIMIT $offset , 1");

	if($res[0]['extra_field'] === 'sidebar_filtered'){
		echo json_encode([
			'offset' => $offset,
			'count' => $count,
			'res' => $res[0],
			'resp' => 'sidebar_filtered',
			'ERRORS' => $_ERRORS,
		]);
		return;
	}
	//=============================================================================
	$ebay_id = $res[0]['ebay_id'];
	$games_id = $res[0]['id'];

	$item_info = getSingleItem($ebay_id, ['as_array'=>true,'IncludeSelector'=>'Description']);

	$description = $item_info['Item']['Description'];

	$title = 'description backup';
	$full_desc = _esc($description);
	arrayDB("INSERT INTO ebay_data 
		(ebay_id,title,full_desc)
		VALUES
		('$ebay_id','$title','$full_desc')");

$filter_link_de = file_get_contents('Files/filter_link_de.html');
$filter_link_en = file_get_contents('Files/filter_link_en.html');
$filter_link_fr = file_get_contents('Files/filter_link_fr.html');
$filter_link_es = file_get_contents('Files/filter_link_es.html');
$filter_link_it = file_get_contents('Files/filter_link_it.html');

$description = preg_replace('/(--sys-de.+?<div class="col-md-3 pos-rel">)\s+(<\/div>)/s','${1}
'.$filter_link_de.'
			</div>', $description);

$description = preg_replace('/(--sys-en.+?<div class="col-md-3 pos-rel">)\s+(<\/div>)/s','${1}
'.$filter_link_en.'
			</div>', $description);
$description = preg_replace('/(--sys-fr.+?<div class="col-md-3 pos-rel">)\s+(<\/div>)/s','${1}
'.$filter_link_fr.'
			</div>', $description);

$description = preg_replace('/(--sys-es.+?<div class="col-md-3 pos-rel">)\s+(<\/div>)/s','${1}
'.$filter_link_es.'
			</div>', $description);

$description = preg_replace('/(--sys-it.+?<div class="col-md-3 pos-rel">)\s+(<\/div>)/s','${1}
'.$filter_link_it.'
			</div>', $description);




$description = preg_replace('/\S+?ber 3500 PC-Spiele.+?splash">Keine CD\/DVD<\/div>/s', '<div class="splash">Keine CD/DVD</div><br>
				deutsches Support 24/7 <br>
				Sie erhalten digitale Aktivierungsdaten.', $description);

$description = preg_replace('/over 3500 PC games.+?No CD \/ DVD<\/div>/s', '<div class="splash">No CD / DVD</div><br>
				English support 24/7<br>
				You will receive digital activation data.', $description);

$description = preg_replace('/Plus de 3500 jeux.+?Pas de CD \/ DVD<\/div>/s', '<div class="splash">Pas de CD / DVD</div><br>
				Support allemand / anglais 24/7<br>
				Vous recevrez des données d\'activation numérique.', $description);

$description = preg_replace('/Más de 3500 juegos.+?No hay CD\/DVD<\/div>/s', '<div class="splash">No hay CD/DVD</div><br>
				Soporte inglés 24/7<br>
				Recibirá datos de activación digital.', $description);

$description = preg_replace('/Più di 3500 giochi.+?Nessun CD\/DVD<\/div>/s', '<div class="splash">Nessun CD/DVD</div><br>
				Supporto Inglese 24/7<br>
				Si ottiene l\'attivazione di dati digitali.', $description);


	$ebayObj = new Ebay_shopping2();

	if(strlen($description) > 50000){
		$resp = $ebayObj->updateItemDescription($ebay_id, $description);
		unset($resp['Fees']);
	}else {
		$resp = 'old description!';
		arrayDB("UPDATE games SET extra_field = 'old_description' WHERE id = '$games_id'");
	}

	if (isset($resp['Ack']) && $resp['Ack'] !== 'Failure') {
		arrayDB("UPDATE games SET extra_field = 'sidebar_filtered' WHERE id = '$games_id'");
	}
	//=============================================================================


	echo json_encode([
		'offset' => $offset,
		'count' => $count,
		'ebay_id' => $ebay_id,
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

<h3>Remove forms</h3>
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
			if (offset < data.count && offset < 5000) {
				if (data.resp && data.resp.Ack) 	var add = data.resp.Ack;
				else var add = data.resp;
				it_ins_msg(offset + ' : <a href="https://www.ebay.de/itm/'+data.ebay_id+'" target="_blank">' + data.res.name + '</a> | ' + add);
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