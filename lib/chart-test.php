<?php 
	function showDiagram(){
		?>
   <!--Load the AJAX API-->
	<script>

	  // Load the Visualization API and the piechart package.
	  //google.load('visualization', '1.0', {'packages':['corechart']});

	  // Set a callback to run when the Google Visualization API is loaded.
	  google.setOnLoadCallback(drawChart(730));

	  // Callback that creates and populates a data table,
	  // instantiates the pie chart, passes in the data and
	  // draws it.
	  function drawChart(pappid) {

		$.ajax({
		  method: "POST",
		  url: "lib/chart-test.php",
		  async: true,
		  dataType: 'json',
		  data: { post: "data", appid: pappid }
		})
		  .done(function( msg ) {
				var arrdata = [];
				arrdata[0] = msg[0];
				for (var i = msg.length - 1; i > 0; i--) {
					arrdata[i] = [];
					arrdata[i][0]=msg[i]['title'];
					arrdata[i][1]=+msg[i]['price'];
				};
				console.dir(arrdata);
				        // Create the data table.
				var data = google.visualization.arrayToDataTable(arrdata);

						// Set chart options
				var options = {
				  title: 'Измениния цен',
				  hAxis: { titleTextStyle: {color: '#333'}},
				  vAxis: {minValue: 0},
				  colors: ['#366A73', '#1B2F4C']
				};

				var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));

				chart.draw(data, options);
		  });
	  }
	</script>

	<!--Div that will hold the pie chart-->
	<div id="chart_div"></div>

	<?php
	} // showDiagram()

	function sendData(){
		include('array_DB.php');
		$arr1 = array(array('asd','price'));
		if (isset($_POST['appid'])) $appid = $_POST['appid'];
		$arr2 = arrayDB("SELECT title,price FROM slist WHERE appid='$appid' AND appsub='app' LIMIT 10");
		//echo "<pre>";
		echo json_encode(array_merge ($arr1,$arr2));
		//echo "</pre>";
	} // sendData()




if (isset($_POST['post']) && $_POST['post'] = 'data') {
	sendData();
	//var_dump($_POST);
}else{
	showDiagram();
}

?>