<style>
html,body{
	height: 100%;
	margin: 0;
}
#round{
	height: 50px;
	width: 50px;
	position: fixed;
	border-radius: 50px;
	/* border: 2px solid grey; */
	background: #999;
	user-select: none;
	cursor: pointer;
	top: 100px;
	left: 100px;
	z-index: 5000;
}
</style>

<div id="round"></div>

<script>

var round = document.all.round;

var windowWidth = $(window).width();
var windowHeight = $(window).height();

console.dir(round);

var r = {holded : false};
document.body.onmousedown = function(e) {
	// console.log(e);
	if(e.target.id !== 'round') return;
	// console.log('asd');
	r.holded = true;
	r.x = e.offsetX;
	r.y = e.offsetY;
	// console.log(r);
	// this.style.left = e.clientX+'px';
	// this.style.top = e.clientY+'px';
}
document.body.onmouseup = function(e) {
	// console.log(e);
	r.holded = false;
	console.log('up');
	// this.style.left = e.clientX+'px';
	// this.style.top = e.clientY+'px';
}
document.body.onmousemove = function(e) {
	if (!r.holded) return;
	// console.log(e.clientX-r.x);
	// console.log(e.pageX-r.x);

	round.style.left = (e.pageX-r.x)+'px';
	round.style.top = (e.pageY-r.y)+'px';
}

</script>