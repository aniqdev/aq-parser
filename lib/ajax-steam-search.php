<?php

$q = _esc($_GET['q']);

$q = 'need';

$res = arrayDB("SELECT title,link FROM steam WHERE title LIKE '%$q%' LIMIT 10");


?>


<pre>
	
	<?= print_r($res,1)?>

</pre>