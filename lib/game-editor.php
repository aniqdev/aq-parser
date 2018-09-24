<link rel="stylesheet" href="css/typeahead-examples.css">
<style>
.row2{
	position: relative;
}
span.twitter-typeahead{
	    display: inline-block;
}
.game-link{
    color: #FFDA81;
    font-size: 20px;
    border: 1px solid #FFDA81;
    display: block;
    border-radius: 6px;
    height: 34px;
    line-height: 34px;
}
.ge-subtitle + hr{
	margin-top: -3px;
}
.spec-input{
	margin-bottom: 1px;
}
.js-spec-add{
    margin-left: -64px;
    margin-right: 6px;
}
#game_img{
    border-radius: 4px;
}
</style>
<div class="container">
	<div class="row">
		<div class="col-sm-9"><br>
			<div class="row form-horizontal">
				<div class="col-sm-3"><h3 style="margin: 0;">Game Editor</h3></div>
				<label class="col-sm-3 control-label">plattform:</label>
				<div class="col-sm-3">
					<select class="form-control" id="plattform_select">
					    <option value="gig-games">gig-games</option>
					    <option value="cdvet">cdVet</option>
					</select>
				</div>
				<div class="col-sm-3 text-center">
					<a class="game-link" href="#" id="ebay_id_link" target="_blank">Link</a>
				</div>
			</div><br>
			<div>
				<form class="row">
					<input type="hidden" name="function" value="qwerty">
					<div class="col-sm-9">
						<input id="game_name_inp" data-provide="typeahead" type="text" class="form-control" placeholder="start typing game name" name="game_name">
					</div>
					<div class="col-sm-3">
						<input class="form-control" type="text" readonly="readonly" name="ebay_id" id="ebay_id_inp" readonly placeholder="ebay id">
					</div>
				</form><br>
				<form class="row" name="ebay_id_enter_form">
					<div class="col-xs-3">
						<input name="ebay_id_enter" type="text" class="form-control" placeholder="enter ebay id">
					</div>
					<div class="col-xs-3">
						<button type="submit" class="btn btn-primary">apply</button>
					</div>
				</form><br>
			</div>
		</div>
		<div class="col-sm-3 text-right">
			<img id="game_img" src="images/SixtyGigGames.png" alt="" width="200" height="200">
		</div>
	</div>
	
	<div class="ge-subtitle">Base data:</div><hr>
	
	<form class="form-horizontal" name="base_data_form">
		<input type="hidden" name="function" value="ge_update_base_data">
		<div class="form-group">
			<label for="title_inp" class="col-sm-2 control-label">Title</label>
			<div class="col-sm-10">
			  <!-- <input id="title_inp" name="title" type="text" class="form-control" placeholder="title"> -->
				<div class="input-group">
					<input id="title_inp" name="title" type="text" class="form-control" placeholder="title">
					<span id="title_length" class="input-group-addon" title="title length (80max)">0</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="price_inp" class="col-sm-2 control-label">Price</label>
			<div class="col-sm-10">
			  <input id="price_inp" name="price" type="text" class="form-control" placeholder="price">
			</div>
		</div>
		<div class="form-group">
		    <label for="quantity_inp" class="col-sm-2 control-label">Quantity</label>
		    <div class="col-sm-10">
	    	  <input id="quantity_inp" name="quantity" type="text" class="form-control" placeholder="quantity">
	    	</div>
	  	</div>
		<div class="form-group">
		    <div class="col-sm-offset-2 col-sm-10">
		      <button type="submit" class="btn btn-primary">Save</button>
		      <a type="submit" class="btn btn-warning ge-game-link" target="_blank">Link</a>
		    </div>
		</div>
	</form>

	<div class="ge-subtitle">Item specifics:</div><hr>

	<form class="form-horizontal" id="specs_form" name="specs_form">
		<input type="hidden" name="function" value="ge_update_specifics">
		<div id="specs_form_inner"></div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-6">
				<button type="submit" class="btn btn-primary">Save</button>
	      		<a type="submit" class="btn btn-warning ge-game-link" target="_blank">Link</a>
	      	</div>
			<div class="text-right col-sm-3">
				<input type="text" class="form-control" id="new_spec_inp" placeholder="enter new specific">
	      	</div>
			<div class="text-right col-sm-1">
				<button type="button" class="btn btn-info" id="add_new_spec" style="margin-left: -50px;">Add new</button>
	      	</div>
	    </div>
	</form>

	<div class="ge-subtitle">Item description:</div><hr>

	<form class="form-horizontal" id="desc_form" name="desc_form">
		<input type="hidden" name="function" value="ge_update_description">
		<textarea style="resize: vertical;" class="form-control" name="description" id="desc_inp" rows="20"></textarea><br>
	  <div class="form-group">
	    <div class="col-sm-10">
	      <button type="submit" class="btn btn-primary">Save</button>
	      <a type="submit" class="btn btn-warning ge-game-link" target="_blank">Link</a>
	    </div>
	  </div>
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
	    suggestion: Handlebars.compile('<div>{{title_clean}} – <b>€{{price}}</b></div>')
	}
});
$('#game_name_inp').bind('typeahead:select', function(event, suggestion) {
  var img_src = 'https://i.ebayimg.com/images/g/'+suggestion.picture_hash+'/s-l200.jpg';
  document.all.game_img.src = img_src;
  build_specific_list(suggestion.item_id);
});

// код для мгновенных сообщений
// пример использования:
// $('#report_screen').append(galert('success','<b>Success!</b> '+data.report));
function galert(type,text) {
	return ('<div class="alert alert-'+type+' alert-dismissible height-anim" role="alert">'+
  '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+text+'</div>');
}

$('#specs_form_inner').on('click', '.js-spec-remove', function() {
	if(confirm('Remove?')) $(this.name).remove();
});

$('#specs_form_inner').on('click', '.js-spec-add', function() {
	console.log(this.name);
	$('.js-holder-'+this.id).append('<input name="specs['+this.name+'][]" type="text" class="form-control spec-input" placeholder="add new property">')
});

var new_spec_counter = 99
document.all.add_new_spec.onclick = function() {
	var i = ++new_spec_counter;
	var label = document.all.new_spec_inp.value;
	var specs_html = '<div class="form-group" id="spec_row'+i+'">'+
	'<label for="ge'+i+'" class="col-sm-2 control-label">'+label+'</label>'+
	'<div class="col-xs-11 col-sm-9 js-holder-spec_add'+i+'">';
		specs_html +='<input name="specs['+label+'][]" type="text" class="form-control spec-input" placeholder="enter new value" id="ge'+i+'">';
	// specs_html +='</div><div class="col-xs-1"><button type="button" class="btn btn-danger js-spec-remove" name="#spec_row'+i+'">×</button>';
	specs_html +='</div><div class="col-xs-1 text-right">'+
		'<button type="button" class="btn btn-info js-spec-add" name="'+label+'" id="spec_add'+i+'">Add</button>'+
		'<button type="button" class="btn btn-danger js-spec-remove" name="#spec_row'+i+'">×</button>'+
		'</div>'+
	'</div>';
	$('#specs_form_inner').append(specs_html);
}

var max_tit_len = 80;
document.all.title_inp.onkeyup = function() {
	if (this.value.length > max_tit_len) {
		this.value = this.value.substr(0, max_tit_len);
	}
	$('#title_length').text(this.value.length);
}

document.forms.ebay_id_enter_form.onsubmit = function(e) {
	e.preventDefault();
	document.all.game_img.src = 'images/SixtyGigGames.png';
  	build_specific_list(this.ebay_id_enter.value);
}

document.forms.base_data_form.onsubmit = save_form_data;
document.forms.specs_form.onsubmit = save_form_data;
document.forms.desc_form.onsubmit = save_form_data;

function save_form_data(e) {
	e.preventDefault();
	var post_data = 'ebay_id='+document.all.ebay_id_inp.value+
					'&plattform='+document.all.plattform_select.value+
					'&'+$(this).serialize();
	$.post('/ajax-controller.php', post_data, function(data) {
		$('#report_screen').append(data.report);
	},'json');
}
// ge_update_description
function build_specific_list(item_id) {
  document.all.ebay_id_inp.value = item_id;
  document.all.ebay_id_link.href = 'https://ebay.de/itm/'+item_id;
  $('.ge-game-link').attr('href','https://ebay.de/itm/'+item_id);
  $.post('/ajax-controller.php',
  	{function:'ge_get_ebay_item_info', ebay_id:item_id},
  	function(data) {
  		if (data.Ack === 'Failure') {
  			forms_reset();
  			return;
  		}
  		document.all.plattform_select.value = data.Item.Seller.UserID;
  		document.all.game_img.src = data.Item.PictureURL[0];

  		document.all.title_inp.value = data.Item.Title;
  		document.all.price_inp.value = data.Item.CurrentPrice.Value;
  		document.all.quantity_inp.value = data.Item.Quantity;
		$('#title_length').text(data.Item.Title.length);

  		var specs_html = '';
  		for (var i = 0; i < data.Item.ItemSpecifics.NameValueList.length; i++) {
  			var spec = data.Item.ItemSpecifics.NameValueList[i];
  			specs_html +=
'<div class="form-group" id="spec_row'+i+'">'+
	'<label for="ge'+i+'" class="col-sm-2 control-label">'+spec.Name+'</label>'+
	'<div class="col-xs-11 col-sm-9 js-holder-spec_add'+i+'">';
	for (var j = 0; j < spec.Value.length; j++) {
		specs_html +='<input name="specs['+spec.Name+'][]" type="text" class="form-control spec-input" value="'+spec.Value[j]+'" id="ge'+i+'">';
	}
	specs_html +='</div><div class="col-xs-1 text-right">'+
		'<button type="button" class="btn btn-info js-spec-add" name="'+spec.Name+'" id="spec_add'+i+'">Add</button>'+
		'<button type="button" class="btn btn-danger js-spec-remove" name="#spec_row'+i+'">×</button>'+
	'</div>'+
'</div>';
  		}
  		$('#specs_form_inner').html(specs_html);

  		document.all.desc_inp.value = data.Item.Description;
  	},'json');
}

function forms_reset() {
	document.forms.base_data_form.reset();
	$('#specs_form_inner').html('');
	document.forms.desc_form.reset();
}

});
</script>