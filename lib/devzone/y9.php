
<form action="/">
	<input type="hidden" name="action" value="devzone/y9">
	<input type="text" name="specs[asd][]">
	<input type="text" name="specs[qwe][]">
	<input type="submit" name="pop" value="go!">
</form>
<?php
parse_str('action=devzone%2Fy9&specs%5Basd%5D%5B%5D=ccc&specs%5Bqwe%5D%5B%5D=vvv&pop=go%21',$res);
sa($res);

sa(http_build_query([
	'asd' => 'dsa',
	'qwe' => [
		'fgh' => 999,
		'zxc' => 'third'
	]
]));