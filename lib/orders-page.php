<?php require_once __DIR__.'/orders-page-functions.php';

$orders = get_orders($_GET['list_type']);
?>
<div class="ajax-loader ajaxed"></div>

<div class="op-tab-navigator">
	<div class="op-tab <?php op_act('act_all');?>">
		<a href="<?php op_href('l1');?>">
			<i class="glyphicon glyphicon-star">&nbsp;</i>
			All orders
		</a>
	</div>
	<div class="op-tab <?php op_act('act_paid');?>">
		<a href="<?php op_href('l2');?>">
			<i class="glyphicon glyphicon-euro">&nbsp;</i>
			Paid orders
		</a>
	</div>
	<div class="op-tab <?php op_act('act_shipped');?>">
		<a href="<?php op_href('l3');?>">
			<i class="glyphicon glyphicon-euro">&nbsp;</i>
			Shipped orders
		</a>
	</div>
	<div class="op-tab"><a href="<?php op_href(1);?>"><i class="glyphicon glyphicon-star">&nbsp;</i>three</a></div>
	<div class="op-tab"><a href="<?php op_href(1);?>"><i class="glyphicon glyphicon-star">&nbsp;</i>four</a></div>
	<div class="op-tab"><a href="<?php op_href(1);?>"><i class="glyphicon glyphicon-star">&nbsp;</i>five</a></div>
	<div class="op-tab"><a href="<?php op_href(1);?>"><i class="glyphicon glyphicon-star">&nbsp;</i>six</a></div>
	<div class="op-tab"><a href="<?php op_href(1);?>"><i class="glyphicon glyphicon-star">&nbsp;</i>seven</a></div>
</div>

<div class="container-fluid">
<div class="row">
	<div class="col-sm-4">
		<?php op_pagination();?>
	</div>
	<div class="col-sm-4 col-md-3">
	  <div class="input-group">
	      <input type="text" class="form-control" placeholder="Search for...">
	      <span class="input-group-btn">
	        <button class="btn btn-default" type="button">Go!</button>
	      </span>
	   </div><!-- /input-group -->
	</div>
</div>
</div>

<div class="ppp-block" style="max-width:100%;">
<table class="orders-table">
	<tr>
		<th>#</th>
		<th>Cntry</th>
		<th>Amnt</th>
		<th>Game title</th>
		<th>Price</th>
		<th>Link</th>
		<th>Total</th>
		<th>BuyerUserID</th>
		<th>Buyer Email</th>
		<th>Buyer Name</th>
		<th>PaidTime</th>
		<th>ShippedTime</th>
		<th>Pt</th>
		<th>St</th>
	</tr>
<?php

//$orders = arrayDB("SELECT * FROM ebay_orders WHERE PaidTime<>0 AND ShippedTime=0 AND OrderStatus='Completed'");
//showArray($orders);
foreach ($orders as $key => $order) {
	$goods = json_decode($order['goods'], true);
	$address = json_decode($order['ShippingAddress'], true);
	echo '<tr>',
			'<td>',$key+1,'</td>',
			'<td title="',print_r($address['CountryName'],true),'"><a href="',op_href('open_modal',$order['id']),'">',@$address['Country'],'</td>',
			'<td>';foreach($goods as $g) echo $g['amount'],'<br>';echo '</td>',
			'<td>';foreach($goods as $g) echo '<div class="op-titles">',$g['title'],'</div>';echo '</td>',
			'<td>';foreach($goods as $g) echo $g['price'],'<br>';echo '</td>',
			'<td>';foreach($goods as $g) echo '<a href="',$g['itemid'],'">link</a><br>';echo '</td>',
			'<td>',$order['total_price'],'</td>',
			'<td>',$order['BuyerUserID'],'</td>',
			'<td>',$order['BuyerEmail'],'</td>',
			'<td>',$order['BuyerFirstName'],' ',$order['BuyerLastName'],'</td>',
			'<td>',$order['PaidTime'],'</td>',
			'<td>',$order['ShippedTime'],'</td>',
			'<td><button title="'.(($order['PaidTime']==0)?'mark as paid':'unmark as paid').'" class="op-markorder" name="mark_as_paid" value="',$order['order_id'],'">'.(($order['PaidTime']==0)?'+':'-').'</button></td>',
			'<td><button title="'.(($order['ShippedTime']==0)?'mark as shipped':'unmark as shipped').'" class="op-markorder" name="mark_as_shipped" value="',$order['order_id'],'">'.(($order['ShippedTime']==0)?'+':'-').'</button></td>',
		 '</tr>';
}

?>
</table>
</div>
<?php

$modal_array = ['header'=>'','body'=>'','footer'=>''];

if(isset($_GET['order_id']) && $_GET['order_id'] > 0 && $_GET['modal_type'] === 'info') {
	
	$order_info = arrayDB("SELECT * FROM ebay_orders WHERE id='$_GET[order_id]'")[0];
	$order_info['goods'] = json_decode($order_info['goods'], true);
	$order_info['ShippingAddress'] = json_decode($order_info['ShippingAddress'], true);
	$modal_array['header'] = $order_info['BuyerFirstName'].' '.$order_info['BuyerLastName'].' ('.$order_info['BuyerUserID'].')';
	$modal_array['body'] = '<pre>'.print_r($order_info, true).'</pre>';

}elseif(isset($_GET['order_id']) && $_GET['order_id'] > 0 && $_GET['modal_type'] === 'chat') {
	
	$modal_array['header'] = 'Chat '.$_GET['order_id'];
	$modal_array['body'] = chat_item_links().op_sugest_send_product();
}elseif(isset($_GET['order_id']) && $_GET['order_id'] > 0 && $_GET['modal_type'] === 'plati') {
	
	$modal_array['header'] = 'Plati.ru product';
	$modal_array['body'] = chat_item_links().op_platiru_product();
}

?>
<div class="op-main-modal <?php op_act('act_modal');?>">
	<div class="op-modal-header">
		[<?php echo $modal_array['header']; ?>]
		<div class="op-modal-tabs pull-right">
			<a href="<?php op_href('m1');?>" class="op-modal-tab <?php op_act('act_m1');?>">order info</a>
			<a href="<?php op_href('m2');?>" class="op-modal-tab <?php op_act('act_m2');?>">chat</a>
			<a href="<?php op_href('m3');?>" class="op-modal-tab <?php op_act('act_m3');?>">plati</a>
		</div>
	</div>
	<div class="op-modal-body">
			<?php echo $modal_array['body']; ?>
	</div>
	<div class="op-modal-footer">
		<button type="button" class="op-modal-btn" name="save">save</button>
		<button type="button" class="op-modal-btn" name="cancel">cancel</button>
	</div>
</div>