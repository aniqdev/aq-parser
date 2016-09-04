<?php
if (isset($_POST['submitted'])) {
	$log = $_POST['login'];
	$pas = $_POST['password'];
	$ip = $_SERVER['REMOTE_ADDR'];
	if (($log === 'goldenpars' && $pas === 'v$sK6HfqDd') || $ip === '37.46.229.203'){
		session_start();
		$_SESSION['logged'] = true;
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
		<h2 align="center">Авторизуйтесь!</h2>
		<input type="text" name="login" maxlength="100" placeholder="login">
		<input type="password" name="password" maxlength="100" placeholder="password">
		<button type="submit" name="submitted" value="Войти">Sign In!</button>
	</form>
</body>
</html>