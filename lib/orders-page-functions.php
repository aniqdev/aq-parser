<?php

function op_href($state = 'l1', $order_id = '', $item_id = ''){

	switch ($state) {
		case 'l1': echo '?action=orders-page&list_type=all&active[]=act_all&offset=0&limit=100';
			# code...
			break;

		case 'l2': echo '?action=orders-page&list_type=paid&active[]=act_paid&offset=0&limit=100';
			# code...
			break;

		case 'l3': echo '?action=orders-page&list_type=shipped&active[]=act_shipped&offset=0&limit=100';
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


function op_href2($arr = []){
	$qs_obj = new QueryString();
	foreach ($arr as $key => $val) $qs_obj->set($key,$val);
	echo '?'.$qs_obj->give();
}
function op_hrefE($arr = []){
	$qs_obj = new QueryString();
	foreach ($arr as $key => $val) $qs_obj->set($key,$val);
	echo '?'.$qs_obj->give();
}
function op_hrefR($arr = []){
	$qs_obj = new QueryString();
	foreach ($arr as $key => $val) $qs_obj->set($key,$val);
	return '?'.$qs_obj->give();
}


function op_act($value='f'){
	if(!isset($_GET['active'])) $_GET['active'] = ['act_all'];
	if (in_array($value, $_GET['active'])) echo 'active';
}


function op_active($get, $val){
	if($_GET[$get] == $val) echo 'active';
}


function get_orders($option){

	if($option === null) return [];
	if(isset($_REQUEST['q']) && $_REQUEST['q']) $option = 'search';
	if(isset($_GET['offset']) && isset($_GET['limit'])){
		$limit = 'LIMIT '.(int)$_GET['offset'].','.(int)$_GET['limit'];
	}else{
		$limit = 'LIMIT 500';
	}
	switch ($option) {
		case 'all':	
			$_GET['count'] = arrayDB("SELECT count(*) as count FROM ebay_orders ORDER BY id DESC");
			$_GET['count'] = $_GET['count'][0]['count'];
			return arrayDB("SELECT msgs.*,ebay_users.is_trusted FROM
				(SELECT * FROM ebay_orders ORDER BY id DESC $limit) msgs
				left join ebay_users
				on msgs.BuyerUserID = ebay_users.user_id
				ORDER BY msgs.id DESC");

		case 'paid':	
			$_GET['count'] = arrayDB("SELECT count(*) as count FROM ebay_orders WHERE PaidTime<>0");
			$_GET['count'] = $_GET['count'][0]['count'];
			return arrayDB("SELECT msgs.*,ebay_users.is_trusted FROM
				(SELECT * FROM ebay_orders WHERE PaidTime<>0 ORDER BY id DESC $limit) msgs
				left join ebay_users
				on msgs.BuyerUserID = ebay_users.user_id
				ORDER BY msgs.id DESC");

		case 'shipped':	
			$_GET['count'] = arrayDB("SELECT count(*) as count FROM ebay_orders WHERE ShippedTime<>0");
			$_GET['count'] = $_GET['count'][0]['count'];
			return arrayDB("SELECT msgs.*,ebay_users.is_trusted FROM
				(SELECT * FROM ebay_orders WHERE ShippedTime<>0 ORDER BY id DESC $limit) msgs
				left join ebay_users
				on msgs.BuyerUserID = ebay_users.user_id
				ORDER BY msgs.id DESC");

		case 'search':
			$q = _esc(str_replace('_', '\_', trim($_REQUEST['q'])));
			$_GET['count'] = arrayDB("SELECT count(*) as count FROM ebay_orders 
				WHERE order_id LIKE '%{$q}%' 
				OR BuyerUserID LIKE '%{$q}%' 
				OR BuyerEmail LIKE '%{$q}%'");
			$_GET['count'] = $_GET['count'][0]['count'];
			return arrayDB("SELECT ebay_orders.*, ebay_users.is_trusted FROM ebay_orders 
				left join ebay_users
				on ebay_orders.BuyerUserID = ebay_users.user_id
				WHERE order_id LIKE '%{$q}%' OR BuyerUserID LIKE '%{$q}%' OR BuyerEmail LIKE '%{$q}%'");
		
		default: return [];
	}
}


function chat_item_links(){
	$order_id = $_GET['order_id'];
	$order_info = arrayDB("SELECT * FROM ebay_orders WHERE id='$order_id'");
	$goods = json_decode($order_info[0]['goods'], true);
	$html_str = '';
	foreach ($goods as $gk => $good) {
		$active = '';
		if($_GET['item_id'] === $good['itemid']) $active = 'active';
		$html_str .= '<a class="op_modal_game_link '.$active.'" href="'.op_hrefR(['item_id'=>$good['itemid']]).'">'.$good['title'].'</a><br>';
	}
	return $html_str;
}


function op_sugest_send_product(){

	if(!isset($_GET['item_id']) || !$_GET['item_id']) return '';
	$order_info = get_order_data_for_senders($_GET['order_id'], $_GET['item_id']);
	$item_title = $order_info['item_title'];
	$item_title_clean = clean_ebay_title2($item_title);

	$msg_email = arrayDB('SELECT * FROM ebay_inv_messages WHERE country_alias="EN" AND ebay_or_mail="mail" LIMIT 1')[0]['message'];
	$msg_email = str_ireplace('{{PRODUCT}}', product_html($item_title_clean,'{{PRODUCT}}'), $msg_email);
	$msg_email = str_ireplace('{{USER_EMAIL}}', $order_info['bayer_email'], $msg_email);
	$msg_email = fill_email_item_panel($msg_email);
	$msg_ebay = arrayDB('SELECT * FROM ebay_inv_messages WHERE country_alias="EN" AND ebay_or_mail="ebay" LIMIT 1')[0]['message'];
	$msg_ebay = str_ireplace('{{EMAIL}}', $order_info['bayer_email'], $msg_ebay);

	
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


function op_platiru_product(){
	
	if (!isset($_GET['item_id'])) return '';
	$order_id = $_GET['order_id'];
	$item_id = $_GET['item_id'];

	$frame_links = arrayDB("SELECT * FROM ebay_invoices WHERE parser_order_id='$order_id' AND ebay_game_id='$item_id'");

	$frames = '';
	foreach ($frame_links as $key => $frame_link) {
		
		$frames .= '<br><iframe class="invoice-iframe" src="'.$frame_link['product_frame_link'].'&oper=checkpay">
        Ваш браузер не поддерживает плавающие фреймы!
     </iframe>';
	}
	return $frames;
}


function op_pagination()
{	
	$count = (int)$_GET['count'];
	$offset = (int)$_GET['offset'];
	$limit = (int)$_GET['limit'];
	if($limit > 500) $limit = 500;
	$offset_prev = $offset - $limit;
	if($offset_prev < 0) $offset_prev = 0;
	$offset_next = $offset + $limit;
	if($offset_next > $count) $offset_next = $offset;
	//var_dump($count/$limit);
	$str =
	'<nav aria-label="Page navigation" class="navigation">
	  <ul class="pagination op-pagination">
	    <li>
	      <a href="?'.obj(QS)->SET('offset',$offset_prev)->give().'" aria-label="Previous">
	        <span aria-hidden="true">&laquo;</span>
	      </a>
	    </li>';
	for ($i=-4; $i <= 4; $i++) {
		$inoffset = $offset + ($limit * $i);
		$num = floor($inoffset / $limit + 1);
		$b = $count - $inoffset;
		$a = $b - $limit + 1;
		if($a < 0) $a = 1;
		if ($inoffset < 0) {
			continue;
		}elseif ($inoffset > $count) {
			break;
		} elseif ($inoffset == $offset) {
			$str .= '<li class="active"><a class="curren" title="'.$a.'-'.$b.'">'.$num.'</a></li>';
			$epilog = $a.'-'.$b.' of '.$count.' results';
		}else{
			$str .= '<li><a href="'.op_hrefR(['offset'=>$inoffset]).'" title="'.$a.'-'.$b.'">'.$num.'</a></li>';
		}
	}
	$str .= '<li>
	      <a href="?'.obj(QS)->SET('offset',$offset_next)->give().'" aria-label="Next">
	        <span aria-hidden="true">&raquo;</span>
	      </a>
	    </li>
	  </ul><br><b>'.@$epilog.'</b>'.
	'</nav>';

	echo $str;

}


function status_shorter($status = ''){
	
	switch ($status) {
		case 'Completed': return '<i class="glyphicon glyphicon-check" title="Completed"></i>';
		case 'Cancelled': return '<i class="glyphicon glyphicon-stop" title="Cancelled"></i>';
		case 'Active': return '<i class="glyphicon glyphicon-play" title="Active"></i>';
		case 'CancelPending': return'<i class="glyphicon glyphicon-pause" title="CancelPending"></i>';
		
		default: return 'n/a';
	}
}
?>