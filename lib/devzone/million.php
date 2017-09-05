<?php
ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.

$games = arrayDB("SELECT appid,title,link,reg_price FROM steam_de");

?>

<style>
.m-cont{
	margin-top: 50px;
	padding: 0;
	background: url('images/7x7-1.jpg');
	height: 570px;
}
.m-cont > a{
	display: block;
	float: left;
	width: 5px;
	height: 5px;
}
#m-tooltip{
	position: absolute;
	width: 500px;
	height: 82px;
	padding-left: 181px;
	background: #0a0c1c;
	border: 1px solid #eaeaea;
	top: -82px;
}
#m-tooltip img{
	margin-left: -181px;
	float: left;
	height: 80px;
}
</style>


<div class="container m-cont" id="m-cont">
<div id="m-tooltip"></div>
	<?php foreach ($games as $k => $g) { ?>
		<a href="<?= $g['link'];?>" lang="<?= $g['appid'];?>" id="<?= $g['reg_price'];?>" title="<?= htmlspecialchars($g['title']);?>"></a>
	<?php } ?>
</div>

<style>.m-cont{display: block;}</style>

<script>
$('#m-cont').mousemove(function(e){
	var left = e.pageX  + 20 + 'px';
	var top = e.pageY  - 90 + 'px';
	var t = e.target;
	var h = '<img src="http://cdn.akamai.steamstatic.com/steam/apps/'+e.target.lang+'/header.jpg?"><h4>'+t.title+'</h4><p>'+t.id+'</p>';
	$('#m-tooltip').css({
        top: top,
        left: left
    }).html(h);
    // console.dir(e.target);
});
</script>