<pre><?php
$db = new DB();
$res = $db->get_results("SHOW COLUMNS FROM blacklist");

print_r($res);
?>
</pre>