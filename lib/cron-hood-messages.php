<?php
ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.



// sa(post_curl('http://hood.gig-games.de/api/getMessageText', ['messageId' => '8951628']));

function cron_save_messages($dir = 'inbox'){

	$last_id = arrayDB("SELECT msg_id FROM hood_messages WHERE dir = '$dir' ORDER BY msg_id DESC LIMIT 1");
	if($last_id) $last_id = $last_id[0]['msg_id'];
	else $last_id = '0';
	$urls = [
		'inbox' => 'http://hood.gig-games.de/api/getInboxMessages',
		'outbox' => 'http://hood.gig-games.de/api/getOutboxMessages',
	];
	$res = post_curl($urls[$dir], ['lastInboxMessageId' => $last_id, 'lastOutboxMessageId' => $last_id]);

	foreach ($res as $k => $msg) {
		$msg_id = _esc(trim($msg['idMessage']));
		$issset = arrayDB("SELECT * FROM hood_messages WHERE msg_id = '$msg_id'");
		if($issset) continue;

		$user_id = _esc($msg['userId']);
		$user_name = _esc(trim($msg['userName']));
		$subject = _esc(trim($msg['title']));
		$body = post_curl('http://hood.gig-games.de/api/getMessageText', ['messageId' => $msg_id]);
		if($body) $body = _esc($body);
		else $body = '';
		$date_time = hood_date_format($msg['date']);
		arrayDB("INSERT INTO hood_messages (msg_id,user_id,user_name,subject,body,dir,date_time)
				VALUES ('$msg_id','$user_id','$user_name','$subject','$body','$dir','$date_time')");
	}
	return $res;
}

?>

<div class="container-fluid">
	<div class="row">
		<div class="col-xs-6"><h4>Inbox</h4>
<?php
	sa(cron_save_messages('inbox'));
?>
		</div>
		<div class="col-xs-6"><h4>Outbox</h4>
<?php
	sa(cron_save_messages('outbox'));
?>
		</div>
	</div>
</div>

