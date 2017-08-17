<script src="//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js"></script>
<link rel="stylesheet" href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css">

<div class="wrapper" id="draggable">
	<h4>Settings</h4>
	<form method="POST" class="form">
		<p></p>
		<h3 style="padding-left: 5px;">Введите необходимые значения и нажмите кнопку "Save"</h3>
		<div class="form-group">
<?php
	if ($_POST) {
		$to_json = array_chunk(json_decode($_POST['array']), 2);
		file_put_contents(ROOT.'/settings/platiru_settings.json', json_encode($to_json));
	}
	$configJSON = file_get_contents(ROOT.'/settings/platiru_settings.json');
	$configArr = json_decode($configJSON, true);
?>
		</div>
		<div class="clearfix rows">
		<?php foreach ($configArr as $key => $val): ?>
			<div class="control-label row">
				<input type="text" class="form-control set col-xs-5" value="<?php echo $val[0]; ?>">
				<input type="text" class="form-control set col-xs-5" value="<?php echo $val[1]; ?>">
				<div class="pull-right del"><b>×</b></div>
			</div>
		<?php endforeach;?>
		</div>
		<div class="button-control">
			<?php if($_POST) echo "<b>saved!</b>";?>
			<div title="reset" class="reset a_demo_one">Reset</div>
			<div title="add string" class="add a_demo_one">Add</div>
			<button id="submit" title="save" class="a_demo_one">Save</button>
		</div>
	</form>
	<a href="index.php?action=platiru_settings&logout=true" class="logout" title="logout">×</a>
</div>

<script>
$(function() {
	var send_data = [
        ' 10 ', ' x ',
        ' 9 ', ' ix ',
        ' 8 ', ' viii ',
        ' 7 ', ' vii ',
        ' 6 ', ' vi ',
        ' 5 ', ' v ',
        ' 4 ', ' iv ',
        ' 3 ', ' iii ',
        ' 2 ', ' ii ',
        ' 1 ', ' i ',
        ' goty', ' game of the year edition',
        ' Edition', '',
        ' DLC', '',
        ' Add-on', '',
        ' Pack', '',
        ' Bundle', ''];

    var post_url = '/index.php?action=platiru_settings';

	$('#submit').on('click', function(e) {
		e.preventDefault();

    var inputs = $('.set');
    var arr = [];
    inputs.each(function(i) {
    	arr.push($( this ).val());
    });
    console.dir(arr);
		$.post(post_url, 'array='+JSON.stringify(arr) );
	});

	$('.add').on('click', function(e) {
		$('.rows').append('<div class="control-label row">'+
				'<input type="text" class="form-control set col-xs-5" value="">'+
				'<input type="text" class="form-control set col-xs-5" value="">'+
				'<div class="pull-right del"><b>×</b></div>'+
			'</div>');
	});

	$('.rows').on('click', '.del', function(e) {
		$(this).parent('.row').remove();
	});

	$('.reset').on('click', function(e) {
		$.post(post_url, 'array='+JSON.stringify(send_data), function(d) {
			location.reload();
		});
	});
	
	$('#draggable').draggable({
        containment: "parent",
        handle: "h4",
        // helper: "clone",
        // opacity: 0.35,
        revert: 'invalid'
    });
});
</script>

<style>
::selection{
	background: #000;
	color: #fff;
}


body{
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    font-size: 14px;
    line-height: 1.42857143;
    background: #fff url(http://blogs.ft.com/photo-diary/files/2014/01/shanghai.jpg) no-repeat;
    background-size: cover;
    margin: 0;
}

.wrapper{
    width: 700px;
    background: rgba(0, 0, 0, 0.4);
    padding: 0 6px 6px 6px;
    color: #333;
    border: 1px solid #878787;
    box-shadow: 0 0 9px #000;
    border-radius: 5px;
    position: relative;
    top: 100px;
    left: 200px;  
}

.wrapper h4 {
    color: #fff;
    margin: 0 -6px;
    padding: 6px 6px 8px;
}

.wrapper form{
    background: #fff;
    padding: 5px;
    background: linear-gradient(135deg, #f5f6f6 0%,#b8bac6 12%,#b8bac6 26%,#dbdce2 55%,#dddfe3 80%,#f5f6f6 100%); 
    background: linear-gradient(135deg, #f6f8f9 0%,#d7dee3 22%,#d7dee3 22%,#e5ebee 50%,#f5f7f9 100%);
}

.del{
    cursor: pointer;
    margin: 5px;
	background-color: #3bb3e0;
    border: solid 1px #186f8f;
        border-radius: 3px;
          -webkit-user-select: none;  /* Chrome all / Safari all */
  -moz-user-select: none;     /* Firefox all */
  -ms-user-select: none;      /* IE 10+ */
  user-select: none;  
}

.del:hover{
    background: #FC5858;
}

.button-control{
	text-align: right;
    padding: 15px 10px;
    position: relative;
    z-index: 1;
}

.control-label{

}

.control-label b {
    display: inline-block;
    line-height: 23px;
    padding: 0 10px;
    color: #fff;
        text-align: right;
}

.control-label input{
    border: solid 1px #8BC3D8;
    background: #FFFFFF;
    line-height: 23px;
    padding: 0 10px;
    margin: 5px;
    width: 45%;
    color: #353535;
}

.control-label input:focus{
	background: #E1EDFF;
}

.logout{
	position: absolute;
    top: 0;
    right: 0;
    text-decoration: none;
    color: #fff;
    border: 1px solid #999;
    background: red;
    background: linear-gradient(to bottom, #f3c5bd 0%,#e86c57 50%,#ea2803 51%,#ff6600 75%,#c72200 100%);
    padding: 3px 10px;
    line-height: 10px;
    font-size: 20px;
    font-weight: bold;
    border-radius: 1px 3px 1px 4px;
}

.rows{
	padding: 15px;
}

.rows input[type=text]{
	height: 26px;
}
</style>
