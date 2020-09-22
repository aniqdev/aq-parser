<?php

$test = '{"sendebay_ans":{"Timestamp":"2017-03-08T08:33:54.829Z","Ack":"Success","Version":"963","Build":"E963_CORE_APIMSG_17909225_R1"},"sendemail_ans":true,"marked":"no","reload":"no","post":{"sendemail":"1","ebay_orderid":"122325326998-1760861005002","user_email":"cobo2004@web.de","email_subject":"Activation data for: DiRT 3 Complete Edition PC spiel","email_body":"<p>Thank you for your order!<\/p>\n<p>Activation link\/key:<\/p>\n<p>BMZ7L-Q0802-AY3LV<\/p>\n<br>\n<p>\nKind regards, Team gig-games.de<\/p>\n","ebay_order_item_id":"5557","sendebay":"1","ebay_user":"cobo2004","ebay_item":"122325326998","ebay_subject":"Activation data for: DiRT 3 Complete Edition PC spiel","ebay_body":"Thank you for your order! The activation data for the game have already been sent to your email address cobo2004@web.de, please check it (and Spam folder too \u00a0:) )\nKind Regards, GIG Games Team\n"},"mark_as_shipped_ans":{"Timestamp":"2017-03-08T08:33:53.252Z","Ack":"Success","Version":"1001","Build":"E1001_CORE_APIXO_18331499_R1"},"errors":[], "POST":'.json_encode($_POST).'}';



if (defined('DEV_MODE')) {
	echo $test;
	die;
}

function axaj_send_email(){
	
	if(!filter_var(trim($_POST['user_email']), FILTER_VALIDATE_EMAIL)) return 'no email ('.$_POST['user_email'].')';

	file_put_contents(ROOT.'/lib/adds/invoice-sender-log.html', print_r($_POST,1));

	$email_alt_body = strip_tags($_POST['email_body']);
	$email_subject = trim($_POST['email_subject']." ");

	$msg_email_2018 = get_mail2018_template($_POST['country_alias']?$_POST['country_alias']:'DE');
	$msg_email_2018 = str_replace('{{PRIVATE_MAIL_LINK}}', private_mail_link($_POST['secret_hash']), $msg_email_2018);

	$mail = get_a3_smtp_object();
	$mail->addAddress($_POST['user_email']);
	$mail->addBCC('thenav@mail.ru');
	$mail->addBCC('store@gig-games.de');
	$mail->Subject = $email_subject;
	// $mail->Body = str_replace('<!-- mail_link_block -->', mail_link_block($_POST['secret_hash'], 'EN'), $_POST['email_body']);
	// $mail->Body = str_replace('{{PRIVATE_MAIL_LINK}}', private_mail_link($_POST['secret_hash']), $mail->Body);
	$mail->Body = $msg_email_2018;
	$mail->AltBody = strip_tags($msg_email_2018);



	$email_slug = _esc($_POST['secret_hash']);
	$email_subject = _esc($email_subject);
	$email_body = _esc(str_replace('<!-- facebook_paragraph -->', get_facebook_paragraph($_POST['ebay_item']?$_POST['ebay_item']:0), $_POST['email_body']));
	arrayDB("INSERT INTO gig_email_saver (email,email_slug,subject,body_html,errors) 
			VALUES ('"._esc($_POST['user_email'])."','$email_slug','$email_subject','$email_body','"._esc($_ERRORS)."')");

	if (!$mail->send()) return false;
	return true;
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
	$is_public = isset($_POST['is_public']) ? 'true' : 'false';
	return $ebayObj->AnswerQuestion($_POST['correspondent'], $_POST['message_id'], $_POST['text'], $is_public);
}


$sendemail_ans = 'no';
$mark_as_shipped_ans = 'no';
if (!defined('DEV_MODE') && isset($_POST['sendemail'])){
	
	$sendemail_ans = axaj_send_email();
	if ($sendemail_ans && isset($_POST['ebay_orderid'])) {
		$mark_as_shipped_ans = ajax_mark_as_shipped($_POST['ebay_orderid']);
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
		'reload' => $reload,
		'post' => $_POST,
		'mark_as_shipped_ans' => $mark_as_shipped_ans,
		'errors' => $_ERRORS,
	]);