<pre>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

	$mail = get_a3_smtp_object(); // первая
	$mail->addAddress('konstant.i.n@gmx.de');
	$mail->addBCC('aniq.dev@gmail.com');
	$mail->Subject = 'Первое из двух писем отправленных скриптом '.date('H:i:s');
	$mail->Body    = '<h2>Message body html</h2>';
	$mail->AltBody = 'Message body plain';

	//var_dump($mail->send());
	echo '<br>Mailer Error: ' . $mail->ErrorInfo;






echo "<hr>";
	$mail = get_store_smtp_object(); // вторая
	$mail->addAddress('konstant.i.n@gmx.de');
	$mail->addBCC('aniq.dev@gmail.com');
	$mail->Subject = 'Второе из двух писем отправленных скриптом '.date('H:i:s');
	$mail->Body    = '<h2>Message body html</h2>';
	$mail->AltBody = 'Message body plain';

	//var_dump($mail->send());
	echo '<br>Mailer Error: ' . $mail->ErrorInfo;

var_dump(date('H:i:s'));
?>
<hr>
var
</pre>