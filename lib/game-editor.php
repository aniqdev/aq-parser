<link rel="stylesheet" href="css/typeahead-examples.css">
<!-- <link rel="stylesheet" href="multiselect/css/bootstrap-multiselect.css"> -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
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
#game_img{
    border-radius: 4px;
}
.pics-form img{
	height:34px;
	border-radius:4px;
	border: 1px solid #ccc;
	width: 100%;
	transition: .4s all linear;
}
.pics-form img:hover{
    height: auto;
	position: absolute;
    z-index: 1;
    width: 180%;
    margin-left: -20px;
}
.last-col{
	margin-right: -180px;
	width: 180px;
	padding-left: 0;
}
.last-col button{
    margin-right: 6px;
}
.upload-form{
    padding: 15px;
    background: #343a40;
    border: 1px solid #ccc;
    margin: 20px 0 30px;
    border-radius: 8px;
}
.upload-form .file-input{
    color: #ccc;
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
			<label for="prim_cat_inp" class="col-sm-2 control-label">PrimaryCategory</label>
			<div class="col-sm-10">
			  <input id="old_prim_cat_inp" type="hidden" name="PrimaryCategory_old">
			  <input id="prim_cat_inp" name="PrimaryCategory" type="text" class="form-control" placeholder="PrimaryCategory">
			</div>
		</div>
		<div class="form-group">
		    <div class="col-sm-offset-2 col-sm-10">
		      <button type="submit" class="btn btn-primary">Save</button>
		      <a type="submit" class="btn btn-warning ge-game-link" target="_blank">Link</a>
		    </div>
		</div>
	</form>


	<div class="ge-subtitle">Item pictures:</div><hr>
	<form class="form-horizontal pics-form" id="pics_form" name="pics_form">
		<input type="hidden" name="function" value="ge_update_pictures">
		<div id="pics_form_inner"></div>
		<div class="form-group">
			<div class="col-xs-offset-2 col-xs-6">
				<button type="submit" class="btn btn-primary">Save</button>
	      		<a type="submit" class="btn btn-warning ge-game-link" target="_blank">Link</a>
	      	</div>
			<div class="text-right col-xs-4">
				<button type="button" class="btn btn-info" id="add_new_pic" style="margin-left: -50px;">Add new</button>
	      	</div>
	    </div>
	</form>

	<form class="form-inline row" id="upload_file_form" enctype="multipart/form-data">
		<div class="col-xs-offset-2 col-xs-10"><div class="upload-form">
			<h4>ebay file downloader</h4>
			<input type="hidden" name="function" value="ge_upload_file">
		    <div class="form-group">
		      <label for="exampleInputFile">File input</label>
		      <input name="ge-file" type="file" id="exampleInputFile" class="file-input">
		      <button type="submit" class="btn btn-default">Upload</button>
		    </div>
		</div></div>
	</form>

	<div class="ge-subtitle">Item specifics:</div><hr>
	<form class="form-horizontal" id="specs_form" name="specs_form">
		<input type="hidden" name="function" value="ge_update_specifics">
		<div id="specs_form_inner"></div>
		<div class="form-group">
			<div class="col-sm-8"></div>
			<div class="text-right col-sm-3">
				<input type="text" class="form-control" id="new_spec_inp" placeholder="enter new specific">
	      	</div>
			<div class="text-right col-sm-1">
				<button type="button" class="btn btn-info" id="add_new_spec" style="margin-left: -50px;">Add new</button>
	      	</div>
	    </div>
		<div class="row"><div class="col-sm-offset-2 col-sm-10">
			<div class="ge-subtitle">Additional specifics:</div><hr>
		</div></div>
		<div id="a_specs_form_inner"></div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-6">
				<button type="submit" class="btn btn-primary">Save</button>
	      		<a type="submit" class="btn btn-warning ge-game-link" target="_blank">Link</a>
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


	<div class="ge-subtitle">Item subtitle:</div><hr>
	<form class="form-horizontal" id="subtitle_form" name="subtitle_form">
		<input type="hidden" name="function" value="ge_update_subtitle">
		<div class="input-group">
			<input id="subtitle_inp" name="subtitle" type="text" class="form-control" placeholder="subtitle">
			<span id="subtitle_length" class="input-group-addon" title="title length (55max)">0</span>
		</div><br>
	  <div class="form-group">
	    <div class="col-sm-10">
	      <button type="submit" class="btn btn-primary">Save</button>
	      <a type="submit" class="btn btn-warning ge-game-link" target="_blank">Link</a>
	    </div>
	  </div>
	</form>


	<div class="ge-subtitle">Item gelery type:</div><hr>
	<form class="form-horizontal" id="gallery_form" name="gallery_form">
		<input type="hidden" name="function" value="ge_update_gelery_type">
		<div class="row">
			<div class="col-xs-6">
				<select class="form-control" name="GalleryType">
				  <option >choose GalleryType</option>
				  <option value="None">None</option>
				  <option value="Gallery">Gallery</option>
				  <option value="Plus">Plus</option>
				  <option value="Featured">Featured</option>
				</select>
			</div>
			<div class="col-xs-6">
				<select class="form-control" name="GalleryDuration">
				  <option >choose GalleryDuration</option>
				  <option value="Days_7">Days_7</option>
				  <option value="Lifetime">LifeTime</option>
				</select>
			</div>
		</div><br>
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
<!-- <script src="multiselect/js/bootstrap-multiselect.js"></script> -->
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

<!-- react 15 -->
<!-- <script src="https://unpkg.com/react@15/dist/react.min.js"></script>
<script src="https://unpkg.com/react-dom@15/dist/react-dom.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.23/browser.min.js"></script> -->

<!-- react 16 -->
<!-- <script src="js/react.production.min.js"></script>
<script src="js/react-dom.production.min.js"></script>
<script src="js/babel.min.js"></script> -->

<!-- <script type="text/babel" src="js/game-editor.jsx"></script> -->

<template id="spec_row_template">
<div class="form-group" id="spec_row{i}">
	<label for="ge{i}" class="col-sm-2 control-label">{spec.Name}</label>
	<div class="col-xs-11 col-sm-9 js-holder-spec_add{i}">
		{inputs}
	</div>
	<div class="col-xs-1 last-col">
		<button type="button" class="btn btn-info js-spec-add" name="{spec.Name}" id="spec_add{i}">
			<i class="glyphicon glyphicon-plus"></i>
		</button>
		<button type="button" class="btn btn-danger js-spec-remove" name="#spec_row{i}">
			<i class="glyphicon glyphicon-trash"></i>
		</button>
		{third_btn}
	</div>
</div>	
</template>

<template id="a_spec_row_template">
<div class="form-group" id="a_spec_row{i}">
	<label for="ge{i}" class="col-sm-2 control-label">{a_spec.Name} <s class="glyphicon glyphicon-comment" data-toggle="tooltip" data-placement="top" title="{tooltip}"></s></label>
	<div class="col-sm-3">
		<input name="input{i}" type="text" class="form-control spec-input js-left-input" value="{old_val}" id="ge{i}">
	</div>
	<div class="col-sm-4">
		<input id="elem{i}" name="specs[{a_spec.Name}][]" type="text" class="form-control spec-input" value="{old_val}" readonly/>
	</div>
	<div class="col-sm-3">
		<select class="form-control js-multiselect" name="multiselect{i}" multiple="multiple" size="1">
	  		{options}
		</select>
	</div>
</div>
</template>

<script>
$(function() {
	
var sfi = document.all.specs_form_inner;
var a_sfi = document.all.a_specs_form_inner;

var spec_row_template_html = $('#spec_row_template').html();
var a_spec_row_template_html = $('#a_spec_row_template').html();

$('#multi_select1').multiselect({
	buttonWidth : '100%'
});

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
  fill_all_forms(suggestion.item_id);
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

$('#specs_form_inner').on('click', '.js-third-btn', function() {
	$('#report_screen').append(galert('success','<pre>'+this.value+'</pre>'));
});

var spec_counter = 0
document.all.add_new_spec.onclick = function() {
	i = spec_counter++;
	var new_spec_name = document.all.new_spec_inp.value;
	var input = '<input name="specs['+new_spec_name+'][]" type="text" class="form-control spec-input" value="" id="ge'+i+'">'
	var specs_html = spec_row_template_html
								.replace(/{i}/g, i)
								.replace(/{spec.Name}/g, new_spec_name)
								.replace(/{inputs}/g, input)
								.replace(/{third_btn}/g, '');
	$(sfi).append(specs_html);
}

var pics_counter = 0;
function get_pic_row(i, pic_url) {
  			return(
'<div class="form-group" id="pic_row'+i+'">'+
	'<label for="gep'+i+'" class="col-sm-2 control-label">'+(i+1)+'</label>'+
	'<div class="col-xs-11 col-sm-9">'+
		'<input name="pic_urls[]" type="text" class="form-control js-pic-input" value="'+pic_url+'" id="gep'+i+'">'+
	'</div>'+
	'<div class="col-xs-1">'+
		'<img src="'+pic_url+'" class="gep'+i+'">'+
	'</div>'+
'</div>');
}

$('#pics_form_inner').on('change', '.js-pic-input', function() {
	$('.'+this.id)[0].src = this.value;
})

document.all.add_new_pic.onclick = function() {
  	$('#pics_form_inner').append(get_pic_row(pics_counter++, ''));
}

// --------------- ebay file downloader -------------------
$('#upload_file_form').on('submit', function(e) {
	e.preventDefault()

	var form = document.getElementById('upload_file_form')
	var fd = new FormData(form)
	var url = "/ajax-controller.php"

	$.ajax({
	  url: url,
	  type: "POST",
	  data: fd,
	  processData: false,  // tell jQuery not to process the data
	  contentType: false,   // tell jQuery not to set contentType
	  dataType: 'json',
	}).done(function(data) {
	  	if(data.FullURL) $('#pics_form_inner').append(get_pic_row(pics_counter++, data.FullURL));
	});
});
//-----------------------------------------------------------

var max_tit_len = 80;
document.all.title_inp.onkeyup = function() {
	if (this.value.length > max_tit_len) {
		this.value = this.value.substr(0, max_tit_len);
	}
	$('#title_length').text(this.value.length);
}

var max_subtit_len = 55;
document.all.subtitle_inp.onkeyup = function() {
	if (this.value.length > max_subtit_len) {
		this.value = this.value.substr(0, max_subtit_len);
	}
	$('#subtitle_length').text(this.value.length);
}

document.forms.ebay_id_enter_form.onsubmit = function(e) {
	e.preventDefault();
	document.all.game_img.src = 'images/SixtyGigGames.png';
  	fill_all_forms(this.ebay_id_enter.value);
}

document.forms.base_data_form.onsubmit = save_form_data;
document.forms.pics_form.onsubmit = save_form_data;
document.forms.specs_form.onsubmit = save_form_data;
document.forms.desc_form.onsubmit = save_form_data;
document.forms.subtitle_form.onsubmit = function(e) {
	if (!confirm("This is a paid feature. Continue?")) return false;
	save_form_data.call(this, e);
};
document.forms.gallery_form.onsubmit = function(e) {
	if (!confirm("This is a paid feature. Continue?")) return false;
	save_form_data.call(this, e);
};

function save_form_data(e) {
	e.preventDefault();
	var post_data = 'ebay_id='+document.all.ebay_id_inp.value+
					'&plattform='+document.all.plattform_select.value+
					'&'+$(this).serialize();
	$.post('/ajax-controller.php', post_data, function(data) {
		$('#report_screen').append(data.report);
		console.dir(data.ebay_resp);
	},'json');
}

function get_spec_row(spec, i) {
}

function get_additional_spec_row(spec, i) {
}

function concatVals(lef_col_str,right_col_str){

		if (lef_col_str && right_col_str) {
			return lef_col_str+','+right_col_str;
		}else if (lef_col_str) {
			return lef_col_str;
		}else if (right_col_str) {
			return right_col_str;
		}else{
			return '';
		}
}

function fill_all_forms(item_id) {
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
		document.all.prim_cat_inp.value = data.Item.PrimaryCategoryID;
		document.all.old_prim_cat_inp.value = data.Item.PrimaryCategoryID;
		$('#title_length').text(data.Item.Title.length);

		var specs = data.Item.ItemSpecifics.NameValueList;
		var specs_keys = data.specs_name_keys;
		// var react_elment = React.createElement(SpecsFormInner,
		// 	{ specs: specs, additional_specs: [] });
		// ReactDOM.render(react_elment, sfi);

  		$.post('/ajax-controller.php',
		  	{function:'ge_get_additional_specifics', cat_id:data.Item.PrimaryCategoryID},
		  	function(data) {
				var props = { specs: specs, a_specs: data.a_specs, 
						specs_keys: specs_keys, a_specs_keys: data.a_specs_name_keys }

				var specs_html = props.specs.map(function(spec,i){
					i = spec_counter++;
					// заполняем template
					if(!props.a_specs_keys[spec.Name]) var third_btn = '';
					else{
						if (props.a_specs_keys[spec.Name].ValidationRules && props.a_specs_keys[spec.Name].ValidationRules.MaxValues && props.a_specs_keys[spec.Name].ValidationRules.MaxValues > 1) {
							var third_btn = '<button type="button" class="btn btn-success js-third-btn" name="third_btn'+i+'" value="'+props.a_specs_keys[spec.Name].values+'" title="'+props.a_specs_keys[spec.Name].tooltip+'"><i class="glyphicon glyphicon-open"></i></button>';
						}else{
							return '';
						}
					}
					var inputs = spec.Value.map(function(value,j){
						return('<input name="specs['+spec.Name+'][]" type="text" class="form-control spec-input" value="'+value+'" id="ge'+i+'">');
					}).join('');
					return spec_row_template_html
								.replace(/{i}/g, i)
								.replace(/{spec.Name}/g, spec.Name)
								.replace(/{inputs}/g, inputs)
								.replace(/{third_btn}/g, third_btn);
				}).join('');
				$(sfi).html(specs_html);

				var a_specs_html = props.a_specs.map(function(a_spec,i){
					if (a_spec.ValidationRules && a_spec.ValidationRules.MaxValues && a_spec.ValidationRules.MaxValues > 1) {
						return;
					}
					i += 100;
					// заполняем template
					var old_val = props.specs_keys[a_spec.Name] ? props.specs_keys[a_spec.Name].Value.toString() : '';
					var options = a_spec.Value.map(function(value,j){
			  			return('<option type="text" value="'+value+'">'+value+'</option>');
			  		}).join('');
					return a_spec_row_template_html
								.replace(/{i}/g, i)
								.replace(/{a_spec.Name}/g, a_spec.Name)
								.replace(/{old_val}/g, old_val)
								.replace(/{options}/g, options)
								.replace(/{tooltip}/g, a_spec.tooltip);
				}).join('');
				$(a_sfi).html(a_specs_html);

				$(a_sfi).on('keyup', '.js-left-input' ,function() {
					var i = this.name.replace('input','');
					var lef_col_str = this.value;
					var select_val = $("[name='multiselect"+i+"']").val()
					var right_col_str = select_val ? select_val : '';
					$('#elem'+i).val(concatVals(lef_col_str,right_col_str));
				})

				$('.js-multiselect').multiselect({
					buttonWidth : '100%',
					onChange: function(element, checked) {
		        		var i = this.$select[0].name.replace('multiselect','');
		        		var lef_col_str = $("[name='input"+i+"']").val();
		        		var right_col_str = this.$select.val()?this.$select.val()+'':'';
						$('#elem'+i).val(concatVals(lef_col_str,right_col_str));
					}
				});

				// $('[data-toggle="tooltip"]').tooltip();

		  	},'json');

  		var pics_html = '';
  		for (var i = 0; i < data.Item.PictureURL.length; i++) {
  			pics_html += get_pic_row(pics_counter++, data.Item.PictureURL[i]);
  		}
  		$('#pics_form_inner').html(pics_html);

  		document.all.desc_inp.value = data.Item.Description;

		if(data.Item.Subtitle){
  			document.all.subtitle_inp.value = data.Item.Subtitle;
			$('#subtitle_length').text(data.Item.Subtitle.length);
		}else{
  			document.all.subtitle_inp.value = '';
			$('#subtitle_length').text(0);
  		}
  	},'json');
}

function forms_reset() {
	document.forms.base_data_form.reset();
	$('#pics_form_inner').html('');
	$('#specs_form_inner').html('');
	$('#a_specs_form_inner').html('');
	document.forms.desc_form.reset();
	document.forms.subtitle_form.reset();
}

});
</script>