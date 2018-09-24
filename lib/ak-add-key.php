<link rel="stylesheet" href="css/typeahead-examples.css">
<style>
.row2{
	position: relative;
}
span.twitter-typeahead{
	    display: inline-block;
}
</style>
<div class="container">
	<h3>Add keys</h3>
	<form id="add_key_form">
		<input type="hidden" name="function" value="ak_add_key">
		<div class="row">
			<div class="col-sm-5">
				<input id="game_name_inp" data-provide="typeahead" type="text" class="form-control" placeholder="game name" name="game_name">
			</div>
			<div class="col-sm-4" style="padding: 0;">
				<button id="price_info" type="button" disabled class="form-control">...</button>
			</div>
			<div class="col-sm-3">
				<input class="form-control" type="text" name="ebay_id" id="ebay_id_inp" readonly placeholder="ebay id">
			</div>
		</div><br>
		<div class="row row2">
			<div class="col-xs-10">
				<textarea name="keys" id="keys_inp" cols="30" rows="10" class="form-control" placeholder="keys (each one from new line)"></textarea>
			</div>
			<div class="col-xs-2">
				<input type="text" class="form-control" id="js_price_inp" placeholder="price" name="price"><br>
				<input id="seller_inp" type="text" class="form-control" placeholder="seller" name="seller"><br><br>
				<button type="submit" class="btn btn-primary add-btn" id="add_btn">Add keys</button>
			</div>
		</div>
	</form>

	<hr>
	
	<h3>Add seller</h3>
	<form id="add_seller_form">
	  <input type="hidden" name="function" value="ak_add_seller">
	  <div class="form-group">
	    <label for="exampleInputEmail1">seller</label>
	    <input type="text" class="form-control" placeholder="seller" name="username">
	  </div>
	  <div class="form-group">
	    <label for="exampleInputPassword1">info</label>
	    <input type="text" class="form-control" placeholder="info" name="info">
	  </div>
	  <button type="submit" class="btn btn-default">add seller</button>
	</form>
</div>





<div id="report_screen" title="report screen"></div>

<script src="https://twitter.github.io/typeahead.js/js/handlebars.js"></script>
<!-- <script src="js/typeahead.bundle.min.js"></script> -->
<script src="js/typeahead.jquery.js"></script>
<script>
$(function() {



// автозаполнение игр
$('#game_name_inp').typeahead({hint: true, highlight: true, minLength: 1
},{
	name: 'states',
	display: 'title_clean',
	async : true,
	// limit: 10,
	source: function (query, process, process_async) {
  		document.all.ebay_id_inp.value = '';
  		$('#price_info').html('...');
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
  document.all.ebay_id_inp.value = suggestion.item_id;
  $.post('/ajax-controller.php',
  	{function:'ak_get_prices', ebay_id:suggestion.item_id},
  	function(data) {
  		$('#price_info').html(data);
  	});
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

// код для мгновенных сообщений
// пример использования:
// $('#report_screen').append(galert('success','<b>Success!</b> '+data.report));
function galert(type,text) {
	return ('<div class="alert alert-'+type+' alert-dismissible height-anim" role="alert">'+
  '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+text+'</div>');
}

$('#add_key_form').on('submit', function(e) {
	e.preventDefault();
	$.post('/ajax-controller.php',
		$(this).serialize(),
		function(data) {
			$('#report_screen').append(galert(data.report_status,'<b>'+data.report_status+'!</b> '+data.report));
		},'json');
});

$('#add_seller_form').on('submit', function(e) {
	e.preventDefault();
	$.post('/ajax-controller.php',
		$(this).serialize(),
		function(data) {
			$('#report_screen').append(galert(data.report_status,'<b>'+data.report_status+'!</b> '+data.report));
		},'json');
});

$('#js_price_inp').on('blur', function() {
	$(this).val(parseFloat($(this).val()).toFixed(2));
});

});
</script>