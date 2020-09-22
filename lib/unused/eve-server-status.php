<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(ajax_get_data);

  function ajax_get_data() {
    jQuery.post('http://parser.gig-games.de/ajax-controller.php',
      { function: 'ajax_get_eve_data',
        last: 1000 },
      function(data) {
        if(data.status === 'OK') drawChart(data.data);
      },
      'json');
  }

  function drawChart(data) {
    var data = google.visualization.arrayToDataTable(data);

    var options = {
      title: 'Eve servet status',
      hAxis: {title: 'Server status',  titleTextStyle: {color: '#333'}},
      vAxis: {minValue: 0},
    };

    var chart = new google.visualization.AreaChart(document.getElementById('eve_chart_div'));
    chart.draw(data, options);
  }
</script>
<style>
  #eve_chart_div{
    /* width: 100%; */
    height: 200px;
  }
/*   #eve_chart_div svg>rect{
    fill:  #555;
  }
  #eve_chart_div svg text{
    fill:  #eaeaea;
  } */
  .aqs-ranger{
    position: relative;
  }
  .inp-right-wrapper{
    position: absolute;
        width: 100%;
    top: 0;
  }
  .inp-left::-webkit-slider-runnable-track {
    background: red; 
  }
  .inp-right::-webkit-slider-runnable-track {
    /* background: green;  */
  }

</style>


<!--Div that will hold the pie chart-->
<div class="container">
  <br>
  <br>
  <div id="eve_chart_div"></div>
  <br>
  <hr>
  <br>
  <form action="">
    <div class="aqs-ranger">
      <div class="inp-left-wrapper">
        <input id="inp_left" class="inp-left" type="range" step="1" min="1" max="1000">
      </div>
      <div class="inp-right-wrapper">
        <input id="inp_right" class="inp-right" type="range" step="1" min="1" max="1000">
      </div>
    </div>
    <br>
    <input id="inp_left_val" type="text" readonly="readonly">
    <input id="inp_right_val" type="text" readonly="readonly">
  </form>
</div>

<script>
(function() {
  
  var cork = document.createElement('div');
  function get_element(id) {
    return document.all[id] || cork;
  }

  var inp_left = get_element('inp_left'),
   inp_right = get_element('inp_right'),
   inp_left_val = get_element('inp_left_val'),
   inp_right_val = get_element('inp_right_val');

  inp_left.addEventListener('input', function(e) {
    inp_left_val.value = e.target.value
  })

  inp_right.addEventListener('input', function(e) {
    inp_right_val.value = e.target.value
  })

}())
</script>