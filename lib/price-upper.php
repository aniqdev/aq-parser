<div class="container text-center">
	<h3>price upper</h3>
	<div class="row">
		<div class="col-xs-4">
			<button class="devzone-btn js-btn" id="js_btn1" title="cron-ebay-price-saver">ebay-price-saver</button>
			<p>Сохраняет цены в базу</p>
		</div>
		<div class="col-xs-4">
			<button class="devzone-btn js-btn" id="js_btn2" title="cron-ebay-price-upper">ebay-price-upper</button>
			<p>повышает цены до минимальных конкурентов</p>
		</div>
		<div class="col-xs-4">
			<button class="devzone-btn js-btn" id="js_btn3" title="cron-ebay-price-upper-c">ebay-price-upper-c</button>
			<p>повышает цены до рекомендуемых</p>
		</div>
	</div>
</div><hr>

<div id="js_frame"></div>

<script>
$('.js-btn').on('click', function() {
	$(this).attr('disabled','disabled');
	$.post('ajax.php?action='+this.title, function(data) {
		$('#js_frame').html(data);
	});
})
</script>