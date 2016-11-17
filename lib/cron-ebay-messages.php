<?php
ini_get('safe_mode') or set_time_limit(240); // Указываем скрипту, чтобы не обрывал связь.

function cron_save_inbox()
{
	$ebay_obj = new Ebay_shopping2();
	$msgs_arr = $ebay_obj->GetMessages('inbox', 100);
	$exist100 = arrayDB("SELECT e_MessageID FROM ebay_msgs_inbox ORDER BY id DESC LIMIT 200");
	if($msgs_arr['Ack'] === 'Failure') return;

	$new = [];
	foreach ($msgs_arr['Messages']['Message'] as $key => $msg) {

		if ($msg['Sender'] === 'eBay') continue;
		foreach ($exist100 as $existed) {
			if($existed['e_MessageID'] === $msg['MessageID']) continue 2;
		}
		$new[] = $msg['Subject'];

		$e_MessageID = $msg['MessageID'];
		$e_ExternalMessageID = $msg['ExternalMessageID'];
		$e_ItemID = isset($msg['ItemID']) ? $msg['ItemID'] : '0';
		$e_ItemTitle = isset($msg['ItemTitle']) ? _esc($msg['ItemTitle']) : '';
		$e_Sender = _esc($msg['Sender']);
		$e_Subject = _esc($msg['Subject']);
		$adds = $ebay_obj->GetMessageBody($e_MessageID);
		$e_Body = _esc($adds['msg_body']);
		$e_transId = $adds['transId'];
		$e_MediaURL = _esc(@$msg['MessageMedia']['MediaURL']);
		$e_MediaName = _esc(@$msg['MessageMedia']['MediaName']);

		$e_ReceiveDate = $msg['ReceiveDate'];;
		$d = new DateTime($e_ReceiveDate);
		$e_ReceiveDate = $d->format('Y-m-d H:i:s');

		arrayDB("INSERT INTO ebay_msgs_inbox 
			(e_MessageID,
			e_ExternalMessageID,
			e_ItemID,
			e_transId,
			e_ItemTitle,
			e_Correspondent,
			e_Subject,
			e_Body,
			e_MediaURL,
			e_MediaName,
			e_ReceiveDate) 
	VALUES ('$e_MessageID',
			'$e_ExternalMessageID',
			'$e_ItemID',
			'$e_transId',
			'$e_ItemTitle',
			'$e_Sender',
			'$e_Subject',
			'$e_Body',
			'$e_MediaURL',
			'$e_MediaName',
			'$e_ReceiveDate')");
	}
	return print_r($new, true);
}


function cron_save_outbox()
{
	$ebay_obj = new Ebay_shopping2();
	$msgs_arr = $ebay_obj->GetMessages('outbox', 100);
	$exist100 = arrayDB("SELECT e_MessageID FROM ebay_msgs_outbox ORDER BY id DESC LIMIT 200");
	if($msgs_arr['Ack'] === 'Failure') return;

	$new = [];
	foreach ($msgs_arr['Messages']['Message'] as $key => $msg) {

		if ($msg['Sender'] === 'eBay') continue;
		foreach ($exist100 as $existed) {
			if($existed['e_MessageID'] === $msg['MessageID']) continue 2;
		}
		$new[] = $msg['Subject'];

		$e_MessageID = $msg['MessageID'];
		$e_ExternalMessageID = $msg['ExternalMessageID'];
		$e_ItemID = isset($msg['ItemID']) ? $msg['ItemID'] : '0';
		$e_ItemTitle = isset($msg['ItemTitle']) ? _esc($msg['ItemTitle']) : '';
		$e_SendToName = _esc($msg['SendToName']);
		$e_Subject = _esc($msg['Subject']);
		$adds = $ebay_obj->GetMessageBody($e_MessageID);
		$e_Body = _esc($adds['msg_body']);
		$e_transId = $adds['transId'];
		$e_MediaURL = _esc(@$msg['MessageMedia']['MediaURL']);
		$e_MediaName = _esc(@$msg['MessageMedia']['MediaName']);

		$e_ReceiveDate = $msg['ReceiveDate'];;
		$d = new DateTime($e_ReceiveDate);
		$e_ReceiveDate = $d->format('Y-m-d H:i:s');

		arrayDB("INSERT INTO ebay_msgs_outbox 
			(e_MessageID,
			e_ExternalMessageID,
			e_ItemID,
			e_transId,
			e_ItemTitle,
			e_Correspondent,
			e_Subject,
			e_Body,
			e_MediaURL,
			e_MediaName,
			e_ReceiveDate) 
	VALUES ('$e_MessageID',
			'$e_ExternalMessageID',
			'$e_ItemID',
			'$e_transId',
			'$e_ItemTitle',
			'$e_SendToName',
			'$e_Subject',
			'$e_Body',
			'$e_MediaURL',
			'$e_MediaName',
			'$e_ReceiveDate')");
	}
	return print_r($new, true);
}



if (@$_GET['folder'] === 'inbox') {

	echo '<pre style="white-space: pre-wrap;">';
	echo cron_save_inbox();
	echo '</pre>';

}elseif (@$_GET['folder'] === 'outbox') {

	echo '<pre style="white-space: pre-wrap;">';
	echo cron_save_outbox();
	echo '</pre>';

}

?>