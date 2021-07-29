<link rel="stylesheet" href="css/css-cube.css">


<div class="scene">
	<div class="cube">
		<div class="front">front.</div>
		<div class="back">back.</div>
		<div class="left">left.</div>
		<div class="right">right.</div>
		<div class="top">top.</div>
		<div class="bottom">bottom.</div>
	</div>
</div>

<script>
(function () {
	var rotateY = 0,
		rotateX = 0

	document.addEventListener('keydown', function (e) {
			 if (e.keyCode === 37) rotateY -= 4
		else if (e.keyCode === 38) rotateX += 4
		else if (e.keyCode === 39) rotateY += 4
		else if (e.keyCode === 40) rotateX -= 4

		document.querySelector('.cube').style.transform = 
      'rotateY(' + rotateY + 'deg)'+
      'rotateX(' + rotateX + 'deg)';
    })
})();
</script>