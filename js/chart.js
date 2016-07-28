google.load("visualization", "1", {packages:["corechart"]});
//google.setOnLoadCallback(ebayChart);
function ebayChart(forChart, indiv, chartTitle) {

  switch(indiv){
    case 1: var diver = document.getElementById('ebay_chart1');
    break
    case 2: var diver = document.getElementById('ebay_chart2');
    break
    default: console.log('Не выбран div для графика');
  }
  
  var data = google.visualization.arrayToDataTable([
    ['Year', 'Price'],
    ['2013',  1000  ],
    ['2014',  1170  ],
    ['2015',  660   ],
    ['2016',  1030  ],
    ['2017',  1000  ],
    ['2018',  1170  ],
    ['2019',  660   ],
    ['2020',  1030  ]
  ]);

  var data2 = google.visualization.arrayToDataTable(forChart);

        var options = {
          title: chartTitle,
          hAxis: { titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0},
          //colors: ['#366A73', '#1B2F4C']
          pointSize: 5,
        };

  var chart = new google.visualization.AreaChart(diver);

  chart.draw(data2, options);
  
  //console.dir(forChart);
}

var h5Ar = new Object;

function allCharts (h5Arr) {
  var first = 99999;
  var last = 0;
  for (var i = 4; i >= 0; i--) {
    console.log('first: ',h5Arr[i]['first'])
    if (h5Arr[i]['first'] != 1 && h5Arr[i]['first'] < first) {
      first = h5Arr[i]['first'];
    }
    if (h5Arr[i]['last'] > last) {
      last = h5Arr[i]['last'];
    }
  }
  var amountsArr = [];
  for (var i = first; i <= last; i++) {
    if (h5Ar[0].dayArr[i]) var a = h5Ar[0].dayArr[i];
    else var a = 0;
    if (h5Ar[1].dayArr[i]) var b = h5Ar[1].dayArr[i];
    else var b = 0;
    if (h5Ar[2].dayArr[i]) var c = h5Ar[2].dayArr[i];
    else var c = 0;
    if (h5Ar[3].dayArr[i]) var d = h5Ar[3].dayArr[i];
    else var d = 0;
    if (h5Ar[4].dayArr[i]) var e = h5Ar[4].dayArr[i];
    else var e = 0;
    amountsArr[i] = [a, b, c, d, e];
  }

  var i = 1;
  var forChart1 = [['Date', 'Amount1', 'Amount2', 'Amount3', 'Amount4', 'Amount5']];
  for (var j in amountsArr){
    var dd = new Date(j*24*60*60*1000);
    forChart1[i++] = [dd, amountsArr[j][0], amountsArr[j][1], amountsArr[j][2], amountsArr[j][3], amountsArr[j][4]];
  }
  ebayChart(forChart1, 1, 'Количество');

} // allCharts()

function counterAgregator (oldD, newD, number) {
  h5Ar[0] = oldD
  h5Ar[number] = newD;
  
    console.log('number = ',number);
  if (h5Ar[0] && h5Ar[1] && h5Ar[2] && h5Ar[3] && h5Ar[4]) {
    console.log('numberDone = ',number);
    console.dir(h5Ar);
    allCharts(h5Ar);
  }
}

function nextChart (oldData, newId, number) {
    $.post( "lib/ebay_getprices.php",
    {ebay_getpurhis:'true', itemid: newId},
    function( newData ) {
 
      counterAgregator(oldData, newData, number);

    },'json');
}