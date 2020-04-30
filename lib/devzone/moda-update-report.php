<?php




$pie_chart_json = _esc_attr(json_encode([
      ['Task', 'Hours per Day'],
      ['Work',     11],
      ['Eat',      2],
      ['Commute',  2],
      ['Watch TV', 2],
      ['Sleep',    7]
    ]));


?>
<div id="pie_chart_json" json="<?= $pie_chart_json; ?>"></div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

  	var jsonStr = $('#pie_chart_json').attr('json')

    var data = google.visualization.arrayToDataTable(JSON.parse(jsonStr));

    var options = {
      title: 'My Daily Activities'
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

    chart.draw(data, options);
  }
</script>

<div id="piechart" style="width: 900px; height: 500px;"></div>
  <?php

