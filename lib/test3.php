<pre>
<?php

$a = obj('querYstrinG')->SET('foo','bar')->del(0,'bar')->give();

var_dump($a);

print_r(ArrayDB("SELECT count(*) as count FROM ebay_orders ORDER BY id DESC"));


?>
</pre>