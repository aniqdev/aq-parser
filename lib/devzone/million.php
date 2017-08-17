<?php
ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.

$games = arrayDB("SELECT appid,title,link,reg_price FROM steam_de");

?>

<style>
.m-cont{
	margin-top: 50px;
}
.m-cont > a{
	display: block;
	float: left;
	/* border: 1px solid #888; */
	/* background: #888; */
	width: 5px;
	height: 5px;
}
.m-cont > a:nth-child(7n+1){	background: #eaeaea;}
.m-cont > a:nth-child(7n+2){	background: #fbb;}
.m-cont > a:nth-child(7n+3){	background: #888;}
.m-cont > a:nth-child(7n+4){	background: blue;}
.m-cont > a:nth-child(7n+5){	background: green;}
.m-cont > a:nth-child(7n+6){	background: aqua;}
.m-cont > a:nth-child(7n+7){	background: maroon;}
#m-tooltip{
	position: absolute;
	width: 500px;
	height: 82px;
	padding-left: 181px;
	background: #0a0c1c;
	border: 1px solid #eaeaea;
	/* box-sizing: border-box; */
}
#m-tooltip img{
	margin-left: -181px;
	float: left;
	height: 80px;
}
</style>

<div id="m-tooltip"></div>

<div class="container m-cont" id="m-cont">
	<?php foreach ($games as $k => $g) { ?>
		<a href="<?= $g['link'];?>" lang="<?= $g['appid'];?>" id="<?= $g['reg_price'];?>" title="<?= htmlspecialchars($g['title']);?>"></a>
	<?php } ?>
</div>

<script>
$('#m-cont').mousemove(function(e){
	var top = e.pageY  - 90 + 'px';
	var left = e.pageX  + 20 + 'px';
	var t = e.target;
	var h = '<img src="http://cdn.akamai.steamstatic.com/steam/apps/'+e.target.lang+'/header.jpg?"><h4>'+t.title+'</h4><p>'+t.id+'</p>';
	$('#m-tooltip').css({
        top: top,
        left: left
    }).html(h);
    // console.dir(e.target);
});
</script>