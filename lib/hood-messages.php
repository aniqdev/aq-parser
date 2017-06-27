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


.chat li .chat-body .bb
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
textarea{
	resize: vertical;
}
</style><?php
draw_messages_submenu('hood');

$days_ago = date('Y-m-d', time()-(60*60*24*5));

if (isset($_POST['send'])) {
	$user_id = $_GET['user_id'];
	$text = $_POST['text'];
	$res = post_curl('http://hood.gig-games.de/api/createMessage', ['userId' => $user_id, 'messageText' => $text]);
	// sa($res);
	echo '<div class="container"><div class="alert alert-success alert-dismissible" role="alert">
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.
	  print_r($res,1).
	  '</div></div>';
	file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/ajax.php?action=cron-hood-messages');
}


if (isset($_GET['search_user']) && $_GET['search_user']) {
	$user_name = _esc($_GET['search_user']);
	$messages_all = arrayDB("SELECT * FROM hood_messages WHERE user_name LIKE '%${user_name}%' order by date_time DESC LIMIT 500");
}elseif (isset($_GET['user_id']) && $_GET['user_id']) {
	$user_id = _esc($_GET['user_id']);
	$messages_all = arrayDB("SELECT * FROM hood_messages WHERE user_id='$user_id' order by date_time DESC LIMIT 200");
	view('hood-messages/send-form');
}else{
	// потом изменить лимит 200 на сообщения за последние 5 дней
	$messages_all = arrayDB("SELECT * FROM hood_messages WHERE date_time>'$days_ago' order by date_time DESC");
}

// sa($messages_all);

?>
<div class="container">
<br>
<div class="row">
	<div class="col-sm-4">
		<a href="?action=hood-messages&show=not_answerd" class="btn btn-default hm-btns <?php tab_active('show', 'not_answerd');?>">New Messages</a>
		<a href="?action=hood-messages&show=all" class="btn btn-default hm-btns <?php tab_active('show', 'all');?>">All Messages</a>
	</div>
	<div class="col-sm-4">
		<form action="" method="GET">
		    <div class="input-group">
		      <input type="search" class="form-control" name="search_user" placeholder="Enter user name" value="">
		      <input type="hidden" name="action" value="hood-messages">
		      <span class="input-group-btn">
		        <button class="btn btn-default" type="submit">Go!</button>
		      </span>
		    </div><!-- /input-group -->
		</form>
	</div>
</div>
<br>
<ul class="chat" id="hm-deligator">
<?php
function is_chkd($a, $b){if ($a === $b) echo 'checked';}
	
	// создаем массив который будет содержать user_id и направление верхнего сообщения в цепочке
	$skip = [];
	// Глвный цикл вывода сообщений
	foreach ($messages_all as $key => $msg):
		// пропускаем не новые сообщения
		if (isset($_GET['show']) && $_GET['show'] === 'not_answerd') {
			// заполняем массив not_answerd
			if(!isset($skip[$msg['user_id']])){
				if($msg['dir'] === 'outbox' || $msg['status'] === 'answerd') {
					  $skip[$msg['user_id']] = true;
				}else $skip[$msg['user_id']] = false;
			}
			if($msg['status'] === 'asked') $skip[$msg['user_id']] = false;
			// пропускаем сообщение, у которого верхнее сообщение исходящее
			if ($skip[$msg['user_id']]) continue;
		}

		// вывод входящего сообщения
		if ($msg['dir'] === 'inbox') {
?>
	<li class="left clearfix">
		<div class="pull-left">
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
				<a href="?action=hood-messages&user_id=<?= $msg['user_id'];?>&user_name=<?= $msg['user_name'];?>" title="<?php echo $msg['subject'];?>"><strong class="primary-font"><?php echo $msg['user_name']; ?></strong></a>
				<a><small class="text-muted"><?php echo $msg['subject']; ?></small></a>
				<small class="pull-right text-muted">
					<span class="glyphicon glyphicon-time"></span><?php echo add_hours($msg['date_time'], 2); ?></small>
			</div>
			<div class="bb">
				<?php echo $msg['body']; ?>
			</div>
		</div>
	</li>
<?php
		}else{
?>
	<li class="right clearfix">
		<img src="images/50px-ME.png" alt="User Avatar" class="img-circle pull-right">
		<div class="chat-body clearfix">
			<div class="header">
				<small class=" text-muted"><span class="glyphicon glyphicon-time"></span><?php echo add_hours($msg['date_time'], 2); ?></small>
				<a href="?action=hood-messages&user_id=<?= $msg['user_id'];?>&user_name=<?= $msg['user_name'];?>" title="<?php echo $msg['subject'];?>"><strong class="pull-right primary-font"><?php echo $msg['user_name']; ?></strong></a>
				<small class="pull-right text-muted"><?php echo $msg['subject']; ?>&nbsp;</small>
			</div>
			<div class="bb">
				<?php echo $msg['body']; ?>
			</div>
		</div>
	</li>
<?php
		}
	endforeach;
		// sa($nt_answrd);

?>
</ul>
</div>
