<?php


function ajax_mark_as_asked($msg_id){
	
	arrayDB("UPDATE ebay_msgs_inbox SET status='asked' WHERE id=$msg_id");
}

function ajax_mark_as_answerd($msg_id){
	
	arrayDB("UPDATE ebay_msgs_inbox SET status='answerd' WHERE id=$msg_id");
}


if (isset($_POST['mark_as_asked'])) {
	ajax_mark_as_asked((int)$_POST['msg_id']);
}

if (isset($_POST['mark_as_answerd'])) {
	ajax_mark_as_answerd((int)$_POST['msg_id']);
}