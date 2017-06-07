<?php
if (isset($_POST['ebay_id']) && isset($_POST['hor_vert'])) {
	$ebay_id = $_POST['ebay_id'];
	$hor_vert = $_POST['hor_vert'];
	$pics = file_get_contents('http://hot-body.net/img-generator/?ebay_id='.$ebay_id.'&hor_vert='.$hor_vert);
	$pics = json_decode($pics, true);
	$ebayObj = new Ebay_shopping2();
	$res = $ebayObj->updateItemPictureDetails($ebay_id, $pics);
	unset($res['Fees']);
	echo json_encode([$pics,$res,$_ERRORS]);
	return;
}




// $limit = aqs_pagination('ebay_games');
// $ebay_games = arrayDB("SELECT * FROM ebay_games LIMIT $limit");

$ebay_games = arrayDB("SELECT * FROM ebay_games");

//sa($ebay_games[0]);
//==============================================================================
//==============================================================================
$str = '122455219488
112377513506
112377513517
112377513525
112377513528
112377513529
112377513533
112377513541
112377513555
112377513558
122455219492
122455219494
122455219496
122455219497
122455219498
122455219501
122455219502
122455219504
122455219506
112377513551
112377513565
112377513574
112377513577
112377513578
112377513583
112377513593
112377513608
122455219507
122455219508
122455219510
122455219513
122455219521
122455219524
122455219527
112377522638
122455219489';

$arr = explode(PHP_EOL, $str);
//==============================================================================
//==============================================================================


?>
<style>
	img{width: 100%;}
	label{user-select: none;}
	label{cursor: pointer;}
	input:checked+label{color: #4597dd;cursor: default;}
</style>
<div class="container">
<?php foreach ($ebay_games as $key => $game): 

if(!in_array($game['item_id'], $arr)) continue;

$image_path = 'http://i.ebayimg.com/images/g/'.$game['picture_hash'].'/s-l200.jpg';
$color = imagecolorat(imagecreatefromjpeg($image_path), 25, 115);
  $r = ($color >> 16) & 0xFF;
  $g = ($color >> 8) & 0xFF;
  $b = $color & 0xFF;
  $is_vert = false;
  if($r > 230 && $g > 230 && $b > 230) $is_vert = true;
//echo '<h3>('.$r.') ('.$g.') ('.$b.')</h3>';
?>
	<form class="row april-forms">
		<div class="col-xs-2">
			<img src="http://i.ebayimg.com/images/g/<?= @$game['picture_hash']?>/s-l200.jpg" alt="">
		</div>
		<div class="col-xs-6">
			<h3 class=""><a href="http://www.ebay.de/itm/<?= $game['item_id'];?>" target="_blank"><?= $game['title_clean']?></a></h3>
				<?= '<h4>('.$r.') ('.$g.') ('.$b.')</h4>';?>
				<h4><?= $game['item_id'];?></h4>
		</div>
		<div class="col-xs-2">
			<input type="radio" name="hor_vert" value="hor" id="hor<?= $key?>" <?= $is_vert?'':'checked';?>>
			<label for="hor<?= $key?>">Horizontal</label>
			<input type="radio" name="hor_vert" value="vert" id="vert<?= $key?>" <?= $is_vert?'checked':'';?>>
			<label for="vert<?= $key?>">Vertical</label>
		</div>
		<div class="col-xs-2">
			<input type="hidden" name="ebay_id" value="<?= $game['item_id']?>">	
			<button class="btn btn-default" type="submit">Update</button>
		</div>
	</form>
	<hr>
<?php endforeach; ?>
</div>

<script>
	$('.april-forms').on('submit', function(e) {
		e.preventDefault();
		var send_data = $(this).serialize();
		$.post('ajax.php?action=april-add-pics',
		send_data,
		function(data) {
			// body...
		});
	});
</script>