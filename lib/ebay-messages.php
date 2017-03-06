<pre><?php

$two_days_ago = date('Y-m-d', time()-(60*60*24*2));

if (isset($_GET['correspondent'])) {
	$corresp = _esc($_GET['correspondent']);
	$messages_all = arrayDB("
		SELECT *,'INBOX' as dir FROM ebay_msgs_inbox WHERE e_Correspondent='$corresp'
		UNION
		SELECT *,'OUTBOX' as dir FROM ebay_msgs_outbox WHERE e_Correspondent='$corresp'
		ORDER BY e_MessageID DESC");
}elseif (@$_GET['show'] === 'not_answerd') {
	$e_MessageID_list = arrayDB("SELECT MAX(e_MessageID) as e_MessageID FROM ebay_msgs_inbox WHERE e_ReceiveDate>'2016-11-06' GROUP BY e_Correspondent  ORDER BY e_MessageID DESC");
	$MessageID_list = [];
	foreach ($e_MessageID_list as $k => $val) {
		$MessageID_list[$val['e_MessageID']] = $val['e_MessageID'];
	}
	$messages_all = arrayDB("
		SELECT *,'INBOX' as dir FROM ebay_msgs_inbox WHERE e_ReceiveDate>'$two_days_ago'
		UNION
		SELECT *,'OUTBOX' as dir FROM ebay_msgs_outbox WHERE e_ReceiveDate>'$two_days_ago'
		ORDER BY e_MessageID ASC LIMIT 200");
	$answerd_list = [];
	foreach ($messages_all as $key => $value) {
		if ($value['dir'] === 'OUTBOX') {
			$answerd_list[$value['e_Correspondent']] = 1;
		}else{
			$answerd_list[$value['e_Correspondent']] = 0;
		}
	}
	$messages_list = arrayDB("
		SELECT *,'INBOX' as dir FROM ebay_msgs_inbox WHERE e_ReceiveDate>'$two_days_ago' ORDER BY e_MessageID DESC LIMIT 200");
	$messages_all = [];
	foreach ($messages_list as $key => $v) {
		if (isset($MessageID_list[$v['e_MessageID']]) && isset($answerd_list[$v['e_Correspondent']]) && $answerd_list[$v['e_Correspondent']] === 0 && $v['status'] === 'neutral') {
			$messages_all[] = $v;
		}elseif ($v['status'] === 'asked') {
			$messages_all[] = $v;
		}
	}
}else{
	$messages_all = arrayDB("
		SELECT *,'INBOX' as dir FROM ebay_msgs_inbox WHERE e_ReceiveDate>'$two_days_ago'
		UNION
		SELECT *,'OUTBOX' as dir FROM ebay_msgs_outbox WHERE e_ReceiveDate>'$two_days_ago'
		ORDER BY e_MessageID DESC LIMIT 200");
}
//print_r($messages_all);

?></pre>
<div class="container">

<a href="?action=ebay-messages&show=not_answerd" class="btn btn-default">New Messages <small>(beta)</small></a>
<a href="?action=ebay-messages" class="btn btn-default">All Messages</a>
<br><br>
<?php if (isset($_GET['correspondent']) && isset($_GET['message_id'])){
	$item_title = $messages_all[0]['e_ItemTitle'];
	if (!$item_title) {
		$item_id = $messages_all[0]['e_ItemID'];
		$title = arrayDB("SELECT title FROM ebay_games WHERE item_id='$item_id'");
		@$item_title = $title[0]['title'];
	}
	$data = ['correspondent'=>$_GET['correspondent'],
			 'message_id'=>$_GET['message_id'],
			 'game_name' => $item_title];
	view('ebay-messages+send-form',['data'=>$data]);
}?>
<ul class="chat">
<?php
function is_chkd($a, $b){if ($a === $b) echo 'checked';}

	foreach ($messages_all as $key => $msg) {
		if ($msg['dir'] === 'INBOX') {
?>
	<li class="left clearfix">
		<div class="chat-img pull-left">
			<img src="images/50px-U.png" alt="User Avatar" class="img-circle">
			<div class="q-status clearfix">
				<input <?php is_chkd($msg['status'], 'asked');?> class="q-radio q-radio1" id="q-radio<?php echo $key; ?>1" type="radio" name="status<?php echo $msg['id']; ?>" value="asked">
				<input <?php is_chkd($msg['status'], 'answerd');?> class="q-radio q-radio2" id="q-radio<?php echo $key; ?>2" type="radio" name="status<?php echo $msg['id']; ?>" value="answerd">
				<label class="q-label q-label1" for="q-radio<?php echo $key; ?>1" title="unsolved"></label>
				<label class="q-label q-label2" for="q-radio<?php echo $key; ?>2" title="solved"></label>
			</div>
		</div>
		<div class="chat-body clearfix">
			<div class="header">
				<a title="<?php echo $msg['e_Subject'];?>" href="?action=ebay-messages&correspondent=<?php echo $msg['e_Correspondent'];?>&message_id=<?php echo $msg['e_ExternalMessageID'];?>"><strong class="primary-font"><?php echo $msg['e_Correspondent']; ?></strong></a>
				<a href="?<?php echo query_to_orders_page(['order_id'=>'0','q'=>$msg['e_transId']]);?>"><small class="text-muted"><?php echo $msg['e_ItemTitle']; ?></small></a>
				<small class="pull-right text-muted">
					<span class="glyphicon glyphicon-time"></span><?php echo $msg['e_ReceiveDate']; ?></small>
			</div>
			<p>
				<?php echo $msg['e_Body']; ?>
			</p>
		</div>
	</li>
<?php
		}else{
?>
	<li class="right clearfix"><span class="chat-img pull-right">
		<img src="images/50px-ME.png" alt="User Avatar" class="img-circle">
	</span>
		<div class="chat-body clearfix">
			<div class="header">
				<small class=" text-muted"><span class="glyphicon glyphicon-time"></span><?php echo $msg['e_ReceiveDate']; ?></small>
				<a title="<?php echo $msg['e_Subject'];?>" href="?action=ebay-messages&correspondent=<?php echo $msg['e_Correspondent'];?>"><strong class="pull-right primary-font"><?php echo $msg['e_Correspondent']; ?></strong></a>
				<small class="pull-right text-muted"><?php echo $msg['e_ItemTitle']; ?>&nbsp;</small>
			</div>
			<p>
				<?php echo $msg['e_Body']; ?>
			</p>
		</div>
	</li>
<?php
		}
	}

?>
</ul>
</div>




















<style>
.chat
{
    list-style: none;
    margin: 0;
    padding: 0;
}

.chat li
{
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px solid #424242;
}

.chat li.left .chat-body
{
    margin-left: 60px;
}

.chat li.right .chat-body
{
    margin-right: 60px;
}


.chat li .chat-body p
{
    margin: 0;
    color: #eaeaea;
}

.panel .slidedown .glyphicon, .chat .glyphicon
{
    margin-right: 5px;
}

.panel-body
{
    overflow-y: scroll;
    height: 250px;
}

::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
}

::-webkit-scrollbar
{
    width: 12px;
    background-color: #F5F5F5;
}

::-webkit-scrollbar-thumb
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
}

</style>