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
    <meta name="viewport" content="width=device-width, initial-scale=1">
<style>
*{
	box-sizing: border-box;
}
html,body{
	height: 100%;
}
body {
	background: url('images/Colorful-Nature-Hd.jpg');
	font-family: helvetica, arial;
	background-size: cover;
	background-attachment: fixed;
	margin: 0;
	padding: 15px;
}
form{
	position: absolute;
    width: 300px;
    margin-left: -150px;
	top: 50%;
	left: 50%;
	border: 1px solid #616161;
	background-color: rgba(9, 30, 37, 0.5);
	padding: 10px;
	line-height: 18px;
	margin-top: -100px;
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
	background: #0d0a3042;
	color: white;
}
/* input:-webkit-autofill {
	background-color: #240259 !important;
    background-image: none !important;
    color: #c82d2d !important;
} */
/* input:-webkit-autofill { -webkit-box-shadow:200px 200px 100px white inset; box-shadow:200px 200px 100px white inset; } */

form button{
    width: 100%;
    display: block;
    padding: 5px;
    background: black;
    border: 1px solid #818181;
    background: linear-gradient(to bottom, rgba(0,0,0,0.65) 0%,rgba(0,0,0,0) 100%);
    color: #fff;
    /* background: linear-gradient(to bottom, rgb(41, 66, 126) 0%,rgba(0,0,0,0) 100%); */
}
@media (max-width: 767px){
	form{
		width: 100%;
		position: relative;
	    top: 40%;
	    left: 0;
	    margin: 0;
	}
	form button{
		padding: 25px;
	}
}
</style>
</head>
<body>
	<form method="post">
		<!-- <h2 align="center">Authorization!</h2> -->
		<input type="text" name="login" maxlength="100" placeholder="login">
		<input type="password" name="password" maxlength="100" placeholder="password">
		<button type="submit" name="submitted" value="Войти">Sign In!</button>
	</form>
</body>
</html>
<?php ?>