<style>	line{ display: block; border-bottom: 1px dashed #777;}</style><pre><?php

use PhpImap\Mailbox as ImapMailbox;
use PhpImap\IncomingMail;
use PhpImap\IncomingMailAttachment;

// $server = new Server('imap.mail.ru');
// $connection = $server->authenticate('thenav@mail.ru', 'kajmadaa');

//4. argument is the directory into which attachments are to be saved:
//$mailbox = new PhpImap\Mailbox('{imap.strato.de:993/imap/ssl/validate-cert}INBOX', 'a3@gig-games.de', A3_GIG_MAIL_PWD, __DIR__.'/../Files');

$mailbox = new PhpImap\Mailbox('{imap.mail.ru:993/imap/ssl/validate-cert}INBOX', 'thenav@mail.ru', 'kajmadaa', __DIR__.'/../Files');

// Read all messaged into an array:
$mailsIds = $mailbox->searchMailbox('ALL');
if(!$mailsIds) {
    die('Mailbox is empty');
}
print_r($mailsIds);  


// for ($i=600; $i < 610; $i++) { 
// 	print_r($mailbox->getMail($i));
// 	echo "<hr>";
// }

// Get the first message and save its attachment(s) to disk:
$mail = $mailbox->getMail(17676);                       //   DODELAT'  DDEBOER/IMAP
print_r($mail);


// echo "\n\n\n\n\n";
// var_dump($mail->getAttachments());

                               // Set mailer to use SMTP
// $mail->Host = 'smtp.strato.de';  // Specify main and backup SMTP servers
// $mail->SMTPAuth = true;                               // Enable SMTP authentication
// $mail->Username = 'a3@gig-games.de';                 // SMTP username
// $mail->Password = A3_GIG_MAIL_PWD;                           // SMTP password
// $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption,
// $mail->Port = 465;
// $mail->CharSet = "UTF-8";                               // TCP port to connect to
// $mail->setFrom('a3@gig-games.de', 'GiG-Games');
// $mail->isHTML(true);   


?></pre>