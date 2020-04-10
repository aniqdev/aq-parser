<?php



$emails = arrayDB("SELECT msg_email,email_slug FROM ebay_automatic_log WHERE email_slug <> ''");

sa($emails);

foreach ($emails as $key => $email) {
	$email_slug = _esc($email['email_slug']);
	$email_body = _esc($email['msg_email']);
	arrayDB("INSERT INTO gig_email_saver (email_slug,body_html) 
			VALUES ('$email_slug','$email_body')");
}


?>