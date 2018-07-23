<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<h2>Product filter</h2>
<div class="container">
	<form id="filter_form">
		<div class="row">
			<div class="col-sm-6">
				<br><label for="">Genres</label>
				<select id="genre_select" name="genre[]" multiple class="form-control"></select>
			</div>
			<div class="col-sm-6">
				<br><label for="">Tags</label>
				<select id="tag_select" name="tag[]" multiple class="form-control"></select>
			</div>
		</div><!-- /row -->
		<div class="row">
			<div class="col-sm-6">
				<br><label for="">Developers</label>
				<select id="developer_select" name="developer[]" multiple class="form-control"></select>
			</div>
			<div class="col-sm-6">
				<br><label for="">Publishers</label>
				<select id="publisher_select" name="publisher[]" multiple class="form-control"></select>
			</div>
		</div><!-- /row -->
		<div class="row">
			<div class="col-sm-6">
				<br><label>Year</label>
				<select id="year_select1" name="year_from" class="form-control"></select>
			</div>
			<div class="col-sm-6">
				<br><div class="row">
					<div class="col-xs-6">
						<label for="">from</label>
						<select id="year_select1" name="year_from" class="form-control"></select>
					</div>
					<div class="col-xs-6">
						<label for="">to</label>
						<select id="year_select2" name="year_to" class="form-control"></select>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<br>
				<p>
					<label for="amount">Price range:</label>
					<input type="text" id="amount" readonly style="background: transparent;border:0; color:#f6931f; font-weight:bold;">
				</p>
				<div id="slider-range"></div>
			</div>
		</div>
		<br>
		<button class="btn btn-primar">Submit</button>
	</form>
</div>
<script>
	var multiselect_options = {
	    nonSelectedText: 'Check an option!',
	    enableFiltering: true,
	    includeSelectAllOption: true,
	    buttonWidth: '100%',
	    maxHeight: 500,
	    enableCaseInsensitiveFiltering: true,
	};
	$('#genre_select').multiselect(multiselect_options);
	$('#tag_select').multiselect(multiselect_options);
	$('#developer_select').multiselect(multiselect_options);
	$('#publisher_select').multiselect(multiselect_options);


    // var example_data = [
    //     {label: 'Option 1', title: 'Option 1', value: '1', selected: true},
    //     {label: 'Option 2', title: 'Option 2', value: '2'},
    //     {label: 'Option 6', title: 'Option 6', value: '6', disabled: true}
    // ];

    var range_select_options = {
		range: true,
		min: 0,
		max: 500,
		values: [ 75, 300 ],
		slide: function( event, ui ) {
			$( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
		}
	};
    $( "#slider-range" ).slider(range_select_options);
	$( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
		" - $" + $( "#slider-range" ).slider( "values", 1 ) );

	// =====================================================================
    $.post('http://parser.gig-games.de/ajax.php?action=update-filter-values',
    	{action:'get_filter_data', steam_table:'steam_fr'},
    	function(data) {

    		if(!data) return false;

    		var genres_data = data.genres.map(function(el) {
    			return {title: el.v, label: el.v+' ('+el.c+')', value: el.v};	});
    		$('#genre_select').multiselect('dataprovider', genres_data);

    		var tags_data = data.tags.map(function(el) {
    			return {title: el.v, label: el.v+' ('+el.c+')', value: el.v};	});
    		$('#tag_select').multiselect('dataprovider', tags_data);

    		var developer_data = data.developer.map(function(el) {
    			return {title: el.v, label: el.v+' ('+el.c+')', value: el.v};	});
    		// $('#developer_select').multiselect('dataprovider', developer_data);

    		var publisher_data = data.publisher.map(function(el) {
    			return {title: el.v, label: el.v+' ('+el.c+')', value: el.v};	});
    		// $('#publisher_select').multiselect('dataprovider', publisher_data);

			var ys1 = document.getElementById("year_select1");
			var ys2 = document.getElementById("year_select2");
			// log(ys);
    		data.year.map(function(el) {
			    var option1 = document.createElement("option");
			    option1.text = el.v+' ('+el.c+')';
			    var option2 = document.createElement("option");
			    option2.text = el.v;
			    ys1.add(option1);
			    ys2.add(option2);
			// log(ys);
    		});



    	}, 'json');

    			// console.log(genres_data);


	// $('#filter_form').on('keyup', function(e) {
	// 	e.preventDefault();
	// 	var form_data = $(this).serialize();
	// 	log(form_data);
	// });
</script>