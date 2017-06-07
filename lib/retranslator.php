<?php





if ($_POST && isset($_POST['location'])) {
	$url = $_POST['location'];
	unset($_POST['location']);
	echo json_encode(post_curl($url, $_POST));
}











?>