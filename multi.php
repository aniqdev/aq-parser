
<pre>
	
	<?php

		var_dump(date('d-m-Y',1447712322));
		var_dump(date('d-m-Y',16755*24*60*60));
var_dump(file_get_contents('http://www.koeln-bonn-airport.de/index.php?eID=ajax_fs&mode=S&page=1&q=6498&sd=0'))
	?>

</pre>
<script>
	var dd = new Date(16755*24*60*60*1000);
	document.write(dd.getDate(),'-',dd.getMonth()+1,'-',dd.getFullYear())
</script>
<?php


?>