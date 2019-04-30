<?php

if (isset($_GET['sales-chart'])) {
	$res = arrayDB("SELECT DATE_FORMAT(ShippedTime, '%d-%m') as date, sum(total_price ) as sum ,count(*) as count FROM ebay_orders WHERE ShippedTime > NOW() - INTERVAL 28 DAY GROUP BY day(ShippedTime) order by ShippedTime");
	$ret = [['date','sales', 'sum']];
	foreach ($res as $k => $val) if($k > 0) $ret[] = [$val['date'], +$val['count'], +$val['sum']];
	header('Content-Type: application/json');
	echo json_encode($ret);
	return;
}

require_once __DIR__.'/orders-page-functions.php';

$orders = get_orders($_GET['list_type']);


op_tab_navigator();
?>
<div class="container-fluid op-search-panel">
<div class="row">
	<div class="col-sm-4">
		<?php op_pagination();?>
	</div>
	<div class="col-sm-4 col-md-3">
		<form action="" method="POST">
		    <div class="input-group">
		      <input type="search" class="form-control" name="q" value="<?= @$_REQUEST['q']?$_REQUEST['q']:''?>" placeholder="Search for...">
		      <span class="input-group-btn">
		        <button class="btn btn-default" type="submit">Go!</button>
		      </span>
		    </div><!-- /input-group -->
	    </form>
	</div>
	<div class="col-sm-4">
		<button id="show-chrt" class="btn btn-success pull-right" title="last 30 days diagram">dia</button>
	</div>
</div>

<div class="chart-wrapper">
	<div id="chrt" style="height: 250px; background-color: #fff; padding: 10px;"></div>
</div>

</div>

<div class="ppp-block" style="max-width:100%;">
<table class="orders-table op-orders-table">
	<tr>
		<th>#</th>
		<th title="Country">Cntry</th>
		<th title="Status">Stts</th>
		<th title="Comment">cmm</th>
		<th title="Execution Method">EM</th>
		<th title="Ammount">Amnt</th>
		<th>Game title</th>
		<th>Price</th>
		<th>Link</th>
		<th>Total</th>
		<th>BuyerUserID</th>
		<th>Buyer Email</th>
		<th>Buyer Name</th>
		<th>Rating</th>
		<th>RegDate</th>
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
	$comm = ($order['comment'])?'<div class="glyphicon glyphicon-envelope" title="'.$order['comment'].'"></div>':'';
	echo '<tr class="',op_active('order_id',$order['id']),'">',
			'<td title="',$order['id'],'">',$key+$_GET['offset']+1,'</td>',
			'<td title="',htmlspecialchars(print_r($address['CountryName'],true)),'">
				<a href="',op_hrefR(['order_id'=>$order['id'],'item_id'=>$goods[0]['itemid']]),'">[',@$address['Country'],']</a></td>',
			'<td>',status_shorter($order['OrderStatus']),'</td>',
			'<td>',$comm,'</td>',
			'<td title="',$order['ExecutionMethod'],'">',ucfirst($order['ExecutionMethod'][0]),'</td>',
			'<td>';foreach($goods as $g) echo $g['amount'],'<br>';echo '</td>',
			'<td>';foreach($goods as $g) echo '<div class="op-titles">',$g['title'],'</div>';echo '</td>',
			'<td>';foreach($goods as $g) echo $g['price'],'<br>';echo '</td>',
			'<td>';foreach($goods as $g) echo '<a href="//www.ebay.de/itm/',$g['itemid'],'" target="_blank">link</a><br>';echo '</td>',
			'<td>',$order['total_price'],'</td>',
			'<td>',$order['BuyerUserID'],' ',user_star_sign($order),'</td>',
			'<td>',$order['BuyerEmail'],'</td>',
			'<td>',$order['BuyerFirstName'],' ',$order['BuyerLastName'],'</td>',
			'<td>',$order['BuyerFeedbackScore'],'</td>',
			'<td>',date_shorter($order['BayerRegistrationDate']),'</td>',
			'<td>',date_shorter($order['PaidTime']),'</td>',
			'<td>',date_shorter($order['ShippedTime']),'</td>',
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
	$modal_array['body'] = '<pre>'.print_r(array_collapser($order_info), true).'</pre>';

}elseif(isset($_GET['order_id']) && $_GET['order_id'] > 0 && $_GET['modal_type'] === 'chat') {
	
	$modal_array['header'] = 'Chat '.$_GET['order_id'];
	$modal_array['body'] = chat_item_links().op_sugest_send_product();
}elseif(isset($_GET['order_id']) && $_GET['order_id'] > 0 && $_GET['modal_type'] === 'plati') {
	
	$modal_array['header'] = 'Plati.ru product';
	$modal_array['body'] = chat_item_links().op_platiru_product();
}

?>
<div class="op-main-modal <?php if($_GET['order_id']!='0') echo 'active';?>">
	<div class="op-modal-header">
		[<?php echo $modal_array['header']; ?>]
		<div class="op-modal-tabs pull-right">
			<a href="<?php op_href2(['modal_type'=>'info']);?>" class="op-modal-tab <?php op_active('modal_type','info');?>">order info</a>
			<a href="<?php op_href2(['modal_type'=>'chat']);?>" class="op-modal-tab <?php op_active('modal_type','chat');?>">chat</a>
			<a href="<?php op_href2(['modal_type'=>'plati']);?>" class="op-modal-tab <?php op_active('modal_type','plati');?>">plati</a>
		</div>
	</div>
	<div class="op-modal-body">
			<?php echo $modal_array['body']; ?>
	</div>
	<div class="op-modal-footer">
		<a href="<?php op_href2(['order_id'=>'0','item_id'=>'0'])?>" class="op-modal-btn">close</a>
	</div>
</div>

<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	google.charts.load('current', {'packages':['line']});
	google.charts.setOnLoadCallback(function(){

	    $('#show-chrt').on('click', function(e) {
	    	if ($('.chart-wrapper').toggleClass('active').hasClass('active')) {
				$.get('ajax.php?action=orders-page&sales-chart',function(data) {
			    	op_drawChart(data);
			    },'json');
	    	}
	    });
	});

  function op_drawChart(danye) {

    var data = google.visualization.arrayToDataTable(danye);
    var options = {
      title: 'Company Performance',
      curveType: 'function',
      legend: { position: 'bottom' }
    };
    var chart = new google.charts.Line(document.getElementById('chrt'));
    chart.draw(data, options);
  }

  // звездочки
  $(function(){EbayMessages.init()});
</script>