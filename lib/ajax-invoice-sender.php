<?php

function axaj_send_email(){
	
	if(!filter_var(trim($_POST['user_email']), FILTER_VALIDATE_EMAIL)) return 'no email ('.$_POST['user_email'].')';

	$mail = get_a3_smtp_object();
	$mail->addAddress($_POST['user_email']);
	$mail->addBCC('thenav@mail.ru');
	$mail->addBCC('store@gig-games.de');
	$mail->Subject = $_POST['email_subject'];
	$mail->Body    = $_POST['email_body'];
	$mail->AltBody = strip_tags($_POST['email_body']);

	return $mail->send();	
}

function axaj_send_ebay(){

	$ebayObj = new EbayOrders();

	$userId = htmlspecialchars(stripslashes(strip_tags($_POST['ebay_user'])));
	$itemId = htmlspecialchars(stripslashes($_POST['ebay_item']));
	$subject = htmlspecialchars(stripslashes($_POST['ebay_subject']));
	$body = htmlspecialchars(stripslashes(strip_tags($_POST['ebay_body'])));

	return $ebayObj->SendMessage($userId, $itemId, $subject, $body);
}

function ajax_send_answer(){

	if(!isset($_POST['correspondent']) || !isset($_POST['message_id'])) return false;
	$ebayObj = new Ebay_shopping2();
	return $ebayObj->AnswerQuestion($_POST['correspondent'], $_POST['message_id'], $_POST['text']);
}


$sendemail_ans = 'no';
$m = 'no';
if (!defined('DEV_MODE') && isset($_POST['sendemail'])){
	
	$sendemail_ans = axaj_send_email();
	if ($sendemail_ans && isset($_POST['ebay_orderid'])) {
		$ebayObj = new EbayOrders();
		$OrderID = $_POST['ebay_orderid'];
		$m = $ebayObj->MarkAsShipped($OrderID);
		if ($m['Ack'] == 'Success') {
			arrayDB("UPDATE ebay_orders SET ShippedTime=CURRENT_TIMESTAMP WHERE order_id='$OrderID'");
		}
	}
}


$sendebay_ans = 'no';
if (!defined('DEV_MODE') && isset($_POST['sendebay'])){
	$sendebay_ans = axaj_send_ebay();
} 

$reload = 'no';
if (!defined('DEV_MODE') && isset($_POST['sendanswer'])){
	$sendebay_ans = ajax_send_answer();
	if ($sendebay_ans['Ack']==='Success') {
		include_once __DIR__.'/cron-ebay-messages.php';
		cron_save_outbox();
		$reload = 'yes';
	}
	$sendebay_ans = print_r($sendebay_ans, true);
}


echo json_encode([
		'sendebay_ans' => $sendebay_ans,
		'sendemail_ans' => $sendemail_ans,
		'marked' => $m,
		'reload' => $reload,
		'post' => $_POST,
		'errors' => $_ERRORS,
	]);