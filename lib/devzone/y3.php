<div style="display: block; margin: 250px auto; width: 100px; text-align: center;">
	<h1 id="hh"></h1>
	<button id="aa">true</button>
	<button id="bb">false</button>
</div>


<script>
var t = 0;
var f = 0;
	$('#hh').html(t+'/'+(t+f));
$('#aa').click(function() {
	if(t+f >= 100) return;
	$('#hh').html(++t+'/'+(t+f));
})
$('#bb').click(function() {
	if(t+f >= 100) return;
	$('#hh').html(t+'/'+(t+ ++f));
})
</script>