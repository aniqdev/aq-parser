<hr>
<?php

$_POST['tch-order-orderid'] = '203';
$_POST['tch-order-itemid'] = '111538503643';

$receive_item_link = 'https://shop.digiseller.ru/xml/purchase.asp?id_i=51453514&uid=61AF12D9BFED49D0AB20E491D6891259';

$product = '';
$received_item = get_item_xml($receive_item_link);
if($received_item['success'] === 'OK'){

	$product = $received_item['result'];
	echo "<pre>";
	print_r($product);
	print_r(get_steam_key_from_text($product));
	echo "</pre>";

	if (isset($_POST['tch-order-orderid']) && isset($_POST['tch-order-itemid']) && $_POST['tch-order-orderid'] && $_POST['tch-order-itemid']) {
		sugest_send_product($product);
	}else{
		echo 'Отсутствуют данные о заказе. Видимо товар куплен напрямую';
	}

}else{

	echo "<pre>";
	print_r($received_item);
	echo "</pre>";

}




?>