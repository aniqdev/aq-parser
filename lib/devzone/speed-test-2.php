<?php

$start_time = time();

$dirs = scandir(ROOT.'/steam-images');

unset($dirs[0]);
unset($dirs[1]);

?>
<style>
.card-row{
	    display: flex;
    flex-wrap: wrap;
	padding: 10px;
}
#card{
    background: #eaeaea;
    padding: 8px;
    color: #333;
}
.elems{
	border-top: 1px solid #ccc;
	border-left: 1px solid #ccc;
	    padding: 0;
}
.el{
	display: block;
	width: 10px;
	height: 10px;
	border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
	float: left;
}
.st-report{
	    position: fixed;
    right: 5px;
    top: 52px;
}
</style>
<div class="container">
	<h2>many items test</h2>
	<form>
		<input type="hidden" name="action" value="<?= $_GET['action']; ?>">
		<div class="btn-group" role="group" aria-label="...">
		    <button type="submit" name="limit" value="1000" class="btn btn-default">1k</button>
		    <button type="submit" name="limit" value="5000" class="btn btn-default">5k</button>
		    <button type="submit" name="limit" value="10000" class="btn btn-default">10k</button>
		    <button type="submit" name="limit" value="30000" class="btn btn-default">30k</button>
		</div>
		<button type="button" id="colorize" class="btn btn-default" style="margin-left: 25px;">colorize</button>
	</form>
	<div class="row card-row">
		<div class="col-sm-2">
			<div id="card"></div>
		</div>
		<div class="col-sm-10 elems" id="elems">
<?php
$limit = (isset($_GET['limit']) ? $_GET['limit'] : 1000)+1;
$total_size = 0;
foreach ($dirs as $k => $dir) :
	if($k > $limit) break;
	$file_path = ROOT.'/steam-images/'.$dir.'/header.jpg';
	if(!file_exists(ROOT.'/steam-images/'.$dir.'/header.jpg')) continue;

	$appid = @explode('-', $dir)[1];
	$app_sub = @explode('-', $dir)[0];
	$app_sub = substr($app_sub, 0, 3);
	$src = 'steam-images/'.$dir.'/header.jpg';
	$total_size += filesize($file_path);
?>

<a  class="el" 
	data-href="http://store.steampowered.com/<?= $app_sub; ?>/<?= $appid; ?>/"
	data-src="<?= $src; ?>"
	data-title="<?= $dir; ?>"
	data-price="<?= rand(1,100); ?>"
	href="http://store.steampowered.com/<?= $app_sub; ?>/<?= $appid; ?>/"
	title="<?= $dir; ?>"
	target="_blank"></a>

	<?php
endforeach;
?>
		</div>
	</div>
</div>

<div class="st-report">
<?php
$time = time() - $start_time;
sa([
	'total size' => round($total_size/1000000, 2).'Mb',
	'total time' => $time.'s',
]);
?>
</div>

<template id="tpl">
	<div class="card text-center">
		<img class="card-img-top" style="max-width: 100%;" src="$src" width="146" height="68">
		<div class="card-body">
			<h5 class="card-title">$title</h5>
			<p class="card-text">$price</p>
			<div class="text-center">
				<a href="$href" target="_blank" class="btn btn-primary">buy</a>
			</div>
		</div>
	</div>
</template>

<script>

var tpl_html = $('#tpl').html();

function load_item() {
	$('#card').html(tpl_html.replace('$src', this.dataset.src)
							.replace('$title', this.dataset.title)
							.replace('$price', this.dataset.price+'.00$')
							.replace('$href', this.dataset.href));
}

load_item.call($('.el')[0]);

$('#elems').on('mouseover', '.el', function() {
	load_item.call(this);
});


function hashCode(str) { // java String#hashCode
    var hash = 0;
    for (var i = 0; i < str.length; i++) {
       hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    return hash;
} 

function intToRGB(i){
    var c = (i & 0x00FFFFFF)
        .toString(16)
        .toUpperCase();

    return "00000".substring(0, 6 - c.length) + c;
}

$('#colorize').on('click', function() {
	$('.el').map(function(i,el) {
		var color = intToRGB(hashCode(el.title));
		el.style = 'background:#'+color;
	});
})

</script>