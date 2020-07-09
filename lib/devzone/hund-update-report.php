<?php

$hund_list_count = arrayDB("SELECT count(*) FROM hund_list")[0]['count(*)'];
$modatoday_count = arrayDB("SELECT count(*) FROM hund_list WHERE post_id <> ''")[0]['count(*)'];


$speed_res = arrayDB("SELECT date_format(created_at, '%H:00') as daten, count(*) as count FROM `hund_cron_update` where created_at > (now() - interval  1 day)  group by hour(created_at) order by id");

$arr = [    ['updates per hour', 'updates per hour']    ];

foreach ($speed_res as $val) {
  $arr[] = [$val['daten'],  (int)$val['count']];
}

$curve_chart_json = _esc_attr(json_encode($arr));



$pie_chart_res = arrayDB("SELECT report, count(*) as count FROM hund_cron_update where created_at > (now() - interval  1 day)  group by report");

$pie_arr = [    ['action', 'count']    ];

foreach ($pie_chart_res as $val) {
  $pie_arr[] = [$val['report'], (int)$val['count']];
}

$pie_chart_json = _esc_attr(json_encode($pie_arr));

$last_res = arrayDB("SELECT `id`,  `hund_id`,  `Ack`,  `endTime`,  `time_spent`,  `cron_status`,  `action`,  `report`,  `comment`,  LEFT(`errors`, 50) as errors,  `created_at` FROM `hund_cron_update` order by id desc limit 100");

$time_dif = mur_time_dif($last_res[0]['created_at']);
// $last_res = array_reverse($last_res);
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
      curveType: 'function', // none,function
      legend: { position: 'bottom' },
      pointSize: 5,
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

<div class="container" style="width: 1400px;">
  <h3>Data for the last 24 hours</h3>
  <div>
    товаров в базе парсера: <?= $hund_list_count; ?>
    товаров в базе modatoday: <?= $modatoday_count; ?>
    <br>
    Last update was <b><?= $time_dif; ?></b> ago
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
    <h4>Last 100 records(descending):</h4>
    <?php draw_table_with_sql_results($last_res, 1); ?>
  </div>
</div>
























<?php


function mur_time_dif($date_str)
{
    $last_update = date_create($date_str)->getTimestamp();

    $secs = time() - $last_update;
// sa($secs);
    if(defined('DEV_MODE')) $secs = $secs + 60*60; // что-то с часовым поясом

    return gmdate("H:i:s", $secs);
}