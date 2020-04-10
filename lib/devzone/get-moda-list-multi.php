<?php

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');



	if ($_POST['btn'] === 'restart') {
		arrayDB("UPDATE moda_cats SET page = 0, done = 0, err = ''");
	}

	$_GET['dich'] = [];

	$_GET['resps'] = [];

	$multi_curl = new \Curl\MultiCurl();

	$multi_curl->setOpt(CURLOPT_TIMEOUT , 140);

	$multi_curl->success(function($instance) {

		$res = json_decode($instance->response,1);

		$res = clean_result($res);

		$updated = $inserted = 0;

		$categoryId = $_GET['dich'][$instance->url]['categoryId'];

		if ($categoryId
			&& $res['findItemsAdvancedResponse']['ack'] === 'Success' 
			&& isset($res['findItemsAdvancedResponse']['searchResult']['item'][0]) 
			&& count($res['findItemsAdvancedResponse']['searchResult']['item']) > 0) {

			foreach ($res['findItemsAdvancedResponse']['searchResult']['item'] as $item) {

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
							currentPrice = '{$item['sellingStatus']['currentPrice']['__value__']}',
							sellingState = '{$item['sellingStatus']['sellingState']}',
							timeLeft = '{$item['sellingStatus']['timeLeft']}',
							startTime = '{$item['listingInfo']['startTime']}',
							endTime = '{$item['listingInfo']['endTime']}'";

				$check = arrayDB("SELECT id FROM moda_list WHERE itemId = '$item[itemId]' LIMIT 1");
				if ($check) {
					$sql_query = "UPDATE moda_list SET $set_list WHERE itemId = '$item[itemId]'";
					$updated += 1;
				}else{
					$sql_query = "INSERT INTO moda_list SET $set_list";
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
		$_GET['resps'][$instance->url] = [
			'finish' => 0,
			'$updated' => $updated,
			'$inserted' => $inserted,
			'$categoryId' => $categoryId,
			'res' => @$res['findItemsAdvancedResponse']['ack'],
			'title' => $title,
			'sql_query' => $sql_query,
			'itm_link' => $res['findItemsAdvancedResponse']['itemSearchURL'],
		];

	});

	$multi_curl->error(function($instance) {
		global $_ERRORS;
		$_ERRORS[] = 'THAT WAS multi_curl ERROR!!!';
	    $_ERRORS[] = $instance->errorMessage;
	});


	$cats = arrayDB("SELECT * from moda_cats where type = 'women' AND done = 0 LIMIT 5");

	if (!$cats) {
		echo json_encode([
			'finish' => 1,
			'ERRORS' => $_ERRORS,
		]);
		return;
	}

	// for ($offs=0; $offs < 701; $offs += 100) { 
	// 	$url = get_google_url($word, $offs);
	// 	$multi_curl->addGet($url);
	// }

	foreach ($cats as $key => $cat) {

		$page = $cat['page'];

		$categoryId = $cat['CategoryID'];

		$url = Ebay_shopping2::findItemsAdvanced_moda_url($categoryId, ++$page, $perPage = 100);

		$_GET['dich'][$url]['categoryId'] = $categoryId;

		$multi_curl->addGet($url);
	}

	$multi_curl->start();

	$_GET['resps']['finish'] = count($_GET['resps']) ? 0 : 1;
	$_GET['resps']['$cats'] = $cats;
	$_GET['resps']['ERRORS'] = $_ERRORS;

	echo json_encode($_GET['resps']);
	//=============================================================================
	// sript below

}


// sa($_ERRORS);

if($_POST) return;
?>
<style>
	
</style>

<h3>update cdVet Specifics</h3>
<form id="js_go_form" class="go-form">
    <button name="aaa" value="continue" type="button" class="js-go-btn">Continue!</button>
    <button name="aaa" value="restart" type="button" class="js-go-btn">Restart!</button>
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
var first_row = 0; // first row
var row_limit = 1000; // row limit
function send_post(offset, btn) {
	$.post('ajax.php' + window.location.search,
		{action:'iterate', offset:offset, btn:btn},
		function (data) {
			if (!data.finish) {
				it_ins_msg(offset + ' : <a href="'+data.itm_link+'" target="_blank">' + data.title + '</a>');
				send_post(offset+1, 'continue');
			}else{
				$('.loading').removeClass('inaction');
				it_ins_msg('Done!');
				it_ins_msg( "или что-то пошло не так" );
			}
		}, 'json');
}
$('.js-go-btn').on('click', function() {
	$(this).attr('disabled','true');
	var btn = $(this).val()
	send_post(first_row, btn);
});
</script>





<?php






function cr_ccallback($item)
{
	if (is_array($item) && isset($item[0]) && count($item) === 1) {
		// var_dump($item[0]);
		return $item[0];
	}elseif (is_array($item)) {
		return array_map('cr_ccallback', $item);
		return $item;
	}else{
		return $item;
	}
}


function clean_result($res = [])
{
	if(!$res) return $res;
	$res = array_map('cr_ccallback', $res);
	$res = array_map('cr_ccallback', $res);
	$res = array_map('cr_ccallback', $res);
	$res = array_map('cr_ccallback', $res);
	$res = array_map('cr_ccallback', $res);
	return $res;
}