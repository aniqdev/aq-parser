<pre><?php

$receive_item_link = 'https://shop.digiseller.ru/xml/purchase.asp?id_i=51453514&uid=61AF12D9BFED49D0AB20E491D6891259';

//$receive_item_xml = file_get_contents($receive_item_link);



print_r(get_item_xml($receive_item_link));




?></pre>