<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate-list') {
	header('Content-Type: application/json');



	if ($_POST['btn'] === 'restart') {
		arrayDB("UPDATE moda_cats SET page = 0, done = 0, err = '' where type = 'hund'");
	}


	$cats = arrayDB("SELECT * from moda_cats where type = 'hund' AND done = 0 LIMIT 1");

	if (!$cats) {
		echo json_encode([
			'finish' => 1,
			'ERRORS' => $_ERRORS,
		]);
		return;
	}

	$page = $cats[0]['page'];

	$categoryId = $cats[0]['CategoryID'];

	$res = Ebay_shopping2::findItemsAdvanced_moda($categoryId, ++$page, $perPage = 100);

	$res = json_decode($res,1);

	$res = gml_clean_result($res);

	//=============================================================================
	// sript below

	$updated = $inserted = 0;

	if ($categoryId 
			&& $res['findItemsAdvancedResponse']['ack'] === 'Success' 
			&& isset($res['findItemsAdvancedResponse']['searchResult']['item'][0]) 
			&& count($res['findItemsAdvancedResponse']['searchResult']['item']) > 0) {

		foreach ($res['findItemsAdvancedResponse']['searchResult']['item'] as $item) {

			if($item['globalId'] !== 'EBAY-DE') continue;

			array_walk_recursive($item, function(&$val){ $val = _esc($val); });

			$item['subtitle'] = isset($item['subtitle']) ? $item['subtitle'] : '';

			$set_list = "itemId = '$item[itemId]',
						title = '$item[title]',
						globalId = '$item[globalId]',
						subtitle = '$item[subtitle]',
						categoryId = '{$item['primaryCategory']['categoryId']}',
						categoryName = '{$item['primaryCategory']['categoryName']}',
						galleryURL = '$item[galleryURL]',
						viewItemURL = '$item[viewItemURL]',
						location = '$item[location]',
						country = '$item[country]',
						currentPrice = '{$item['sellingStatus']['convertedCurrentPrice']['__value__']}',
						sellingState = '{$item['sellingStatus']['sellingState']}',
						timeLeft = '{$item['sellingStatus']['timeLeft']}',
						startTime = '{$item['listingInfo']['startTime']}',
						endTime = '{$item['listingInfo']['endTime']}',
						updated_at = now() - interval 5 day";

			$check = arrayDB("SELECT id FROM hund_list WHERE itemId = '$item[itemId]' LIMIT 1");
			if ($check) {
				$sql_query = "UPDATE hund_list SET $set_list WHERE itemId = '$item[itemId]'";
				$updated += 1;
			}else{
				$sql_query = "INSERT INTO hund_list SET $set_list";
				$inserted += 1;
			}
			arrayDB($sql_query);
			$title = $item['title'];
		}
		arrayDB("UPDATE moda_cats SET page = page + 1 WHERE CategoryID = '$categoryId'");

	}else{

		$err = _esc(json_encode($res));
		arrayDB("UPDATE moda_cats SET done = 1, err = '$err' WHERE CategoryID = '$categoryId'");
		$title = $sql_query = $cats[0]['CategoryName'].' | DONE!';
	}

	//=============================================================================
	echo json_encode([
		'finish' => 0,
		'$updated' => $updated,
		'$inserted' => $inserted,
		'res' => @$res['findItemsAdvancedResponse'],
		'title' => $title,
		'sql_query' => $sql_query,
		'itm_link' => $res['findItemsAdvancedResponse']['itemSearchURL'],
		'ERRORS' => $_ERRORS,
	]);
}


// sa($_ERRORS);

if($_POST) return;
?>
<style>
	
</style>
<div id="<?= js_alpha_dash(__FILE__); ?>">
	<h3><?= script_title(__FILE__); ?></h3>
	<form id="js_go_form" class="go-form">
	    <button name="aaa" value="continue" type="button" class="js-go-btn">Continue!</button>
	    <button name="aaa" value="restart" type="button" class="js-go-btn">Restart!</button>
	</form><br><br><br>
	<span class="loading"></span>
	<h3>Состояние процесса:</h3>
	<ul id="message" class="message list-unstyled"><li></li></ul>
</div>
<script>
(function() {
var js_alpha_dash = '<?= js_alpha_dash(__FILE__); ?>'
function it_ins_msg(msg) {
	$( '#'+js_alpha_dash+" #message li:first" ).before( "<li>"+msg+"</li>" );
	if($('#'+js_alpha_dash+' #message li').length > 100) {
		$('#'+js_alpha_dash+' #message li:last').remove();
	}
}
var first_row = 0; // first row
var row_limit = 1000; // row limit
function send_post(offset, btn) {
	$.post('ajax.php' + window.location.search,
		{action:'iterate-list', offset:offset, btn:btn},
		function (data) {
			if (!data.finish) {
				it_ins_msg(offset + ' : <a href="'+data.itm_link+'" target="_blank">' + data.title + '</a>');
				send_post(offset+1, 'continue');
			}else{
				$('#'+js_alpha_dash+' .loading').removeClass('inaction');
				it_ins_msg('Done!');
				it_ins_msg( "или что-то пошло не так" );
			}
		}, 'json');
}
$('#'+js_alpha_dash+' .js-go-btn').on('click', function() {
	$(this).attr('disabled','true');
	var btn = $(this).val()
	send_post(first_row, btn);
});
}())

</script>





<?php








