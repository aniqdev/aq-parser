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
require_once 'lib/array_DB.php';
define('ROOT', __DIR__);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<title>Aqs-Parser</title>
	<!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css?t=<?php echo date('d-m-y_H:i:s',filemtime ('css/style.css')); ?>">
	<script src="js/jquery-2.1.3.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/list.min.js"></script>
</head>
<body>

<?php 
include_once('lib/navigate.php');
if (isset($_GET['action'])) {
	$toFile = 'lib/'.$_GET['action'].'.php';
	if (file_exists($toFile)) include_once($toFile);
	else echo "<h2>404 - Страница не найдена!!!</h2>";
} else {
	echo "<h2>Главная страница</h2>";
}
?>

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