<?php ini_get('safe_mode') or set_time_limit(200); // Указываем скрипту, чтобы не обрывал связь.

$jpegs = json_decode(file_get_contents('csv/jpegs.json'), true);

// sa($jpegs);
?>
<style>
	.js-url{
		cursor: pointer;
	}
	.js-url:focus{
		background: #555;
	}
</style>
<img src="<?= $jpegs[0];?>" alt="" id="js_img" style="position: fixed; top: 52px; right: 0; max-width: 500px;">
<div class="container" id="js_deligator">
<?php
foreach ($jpegs as $k => $jpeg) {
	echo '<div class="js-url" tabindex="',$k+1,'">',$jpeg,'</div>';
}
?>
</div>
<script>
$('#js_deligator').on('click', '.js-url', function(){
	var url = this.innerHTML;
	$('#js_img')[0].src = '';
	$('#js_img')[0].src = url;
});
</script>