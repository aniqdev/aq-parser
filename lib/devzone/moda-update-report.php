<?php

$moda_list_count = arrayDB("SELECT count(*) FROM moda_list")[0]['count(*)'];
$modatoday_count = arrayDB("SELECT count(*) FROM moda_list WHERE post_id <> ''")[0]['count(*)'];


$speed_res = arrayDB("SELECT date_format(created_at, '%H:00') as daten, count(*) as count FROM `moda_cron_update` where created_at > (now() - interval  1 day)  group by hour(created_at)");

$arr = [    ['updates per hour', 'updates per hour']    ];

foreach ($speed_res as $val) {
  $arr[] = [$val['daten'],  (int)$val['count']];
}

$curve_chart_json = _esc_attr(json_encode($arr));



$pie_chart_res = arrayDB("SELECT cron_status, count(*) as count FROM moda_cron_update where created_at > (now() - interval  1 day)  group by cron_status");

$pie_arr = [    ['cron status', 'count']    ];

foreach ($pie_chart_res as $val) {
  $pie_arr[] = [$val['cron_status'], (int)$val['count']];
}

$pie_chart_json = _esc_attr(json_encode($pie_arr));

$last_res = arrayDB("SELECT `id`,  `moda_id`,  `Ack`,  `endTime`,  `time_spent`,  `cron_status`,  `action`,  `report`,  LEFT(`errors`, 50),  `created_at` FROM `moda_cron_update` order by id desc limit 100");
$last_res = array_reverse($last_res);
?>
<div id="curve_chart_json" json="<?= $curve_chart_json; ?>"></div>
<div id="pie_chart_json" json="<?= $pie_chart_json; ?>"></div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    //======================= curve chart =======================
    var jsonStr = $('#curve_chart_json').attr('json')
    var data = google.visualization.arrayToDataTable(JSON.parse(jsonStr));
    var options = {
      title: 'Update speed',
      curveType: 'function',
      legend: { position: 'bottom' },
      trendlines: {
        0: {
          type: 'linear',
          color: 'green',
          lineWidth: 3,
          opacity: 0.3,
          showR2: true,
          visibleInLegend: true
        }
      }
    };

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

    chart.draw(data, options);

    //======================= line chart =======================
    // var options = {
    //   title: 'Company Performance',
    //   hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
    // };

    // var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
    // chart.draw(data, options);

    //======================= pie chart =======================
  	var jsonStr = $('#pie_chart_json').attr('json')
    var data = google.visualization.arrayToDataTable(JSON.parse(jsonStr));
    var options = {
      title: 'Updated / Removed'
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

    chart.draw(data, options);
  }
</script>

<div class="container">
  <div>
    товаров в базе парсера: <?= $moda_list_count; ?>
    товаров в базе парсера: <?= $modatoday_count; ?>
  </div><hr>
  <div class="row">
    <div class="col-sm-2" style="height: 300px; overflow-y: auto;">
      <?php draw_table_with_sql_results($speed_res, 1); ?>
    </div>
    <div class="col-sm-10">
      <div id="curve_chart" style="height: 300px;"></div>
    </div>
  </div><hr>
  
  <div class="row">
    <div class="col-sm-10">
      <div id="piechart" style="height: 300px;"></div>
    </div>
    <div class="col-sm-2" style="height: 300px; overflow-y: auto;">
      <?php draw_table_with_sql_results($pie_chart_res, 1); ?>
    </div>
  </div><hr>
  <!-- <div id="chart_div" style="width: 900px; height: 300px;"></div><hr> -->

  <style>
    .last100table table{
      width: 100%;
    }
  </style>
  <div class="last100table">
    <?php draw_table_with_sql_results($last_res, 1); ?>
  </div>
</div>


