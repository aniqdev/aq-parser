<?php
ini_set("display_errors",1);
error_reporting(E_ALL);
require_once 'vendor/autoload.php';
// require_once 'lib/kint-master/Kint.class.php';
// require_once 'lib/PHPExcel.php';
// require_once 'lib/simple_html_dom.php';
require_once 'lib/array_DB.php';
if (isset($_POST['submitted'])) {
	$log = _esc($_POST['login']);
	$pas = _esc(md5($_POST['password']));
	$user_check = arrayDB("SELECT * FROM gig_users WHERE username='$log' AND password='$pas'");
	if ($user_check){
		$page_list = $user_check[0]['page_list'];
		$user_white_list = arrayDB("SELECT page_action FROM gig_users_page_list WHERE list_name='$page_list' AND white_or_black='white'");
		foreach ($user_white_list as &$qwasd) $qwasd = $qwasd['page_action'];
		session_start();
		$_SESSION['logged'] = true;
		$_SESSION['username'] = $user_check[0]['username'];
		$_SESSION['page_list_name'] = $page_list;
		$_SESSION['user_white_list'] = $user_white_list;
		$_SESSION['csrf-buy-token'] = md5(time());
		header("Location: index.php?".$_SERVER['QUERY_STRING']);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<style>
		body {
 			background: url("images/SoftWhiteBricks.jpg");
			font-family: helvetica, arial;
		}
		form{
			position: absolute;
		    width: 300px;
		    margin-left: -150px;
			top: 50%;
			left: 50%;
			border: 1px solid #fff;
			background-color: rgba(9, 30, 37, 0.5);
			padding: 10px;
			line-height: 18px;
			margin-top: -100px;
			box-shadow: 0 2px 5px #999;
			color: #fff;
			text-shadow: 0 1px 2px #444;
			text-align: center;
		}
		form input{
		    display: block;
		    width: 100%;
		    border: 1px solid #818181;
		    margin-bottom: 5px;
		    padding: 5px;
		        box-sizing: border-box;
		}
		form button{
			width: 100%;
			display: block;
			padding: 5px;
		}
	</style>
</head>
<body>
	<form method="post">
		<h2 align="center">Authorization!</h2>
		<input type="text" name="login" maxlength="100" placeholder="login">
		<input type="password" name="password" maxlength="100" placeholder="password">
		<button type="submit" name="submitted" value="Войти">Sign In!</button>
	</form>
</body>
</html>
<?php ?>