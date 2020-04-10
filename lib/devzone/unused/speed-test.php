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
.card-col{
	padding: 5px;
}
.card{
	background: #eaeaea;
	margin-bottom: 10px;
	color: #333;
	padding: 3px;
	height: 100%;
}
.card .btn{
	    padding: 1px 10px;
}
.st-report{
	    position: fixed;
    right: 5px;
    top: 70px;
}
.card-img-top{
	max-width: 100%;
	height: auto;
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
	</form>
	<div class="row card-row">
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

<div class="col-xs-3 col-sm-2 col-md-2 card-col">
	<div class="card text-center">
		<img class="card-img-top lazyload" data-src="<?= $src; ?>" alt="100%x180" width="460" height="215">
		<div class="card-body">
			<h5 class="card-title"><?= $dir; ?></h5>
			<p class="card-text"><?= rand(1,100); ?>.00 $</p>
			<div class="text-center">
				<a href="http://store.steampowered.com/<?= $app_sub; ?>/<?= $appid; ?>/" title="<?= $dir; ?>" target="_blank" class="btn btn-primary">buy</a>
			</div>
		</div>
	</div>
</div>

	<?php
endforeach;
?>
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

<script src="js/lazysizes.min.js" async></script>