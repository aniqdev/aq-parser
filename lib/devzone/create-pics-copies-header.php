<?php

use Imagine\Image\Box;
if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');
	$offset = (int)$_POST['offset'];

	$table = 'steam_de';
	$where = "WHERE pics <> ''";

	$count = arrayDB("SELECT count(*) FROM $table $where")[0]['count(*)'];

	$res = arrayDB("SELECT id,type,appid,link,title,pics FROM $table $where LIMIT $offset , 1");


	//=============================================================================
	$id = $res[0]['id'];
	$type = $res[0]['type'];
	$appid = $res[0]['appid'];

	if (strpos($res[0]['pics'], 'header') === false) {
		echo json_encode([
			'offset' => $offset,
			'count' => $count,
			'res' => $res[0],
			'resp' => 'NO HEADER ! ! !',
			'ERRORS' => $_ERRORS,
		]);
		return;
	}

	$imagine = new Imagine\Gd\Imagine();

	$dir_path = get_steam_images_dir_path($type, $appid);
	$header_path = $dir_path.'/header.jpg';
	$h_path = $dir_path.'/header-80p.jpg';
	$s_path = $dir_path.'/header-180x84.jpg';
	$m_path = $dir_path.'/header-210x98.jpg';
	$l_path = $dir_path.'/header-256x120.jpg';
	
	$jpeg_opts = ['quality' => 80];

	$imagine->open($header_path)
	        ->save($h_path, $jpeg_opts);

	$imagine->open($header_path)
	        ->resize(new Box(180, 84))
	        ->save($s_path, $jpeg_opts);

	$imagine->open($header_path)
	        ->resize(new Box(210, 98))
	        ->save($m_path, $jpeg_opts);

	$imagine->open($header_path)
	        ->resize(new Box(256, 120))
	        ->save($l_path, $jpeg_opts);


	$dir_path = get_steam_images_dir_path($type, $appid);

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
		'img_src' => get_steam_images_dir_url($type, $appid).'/header-180x84.jpg',
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
var from = 40000;
var to = 400000;
function send_post(offset) {
	$.post('ajax.php' + window.location.search,
		{action:'iterate', offset:offset},
		function (data) {
			if (offset < data.count && offset < to) {
				if (data.resp.Ack) 	var add = data.resp.Ack;
				else var add = data.resp;
				document.all.img_report.src = data.img_src;
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