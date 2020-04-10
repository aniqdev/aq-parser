<pre>
<?php


$res = arrayDB("SELECT * from ebay_automatic_log where order_id=2705");

//var_dump($res);

$arr = json_decode($res[0]['invoice_resp'], true);

print_r($arr);




?>
<hr>

</pre>