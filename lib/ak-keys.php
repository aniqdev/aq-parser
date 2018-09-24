<?php

// sa($_GET);

if (isset($_GET['del'])) {
	$id = _esc($_GET['del']);
	arrayDB("DELETE FROM ak_keys WHERE id='$id'");
}

$_GET['limit'] = @$_GET['limit'] ? $_GET['limit'] : 50; // типо насройка лимита по умолчанию
$limit = aqs_pagination('ak_keys');

$w_ebay_id = @$_GET['ebay_id'] ? "AND ebay_id = '"._esc($_GET['ebay_id'])."'" : '';
$w_seller = @$_GET['seller'] ? "AND seller = '"._esc($_GET['seller'])."'" : '';
$w_status = (@$_GET['status'] && $_GET['status'] !== 'all') ? "AND status = '"._esc($_GET['status'])."'" : '';

switch (@$_GET['price_sorting']) {
	case 'default':	$price_sorting = ''; break;
	case 'low_to_high':	$price_sorting = 'ORDER BY price ASC';	break;
	case 'high_to_low':	$price_sorting = 'ORDER BY price DESC';	break;
	default: $price_sorting = '';
}

$where = "WHERE id > 0
			 $w_ebay_id 
			 $w_seller 
			 $w_status 
			 $price_sorting";

// sa($where);

$res = arrayDB("SELECT * from ak_keys $where LIMIT $limit");

?>
<link rel="stylesheet" href="css/typeahead-examples.css">
<style>
	.js-price{
		background: #3a3a3a;
	}
</style>
<div class="container">
	<h2>Warehouse</h2>
	<form class="row" id="js_sorting_form" name="js_sorting_form">
		<input type="hidden" name="action" value="ak-keys">
		<input type="hidden" name="ebay_id" value="<?= @$_GET['ebay_id'];?>" id="ebay_id_inp">
	  <div class="col-xs-1"><label>&nbsp;</label><br><button class="btn btn-default" type="button" id="reset_btn" style="width:100%;">reset</button></div>
      <div class="col-sm-4">
        <label for="game_name_inp">Game</label>
        <input name="game_name" type="text" class="form-control" id="game_name_inp" placeholder="" value="<?= @$_GET['game_name'];?>">
      </div>
      <div class="col-sm-3">
        <label for="seller_inp">Seller</label>
        <input name="seller" type="text" class="form-control" id="seller_inp" placeholder="" value="<?= @$_GET['seller'];?>">
      </div>
      <div class="col-sm-2">
        <label for="country">Status</label>
        <select name="status" class="form-control js-select" id="country" value="active">
          <option <?= @$_GET['status']==='all'?'selected':''?> value="all">All</option>
          <option <?= @$_GET['status']==='active'?'selected':''?> value="active">active</option>
          <option <?= @$_GET['status']==='sold'?'selected':''?> value="sold">sold</option>
        </select>
      </div>
      <div class="col-sm-2">
        <label for="price_inp">Price</label>
        <select name="price_sorting" class="form-control js-select" id="price_inp">
          <option <?= @$_GET['price_sorting']==='default'?'selected':''?> value="default">Default</option>
          <option <?= @$_GET['price_sorting']==='low_to_high'?'selected':''?> value="low_to_high">Low to high</option>
          <option <?= @$_GET['price_sorting']==='high_to_low'?'selected':''?> value="high_to_low">High to low</option>
        </select>
      </div>
    </form><br>
<table class="table table-condensed" id="js_table_deligator">

 		<tr>
 			<th>del</th>
 			<th>#</th>
 			<th>id</th>
 			<th>key</th>
 			<th>ebay_id</th>
 			<th>game_name</th>
 			<th>seller</th>
 			<th>price</th>
 			<th>status</th>
		</tr>
		<?php foreach ($res as $kr => $row):
			echo '<tr><td><a href="?action=ak-keys&del=',$row['id'],'" class="btn btn-danger btn-xs js-del">×</a></td><td>',($kr+1),'</td>';
				echo '<td>',$row['id'],'</td>';
				echo '<td>',$row['steam_key'],'</td>';
				echo '<td class="ebay-id">',$row['ebay_id'],'</td>';
				echo '<td>',$row['game_name'],'</td>';
				echo '<td>',$row['seller'],'</td>';
				echo '<td class="js-price" contenteditable lang="',$row['id'],'">',$row['price'],'</td>';
				echo '<td title="',$row['updated_at'],'">',$row['status'],'</td>';
			echo '</tr>';
		endforeach; ?>

</table>
<div class="text-right"><small>* - for saving new price just unselect price field</small></div>
</div>

<div id="report_screen" title="report screen"></div>

<script src="https://twitter.github.io/typeahead.js/js/handlebars.js"></script>
<!-- <script src="js/typeahead.bundle.min.js"></script> -->
<script src="js/typeahead.jquery.js"></script>

<script>

function galert(type,text) {
	return ('<div class="alert alert-'+type+' alert-dismissible height-anim" role="alert">'+
  '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+text+'</div>');
}

$('#js_table_deligator').on('click', '.js-del', function() {
	if (!confirm("Уверен?")) return false;
});

$('#js_table_deligator').on('focus', '.js-price', function() {
	$.post('/ajax-controller.php',
		{function:'ak_get_prices', ebay_id:$(this).parent().find('.ebay-id').text()},
		function(data) {
			$('#report_screen').append(galert('info', data));
		});
});

$('#js_table_deligator').on('blur', '.js-price', function() {
	var new_price = parseFloat($(this).text());
	$(this).text(new_price.toFixed(2));
	$.post('/ajax-controller.php',
  		{function:'ak_change_key_price', key_id:this.lang, new_price:new_price},
		function(data) {
			$('#report_screen').append(galert(data.report_status,'<b>'+data.report_status+'!</b> '+data.report));
		},'json');
});

// автозаполнение игр
$('#game_name_inp').typeahead({hint: true, highlight: true, minLength: 1
},{
	name: 'states',
	display: 'title_clean',
	async : true,
	source: function (query, process, process_async) {
  		$.post("/ajax-controller.php", {
            	'function' : 'ak_get_games',
                'query' : query
            }, function(data) {
            	process_async(data);
            },'json');
	},
	templates: {
	    empty: [
	      '<div class="empty-message">',
	        'unable to find any games that match the current query',
	      '</div>'
	    ].join('\n'),
	    suggestion: Handlebars.compile('<div>{{title_clean}} – <b>{{price}}</b></div>')
	}
});
$('#game_name_inp').bind('typeahead:select', function(ev, suggestion) {
  console.log(suggestion.item_id);
  document.all.ebay_id_inp.value = suggestion.item_id;
  document.forms.js_sorting_form.submit();
});

$('#seller_inp').typeahead({hint: true, highlight: true, minLength: 1
},{
  name: 'states2',
  async : true,
  source: function (query, process, process_async) {
  		$.post("/ajax-controller.php", {
            	'function' : 'ak_get_sellers',
                'query' : query
            }, function(data) {
            	process_async(data);
            },'json');
	}
});
$('#seller_inp').bind('typeahead:select', function(ev, suggestion) {
  console.log(suggestion);
  document.forms.js_sorting_form.submit();
});

$('.js-select').on('change', function() {
  document.forms.js_sorting_form.submit();
})

$('#reset_btn').on('click', function() {
  window.location.href = "?action=ak-keys";
});
</script>