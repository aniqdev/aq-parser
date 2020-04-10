<div class="container">
<style>
.llt-img{
	display: block;
}
</style>
<?php


$arr = arrayDB("SELECT * FROM steam_de LIMIT 5000,1000");




foreach ($arr as $key => $game) {
	$src = get_steam_images_dir_url($game['type'], $game['appid']).'/header-80p.jpg';
?>
	<img data-srcset="<?= $src; ?>" class="lazyload">
<?php }


?>
</div>
<script src="js/lazysizes.min.js"></script>