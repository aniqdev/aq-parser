<?php ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.

use \Curl\MultiCurl;

$_GET['no_unic'] = [];
function unique_multidim_array($array, $key) { 
    $temp_array = array();
    $key_array = array(); 
    
    foreach($array as $k => $val) { 
        if (!in_array($val[$key], $key_array)) { 
            $key_array[] = $val[$key]; 
            $temp_array[$k] = $val; 
        }else{
        	$_GET['no_unic'][$k] = $val;
        } 
    } 
    return $temp_array; 
} 

function get_complited_list()
{
	global $_ERRORS;

	// Requests in parallel with callback functions.
			// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$multi_curl = new MultiCurl();
	$multi_curl->setOpt(CURLOPT_TIMEOUT , 180);

	$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
	    'X-EBAY-API-DEV-NAME: c1f2f124-1232-4bc4-bf9e-8166329ce649',
	    'X-EBAY-API-APP-NAME: Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
	    'X-EBAY-API-CERT-NAME: PRD-ae576df59071-a52d-4e1b-8b78-9156',
		"X-EBAY-API-CALL-NAME: GetSellerList",
		"X-EBAY-API-SITEID: 77",
		"Content-Type: text/xml");
	$multi_curl->setHeader('X-EBAY-API-COMPATIBILITY-LEVEL','967');
	$multi_curl->setHeader('X-EBAY-API-DEV-NAME','c1f2f124-1232-4bc4-bf9e-8166329ce649');
	$multi_curl->setHeader('X-EBAY-API-APP-NAME','Konstant-Projekt1-PRD-bae576df5-1c0eec3d');
	$multi_curl->setHeader('X-EBAY-API-CERT-NAME','PRD-ae576df59071-a52d-4e1b-8b78-9156');
	$multi_curl->setHeader('X-EBAY-API-CALL-NAME','GetSellerList');
	$multi_curl->setHeader('X-EBAY-API-SITEID','77');
	$multi_curl->setHeader('Content-Type','text/xml');

	// MultiCurl::setHeader($key, $value)
	// MultiCurl::setHeaders($headers)
	// $multi_curl->setOpt(CURLOPT_SSL_VERIFYPEER , 0);
	// $multi_curl->setOpt(CURLOPT_FOLLOWLOCATION , 1);
	//$_GET['counter'] = 0;
	
	$multi_curl->success(function($instance) {
		// $instance->url
		// $instance->response
		$res = json_decode(json_encode($instance->response),true);

		if(!isset($res['ItemArray']['Item'][0])) $res['ItemArray']['Item'] = [$res['ItemArray']['Item']];
		foreach ($res['ItemArray']['Item'] as $item) {
			$_GET['item_arr'][$item['ItemID']] = ['t' => $item['Title'],
												  's' => $item['SellingStatus']['ListingStatus']];
		}
		if ($_GET['i'] === 1) $_GET['PaginationResult'] = $res['PaginationResult'];
		$_GET['i']++;
	});
	$multi_curl->error(function($instance) {
		global $_ERRORS;
	    $_ERRORS[] = $instance->errorMessage;
	});

	$api_url = 'https://api.ebay.com/ws/api.dll';

	//=================== get completed ===================
	$_GET['i'] = 1;
	$post_data = EbayGigGames::setTokenByName($_POST['plattform'])
		->GetSellerListRequestPostData_Completed($page=1, $entires=200);
	$multi_curl->addPost($api_url, $post_data);
	$multi_curl->start(); // Blocks until all items in the queue have been processed.

	$pages = $_GET['PaginationResult']['TotalNumberOfPages'];

	for ($i=2; $i <= $pages; $i++) {
		$post_data = EbayGigGames::setTokenByName($_POST['plattform'])
			->GetSellerListRequestPostData_Completed($i, 200);
		$multi_curl->addPost($api_url, $post_data);
	}
	$multi_curl->start(); // Blocks until all items in the queue have been processed.
	$completed_data = $_GET['item_arr'];
	unset($_GET['item_arr']);
	//=================== /get completed ===================

	//=================== get active ===================
	$_GET['i'] = 1;
	$post_data = EbayGigGames::setTokenByName($_POST['plattform'])
		->GetSellerListRequestPostData_Active($page=1, $entires=200);
	$multi_curl->addPost($api_url, $post_data);
	$multi_curl->start(); // Blocks until all items in the queue have been processed.

	$pages = $_GET['PaginationResult']['TotalNumberOfPages'];

	for ($i=2; $i <= $pages; $i++) {
		$post_data = EbayGigGames::setTokenByName($_POST['plattform'])
			->GetSellerListRequestPostData_Active($i, 200);
		$multi_curl->addPost($api_url, $post_data);
	}
	$multi_curl->start(); // Blocks until all items in the queue have been processed.
	$active_data = $_GET['item_arr'];
	unset($_GET['item_arr']);
	//=================== /get active ===================

	$_GET['tits'] = [];
	foreach ($active_data as $item) if($item['s'] === 'Active') $_GET['tits'][] = $item['t'];

	$completed_arr = array_filter($completed_data,function($item)
	{
		return ($item['s'] === 'Completed' && !in_array($item['t'], $_GET['tits']));
	});
	$completed_arr = unique_multidim_array($completed_arr, 't');

	$list_items_arr = []; $n = 1;
	foreach ($completed_arr as $key => $value) {
		$list_items_arr[] = '<li class="list-group-item js-relist-item" data-itemid="'.$key.'">'.($n++).'. 
		    		<a target="_blank" class="js-relist-link">'.$value['t'].'</a>
		    	</li>';
	}

	$counts = [
			'completed_arr' => count($completed_arr),
			'active_data' => count($active_data),
			'completed_data' => count($completed_data),
		];

	echo json_encode([
		'get_i' => $_GET['i'],
		'completed_arr' => $completed_arr,
		'active_data' => $active_data,
		'completed_data' => $completed_data,
		'list_items_arr' => $list_items_arr,
		'tits' => $_GET['tits'],
		'no_unic' => $_GET['no_unic'],
		'PaginationResult' => $_GET['PaginationResult'],
		'counts' => $counts,
		'notice' => '<small>⟱ check please info below ⟱</small><br>
					Active items: <b>'.$counts['active_data'].'</b><br>
					Complited items: <b>'.$counts['completed_data'].'</b><br>
					Items to relist: <b>'.$counts['completed_arr'].'</b>',
		'errors' =>  $_ERRORS,
	]);
}

function do_relist()
{
	global $_ERRORS;
	if(!$_POST['plattform'] || !$_POST['item_id']) return;
	sleep(1);
	$old_ebay_id = $_POST['item_id'];
	if ($_POST['plattform'] === 'gig-games') {
		$resp = EbayGigGames::setTokenByName('gig-games')
				->RelistItemRequest($_POST['item_id']);
		unset($resp['Fees']);

		if (isset($resp['Ack']) && $resp['Ack'] !== 'Failure') {
			$new_ebay_id = $resp['ItemID'];
			arrayDB("UPDATE games SET 
				ebay_id = $new_ebay_id,
				relisted_at = now()
				WHERE ebay_id = '$old_ebay_id'");
		}
	}
	if ($_POST['plattform'] === 'cdvet') {
		$resp = EbayGigGames::setTokenByName('cdvet')
				->RelistItemRequest($_POST['item_id']);
		unset($resp['Fees']);
				
		if (isset($resp['Ack']) && $resp['Ack'] !== 'Failure') {
			$new_ebay_id = $resp['ItemID'];
			arrayDB("UPDATE games SET 
				ebay_id = $new_ebay_id,
				relisted_at = now()
				WHERE ebay_id = '$old_ebay_id'");
		}
	}

	$title = _esc($_POST['title']);
	$response_json = _esc(json_encode($resp));
	$plattform = _esc($_POST['plattform']);
	arrayDB("INSERT INTO relist_report SET title = '$title',
			old_ebay_id = '$old_ebay_id',
			new_ebay_id = '$new_ebay_id',
			response_json = '$response_json',
			plattform = '$plattform'");

	echo json_encode([
		'post' => $_POST,
		'resp' => $resp,
		'report' => '',
		'errors' =>  $_ERRORS,
	]);	
}

if (isset($_POST['function']) && $_POST['function'] === 'get_complited_list') {
	get_complited_list();
	return;
} elseif (isset($_POST['function']) && $_POST['function'] === 'do_relist') {
	do_relist();
	return;
}

?>
<style>
.list-group-item{background-color: transparent;}
.bg-grey{background: grey;}
.bg-green{background: green;}
.bg-red{background: red;}
#js_notice{margin-top: -60px;}
</style>

<div class="container">
	<h2>Relist items</h2>
	<div class="row">
		<div class="col-sm-3">
			<select class="form-control" id="plattform_select">
			    <option value="gig-games">gig-games</option>
			    <option value="cdvet">cdVet</option>
			</select>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-sm-6">
			<button class="btn btn-success" id="get_complited_list">get list</button>
			<button class="btn btn-primary" id="do_relist">relist</button>
		</div>
		<div class="col-sm-6">
			<div id="js_notice"></div>
		</div>
	</div>
	<br><br>
	<ul class="list-group" id="item_list"></ul>
</div>

<script>
var item_list = $('#item_list');
var n = 1;
document.all.get_complited_list.onclick = function() {
	$(this).attr('disabled','disabled');
	$.post('/ajax.php?action=relist', 
		{function:'get_complited_list',plattform:document.all.plattform_select.value},
		function(data) {
		    $('#js_notice').html(data.notice);
			$.each( data.list_items_arr, function( key, list_item ) {
		    	item_list.append(list_item);
			});
	},'json');
}

var n = 0;
function do_relist(list_item) {
	$(list_item).addClass('bg-grey');
	var item_id = list_item.dataset.itemid;
	var link_elem = $(list_item).find('.js-relist-link')[0];
	var title = $(link_elem).text();
	console.log(item_id);
	$.post('/ajax.php?action=relist', 
		{function:'do_relist',
		plattform:document.all.plattform_select.value,
		item_id:item_id,
		title:title},
		function(data) {
			if (data.resp.Ack && data.resp.Ack !== 'Failure') {
				$(list_item).addClass('bg-green');
			}
			if (data.resp.Ack && data.resp.Ack === 'Failure') {
				$(list_item).addClass('bg-red');
			}
			link_elem.href = 'https://ebay.de/itm/'+data.resp.ItemID;

			// if(n > 1) return;
			// рекурсия
			var new_list_item = $('.js-relist-item')[n++];
			if (new_list_item) do_relist(new_list_item);
	},'json');
}
document.all.do_relist.onclick = function(){
	$(this).attr('disabled','disabled');
	var list_item = $('.js-relist-item')[n++];
	if (list_item) do_relist(list_item);
}
</script>