<?php

if (isset($_POST['mark_as_asked'])) {
	$msg_id = (int)$_POST['msg_id'];
	arrayDB("UPDATE ebay_msgs_inbox SET status='asked' WHERE id=$msg_id");
}

if (isset($_POST['mark_as_answerd'])) {
	$msg_id = (int)$_POST['msg_id'];
	arrayDB("UPDATE ebay_msgs_inbox SET status='answerd' WHERE id=$msg_id");
}

if (isset($_POST['mark_hood_as_asked'])) {
	$msg_id = (int)$_POST['msg_id'];
	arrayDB("UPDATE hood_messages SET status='asked' WHERE id=$msg_id");
}

if (isset($_POST['mark_hood_as_answerd'])) {
	$msg_id = (int)$_POST['msg_id'];
	arrayDB("UPDATE hood_messages SET status='answerd' WHERE id=$msg_id");
}