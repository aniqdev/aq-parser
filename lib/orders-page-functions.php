<?php

function op_href($state = 'l1', $order_id = '', $item_id = ''){

	switch ($state) {
		case 'l1': echo '?action=orders-page&list_type=all&active[]=act_all';
			# code...
			break;

		case 'l2': echo '?action=orders-page&list_type=paid&active[]=act_paid';
			# code...
			break;

		case 'l3': echo '?action=orders-page&list_type=shipped&active[]=act_shipped';
			# code...
			break;

		case 'open_modal': echo '?action=orders-page&list_type='.$_GET['list_type'].'&active[]='.$_GET['active'][0].'&active[]=act_modal&active[]=act_m1&modal_type=info&order_id='.$order_id;
			# code...
			break;

		case 'm1': echo '?action=orders-page&list_type='.$_GET['list_type'].'&active[]='.$_GET['active'][0].'&active[]=act_modal&active[]=act_m1&modal_type=info&order_id='.@$_GET['order_id'];
			# code...
			break;

		case 'm2': echo '?action=orders-page&list_type='.$_GET['list_type'].'&active[]='.$_GET['active'][0].'&active[]=act_modal&active[]=act_m2&modal_type=chat&order_id='.@$_GET['order_id'];
			# code...
			break;

		case 'm3': echo '?action=orders-page&list_type='.$_GET['list_type'].'&active[]='.$_GET['active'][0].'&active[]=act_modal&active[]=act_m3&modal_type=plati&order_id='.@$_GET['order_id'];
			# code...
			break;
		
		default:
			# code...
			break;
	}
}


function op_act($value='f'){
	if(!isset($_GET['active'])) $_GET['active'] = ['act_all'];
	if (in_array($value, $_GET['active'])) echo 'active';
}


function get_orders($option){

	if($option === null) return [];
	switch ($option) {
		case 'all':	return arrayDB("SELECT * FROM ebay_orders ORDER BY id DESC LIMIT 50");

		case 'paid':	return arrayDB("SELECT * FROM ebay_orders WHERE PaidTime<>0 ORDER BY id DESC LIMIT 50");

		case 'shipped':	return arrayDB("SELECT * FROM ebay_orders WHERE ShippedTime<>0 ORDER BY id DESC LIMIT 50");
		
		default: return [];
	}
}


function chat_item_links(){
	$order_id = $_GET['order_id'];
	$order_info = arrayDB("SELECT * FROM ebay_orders WHERE id='$order_id'");
	$goods = json_decode($order_info[0]['goods'], true);
	$html_str = '';
	foreach ($goods as $gk => $good) {
		$query = $_SERVER['QUERY_STRING'];
		parse_str($query, $query_arr);
		$active = '';
		if(@$query_arr['item_id'] === $good['itemid']) $active = 'active';
		$query_arr['item_id'] = $good['itemid'];
		$query = http_build_query($query_arr);
		$query = preg_replace('/%5B[0-9]+%5D/simU', '[]', $query);
		$html_str .= '<a class="op_modal_game_link '.$active.'" href="?'.$query.'">'.$good['title'].'</a><br>';
	}
	return $html_str;
}


function op_sugest_send_product(){

	if(!isset($_GET['item_id']) || !$_GET['item_id']) return '';
	$order_info = get_order_data_for_senders($_GET['order_id'], $_GET['item_id']);

	$msg_email = arrayDB('SELECT * FROM ebay_inv_messages WHERE country_alias="EN" AND ebay_or_mail="mail" LIMIT 1')[0]['message'];
	$msg_ebay = arrayDB('SELECT * FROM ebay_inv_messages WHERE country_alias="EN" AND ebay_or_mail="ebay" LIMIT 1')[0]['message'];

	$item_title = $order_info['item_title'];
	
	return '<br>
	<div class="container-fluid">
		<form class="row" method="POST" id="js-inv-sendemail-form">

			<div class="col-sm-6">
				<input type="hidden" name="sendemail">
				<input type="text" class="form-control" name="user_email" value="'.$order_info['bayer_email'].'"><br>
				<input type="text" class="form-control" name="email_subject" value="'.$item_title.'"><br>
				<textarea class="form-control" name="email_body" id="editor1" cols="30" rows="11" resize="both">'.$msg_email.'</textarea><br>
				<button class="glyphicon op-modal-btn" type="submit">Send All</button>
				<button class="glyphicon op-modal-btn pull-right" id="js-inv-sendemail" type="button">Send Email</button>
			</div>

			<div class="col-sm-6">
				<input type="hidden" name="sendebay">
				<input type="hidden" name="ebay_user" value="'.$order_info['bayer_UserID'].'">
				<input type="hidden" name="ebay_item" value="'.$order_info['item_id'].'">
				<input type="text" class="form-control" name="ebay_subject" value="'.$item_title.'"><br>
				<textarea class="form-control" name="ebay_body" id="" cols="30" rows="11">'.$msg_ebay.'</textarea><br>
				<button class="op-modal-btn glyphicon pull-right" id="js-inv-sendebay" type="button">Send Message</button>
			</div>

		</form>
	</div>';
	
}

?>