<?php



	// $Ebay = new Ebay_shopping2();
	// $response = $Ebay->updateProductPrice(111985294893, 1.78);

	// sa($response);


?>
<style>
#dot-wrapper{
	outline: 1px solid #fff;
	width: 500px;
	height: 500px;
	position: relative;
	margin: 50px auto;
	background: #999;
}
#dot{
	background: #000;
	width: 40px;
	height: 40px;
	border-radius: 50%;
	margin: -20px 0 0 -20px;
	position: absolute;
	top: 50%;
	left: 50%;
}
</style>

<div id="dot-wrapper">
	<div id="dot" draggable="true"></div>
</div>

<script>

do{ 
var number = prompt("enter your number"); 
}while(!number); 

var result = number; 

for (var counter = 0; counter < 10; counter = counter + 1) 
result = result * number; 
console.log(result);


</script>