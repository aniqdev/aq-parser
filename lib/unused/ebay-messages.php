<?php

if (isset($_POST['action']) && $_POST['action'] === 'save_template') {
	
	$tpl_name = _esc($_POST['tpl_name']);
	$tpl_text = _esc($_POST['tpl_text']);
	if (!arrayDB("SELECT id FROM text_templates WHERE tpl_name = '$tpl_name'")) {
		$is_done = arrayDB("INSERT INTO text_templates (category,tpl_name,tpl_text)
			VALUES('ebay_messages','$tpl_name','$tpl_text')");
	}
	echo json_encode(['done' => @$is_done, 'ERRORS' => $_ERRORS]);
	return;
}

$days_ago = date('Y-m-d', time()-(60*60*24*30));

if (isset($_GET['correspondent'])) {
	$corresp = _esc(trim($_GET['correspondent']));
	$messages_all = arrayDB("SELECT msgs.*,ebay_users.is_trusted, ebay_users.is_problematic FROM
		(SELECT *,'INBOX' as dir FROM ebay_msgs_inbox WHERE e_Correspondent='$corresp'
		UNION
		SELECT *,'OUTBOX' as dir FROM ebay_msgs_outbox WHERE e_Correspondent='$corresp') msgs
		left join ebay_users
		on msgs.e_Correspondent = ebay_users.user_id
		ORDER BY e_MessageID DESC");
}elseif (@$_GET['show'] === 'not_answerd') {
	$e_MessageID_list = arrayDB("SELECT MAX(e_MessageID) as e_MessageID FROM ebay_msgs_inbox WHERE e_ReceiveDate>'$days_ago' GROUP BY e_Correspondent  ORDER BY e_MessageID DESC");
	$MessageID_list = [];
	foreach ($e_MessageID_list as $k => $val) {
		$MessageID_list[$val['e_MessageID']] = $val['e_MessageID'];
	}
	$messages_all = arrayDB("
		SELECT *,'INBOX' as dir FROM ebay_msgs_inbox WHERE e_ReceiveDate>'$days_ago'
		UNION
		SELECT *,'OUTBOX' as dir FROM ebay_msgs_outbox WHERE e_ReceiveDate>'$days_ago'
		ORDER BY e_MessageID ASC LIMIT 4000");
	$answerd_list = [];
	foreach ($messages_all as $key => $value) {
		if ($value['dir'] === 'OUTBOX') {
			$answerd_list[$value['e_Correspondent']] = 1;
		}else{
			$answerd_list[$value['e_Correspondent']] = 0;
		}
	}
	$messages_list = arrayDB("SELECT msgs.*, ebay_users.is_trusted, ebay_users.is_problematic FROM
		(SELECT *,'INBOX' as dir FROM ebay_msgs_inbox WHERE e_ReceiveDate>'$days_ago' ORDER BY e_MessageID DESC LIMIT 2000) msgs
		left join ebay_users
		on msgs.e_Correspondent = ebay_users.user_id
		ORDER BY e_MessageID DESC");
	$messages_all = [];
	foreach ($messages_list as $key => $v) {
		if (isset($MessageID_list[$v['e_MessageID']]) && isset($answerd_list[$v['e_Correspondent']]) && $answerd_list[$v['e_Correspondent']] === 0 && $v['status'] === 'neutral') {
			$messages_all[] = $v;
		}elseif ($v['status'] === 'asked') {
			$messages_all[] = $v;
		}
	}
	if (isset($_POST['action']) && $_POST['action'] === 'new_msg_count') {
		echo count($messages_all);
		die;
	}
}else{
	$messages_all = arrayDB("SELECT msgs.*,ebay_users.is_trusted, ebay_users.is_problematic FROM
		(SELECT *,'INBOX' as dir FROM ebay_msgs_inbox WHERE e_ReceiveDate>'$days_ago'
		UNION
		SELECT *,'OUTBOX' as dir FROM ebay_msgs_outbox WHERE e_ReceiveDate>'$days_ago'
		ORDER BY e_MessageID DESC LIMIT 1600) msgs
		left join ebay_users
		on msgs.e_Correspondent = ebay_users.user_id
		ORDER BY e_MessageID DESC");
}
//print_r($messages_all);

draw_messages_submenu('ebay');


// buttons and search component
?>
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

.panel .slidedown .glyphicon,
.chat .glyphicon-time,
.glyphicon-envelope
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
.thumbnail {
    background-color: #424242;
}
</style>
<div class="container">
<div class="row">
	<div class="col-sm-4">
		<a href="?action=ebay-messages&show=not_answerd" class="btn btn-default <?php tab_active('show', 'not_answerd');?>">New Messages</a>
		<a href="?action=ebay-messages" class="btn btn-default <?php tab_active('show', 'all');?>">All Messages</a>
	</div>
	<div class="col-sm-4">
		<form action="" method="GET">
		    <div class="input-group">
		      <input type="search" class="form-control" name="correspondent" placeholder="Enter userID" value="<?= isset($_GET['correspondent'])?strip_tags($_GET['correspondent']):'';?>">
		      <input type="hidden" name="action" value="ebay-messages">
		      <span class="input-group-btn">
		        <button class="btn btn-default" type="submit">Go!</button>
		      </span>
		    </div><!-- /input-group -->
		</form>
	</div>
</div>


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
			 'game_name' => $item_title,
			 'templates' => get_text_template('ebay_messages'),
			 'disabled' => ($_GET['can_be_published']) ? '' : 'disabled="disabled"'];
	view('ebay-messages/send-form',['data'=>$data]);
}?>
<ul class="chat" id="em-deligator">
<?php
function is_chkd($a, $b){if ($a === $b) echo 'checked';}

// messages list
foreach ($messages_all as $key => $msg):
	if ($msg['dir'] === 'INBOX') {
		$can_be_published = (!$msg['e_transId']) ? 1 : 0;
?>
	<li class="left clearfix">
		<div class="chat-img pull-left">
			<img src="images/50px-U.png" alt="User Avatar" class="img-circle">
			<div class="q-status clearfix">
				<input <?php is_chkd($msg['status'], 'asked');?> class="q-radio q-radio1" id="q-radio<?= $key; ?>1" type="radio" name="<?= $msg['id']; ?>" value="asked">
				<input <?php is_chkd($msg['status'], 'answerd');?> class="q-radio q-radio2" id="q-radio<?= $key; ?>2" type="radio" name="<?= $msg['id']; ?>" value="answerd">
				<label class="q-label q-label1" for="q-radio<?= $key; ?>1" title="unsolved"></label>
				<label class="q-label q-label2" for="q-radio<?= $key; ?>2" title="solved"></label>
			</div>
		</div>
		<div class="chat-body clearfix">
			<div class="header">
				<a title="<?= htmlspecialchars($msg['e_Subject']);?>" href="?action=ebay-messages&correspondent=<?= $msg['e_Correspondent'];?>&message_id=<?= $msg['e_ExternalMessageID'];?>&can_be_published=<?= $can_be_published;?>"><strong class="primary-font"><?= $msg['e_Correspondent']; ?></strong></a>
				<?= user_star_sign($msg); ?>
				<?= user_alert_sign($msg); ?>
				<a href="?<?= query_to_orders_page(['order_id'=>'0','q'=>$msg['e_Correspondent']]); // было 'e_transId'?>"><small class="text-muted"><?php echo $msg['e_ItemTitle']; ?></small></a>
				<small class="pull-right text-muted">
					<span class="glyphicon glyphicon-time"></span><?php echo add_hours($msg['e_ReceiveDate'], 1); ?></small>
			</div>
			<div class="row" style="overflow: hidden;">
				<div class="col-xs-10">
					<p><?= $msg['e_Body']; ?></p>
				</div>
				<div class="col-xs-2">
					<?= ebay_messages_thumbnail($msg); ?>
				</div>
			</div>
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
				<small class=" text-muted"><span class="glyphicon glyphicon-time"></span><?php echo add_hours($msg['e_ReceiveDate'], 1); ?></small>
				<div class="pull-right"><?= user_alert_sign($msg); ?></div>
				<div class="pull-right"><?= user_star_sign($msg); ?></div>
				<a title="<?php echo htmlspecialchars($msg['e_Subject']);?>" href="?action=ebay-messages&correspondent=<?php echo $msg['e_Correspondent'];?>"><strong class="pull-right primary-font"><?php echo $msg['e_Correspondent']; ?></strong></a>
				<small class="pull-right text-muted"><?php echo $msg['e_ItemTitle']; ?>&nbsp;</small>
			</div>
			<p>
				<?php echo $msg['e_Body']; ?>
			</p>
		</div>
	</li>
<?php
		}
	endforeach;

?>
</ul>
</div>


<div id="pictureModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <img id="big_pic_img" src="" alt="" style="max-width: 100%; margin: auto; display: block;">
    </div>
  </div>
</div>

<script>$(function(){EbayMessages.init()})</script>