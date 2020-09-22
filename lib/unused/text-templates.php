<?php 

$report = '';
$table = 'text_templates';

if (isset($_POST['action']) && $_POST['action'] === 'save_message') {
	header('Content-Type: application/json');
	$record_id = _esc($_POST['record_id']);
	$message_text = _esc($_POST['message_text']);
	if(!$record_id || !$message_text){
		echo json_encode(['status' => 'error', 'report' => 'Not enough data!', 'ERRORS' => $_ERRORS]);
		die;
	}
	$res = arrayDB("UPDATE $table SET tpl_text = '$message_text' WHERE id = '$record_id'");
	echo json_encode(['status' => 'success', 'report' => 'Record successfully saved!', 'res' => $res, 'ERRORS' => $_ERRORS]);
	die;
}

if (isset($_POST['action']) && $_POST['action'] === 'add_record') {
	$category = _esc($_POST['category']);
	$tpl_name = _esc($_POST['tpl_name']);
	$required_text = _esc($_POST['required_text']);
	$res = arrayDB("INSERT INTO $table (category,tpl_name,required_text) VALUES('$category','$tpl_name','$required_text')");
	if($res) $report .= '<div class="alert alert-success alert-dismissible height-anim" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><b>Success!</b> one record added below</div>';
}


?>
<style>
b.col-aqua{
	color: #66cdaa;
}
.button-control.row{
	margin-top: -5px;
    margin-bottom: 10px;
}
pre{
	padding: 5px;
	border-radius: 0;
    background-color: #555;
    border:  none;
}
blockquote {
    padding: 0px 20px;
}
.btn{
	padding: 1px 12px;
}
.btn,
.form-control{
	border-radius: 0;
}
</style>
<div class="container">
<?php
$prev_category = '';
$messages_texts = arrayDB("SELECT * FROM $table ORDER BY category, tpl_name");
foreach ($messages_texts as $k => $str): 

if ($prev_category !== $str['category']) {
	echo '<hr>';
}
$prev_category = $str['category'];
?>
message for: <b class="col-aqua"><?= $str['category'];?></b> | template name: <b class="col-aqua"><?= $str['tpl_name'];?></b>

<pre><code class="html editor<?= $k;?>" contenteditable="true"><?= htmlentities($str['tpl_text']);?></code></pre>

<div class="button-control row">
	<div class="col-xs-2">
		<button name="<?= $str['required_text'];?>" value="editor<?= $k;?>" title="clear" class="btn btn-danger js-clear" id="clear">Clear</button>
		<button name="<?= $str['required_text'];?>" value="editor<?= $k;?>" id="<?= $str['id'];?>" title="save" class="btn btn-primary js-save">Save</button>
	</div>
	<div class="col-xs-10">
		<blockquote class="blockquote-reverse" style="margin:0;"><p class=""><?= $str['required_text']?'shortcode  '.$str['required_text'].' is required for this text':'&nbsp;'; ?></p>
	</div>
</div>

<?php endforeach;?>
	<hr>
	<div class="row">
		<h4 class="col-xs-12">Add record</h4>
		<form class="form-inline col-xs-12" method="POST">
		  <div class="form-group">
		    <label for="exampleInput1">for:</label>
		    <select name="category" class="form-control" id="exampleInput1">
		    	<option value="ebay">ebay</option>
		    	<option value="mail">email</option>
		    	<option value="feedback_7days">feedback 7 days</option>
		    	<option value="feedback_14days">feedback 14 days</option>
		    	<option value="answer_template">answer template</option>
		    	<option value="ebay_messages">messages template</option>
		    	<option value="identify_messages">identify messages</option>
		    	<option value="private_page">private page</option>
		    </select>
		  </div>
		  <div class="form-group">|
		    <label for="exampleInput2">template name:</label>
		    <input name="tpl_name" value="DE" type="text" class="form-control" id="exampleInput2" maxlength="50" placeholder="DE">
		  </div>
		  <div class="form-group">|
		    <label for="exampleInputl3">shortcode:</label>
		    <input name="required_text" type="text" class="form-control" id="exampleInput3" placeholder="{{EXAMPLE}}">
		  </div>
		  <button type="submit" name="action" value="add_record" class="btn btn-primary">Add</button>
		</form>
	</div>
	
</div>


<div id="report_screen" title="report screen">
	<?= $report;?>
</div>


<link rel="stylesheet" href="hljs/styles/agate.css">
<!-- <link rel="stylesheet" href="hljs/styles/railscasts.css"> -->
<script src="hljs/highlight.pack.js"></script>
<script>

$(document).ready(function() {
var galert = function (type,text) {
	return ('<div class="alert alert-'+type+' alert-dismissible height-anim" role="alert">'+
  '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+text+'</div>');
}
  $('pre code').each(function(i, block) {
  	// console.dir(block);
    hljs.highlightBlock(block);
  });

	$('code').on('blur', function() {
		hljs.highlightBlock(this);
  		// console.dir(this);
	});

	$('.js-save').on('click', function () {
		var required = this.name;
		var editor = this.value;
		var record_id = this.id;
		var message_text = $('.'+editor).text();
  		console.dir(this);
  		if(!(message_text.indexOf(required)+1)){
  			$('#report_screen').append(galert('warning','<b>Warning!</b> There is no required shortcode!'));
  			return false;
  		}
  		$.post('ajax.php?action=text-templates',
  			{action:'save_message',record_id:record_id, message_text:message_text},
  			function(data){
  				if (data.status && data.status === 'success') {
  					$('#report_screen').append(galert('success','<b>Success!</b> '+data.report));
  				}else{
  					$('#report_screen').append(galert('danger','<b>Error!</b> '+data.report));
  				}
  			},'json');
	});

	$('.js-clear').on('click', function () {
		$('.'+this.value).html('');
	});

});

</script>