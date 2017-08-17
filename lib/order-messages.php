<?php 

$report = '';
$table = 'ebay_inv_messages';

if (isset($_POST['action']) && $_POST['action'] === 'save_message') {
	header('Content-Type: application/json');
	$record_id = _esc($_POST['record_id']);
	$message_text = _esc($_POST['message_text']);
	if(!$record_id || !$message_text){
		echo json_encode(['status' => 'error', 'report' => 'Not enough data!', 'ERRORS' => $_ERRORS]);
		die;
	}
	$res = arrayDB("UPDATE $table SET message = '$message_text' WHERE id = '$record_id'");
	echo json_encode(['status' => 'success', 'report' => 'Record successfully saved!', 'res' => $res, 'ERRORS' => $_ERRORS]);
	die;
}

if (isset($_POST['action']) && $_POST['action'] === 'add_record') {
	$ebay_or_mail = _esc($_POST['ebay_or_mail']);
	$country_alias = _esc($_POST['country_alias']);
	$required_text = _esc($_POST['required_text']);
	$res = arrayDB("INSERT INTO $table (ebay_or_mail,country_alias,required_text) VALUES('$ebay_or_mail','$country_alias','$required_text')");
	if($res) $report .= '<div class="alert alert-success alert-dismissible height-anim" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><b>Success!</b> one record added below</div>';
}


?>
<div class="container"><hr>
<?php
$messages_texts = arrayDB("SELECT * FROM $table");
foreach ($messages_texts as $k => $str): 
?>
message for: <b><?= $str['ebay_or_mail'];?></b> | country code: <b><?= $str['country_alias'];?></b>

<pre><code class="html editor<?= $k;?>" contenteditable="true"><?= htmlentities($str['message']);?></code></pre>

<div class="button-control row">
	<div class="col-xs-2">
		<button name="<?= $str['required_text'];?>" value="editor<?= $k;?>" title="clear" class="a_demo_one js-clear" id="clear">Clear</button>
		<button name="<?= $str['required_text'];?>" value="editor<?= $k;?>" id="<?= $str['id'];?>" title="save" class="a_demo_one js-save">Save</button>
	</div>
	<div class="col-xs-10">
		<blockquote class="blockquote-reverse" style="margin:0;"><p class=""><?= $str['required_text']?'shortcode  '.$str['required_text'].' is required for this text':'&nbsp;'; ?></p>
	</div>
</div><hr>

<?php endforeach;?>

	<div class="row">
		<h4 class="col-xs-12">Add record</h4>
		<form class="form-inline col-xs-12" method="POST">
		  <div class="form-group">
		    <label for="exampleInput1">for:</label>
		    <select name="ebay_or_mail" class="form-control" id="exampleInput1">
		    	<option value="ebay">ebay</option>
		    	<option value="mail">email</option>
		    </select>
		  </div>
		  <div class="form-group">|
		    <label for="exampleInput2">Country code:</label>
		    <input name="country_alias" value="DE" type="text" class="form-control" id="exampleInput2" placeholder="DE">
		  </div>
		  <div class="form-group">|
		    <label for="exampleInputl3">Shortcode:</label>
		    <input name="required_text" type="text" class="form-control" id="exampleInput3" placeholder="{{EXAMPLE}}">
		  </div>
		  <button type="submit" name="action" value="add_record" class="btn btn-primary">Add</button>
		</form>
	</div><hr>
	
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
  		$.post('ajax.php?action=order-messages',
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