<?php
session_start();
ini_set("display_errors",1);
error_reporting(E_ALL);
if (!isset($_SESSION['logged']) || $_SESSION['logged'] == null) {
    header("Location: login.php?".$_SERVER['QUERY_STRING']); 
    die;
}
if (isset($_GET['logout'])  && $_GET['logout'] === 'true') {
	session_destroy();
	header("location:login.php");
	die;
}
require_once 'vendor/autoload.php';
require_once 'lib/kint-master/Kint.class.php';
require_once 'lib/PHPExcel.php';
require_once 'lib/simple_html_dom.php';
require_once 'lib/array_DB.php';
define('ROOT', __DIR__);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<title><?= aqs_get_page_title(); ?></title>
	<!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css?t=<?php echo date('d-m-y_H:i:s',filemtime ('css/style.css')); ?>">
	<script src="js/jquery-2.1.3.min.js"></script>
	<script src="js/list.min.js"></script>
</head>
<body>
<div class="ajax-loader ajaxed"></div>
<?php 
include_once('lib/navigate.php');

$isset_get_action = isset($_GET['action']);
$accessed = ($isset_get_action AND ($_SESSION['page_list_name'] === 'admin' || in_array($_GET['action'], $_SESSION['user_white_list'])));

if ($accessed) {

	$toFile = 'lib/'.$_GET['action'].'.php';
	if (file_exists($toFile)) include_once($toFile);
	else echo "<h2>404 - Page not found!!!</h2>";

} elseif ($isset_get_action) {

	echo "<h2>Access denied!</h2>";
	echo "<h4>Contact your administrator</h4>";

} else {

	echo "<h2>Home page</h2>";
	include_once('lib/task-manager.php');

}?>

<script src="js/bootstrap.min.js"></script>
<script src="js/main.js?t=<?php echo date('d-m-y_H:i:s',filemtime ('js/main.js')); ?>"></script>
<?php
if ($_ERRORS){
	echo '<div class="errors"><style>	.errors .kint{margin: 0;}</style>';
	ddd($_ERRORS);
	echo '</div>';
} 
?>
</body>
</html>