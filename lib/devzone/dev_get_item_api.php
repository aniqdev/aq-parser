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
// 12. Отправить товар. Логировать итог.
// Входные данные:

	$msg_email = get_messages_for_send_producr('EN', 'mail');
	$msg_ebay = get_messages_for_send_producr('EN', 'ebay');

	$item_title = 'Title of a product...';

	$product = iconv('CP1251', 'UTF-8', $product);
	$product = get_steam_key_from_text($product);
	$product = get_urls_from_text($product);

	$msg_email = str_replace('{{PRODUCT}}', $product, $msg_email);
	$msg_ebay = str_replace('{{EMAIL}}', 'example@site.com', $msg_ebay);

	$msg_email = key_link_replacer($msg_email);

?>
	<div class="container">
		<form class="row" method="POST" id="js-inv-sendemail-form">

			<div class="col-sm-6">
				<textarea class="form-control" name="email_body" id="editor1" cols="30" rows="11" resize="both"><?php echo $msg_email; ?></textarea>
			</div>

			<div class="col-sm-6">
				<textarea class="form-control" name="ebay_body" id="" cols="30" rows="11"><?php echo $msg_ebay; ?></textarea>
			</div>

		</form>
	</div>
<?php endforeach; ?>