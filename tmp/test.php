<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Old | d025d6a2</title>
</head>
<body>
	<pre>
<?php
// $resource = mysqli_connect('localhost', 'd025d69c', 'v94nDrMpdLD4zLLZ'); // new
$resource = mysqli_connect('localhost', 'd025d6a2', 'MsWNRSeTULgnPGG7'); // old
if (!$resource) {
die('Ошибка при подключении: ' . mysql_error());
}
echo 'Подключено успешно!';
$result = mysqli_query($resource, "SHOW DATABASES");
print_r(mysqli_fetch_assoc($result));
mysqli_close($resource);
?>
	</pre>
</body>
</html>