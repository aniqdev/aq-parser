<?php

aqs_pagination('ebay_invoices');

$offset = @$_GET['offset'] ? (int)$_GET['offset'] : 0;
$limit = @$_GET['limit'] ? (int)$_GET['limit'] : 10;

	if(isset($_GET['offset']) && isset($_GET['limit'])){
		$limit = 'LIMIT '.(int)$_GET['offset'].','.(int)$_GET['limit'];
	}else{
		$limit = 'LIMIT 10';
	}

$trusteesArr = arrayDB("SELECT product_api_link,product_frame_link FROM ebay_invoices ORDER BY id DESC $limit");

foreach ($trusteesArr as $key => $trustee):

// 11. Получить товар товар. Логировать итог.
// Входные данные:
	echo "<div style='border:2px dashed red; margin:10px'></div>";
	$received_item = get_item_xml($trustee['product_api_link']);
	if (!$received_item['success']) {
		var_dump("<br>Not success ");
		continue;
	}
	$product = $received_item['result'];

	echo "<pre>$product</pre>";
	//&oper=checkpay
    echo '<iframe class="invoice-iframe" src="',$trustee['product_frame_link'],'">
        Ваш браузер не поддерживает плавающие фреймы!
     </iframe>';


endforeach; ?>