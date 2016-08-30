<pre>
<?php
// $db = new DB();
// $res = $db->get_results("SHOW COLUMNS FROM blacklist");
// print_r($res);


$xmlStr = '<?xml version="1.0" encoding="windows-1251"?><digiseller.response><retval>0</retval><retdesc></retdesc><inv><id>50566195</id><name><![CDATA[STEAM КЛЮЧ]]></name><type_good>1</type_good><wm_id>568398645946</wm_id><link>https://www.oplata.info/info/buy.asp?id_i=50566195&uid=591231DE6C554DA0A9CEBAE52AB4C678</link><wm_inv>638961484</wm_inv><wm_purse>R781352104789</wm_purse><uid>591231DE6C554DA0A9CEBAE52AB4C678</uid></inv></digiseller.response>';

$xmlObj = simplexml_load_string(str_replace('&', '&amp;', $xmlStr));

$inv = (array)$xmlObj->inv;

print_r($inv);
echo "<hr>";
var_dump($xmlObj);

?>
</pre>