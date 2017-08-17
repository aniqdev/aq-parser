<?php

if (isset($_POST['action']) && $_POST['action'] === 'get_order_info') {
	$gig_hood_order_id = $_POST['gig_hood_order_id'];
	$res = arrayDB("SELECT * FROM hood_orders WHERE id = '$gig_hood_order_id'")[0];
	$json_orderItems = json_decode($res['json_orderItems'], true);
	if (isset($json_orderItems['item']['auctionID'])) {
		$res['json_orderItems'] = [$json_orderItems['item']];
	}else{
		$res['json_orderItems'] = $json_orderItems['item'];
	}
	$res['json_shipAddress'] = json_decode($res['json_shipAddress'], true);
	$res['info'] = print_r($res, true);
 	echo json_encode($res);		die;
}

$count = false;
if(isset($_REQUEST['q']) && $_REQUEST['q']){
	$q = _esc(trim($_REQUEST['q']));
	$orders_arr = arrayDB("SELECT * FROM hood_orders WHERE dtls_orderID LIKE '%{$q}%' OR br_accountName LIKE '%{$q}%' OR br_email LIKE '%{$q}%' OR br_firstName LIKE '%{$q}%' OR br_lastName LIKE '%{$q}%'");
	$count = count($orders_arr);
}else{
	$orders_arr = arrayDB("SELECT * FROM hood_orders ORDER BY id DESC");
}



op_tab_navigator();
?>
<div class="container-fluid op-search-panel">
	<div class="row">
		<div class="col-sm-4">
			<?php aqs_pagination('hood_orders', $count);?>
		</div>
		<div class="col-sm-4 col-md-3">
			<form action="" method="GET">
				<input type="hidden" name="action" value="hood-orders">
				<input type="hidden" name="offset" value="<?= @$_GET['offset'];?>">
				<input type="hidden" name="limit" value="<?= @$_GET['limit'];?>">
			    <div class="input-group">
			      <input type="search" name="q" class="form-control" placeholder="Search for...">
			      <span class="input-group-btn">
			        <button class="btn btn-default" type="submit">Go!</button>
			      </span>
			    </div><!-- /input-group -->
		    </form>
		</div>
		<div class="col-sm-4">
			
		</div>
	</div>
</div>
<div class="ppp-block" style="max-width:100%;" id="ho_js_deligator">
	<table class="orders-table">
		<tr>
			<th>#</th>
			<th>cntry</th>
			<th>qtty</th>
			<th>Game title</th>
			<th>Price</th>
			<th>Link</th>
			<th>StatusActionBuyer</th>
			<th>StatusSeller</th>
			<th>accountName</th>
			<th>email</th>
			<th>Name</th>
			<th>date_time</th>
		</tr>
<?php
foreach ($orders_arr as $key => $order) {
	$goods = json_decode($order['json_orderItems'], true)['item'];
	if(isset($goods['auctionID'])) $goods = [$goods];
	// sa($goods);
	echo '<tr>',
			'<td title="',$order['dtls_orderID'],'">',$key+$_GET['offset']+1,'</td>',
			'<td><a href="#info" class="ho-modal-show" id="',$order['id'],'">[',$order['countryTwoDigit'],']</a></td>',
			// '<td>',$order['dtls_quantity'],'</td>',
			'<td>';foreach($goods as $g) echo $g['quantity'],'<br>';echo '</td>',
			'<td>';foreach($goods as $g) echo '<div class="op-titles">',$g['prodName']['@cdata'],'<div class="op-titles">';echo '</div></td>',
			'<td>';foreach($goods as $g) echo $g['price'],'<br>';echo '</td>',
			'<td>';foreach($goods as $g) echo '<a href="https://www.hood.de/i/-',$g['auctionID'],'.htm" target="_blank">Link</a><br>';echo '</td>',
			'<td>',$order['dtls_price'],'</td>',
			'<td title="',$order['orderStatusBuyer'],'">',$order['orderStatusActionBuyer'],'</td>',
			'<td title="',$order['orderStatusActionSeller'],'">',$order['orderStatusSeller'],'</td>',
			'<td>',$order['br_accountName'],'</td>',
			'<td>',$order['br_email'],'</td>',
			'<td>',$order['br_firstName'],' ',$order['br_lastName'],'</td>',
			'<td>',date_shorter($order['dtls_date']),'</td>',
		 '</tr>';
}
?>
	</table>
</div>

<div class="modal fade bs-example-modal-lg" id="js_ho_modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" id="react_ho_modal">
      <h3>Loadding...</h3>
    </div>
  </div>
</div>

<script src="js/react.min.js"></script>
<script src="js/react-dom.min.js"></script>
<!-- <script src="js/react-router.min.js"></script> -->
<!-- <script src="js/react-router-dom.min.js"></script> -->
<script src="js/hood-orders/index.js?t=<?= time();?>"></script>