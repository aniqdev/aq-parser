<pre><?php


	// $mail = get_a3_smtp_object();
	// $mail->addAddress($to);
	// $mail->Subject = $subject;
	// $mail->Body    = $message;
	// $mail->AltBody = strip_tags($message);
	// $mail->send();

	// $mail = get_a3_smtp_object();
	// $mail->addAddress('thenav@mail.ru');
	// $mail->addBCC('nameaniq@gmail.com');
	// $mail->Subject = '$subject';
	// $mail->Body    = '<i>$message</i>';
	// $mail->AltBody = strip_tags('$message alt');
	// var_dump($mail->send());

$arr = [1,2,3];
$arr[2] = 'asd';
$arr['2'] = 'qwe';
print_r($arr);
var_dump($arr[2] === $arr['2']);

?>
</pre>