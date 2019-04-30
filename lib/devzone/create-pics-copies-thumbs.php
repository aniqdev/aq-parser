<?php

function cerate_thumbs($dir_path, $i)
{
	$big1_path = $dir_path.'/big'.$i.'.jpg';

    $imagine = new Imagine\Gd\Imagine();
    $mode = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
	try {
	    $imagine->open($big1_path)
	        ->thumbnail(new Imagine\Image\Box(470, 1000), $mode)
	        ->save($dir_path.'/thumb-'.$i.'-m.jpg');

	    $imagine->open($big1_path)
	        ->thumbnail(new Imagine\Image\Box(1000, 120), $mode)
	        ->save($dir_path.'/thumb-'.$i.'-s.jpg');
	        return 'good';
	} catch (Exception $e) {
	    return $e->getMessage();
	}
}

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');
	$offset = (int)$_POST['offset'];

	$table = 'steam_de';
	$where = "WHERE pics <> '' AND pics like '%big%' AND pics not like '%thumb%'";

	$count = arrayDB("SELECT count(*) FROM $table $where")[0]['count(*)'];

	$res = arrayDB("SELECT id,type,appid,link,title,pics FROM $table $where LIMIT $offset , 1");


	//=============================================================================
	$id = $res[0]['id'];
	$type = $res[0]['type'];
	$appid = $res[0]['appid'];

	if (strpos($res[0]['pics'], 'big') === false) {
		echo json_encode([
			'offset' => $offset,
			'count' => $count,
			'res' => $res[0],
			'resp' => 'NO SCRINSHOOT ! ! !',
			'ERRORS' => $_ERRORS,
		]);
		return;
	}

	$dir_path = get_steam_images_dir_path($type, $appid);

	if (strpos($res[0]['pics'], 'big1') !== false) $rep1 = cerate_thumbs($dir_path, 1);
	if (strpos($res[0]['pics'], 'big2') !== false) $rep2 = cerate_thumbs($dir_path, 2);
	if (strpos($res[0]['pics'], 'big3') !== false) $rep3 = cerate_thumbs($dir_path, 3);
	if (strpos($res[0]['pics'], 'big4') !== false) $rep4 = cerate_thumbs($dir_path, 4);



	$dir = @scandir($dir_path);

	if ($dir) {

		$dir = array_slice($dir, 2);

		$pics = implode(',', $dir);

		if ($pics) {

			arrayDB("UPDATE $table SET pics = '$pics' WHERE id = '$id'");

			$dir_path = $pics;
		}
	}

	//=============================================================================


	echo json_encode([
		'offset' => $offset,
		'count' => $count,
		'res' => $res[0],
		'resp' => $dir_path,
		'img_src' => get_steam_images_dir_url($type, $appid).'/thumb-1-s.jpg',
		'$rep1' => @$rep1,
		'$rep2' => @$rep2,
		'$rep3' => @$rep3,
		'$rep4' => @$rep4,		
		'ERRORS' => $_ERRORS,
	]);
}


// sa($_ERRORS);

if($_POST) return;
?>
<style>
#img_report{
	float: right;
	margin-right: 100px;
}
</style>

<h3><?= str_replace('devzone/', '', $_GET['action']); ?></h3>
<form id="js_go_form" class="go-form">
    <button name="aaa" value="bbb" type="button" class="js-go-btn">Go!</button>
</form><br><br><br>
<span class="loading"></span>
<img src="https://parser.gig-games.de/steam-images/apps-219760/header-180x84.jpg" alt="" id="img_report">
<h3>Состояние процесса:</h3>
<ul id="message" class="message"><li></li></ul>

<script>
function it_ins_msg(msg) {
	$( "#message li:first" ).before( "<li>"+msg+"</li>" );
	if($('#message li').length > 100) {
		$('#message li:last').remove();
	}
}
var from = 0
var to = 450000;
function send_post(offset) {
	$.post('ajax.php' + window.location.search,
		{action:'iterate', offset:offset},
		function (data) {
			if (offset < data.count && offset < to) {
				if (data.resp.Ack) 	var add = data.resp.Ack;
				else var add = data.resp;
				if(data.img_src) document.all.img_report.src = data.img_src;
				it_ins_msg(offset + ' : <a href="'+data.res.link+'" target="_blank">' + data.res.title + '</a> | ' + add);
				send_post(offset+1);
			}else{
				$('.loading').removeClass('inaction');
				it_ins_msg('Done!');
				it_ins_msg( "или что-то пошло не так" );
			}
		}, 'json');
}
$('.js-go-btn').on('click', function() {
	$(this).attr('disabled','true');
	send_post(from);
});
</script>